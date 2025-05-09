<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Router;
use App\Traits\PhoneValidator;
use App\Traits\EmailValidator;
use App\Traits\LastnameValidator;
use App\Traits\FirstnameValidator;
use App\Traits\GenderValidator;
use App\Traits\PasswordValidator;
use App\Models\Teacher;
use App\Models\Domain;

class TeacherController extends Controller {
    use PhoneValidator, EmailValidator, LastnameValidator, FirstnameValidator, GenderValidator, PasswordValidator;

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
        $this->view('teachers/index', ['teachers' => $teachers]);
    }

    /**
     * Affiche le formualire de création d'un teacher.
     * 
     * @return void
     */
    public function create(): void
    {
        $domains = Domain::all();
        $this->view('teachers/create', ['domains' => $domains]);
    }

    /**
     * Traite le formualire de création d'un teacher.
     * 
     * @return void
     */
    public function store(): void
    {
        $errors = [];

        $lastnameError = $this->validateLastname($_POST['lastname'] ?? '');
        if ($lastnameError) $errors['lastname'] = $lastnameError;

        $firstnameError = $this->validateFirstname($_POST['firstname'] ?? '');
        if ($firstnameError) $errors['firstname'] = $firstnameError;

        $genderError = $this->validateGender($_POST['gender'] ?? '');
        if ($genderError) $errors['gender'] = $genderError;

        $passwordError = $this->validatePassword($_POST['password'] ?? '');
        if ($passwordError) $errors['password'] = $passwordError;

        $emailError = $this->validateEmail($_POST['email'] ?? '');
        if ($emailError) $errors['email'] = $emailError;

        $phoneError = $this->validatePhone($_POST['phone'] ?? '');
        if ($phoneError) $errors['phone'] = $phoneError;

        if (!isset($_POST['domain_id']) || empty($_POST['domain_id'])) {
            $errors['domain_id'] = 'Le champ domaine est requis.';
        }

        if (!empty($errors)) {
            $domains = Domain::all();
            $this->view('teachers/create', ['domains' => $domains, 'errors' => $errors, 'data' => $_POST]);
            return;
        }

        $data = [
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'gender' => $_POST['gender'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'domain_id' => $_POST['domain_id']
        ];

        if (Teacher::create($data)) {
            $this->redirect('teachers');
        } else {
            $domains = Domain::all();
            $this->view('teachers/create', ['domains' => $domains, 'error' => 'Erreur lors de la création de l’enseignant.']);
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
            header('HTTP/1.1 404 Not Found');
            $this->view('errors/404', []);
            return;
        }
        $domains = Domain::all();
        $this->view('teachers/edit', ['teacher' => $teacher, 'domains' => $domains]);
    }

    /**
     * Traite le formulaire d'édition dun teacher.
     * 
     * @return void
     */
    public function update(): void
    {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if ($id <= 0) {
            header('HTTP/1.1 400 Bad Request');
            $this->view('errors/400', ['error' => 'ID invalide']);
            return;
        }

        $teacher = Teacher::find($id);
        if (!$teacher) {
            header('HTTP/1.1 404 Not Found');
            $this->view('errors/404', []);
            return;
        }

        $errors = [];

        $lastnameError = $this->validateLastname($_POST['lastname'] ?? '');
        if ($lastnameError) $errors['lastname'] = $lastnameError;

        $firstnameError = $this->validateFirstname($_POST['firstname'] ?? '');
        if ($firstnameError) $errors['firstname'] = $firstnameError;

        $genderError = $this->validateGender($_POST['gender'] ?? '');
        if ($genderError) $errors['gender'] = $genderError;

        $emailError = $this->validateEmail($_POST['email'] ?? '', $teacher['user_id']);
        if ($emailError) $errors['email'] = $emailError;

        $phoneError = $this->validatePhone($_POST['phone'] ?? '', $id);
        if ($phoneError) $errors['phone'] = $phoneError;

        if (!isset($_POST['domain_id']) || empty($_POST['domain_id'])) {
            $errors['domain_id'] = 'Le champ domaine est requis.';
        }

        if (!empty($errors)) {
            $domains = Domain::all();
            $this->view('teachers/edit', ['teacher' => $teacher, 'domains' => $domains, 'errors' => $errors, 'data' => $_POST]);
            return;
        }

        $data = [
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'gender' => $_POST['gender'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'domain_id' => $_POST['domain_id']
        ];

        if (Teacher::update($id, $data)) {
            $this->redirect('teachers');
        } else {
            $domains = Domain::all();
            $this->view('teachers/edit', ['teacher' => $teacher, 'domains' => $domains, 'error' => 'Erreur lors de la mise à jour de l’enseignant.']);
        }
    }

    /**
     * Supprime un teacher.
     * 
     * @param int $id
     * 
     * @return void
     */
    public function destroy(int $id): void
    {
        if (Teacher::delete($id)) {
            $this->redirect('teachers');
        } else {
            $teachers = Teacher::all();
            $this->view('teachers/index', ['teachers' => $teachers, 'error' => 'Erreur lors de la suppression de l’enseignant.']);
        }
    }
}