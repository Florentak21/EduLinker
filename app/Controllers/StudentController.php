<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Router;
use App\Models\Domain;
use App\Traits\PasswordValidator;
use App\Traits\ThemeValidator;
use App\Traits\CdcValidator;
use App\Traits\MatriculeValidator;
use App\Traits\BinomeMatriculeValidator;
use App\Models\Student;
use App\Models\Teacher;
use App\Traits\EmailValidator;
use App\Traits\FirstnameValidator;
use App\Traits\GenderValidator;
use App\Traits\LastnameValidator;
use App\Traits\RoleValidator;
use DateTime;
use Exception;

class StudentController extends Controller {
    use LastnameValidator, FirstnameValidator, GenderValidator, RoleValidator, PasswordValidator, EmailValidator, ThemeValidator, CdcValidator, MatriculeValidator, BinomeMatriculeValidator;

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
        $this->view('/admin/students/index', [
            'students' => $students,
            'title' => 'Gestion des étudiants'
        ]);
    }

    /**
     * Affiche le tableau de bord de l'étudiant.
     * 
     * @return void
     */
    public function dashboard(): void
    {
        $student = Student::findByUserId($_SESSION['user_id']);
        if (!$student) {
            header('HTTP/1.1 404 Not Found');
            $this->view('errors/404', ['error' => 'Étudiant non trouvé.']);
            return;
        }

        $this->view('student/dashboard', [
            'student' => $student,
            'title' => 'Tableau de bord'
        ]);
    }

    /**
     * Traite la soumission d'un cahier de charge.
     * 
     * @return void
     */
    public function submitCdc(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }

        $student = Student::findByUserId($_SESSION['user_id']);
        if (!$student) {
            header('HTTP/1.1 404 Not Found');
            $this->view('errors/404', ['error' => 'Étudiant non trouvé.']);
            return;
        }

        if (isset($student['theme_status']) && $student['theme_status'] !== 'non-soumis') {
            $this->redirect('student/dashboard', ['error' => 'Une soumission a déjà été effectuée.']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];

            $errors['theme'] = $this->validateTheme($_POST['theme'] ?? '');
            $errors['cdc_file'] = $this->validateCdc($_FILES['cdc_file'] ?? []);
            $hasBinome = isset($_POST['has_binome']) && $_POST['has_binome'] === '1';
            if (!isset($_POST['has_binome']) || !in_array($_POST['has_binome'], ['0', '1'])) {
                $errors['has_binome'] = 'La valeur de "A un binôme ?" est invalide.';
            }
            $errors['matricule_binome'] = $this->validateBinomeMatricule($_POST['matricule_binome'] ?? '', $hasBinome);
            if ($hasBinome && $_POST['matricule_binome'] === $student['matricule']) {
                $errors['matricule_binome'] = 'Vous ne pouvez pas vous choisir comme binôme.';
            }

            $errors = array_filter($errors);

            if (!empty($errors)) {
                $this->view('student/submit-cdc', ['student' => $student, 'errors' => $errors, 'data' => $_POST]);
                return;
            }

            $file = $_FILES['cdc_file'];
            $domain = Domain::find($student['domain_id']);
            if (!$domain) {
                $this->view('student/submit-cdc', ['student' => $student, 'error' => 'Domaine non trouvé pour cet étudiant.']);
                return;
            }
            $fileName = 'cahier_de_charge_' . $student['matricule'] . '.pdf';
            $uploadDir = dirname(__DIR__, 2) . '/storage';
            $uploadPath = $uploadDir . '/' . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $data = [
                    'theme' => htmlspecialchars($_POST['theme']),
                    'theme_status' => 'en-traitement',
                    'cdc' => $fileName,
                    'has_binome' => $hasBinome ? 1 : 0,
                    'matricule_binome' => $hasBinome ? htmlspecialchars(($_POST['matricule_binome'])) : null,
                    'submitted_at' => (new DateTime())->format('Y-m-d H:i:s')
                ];


                try {
                    if (Student::update($student['id'], $data)) {

                        // S'il a un binome on met à jour les infos du binome aussi
                        if ($hasBinome) {
                            $binome = Student::findByMatricule(htmlspecialchars(($_POST['matricule_binome'])));
                            $data = [
                                'theme' => htmlspecialchars($_POST['theme']),
                                'theme_status' => 'en-traitement',
                                'cdc' => $fileName,
                                'has_binome' => 1,
                                'matricule_binome' => $student['matricule'],
                                'submitted_at' => (new DateTime())->format('Y-m-d H:i:s')
                            ];
                            Student::update($binome['id'], $data);
                        }

                        $this->redirect('student/submit-cdc', ['success' => 'CDC soumis avec succès.']);
                    } else {
                        $errorInfo = Student::getPdo()->errorInfo();
                        error_log("Erreur lors de la mise à jour de l'étudiant : " . json_encode($errorInfo));
                        $this->redirect('student/dashboard', ['error' => 'Erreur lors de la soumission.']);
                    }
                } catch (Exception $e) {
                    error_log("Exception lors de la mise à jour de l'étudiant : " . $e->getMessage());
                    $this->redirect('student/dashboard', ['error' => 'Erreur lors de la soumission.']);
                }
            } else {
                $this->redirect('student/dashboard', ['error' => 'Erreur lors de l’upload du fichier.']);
            }
        } else {
            $this->view('student/submit-cdc', ['student' => $student]);
        }
    }

    /**
     * Permet à l'étudiant de relancer la soumission après 7 jours
     * si sa demande n'a pas été traitée.
     * 
     * @return void
     */
    public function remind(): void
    {
        $student = Student::findByUserId($_SESSION['user_id']);
        if (!$student) {
            header('HTTP/1.1 404 Not Found');
            $this->view('errors/404', ['error' => 'Étudiant non trouvé.']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('student/dashboard');
            return;
        }

        if ($student['theme_status'] !== 'en-traitement' || !$student['submitted_at']) {
            $this->redirect('student/dashboard');
            return;
        }

        // Vérifier si 7 jours se sont écoulés depuis la soumission
        if (!$this->canRemind($student['submitted_at'])) {
            $this->redirect('student/dashboard');
            return;
        }

        // Mettre à jour last_reminder_at
        $now = new DateTime();
        $updateData = ['last_reminder_at' => $now->format('Y-m-d H:i:s')];
        if (Student::update($student['id'], $updateData)) {
            $this->redirect('student/dashboard');
        } else {
            $this->redirect('student/dashboard');
        }
    }

    /**
     * Vérifie si l'étudiant peut relancer (7 jours après la soumission).
     * 
     * @param string $submittedAt Date de soumission
     * @return bool
     */
    private function canRemind(string $submittedAt): bool
    {
        $submittedDate = new DateTime($submittedAt);
        $now = new DateTime();
        $interval = $submittedDate->diff($now);
        return $interval->days >= 7;
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
            $this->view('errors/404', [
                'title' => 'Page non trouvée',
                'active' => ''
            ]);
            return;
        }

        $this->view('admin/students/edit', [
            'student' => $student,
            'active' => 'students',
            'domains' => Domain::all(),
            'teachers' => Teacher::findByDomains($student['domain_id']),
            'title' => 'Mêttre à jour un étudiant'
        ]);
    }

    /**
     * Traite le formulaire d'édition d'un teacher.
     * 
     * @return void
     */
    public function update(): void
    {
        $student = Student::find(intval(htmlspecialchars($_POST['id'])));
        if (!$student) {
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

        $emailError = $this->validateEmail($_POST['email'], $student['user_id']);
        if ($emailError) $errors['email'] = $emailError;

        if (!isset($_POST['domain_id']) || empty($_POST['domain_id'])) {
            $errors['domain_id'] = 'Le domaine est requis';
        }
        
        if (!isset($_POST['teacher_id']) || empty($_POST['teacher_id'])) {
            $errors['teacher_id'] = 'L\'encadreur est requis';
        }

        if (!empty($errors)) {
            $this->view('admin/students/edit', [
                'errors' => $errors,
                'data' => $_POST,
                'student' => $student,
                'domains' => Domain::all(),
                'teachers' => Teacher::findByDomains($student['domain_id']),
                'title' => 'Mêttre à jour un étudiant',
                'active' => 'students'
            ]);
            return;
        }

        $data = [
            'firstname' => htmlspecialchars($_POST['firstname']),
            'lastname' => htmlspecialchars($_POST['lastname']),
            'gender' => htmlspecialchars($_POST['gender']),
            'email'  => htmlspecialchars($_POST['email']),
            'domain_id' => htmlspecialchars($_POST['domain_id']),
            'teacher_id' => htmlspecialchars($_POST['teacher_id'])
        ];

        if (Student::update($student['id'], $data)) {
            $this->redirect('admin/students', ['success' => 'Etudiant mis à jour avec succès.']);
        } else {
            $this->redirect('admin/students', ['error' => 'Erreur lors de la mise à jour de l\'étudiant.']);
        }
    }

    /**
     * Supprime un student.
     * 
     * @param int $id
     * @return void
     */
    public function destroy(int $id): void
    {
        $student = Student::find($id);
        if (!$student) {
            $students = Student::all();
            $this->view('admin/students/index', ['students' => $students, 'error' => 'Étudiant non trouvé.']);
            return;
        }

        if ($student['cdc'] !== null) {
            $filePath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR .  $student['cdc'];
    
            $fileError = null;
            if (file_exists($filePath)) {
                if (!is_writable($filePath)) {
                    $fileError = "Le fichier $filePath n'est pas accessible en écriture.";
                } elseif (!unlink($filePath)) {
                    $fileError = "Erreur lors de la suppression du fichier $filePath.";
                }
            }
        }

        if (Student::delete($id)) {
            $students = Student::all();
            $successMessage = 'La suppression de l\'étudiant a été effectuée avec succès';
            if ($fileError) {
                $successMessage .= ' (mais son cdc n\' a pas pu etre supprimé) : ' . $fileError . ').';
            }
            $this->view('admin/students/index', ['students' => $students, 'success' => $successMessage]);
        } else {
            $students = Student::all();
            $errorMessage = 'Erreur lors de la suppression de l\'étudiant.';
            if ($fileError) {
                $errorMessage .= ' Une erreur est aussi survenue lors de la suppression du fichier : ' . $fileError . '.';
            }
            $this->view('admin/students/index', ['students' => $students, 'error' => $errorMessage]);
        }
    }
}