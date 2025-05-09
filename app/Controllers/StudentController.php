<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Router;
use App\Traits\PasswordValidator;
use App\Traits\ThemeValidator;
use App\Traits\CdcValidator;
use App\Traits\MatriculeValidator;
use App\Traits\BinomeMatriculeValidator;
use App\Traits\PhoneValidator;
use App\Models\Student;
use App\Models\Domain;
use App\Traits\EmailValidator;
use App\Traits\FirstnameValidator;
use App\Traits\GenderValidator;
use App\Traits\LastnameValidator;
use App\Traits\RoleValidator;

class StudentController extends Controller {
    use LastnameValidator, FirstnameValidator, GenderValidator, RoleValidator, PasswordValidator, EmailValidator, PhoneValidator, ThemeValidator, CdcValidator, MatriculeValidator, BinomeMatriculeValidator;

    public function __construct(Router $router)
    {
        parent::__construct($router);
    }

    /**
     * Affiche tous les students.
     * 
     * @return void
     */
    public function index(): void
    {
        $students = Student::all();
        $this->view('students/index', ['students' => $students]);
    }

    /**
     * Affiche le formulaire de création d'un student.
     * 
     * @return void
     */
    public function create(): void
    {
        $domains = Domain::all();
        $this->view('students/create', ['domains' => $domains]);
    }

    /**
     * Traite le formulaire de création d'un student.
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

        $emailError = $this->validateEmail($_POST['email'] ?? '');
        if ($emailError) $errors['email'] = $emailError;

        $phoneError = $this->validatePhone($_POST['phone'] ?? '');
        if ($phoneError) $errors['phone'] = $phoneError;

        $passwordError = $this->validatePassword($_POST['password'] ?? '');
        if ($passwordError) $errors['password'] = $passwordError;

        if (!isset($_POST['domain_id']) || empty($_POST['domain_id'])) {
            $errors['domain_id'] = 'Le champ domaine est requis.';
        }

        /* Génération du matricule basé sur le doamine d'étude du student. */
        $domain = Domain::find($_POST['domain_id']);
        $matricule = $this->generateMatricule($domain['code']);

        $data = [
            'matricule' => $matricule,
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'gender' => $_POST['gender'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'domain_id' => $_POST['domain_id']
        ];

        if (!empty($errors)) {
            $domains = Domain::all();
            $this->view('students/create', ['domains' => $domains, 'errors' => $errors, 'data' => $_POST]);
            return;
        }

        if (Student::create($data)) {
            $this->redirect('students');
        } else {
            $this->view('students/create', ['error' => 'Erreur lors de la création de l’étudiant.']);
        }
    }

    /**
     * Affiche le formulaire d'édition d'un student.
     * 
     * @param int $id
     * 
     * @return void
     */
    public function edit(int $id): void
    {
        $student = Student::find($id);
        if (!$student) {
            header('HTTP/1.1 404 Not Found');
            $this->view('errors/404');
            return;
        }
        $domains = Domain::all();
        $this->view('students/edit', ['student' => $student, 'domains' => $domains]);
    }

