<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Router;
use App\Models\Domain;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Traits\ThemeValidator;
use DateTime;
use Exception;

class AdminController extends Controller {
    use ThemeValidator;
    public function __construct(Router $router)
    {
        parent::__construct($router);
    }

    public function index()
    {
        $data = [
            'domains' => [
                'domains_items' => Domain::all(),
                'domains_count' => Domain::count()
            ],
            'users' => User::count(),
            'students' => User::countUsersByRole('student'),
            'teachers' => User::countUsersByRole('teacher'),
            'themes' => Student::countThemes(),
            'pending_themes' => Student::countThemesByStatus('en-traitement'),
            'validated_themes' => Student::countThemesByStatus('validé'),
            'refused_themes' => Student::countThemesByStatus('rejete'),
            'recent_assignments' => Student::getRecentAssignments()
        ];

        return $this->view('admin/dashboard', [
            'data' => $data,
            'title' => 'Tableau de bord',
        ]);
    }

    public function showTheme(int $studentId): void
    {
        $student = Student::find($studentId);
        if (!$student) {
            header('HTTP/1.1 404 Not Found');
            $this->view('errors/404', ['error' => 'Étudiant non trouvé.']);
            return;
        }

        $this->view('admin/students/show-theme', [
            'student' => $student
        ]);
    }

    /**
     * Affiche le formulaire pour valider le thème d'un student.
     * 
     * @param int $studentId
     * 
     * @return void
     */
    public function validateThemeForm(int $studentId): void
    {
        $student = Student::find($studentId);
        if (!$student) {
            header('HTTP/1.1 404 Not Found');
            $this->view('errors/404', ['error' => 'Étudiant non trouvé.']);
            return;
        }

        $teachers = Teacher::findByDomains($student['domain_id']);
        $data = [
            'theme' => $student['theme'],
            'description' => $student['description']
        ];
        $this->view('admin/students/validate-theme', [
            'student' => $student,
            'teachers' => $teachers,
            'data' => $data,
            'title' => 'Validation de thème'
        ]);
    }

    /**
     * Valide le thème et le CDC d'un étudiant.
     * 
     * @return void
     */
    public function validateThemeProcess(): void
    {
        $student = Student::find(htmlspecialchars($_POST['student_id'] ?? ''));
        if (!$student) {
            header('HTTP/1.1 404 Not Found');
            $this->view('errors/404', ['error' => 'Étudiant non trouvé.']);
            return;
        }

        if ($student['theme_status'] !== 'en-traitement' || empty($student['theme']) || empty($student['cdc'])) {
            $this->redirect('admin/students', ['error' => 'Le thème ne peut pas être validé : aucune soumission en cours ou données manquantes.']);
            return;
        }

        // Traitement du formulaire de validation de thème
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {    
            $errors = [];
            $data = [];

            // Récupérer et nettoyer les données du formulaire
            $data['theme'] = htmlspecialchars(trim($_POST['theme'] ?? ''));
            $data['description'] = htmlspecialchars(trim($_POST['description'] ?? ''));
            $teacherId = isset($_POST['teacher_id']) ? intval(htmlspecialchars($_POST['teacher_id'])) : null;

            // Validation
            if (!$teacherId) {
                $errors['teacher_id'] = 'Veuillez sélectionner un encadreur.';
            } else {
                $teacher = Teacher::find($teacherId);
                if (!$teacher) {
                    $errors['teacher_id'] = 'Encadreur non trouvé.';
                }
            }
            $errors['theme'] = $this->validateTheme('theme', $data['theme']);
            $errors['description'] = $this->validateTheme('description', $data['description']);

            // Nettoyer les erreurs vides
            $errors = array_filter($errors);

            // Débogage : vérifier les données soumises
            error_log("Données soumises : " . json_encode($data));
            error_log("Erreurs détectées : " . json_encode($errors));

            if (!empty($errors)) {
                $teachers = Teacher::findByDomains($student['domain_id']);
                $this->view('admin/students/validate-theme', [
                    'student' => $student,
                    'teachers' => $teachers,
                    'errors' => $errors,
                    'data' => $data,
                    'title' => 'Validation de thème'
                ]);
                return;
            }

            $updateData = [
                'theme' => $data['theme'],
                'description' => $data['description'],
                'theme_status' => 'valide',
                'assigned_at' => (new DateTime())->format('Y-m-d H:i:s'),
                'teacher_id' => $teacherId
            ];

            // Débogage : vérifier les données à mettre à jour
            error_log("Données pour mise à jour (ID {$student['id']}) : " . json_encode($updateData));

            // Mettre à jour l'étudiant principal
            try {
                if (Student::update($student['id'], $updateData)) {

                    // Mettre à jour les infos du binôme s'il en a
                    if ($student['has_binome'] && !empty($student['matricule_binome'])) {
                        $binome = Student::findByMatricule($student['matricule_binome']);
                        if ($binome) {
                            if (!Student::update($binome['id'], $updateData)) {
                                $this->redirect('admin/students', ['error' => 'Thème validé pour l\'étudiant, mais erreur lors de la mise à jour du binôme.']);
                                return;
                            }
                        } else {
                            $this->redirect('admin/students', ['error' => 'Thème validé, mais le binôme n\'a pas été trouvé.']);
                            return;
                        }
                    }
                    $this->redirect('admin/students', ['success' => 'Thème validé avec succès.']);
                } else {
                    $teachers = Teacher::findByDomains($student['domain_id']);
                    $this->view('admin/students/validate-theme', [
                        'student' => $student,
                        'teachers' => $teachers,
                        'errors' => ['general' => 'Erreur lors de la validation du thème : ' . ($errorInfo[2] ?? 'Erreur inconnue')],
                        'data' => $data,
                        'title' => 'Validation de thème'
                    ]);
                }
            } catch (Exception $e) {
                $teachers = Teacher::findByDomains($student['domain_id']);
                $this->view('admin/students/validate-theme', [
                    'student' => $student,
                    'teachers' => $teachers,
                    'errors' => ['general' => 'Erreur lors de la validation du thème : '],
                    'data' => $data,
                    'title' => 'Validation de thème'
                ]);
            }
        }
    }

