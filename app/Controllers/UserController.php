<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Router;
use App\Traits\LastnameValidator;
use App\Traits\FirstnameValidator;
use App\Traits\GenderValidator;
use App\Traits\RoleValidator;
use App\Traits\PasswordValidator;
use App\Traits\EmailValidator;
use App\Traits\PhoneValidator;
use App\Models\User;

class UserController extends Controller {
    use LastnameValidator, FirstnameValidator, GenderValidator, RoleValidator, PasswordValidator, EmailValidator, PhoneValidator;

    public function __construct(Router $router)
    {
        parent::__construct($router);
    }

    /**
     * Liste tous les users.
     * 
     * @return void
     */
    public function index(): void
    {
        $users = User::all();
        $this->view('users/index', ['users' => $users]);
    }

    /**
     * Affiche le formulaire création d'un user.
     * 
     * @return void
     */
    public function create(): void
    {
        $this->view('users/create', []);
    }

    /**
     * Traite le formulaire de création d'un user.
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

        $roleError = $this->validateRole($_POST['role'] ?? '');
        if ($roleError) $errors['role'] = $roleError;

        $passwordError = $this->validatePassword($_POST['password'] ?? '');
        if ($passwordError) $errors['password'] = $passwordError;

        $emailError = $this->validateEmail($_POST['email'] ?? '');
        if ($emailError) $errors['email'] = $emailError;

        if (!empty($errors)) {
            $this->view('users/create', ['errors' => $errors, 'data' => $_POST]);
            return;
        }

        $data = [
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'gender' => $_POST['gender'],
            'email'  => $_POST['email'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'role' => $_POST['role']
        ];

        if (User::create($data)) {
            $this->redirect('users');
        } else {
            $this->view('users/create', ['error' => 'Erreur lors de la création de l’utilisateur.']);
        }
    }

    /**
     * Affiche le formulaire d'édition d'un user.
     * 
     * @param int $id
     * 
     * @return void
     */
    public function edit(int $id): void
    {
        $user = User::find($id);
        if (!$user) {
            header('HTTP/1.1 404 Not Found');
            $this->view('errors/404', []);
            return;
        }
        $this->view('users/edit', ['user' => $user]);
    }

    /**
     * Traite le formulaire d'édition d'un user.
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

        $user = User::find($id);
        if (!$user) {
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

        $roleError = $this->validateRole($_POST['role'] ?? '');
        if ($roleError) $errors['role'] = $roleError;

        $emailError = $this->validateEmail($_POST['email'] ?? '', $id);
        if ($emailError) $errors['email'] = $emailError;

        $phoneError = $this->validatePhone($_POST['phone'] ?? '', $id);
        if ($phoneError) $errors['phone'] = $phoneError;

        if (!empty($errors)) {
            $this->view('users/edit', ['errors' => $errors, 'data' => $_POST, 'user' => $user]);
            return;
        }

        $data = [
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'gender' => $_POST['gender'],
            'email'  => $_POST['email'],
            'role' => $_POST['role']
        ];

        if (User::update($id, $data)) {
            $this->redirect('users');
        } else {
            $this->view('users/edit', ['error' => 'Erreur lors de la mise à jour de l’utilisateur.', 'user' => $user]);
        }
    }

    /**
     * Supprime un user.
     * 
     * @param int $id
     * 
     * @return void
     */
    public function destroy(int $id): void
    {
        if (User::delete($id)) {
            $this->redirect('users');
        } else {
            $users = User::all();
            $this->view('users/index', ['users' => $users, 'error' => 'Erreur lors de la suppression de l’utilisateur.']);
        }
    }
}