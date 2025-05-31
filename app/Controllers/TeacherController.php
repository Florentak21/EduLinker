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
            'errors' => $_SESSION['errors'] ?? [],
            'success' => $_SESSION['success'] ?? null
        ]);
        unset($_SESSION['errors'], $_SESSION['success']);
    }

    public function dashboard(int $id): void
    {
        $data = [
            'teacher' => Teacher::find($id),
            'students' => Teacher::getAssignedStudents($id)
        ];

        $this->view('teachers/dashboard', $data);
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
        // dump($teacher);
        if (!$teacher) {
            header('HTTP/1.1 404 Not Found');
            $this->view('errors/404', [
                'title' => 'Page non trouvée',
                'active' => ''
            ]);
            return;
        }

        $this->view('admin/teachers/edit', [
            'teacher' => $teacher,
            'active' => 'teachers',
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
            header('HTTP/1.1 404 Not Found');
            $this->view('errors/404', [
                'title' => 'Page non trouvée',
                'active' => ''
            ]);
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
                'title' => 'Modifier un enseignant',
                'active' => 'teachers'
            ]);
            return;
        }

        $data = [
            'firstname' => htmlspecialchars($_POST['firstname']),
            'lastname' => htmlspecialchars($_POST['lastname']),
            'gender' => htmlspecialchars($_POST['gender']),
            'email'  => htmlspecialchars($_POST['email']),
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