    /**
     * Permet de rejeter le thème d'un étudiant.
     * 
     * @param int $studentId
     * @return void
     */
    public function cancelTheme(): void
    {
        // Traitement du formulaire de rejet de theme
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 400 Bad Request');
            $this->view('errors/400', [
                'title' => 'Méthode non supportée',
                'active' => ''
            ]);
            return;
        }

        if(!isset($_POST['student_id']) || empty($_POST['student_id'])){
            $this->redirect('admin/students', [
                'error' => 'Impossible de rejeter le thème.'
            ]);
        } else {
            $student = Student::find(htmlspecialchars($_POST['student_id']));
            if (!$student) {
                header('HTTP/1.1 404 Not Found');
                $this->view('errors/404', ['error' => 'Étudiant non trouvé.']);
                return;
            }
        }

        // Vérifier si le thème peut être annulé
        if ($student['theme_status'] === 'non-soumis') {
            $this->redirect('admin/students', ['error' => 'Le thème est déjà rejeté ou non soumis.']);
            return;
        }

        // Supprimer le fichier CDC s'il existe
        if (!empty($student['cdc'])) {
            $filePath = dirname(__DIR__, 2) . '/storage/' . $student['cdc'];
            if (file_exists($filePath)) {
                if (!unlink($filePath)) {
                    $this->redirect('admin/students', ['error' => 'Erreur lors de la suppression du fichier CDC.']);
                    return;
                }
            }
        }

        $data = [
            'theme_status' => 'non-soumis',
            'theme' => null,
            'description',
            'cdc' => null,
            'has_binome' => 0,
            'matricule_binome' => null,
            'submitted_at' => null,
        ];

        // Mettre à jour l'étudiant principal
        if (!Student::update($student['id'], $data)) {
            $this->redirect('admin/students', ['error' => 'Erreur lors du rejet du thème pour l\'étudiant.']);
            return;
        }

        // Mettre à jour le binôme s'il existe
        if ($student['has_binome'] && !empty($student['matricule_binome'])) {
            $binome = Student::findByMatricule($student['matricule_binome']);
            if ($binome) {
                if (!Student::update($binome['id'], $data)) {
                    $this->redirect('admin/students', ['error' => 'Thème rejeté pour l\'étudiant, mais erreur lors de la mise à jour du binôme.']);
                    return;
                }
            } else {
                $this->redirect('admin/students', ['error' => 'Thème rejeté, mais le binôme n\'a pas été trouvé.']);
                return;
            }
        }

        $this->redirect('admin/students', ['success' => 'Thème rejeté avec succès.']);
    }
}