    /**
     * Traite le formulaire d'édition d'un student.
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

        $student = Student::find($id);
        if (!$student) {
            header('HTTP/1.1 404 Not Found');
            $this->view('errors/404');
            return;
        }

        $errors = [];

        $lastnameError = $this->validateLastname($_POST['lastname'] ?? '');
        if ($lastnameError) $errors['lastname'] = $lastnameError;

        $firstnameError = $this->validateFirstname($_POST['firstname'] ?? '');
        if ($firstnameError) $errors['firstname'] = $firstnameError;

        $genderError = $this->validateGender($_POST['gender'] ?? '');
        if ($genderError) $errors['gender'] = $genderError;

        $emailError = $this->validateEmail($_POST['email'] ?? '', $student['user_id']);
        if ($emailError) $errors['email'] = $emailError;

        $phoneError = $this->validatePhone($_POST['phone'] ?? '', $id);
        if ($phoneError) $errors['phone'] = $phoneError;

        if (!isset($_POST['domain_id']) || empty($_POST['domain_id'])) {
            $errors['domain_id'] = 'Le champ domaine est requis.';
        }

        if (!empty($errors)) {
            $domains = Domain::all();
            $this->view('students/edit', ['student' => $student, 'domains' => $domains, 'errors' => $errors, 'data' => $_POST]);
            return;
        }

        $data = [
            'firstname' => $_POST['firstname'] ?? null,
            'lastname' => $_POST['lastname'] ?? null,
            'gender' => $_POST['gender'] ?? null,
            'email' => $_POST['email'] ?? null,
            'phone' => $_POST['phone'] ?? null,
            'domain_id' => $_POST['domain_id']
        ];

        if (Student::update($id, $data)) {
            $this->redirect('students');
        } else {
            $domains = Domain::all();
            $this->view('students/edit', ['student' => $student, 'domains' => $domains, 'error' => 'Erreur lors de la mise à jour de l’étudiant.']);
        }
    }

    /**
     * Suuprime un student.
     * 
     * @param int $id
     * 
     * @return void
     */
    public function destroy(int $id): void
    {
        if (Student::delete($id)) {
            $students = Student::all();
            $this->view('students/index', ['students' => $students, 'success' => 'La suppression de l\'étudiant a été effectuée avec succès']);
        } else {
            $students = Student::all();
            $this->view('students/index', ['students' => $students, 'error' => 'Erreur lors de la suppression de l\'étudiant.']);
        }
    }

    /**
     * Traite le formulaire de soumission de CDC.
     * 
     * @param int $id
     * 
     * @return void
     */
    public function submitCdc(int $id): void
    {
        $student = Student::find($id);
        if (!$student) {
            header('HTTP/1.1 404 Not Found');
            $this->view('errors/404');
            return;
        }

        // Vérifier si une soumission a déjà été faite
        if ($student['theme_status'] !== 'non-soumis') {
            $this->view('students/submit-cdc', ['student' => $student, 'error' => 'Une soumission a déjà été effectuée pour cet étudiant.']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];

            $themeError = $this->validateTheme($_POST['theme'] ?? '');
            if ($themeError) $errors['theme'] = $themeError;

            $cdcError = $this->validateCdc($_FILES['cdc_file'] ?? []);
            if ($cdcError) $errors['cdc_file'] = $cdcError;

            $hasBinome = isset($_POST['has_binome']) && $_POST['has_binome'] == '1';
            if (!isset($_POST['has_binome']) || !in_array($_POST['has_binome'], ['0', '1'])) {
                $errors['has_binome'] = 'La valeur de "A un binôme ?" est invalide.';
            }

            $binomeMatriculeError = $this->validateBinomeMatricule($_POST['matricule_binome'] ?? '', $hasBinome);
            if ($binomeMatriculeError) $errors['matricule_binome'] = $binomeMatriculeError;

            if ($hasBinome && $_POST['matricule_binome'] === $student['matricule']) {
                $errors['matricule_binome'] = 'Vous ne pouvez pas vous choisir comme binôme.';
            }

            if (!empty($errors)) {
                $this->view('students/submit-cdc', ['student' => $student, 'errors' => $errors, 'data' => $_POST]);
                return;
            }

            $file = $_FILES['cdc_file'];
            $fileName = uniqid() . '_' . basename($file['name']);
            $uploadDir = __DIR__ . '/../../public/uploads/';
            $uploadPath = $uploadDir . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $data = [
                    'theme' => $_POST['theme'],
                    'theme_status' => 'en-traitement',
                    'cdc' => $fileName,
                    'has_binome' => $hasBinome ? 1 : 0,
                    'matricule_binome' => $hasBinome ? ($_POST['matricule_binome'] ?? null) : null,
                    'description' => $_POST['description']
                ];
                if (Student::update($id, $data)) {
                    $this->redirect('students');
                } else {
                    $this->view('students/submit-cdc', ['student' => $student, 'error' => 'Erreur lors de la soumission.']);
                }
            } else {
                $this->view('students/submit-cdc', ['student' => $student, 'error' => 'Erreur lors de l’upload du fichier.']);
            }
        } else {
            $this->view('students/submit-cdc', ['student' => $student]);
        }
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
}