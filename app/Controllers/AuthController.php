<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Router;
use App\Models\User;

class AuthController extends Controller {
    public function __construct(Router $router)
    {
        parent::__construct($router);
    }

    /**
     * Affiche le formulaire de connexion.
     */
    public function login(): void
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('users');
            return;
        }

        $this->view('auth/login', []);
    }

    /**
     * Traite la soumission du formulaire de connexion.
     */
    public function authenticate(): void
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('users');
            return;
        }

        $errors = [];

        if (!isset($_POST['email']) || empty($_POST['email'])) {
            $errors['email'] = 'L\'email est requis.';
        }

        if (!isset($_POST['password']) || empty($_POST['password'])) {
            $errors['password'] = 'Le mot de passe est requis.';
        }

        if (!empty($errors)) {
            $this->view('auth/login', ['errors' => $errors, 'data' => $_POST]);
            return;
        }

        $email = $_POST['email'];
        $password = $_POST['password'];
        $user = User::findByEmail($email);

        if (!$user) {
            $this->view('auth/login', ['error' => 'Email ou mot de passe incorrect.', 'data' => $_POST]);
            return;
        }

        if (!password_verify($password, $user['password'])) {
            $this->view('auth/login', ['error' => 'Email ou mot de passe incorrect.', 'data' => $_POST]);
            return;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];

        if($_SESSION['user_role'] === 'student')
        {
            $this->redirect('students');
        }
        else if ($_SESSION['user_role'] === 'teacher')
        {
            $this->redirect('teachers');
        }
        else
        {
            $this->redirect('users');
        }

    }

    /**
     * Déconnecte l'utilisateur.
     */
    public function logout(): void
    {
        session_unset();
        session_destroy();

        $this->redirect('login');
    }
}