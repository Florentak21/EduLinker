<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Router;
use App\Models\Domain;
use App\Traits\EmailValidator;
use App\Traits\LastnameValidator;
use App\Traits\FirstnameValidator;
use App\Traits\GenderValidator;
use App\Traits\PasswordValidator;
use App\Traits\RoleValidator;
use App\Models\Teacher;

class TeacherController extends Controller {
    use EmailValidator, LastnameValidator, FirstnameValidator, GenderValidator, PasswordValidator, RoleValidator;

    public function __construct(Router $router)
    {
        parent::__construct($router);
    }

    /**
     * Affiche tous les teachers.
     * 
     * @return void
     */
    public function index(): void
    {
        $teachers = Teacher::all();
        $this->view('admin/teachers/index', [
            'teachers' => $teachers,
        ]);
    }

    /**
     * Récupère les données pour le dashboard du teacher.
     * 
     * @param int $id
     * 
     * @return void
     */
    public function dashboard(int $id): void
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            $this->redirect('error/404', ['message' => 'Enseignant non trouvé.']);
            return;
        }

        $data = [
            'teacher' => $teacher,
            'students' => Teacher::getAssignedStudents($id)
        ];

        $this->view('teachers/dashboard', $data);
    }

    /**
     * Affiche tous les domaines d'un enseignant spécifique (via son user_id).
     * 
     * @param int $userId
     * 
     * @return void
     */
    public function showMyDomains(int $userId): void
    {
        $teacher = Teacher::findByUserId($userId);
        if (!$teacher) {
            $this->redirect('error/404', ['message' => 'Enseignant non trouvé.']);
            return;
        }

        $domains = Teacher::getDomainsByTeacher($userId);
        $this->view('admin/teachers/show-domains', [
            'teacher' => $teacher,
            'domains' => $domains,
        ]);
    }

    /**
     * Affiche le formulaire permettant de rajouter un domaine à un enseignant.
     * 
     * @param int $teacherId
     * 
     * @return void
     */
    public function showOtherDomains(int $teacherId): void
    {
        $teacher = Teacher::findByUserId($teacherId);
        if (!$teacher) {
            $this->redirect('error/404', ['message' => 'Enseignant non trouvé.']);
            return;
        }

        $this->view('admin/teachers/add-domains', [
            'teacher' => $teacher,
            'domains' => Teacher::getDomainsWithoutMine($teacherId)
        ]);
    }

    /**
     * Traite le formulaire de rajout de domaine à un enseignant.
     * 
     * @param int $teacherUserId
     * 
     * @return void
     */
    public function addDomain(int $teacherUserId): void
    {
        $teacher = Teacher::findByUserId($teacherUserId);
        if (!$teacher) {
            $this->redirect('error/404', ['message' => 'Enseignant non trouvé.']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('error/400', ['message' => 'Méthode non supportée']);
            return;
        }

        $errors = [];
        $domainId = htmlspecialchars($_POST['domain_id'] ?? '');

        if (empty($domainId)) {
            $errors['domain_id'] = 'Le domaine est requis.';
        } elseif (!Domain::find($domainId)) {
            $errors['domain_id'] = 'Le domaine n\'existe pas.';
        }

        if (!empty($errors)) {
            $this->view('admin/teachers/add-domains', [
                'teacher' => $teacher,
                'domains' => Teacher::getDomainsWithoutMine($teacherUserId),
                'errors' => $errors,
                'data' => $_POST
            ]);
            return;
        }

        if (Teacher::addDomain($teacherUserId, $domainId)) {
            $this->redirect("admin/teachers/{$teacherUserId}/domains", [
                'success' => 'Le nouveau domaine a été rajouté avec succès.'
            ]);
        } else {
            $this->redirect("admin/teachers/{$teacherUserId}/domains", [
                'error' => 'Impossible de rajouter le domaine.'
            ]);
        }
    }

    /**
     * Traite la suppression d'un domaine associé à un enseignant.
     * 
     * @param int $teacherUserId
     * @param int $id (domain_id)
     * 
     * @return void
     */
    public function removeDomain(int $teacherUserId, int $id): void
    {
        $teacher = Teacher::findByUserId($teacherUserId);
        if (!$teacher) {
            $this->redirect('error/404', ['message' => 'Enseignant non trouvé.']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('error/400', ['message' => 'Méthode non supportée']);
            return;
        }

        // Vérifier s'il y a des étudiants assignés dans ce domaine
        $studentCount = Teacher::countStudentsByDomain($teacherUserId, $id);
        if ($studentCount > 0) {
            $this->redirect("admin/teachers/{$teacherUserId}/domains", [
                'error' => "Impossible de supprimer ce domaine : l'enseignant encadre $studentCount étudiant(s) dans ce domaine."
            ]);
            return;
        }

        // Vérifier si c'est le dernier domaine
        $domains = Teacher::getDomainsByTeacher($teacherUserId);
        if (count($domains) <= 1) {
            $this->redirect("admin/teachers/{$teacherUserId}/domains", [
                'error' => "Impossible de supprimer le dernier domaine de l'enseignant : un domaine minimum est requis."
            ]);
            return;
        }

        if (Teacher::removeDomain($teacherUserId, $id)) {
            $this->redirect("admin/teachers/{$teacherUserId}/domains", [
                'success' => 'Le domaine a été supprimé avec succès.'
            ]);
        } else {
            $this->redirect("admin/teachers/{$teacherUserId}/domains", [
                'error' => 'Impossible de supprimer le domaine.'
            ]);
        }
    }

    /**
     * Affiche le formulaire d'édition d'un teacher.
     * 
     * @param int $id
     * 
     * @return void
     */
    public function edit(int $id): void
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            $this->redirect('error/404', ['message' => 'Enseignant non trouvé.']);
            return;
        }

        $this->view('admin/teachers/edit', [
            'teacher' => $teacher,
            'domains' => Domain::all()
        ]);
    }

    /**
     * Traite le formulaire d'édition d'un teacher.
     * 
     * @return void
     */
    public function update(): void
    {
        $teacher = Teacher::find(intval(htmlspecialchars($_POST['id'])));
        if (!$teacher) {
            $this->redirect('error/404', ['message' => 'Enseignant non trouvé.']);
            return;
        }

        $errors = [];

        $lastnameError = $this->validateLastname($_POST['lastname']);
        if ($lastnameError) $errors['lastname'] = $lastnameError;

        $firstnameError = $this->validateFirstname($_POST['firstname']);
        if ($firstnameError) $errors['firstname'] = $firstnameError;

        $genderError = $this->validateGender($_POST['gender']);
        if ($genderError) $errors['gender'] = $genderError;

        $emailError = $this->validateEmail($_POST['email'], $teacher['user_id']);
        if ($emailError) $errors['email'] = $emailError;

        if (!empty($errors)) {
            $this->view('admin/teachers/edit', [
                'errors' => $errors,
                'data' => $_POST,
                'teacher' => $teacher,
                'domains' => Domain::all(),
            ]);
            return;
        }

        $data = [
            'firstname' => htmlspecialchars($_POST['firstname']),
            'lastname' => htmlspecialchars($_POST['lastname']),
            'gender' => htmlspecialchars($_POST['gender']),
            'email' => htmlspecialchars($_POST['email']),
            'domain_id' => htmlspecialchars($_POST['domain_id'])
        ];

        if (Teacher::update($teacher['id'], $data)) {
            $this->redirect('admin/teachers', ['success' => 'Enseignant mis à jour avec succès.']);
        } else {
            $this->redirect('admin/teachers', ['error' => 'Erreur lors de la mise à jour de l\'enseignant.']);
        }
    }

    /**
     * Supprime un teacher.
     * 
     * @param int $id
     * @return void
     */
    public function destroy(int $id): void
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            $this->redirect('admin/teachers', ['error' => 'Enseignant non trouvé.']);
            return;
        }

        if (Teacher::delete($id)) {
            $this->redirect('admin/teachers', ['success' => 'Enseignant supprimé avec succès.']);
        } else {
            $this->redirect('admin/teachers', ['error' => 'Erreur lors de la suppression de l\'enseignant.']);
        }
    }
}