<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Router;
use App\Models\Domain;
use App\Models\Student;
use App\Models\Teacher;
use App\Traits\LastnameValidator;
use App\Traits\FirstnameValidator;
use App\Traits\GenderValidator;
use App\Traits\RoleValidator;
use App\Traits\PasswordValidator;
use App\Traits\EmailValidator;
use App\Traits\PhoneValidator;
use App\Models\User;

class AuthController extends Controller {
    use LastnameValidator, FirstnameValidator, GenderValidator, RoleValidator, PasswordValidator, EmailValidator, PhoneValidator;

    public function __construct(Router $router)
    {
        parent::__construct($router);
    }

    /**
     * Affiche le formulaire création de compte.
     * 
     * @return void
     */
    public function register(): void
    {
        $domains = Domain::all();
        $this->view('auth/register', [
            'domains' => $domains,
            'title' => 'Créer un compte'
        ]);
    }

    /**
     * Traite le formulaire de création de compte.
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

        if (!isset($_POST['domain_id']) || empty($_POST['domain_id'])) {
            $errors['domain_id'] = 'Le champ domaine est requis.';
        }

        if (!isset($_POST['password_confirmation']) || empty($_POST['password_confirmation'])) {
            $errors['password_confirmation'] = "Veuillez confirmer le mot de passe.";
        } elseif ($_POST['password'] !== $_POST['password_confirmation']) {
            $errors['password_confirmation'] = "Les mots de passe ne correspondent pas.";
        }

        if (!empty($errors)) {
            $domains = Domain::all();
            $this->view('auth/register', [
                'domains' => $domains,
                'errors' => $errors,
                'data' => $_POST
            ]);
            return;
        }

        $data = [
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'gender' => $_POST['gender'],
            'email' => $_POST['email'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'domain_id' => $_POST['domain_id']
        ];

        if ($_POST['role'] === 'student') {
            $domain = Domain::find($_POST['domain_id']);
            $data['matricule'] = $this->generateMatricule($domain['code']);
            $success = Student::create($data);
        } else {
            $success = Teacher::create($data);
        }

        if ($success) {
            $this->redirect('login');
        } else {
            $this->redirect('register', ['error' => 'Erreur lors de la création du compte.']);
        }
    }

    /**
     * Affiche le formulaire de connexion.
     */
    public function login(): void
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard($_SESSION['user_role']);
            return;
        }

        $this->view('auth/login', [
            'title' => 'Se connecter'
        ]);
    }

    /**
     * Traite la soumission du formulaire de connexion.
     */
    public function authenticate(): void
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard($_SESSION['user_role']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('error/400', ['message' => 'Méthode non supportée']);
            return;
        }

        $data = [
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? ''
        ];
        $errors = [];

        if (empty($data['email'])) {
            $errors['email'] = 'L\'email est requis.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'L\'email n\'est pas valide.';
        }

        if (empty($data['password'])) {
            $errors['password'] = 'Le mot de passe est requis.';
        }

        if (!empty($errors)) {
            $this->view('auth/login', ['errors' => $errors, 'data' => $data]);
            return;
        }

        $user = User::findByEmail($data['email']);
        if (!$user || !password_verify($data['password'], $user['password'])) {
            $this->view('auth/login', ['errors' => ['general' => 'Email ou mot de passe incorrect.'], 'data' => $data]);
            return;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];

        $this->redirectToDashboard($user['role']);
    }

    /**
     * Redirige vers le tableau de bord en fonction du rôle.
     * 
     * @param string $role
     * @return void
     */
    private function redirectToDashboard(string $role): void
    {
        switch ($role) {
            case 'student':
                $this->redirect('student/dashboard');
                break;
            case 'teacher':
                $this->redirect('teacher/dashboard');
                break;
            case 'admin':
            default:
                $this->redirect('admin/dashboard');
                break;
        }
    }

    /**
     * Affiche les informations de l'utilisateur.
     * 
     * @return void
     */
    public function show(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('error/403', ['message' => 'Vous devez être connecté pour accéder à cette page.']);
            return;
        }

        $user = User::find($_SESSION['user_id']);
        if (!$user) {
            session_destroy();
            $this->redirect('error/404', ['message' => 'Utilisateur non trouvé.']);
            return;
        }

        if ($user['role'] === 'student') {
            $student = Student::findByUserId($user['id']);
            if ($student) {
                $user['matricule'] = $student['matricule'];
            }
        }

        $this->view('auth/profile-show', [
            'user' => $user,
            'title' => 'Profil personnel'
        ]);
    }

    /**
     * Affiche le formulaire de modification du profil.
     * 
     * @return void
     */
    public function profile(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('error/403', ['message' => 'Vous devez être connecté pour accéder à cette page.']);
            return;
        }

        $user = User::find($_SESSION['user_id']);

        $this->view('auth/profile-update', [
            'user' => $user,
            'title' => 'Modifier mon profil'
        ]);
    }

    /**
     * Traite la soumission du formulaire de modification du profil.
     * 
     * @return void
     */
    public function updateProfile(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('error/403', ['message' => 'Vous devez être connecté pour accéder à cette page.']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('error/400', ['message' => 'Méthode non supportée.']);
            return;
        }

        $user = User::find($_SESSION['user_id']);

        $data = [
            'firstname' => $_POST['firstname'] ?? $user['firstname'],
            'lastname' => $_POST['lastname'] ?? $user['lastname'],
            'email' => $_POST['email'] ?? $user['email'],
            'gender' => $_POST['gender'] ?? $user['gender']
        ];
        $errors = [];

        $firstnameError = $this->validateFirstname($data['firstname']);
        if ($firstnameError) $errors['firstname'] = $firstnameError;

        $lastnameError = $this->validateLastname($data['lastname']);
        if ($lastnameError) $errors['lastname'] = $lastnameError;

        $emailError = $this->validateEmail($data['email'], $user['id']);
        if ($emailError) $errors['email'] = $emailError;

        $genderError = $this->validateGender($data['gender']);
        if ($genderError) $errors['gender'] = $genderError;

        if (!empty($errors)) {
            $this->view('auth/profile-update', [
                'user' => $user,
                'errors' => $errors,
                'success' => null,
                'data' => $data,
                'title' => 'Modifier mon profil'
            ]);
            return;
        }

        $updateData = [
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'gender' => $data['gender']
        ];

        $success = User::update($user['id'], $updateData);

        if ($success) {
            $this->redirect('profile-show', ['success' => 'Votre profil a été mis à jour avec succès.']);
        } else {
            $this->redirect('profile-show', ['error' => 'Erreur lors de la mise à jour du profil.']);
        }
    }

    /**
     * Affiche le formulaire de changement de mot de passe.
     * 
     * @return void
     */
    public function changePassword(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('error/403', ['message' => 'Vous devez être connecté pour accéder à cette page.']);
            return;
        }

        $this->view('auth/change-password', [
            'title' => 'Modifier mon mot de passe'
        ]);
    }

    /**
     * Traite la soumission du formulaire de changement de mot de passe.
     * 
     * @return void
     */
    public function updatePassword(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('error/403', ['message' => 'Vous devez être connecté pour accéder à cette page.']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('error/400', ['message' => 'Méthode non supportée.']);
            return;
        }

        $user = User::find($_SESSION['user_id']);
        $errors = [];

        if (empty($_POST['current_password'])) {
            $errors['current_password'] = 'Le mot de passe actuel est requis.';
        } elseif (!password_verify($_POST['current_password'], $user['password'])) {
            $errors['current_password'] = 'Le mot de passe actuel est incorrect.';
        }

        $newPasswordError = $this->validatePassword($_POST['new_password'] ?? '');
        if ($newPasswordError) {
            $errors['new_password'] = $newPasswordError;
        }

        if (empty($_POST['password_confirmation'])) {
            $errors['password_confirmation'] = 'Veuillez confirmer le nouveau mot de passe.';
        } elseif ($_POST['new_password'] !== $_POST['password_confirmation']) {
            $errors['password_confirmation'] = 'Les mots de passe ne correspondent pas.';
        }

        if (!empty($errors)) {
            $this->view('auth/change-password', [
                'errors' => $errors,
                'success' => null,
                'title' => 'Modifier mon mot de passe'
            ]);
            return;
        }

        $success = User::update($user['id'], [
            'password' => password_hash($_POST['new_password'], PASSWORD_DEFAULT)
        ]);

        if ($success) {
            $this->redirect('profile-show', ['success' => 'Votre mot de passe a été mis à jour avec succès.']);
        } else {
            $this->redirect('profile-show', ['error' => 'Erreur lors de la mise à jour du mot de passe.']);
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

    /**
     * Génère le matricule d'un student.
     * 
     * @return string
     */
    private function generateMatricule(string $domainCode): string
    {
        $year = date('Y');
        $sequence = rand(1000, 9999);
        $matricule = "{$domainCode}-{$year}-{$sequence}";

        $existing = Student::findByMatricule($matricule);
        if ($existing) {
            return $this->generateMatricule($domainCode);
        }

        return $matricule;
    }
}