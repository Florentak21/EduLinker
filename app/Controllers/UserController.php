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
use App\Models\User;

class UserController extends Controller {
    use LastnameValidator, FirstnameValidator, GenderValidator, RoleValidator, PasswordValidator, EmailValidator;

    public function __construct(Router $router)
    {
        parent::__construct($router);
    }

    /**
     * Génére le matricule d'un student.
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

    /**
     * Liste tous les users.
     * 
     * @return void
     */
    public function index(): void
    {
        $users = User::all();
        $this->view('admin/users/index', [
            'users' => $users,
            'title' => 'Liste des utilisateurs',
            'active' => 'users',
            'errors' => $_SESSION['errors'] ?? [],
            'success' => $_SESSION['success'] ?? null
        ]);
        unset($_SESSION['errors'], $_SESSION['success']);
    }

    /**
     * Affiche le formulaire création d'un user.
     * 
     * @return void
     */
    public function create(): void
    {
        $domains = Domain::all();
        $this->view('admin/users/create', [
            'domains' => $domains,
            'data' => [],
            'errors' => [],
            'title' => 'Créer un utilisateur',
            'active' => 'users',
            'error' => $_SESSION['error'] ?? null
        ]);
        unset($_SESSION['error']);
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

        if (!isset($_POST['domain_id']) || empty($_POST['domain_id'])) {
            $errors['domain_id'] = 'Le champ domaine est requis.';
        }

        if (!isset($_POST['password_confirmation']) || empty($_POST['password_confirmation'])) {
            $errors['password_confirmation'] = "Veuillez confirmer le mot de passe.";
        } elseif (!empty($_POST['password_confirmation']) && $_POST['password'] !== $_POST['password_confirmation']) {
            $errors['password_confirmation'] = "Les mots de passe ne correspondent pas.";
        }

        if (!empty($errors)) {
            $domains = Domain::all();
            $this->view('admin/users/create', [
                'domains' => $domains,
                'data' => $_POST,
                'errors' => $errors,
                'title' => 'Créer un utilisateur',
                'active' => 'users',
                'error' => $_SESSION['error'] ?? null
            ]);
            unset($_SESSION['error']);
            return;
        }

        /* Création du user suivant son rôle */
        $created = false;
        if ($_POST['role'] === 'student') {
            
            /* Génération du matricule basé sur le domaine d'étude du student. */
            $domain = Domain::find($_POST['domain_id']);
            if (!$domain) {
                $this->redirect('admin/users/create', ['error' => 'Domaine non trouvé.']);
                return;
            }
            $matricule = $this->generateMatricule($domain['code']);

            $data = [
                'matricule' => $matricule,
                'firstname' => $_POST['firstname'],
                'lastname' => $_POST['lastname'],
                'gender' => $_POST['gender'],
                'email' => $_POST['email'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'domain_id' => $_POST['domain_id']
            ];

            $created = Student::create($data);
        } else {
            $data = [
                'firstname' => $_POST['firstname'],
                'lastname' => $_POST['lastname'],
                'gender' => $_POST['gender'],
                'email' => $_POST['email'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'domain_id' => $_POST['domain_id']
            ];
            $created = Teacher::create($data);
        }

        /* Redirection après succès de la création du compte */
        if ($created) {
            $this->redirect('admin/users', ['success' => 'Utilisateur créé avec succès.']);
        } else {
            $this->redirect('admin/users/create', ['error' => 'Erreur lors de la création de l\'utilisateur.']);
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
            $this->redirect('error/404', ['message' => 'Utilisateur non trouvé.']);
            return;
        }
        $this->view('admin/users/edit', [
            'user' => $user,
            'title' => 'Modifier un utilisateur',
            'active' => 'users'
        ]);
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
            $this->redirect('error/400', ['message' => 'ID invalide']);
            return;
        }

        $user = User::find($id);
        if (!$user) {
            $this->redirect('error/404', ['message' => 'Utilisateur non trouvé.']);
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

        if (!empty($errors)) {
            $this->view('admin/users/edit', [
                'errors' => $errors,
                'data' => $_POST,
                'user' => $user,
                'title' => 'Modifier un utilisateur',
                'active' => 'users'
            ]);
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
            $this->redirect('admin/users', ['success' => 'Utilisateur mis à jour avec succès.']);
        } else {
            $this->redirect('admin/users', ['error' => 'Erreur lors de la mise à jour de l\'utilisateur.']);
        }
    }

    /**
     * Supprime un user.
     * 
     * @param int $id
     * @return void
     */
    public function destroy(int $id): void
    {
        $user = User::find($id);
        if (!$user) {
            $this->redirect('admin/users', ['error' => 'Utilisateur non trouvé.']);
            return;
        }

        if (User::delete($id)) {
            $this->redirect('admin/users', ['success' => 'Utilisateur supprimé avec succès.']);
        } else {
            $this->redirect('admin/users', ['error' => 'Erreur lors de la suppression de l\'utilisateur.']);
        }
    }
}