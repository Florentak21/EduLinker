<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Router;
use App\Models\Domain;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use DateTime;

class AdminController extends Controller {
    public function __construct(Router $router)
    {
        parent::__construct($router);
    }

    public function index()
    {
        $data = [
            'domains' => Domain::count(),
            'users' => User::count(),
            'students' => User::countUsersByRole('student'),
            'teachers' => User::countUsersByRole('teacher'),
            'themes' => Student::countThemes(),
            'pending_themes' => Student::countThemesByStatus('en-traitement'),
            'validated_themes' => Student::countThemesByStatus('validé'),
            'refused_themes' => Student::countThemesByStatus('rejete'),
            'recent_assignments' => Student::getRecentAssignments()
        ];

        return $this->view('admin/dashboard', ['data' => $data,
            'title' => 'Tableau de bord',
        ]);
    }

    /**
     * Valide le thème et le CDC d'un étudiant.
     * 
     * @param int $studentId
     * @return void
     */
    public function validateTheme(int $studentId): void
    {
        $student = Student::find($studentId);
        if (!$student) {
            header('HTTP/1.1 404 Not Found');
            $this->view('errors/404', ['error' => 'Étudiant non trouvé.']);
            return;
        }

        if ($student['theme_status'] !== 'en-traitement' || empty($student['theme']) || empty($student['cdc'])) {
            $this->redirect('admin/students', ['error' => 'Le thème ne peut pas être validé : aucune soumission en cours ou données manquantes.']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $teacherId = isset($_POST['teacher_id']) ? intval($_POST['teacher_id']) : null;

            if (!$teacherId) {
                $this->redirect('admin/validate-theme/' . $studentId, ['error' => 'Veuillez sélectionner un encadreur.']);
                return;
            }

            $teacher = Teacher::find($teacherId);
            if (!$teacher) {
                $this->redirect('admin/validate-theme/' . $studentId, ['error' => 'Encadreur non trouvé.']);
                return;
            }

            $data = [
                'theme_status' => 'validé',
                'assigned_at' => (new DateTime())->format('Y-m-d H:i:s'),
                'teacher_id' => $teacherId
            ];

            // Mettre à jour l'étudiant principal
            if (Student::update($studentId, $data)) {

                // Mettre à jour les infos du binôme s'il en a
                if ($student['has_binome'] && !empty($student['matricule_binome'])) {
                    $binome = Student::findByMatricule($student['matricule_binome']);
                    if ($binome) {
                        if (!Student::update($binome['id'], $data)) {
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
                $this->redirect('admin/validate-theme/' . $studentId, ['error' => 'Erreur lors de la validation du thème.']);
            }
        } else {
            $teachers = Teacher::findByDomains($student['domain_id']);
            $this->view('admin/students/validate-theme', [
                'student' => $student,
                'teachers' => $teachers,
                'title' => 'Validation de thème'
            ]);
        }
    }

    /**
     * Permet de rejeter le thème d'un étudiant.
     * 
     * @param int $studentId
     * @return void
     */
    public function cancelTheme(int $studentId): void
    {
        $student = Student::find($studentId);
        if (!$student) {
            header('HTTP/1.1 404 Not Found');
            $this->view('errors/404', ['error' => 'Étudiant non trouvé.']);
            return;
        }

        // Vérifier si le thème peut être annulé
        if ($student['theme_status'] === 'non-soumis') {
            $this->redirect('admin/students', ['error' => 'Le thème est déjà annulé ou non soumis.']);
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
            'cdc' => null,
            'has_binome' => 0,
            'matricule_binome' => null,
            'submitted_at' => null,
        ];

        // Mettre à jour l'étudiant principal
        if (!Student::update($studentId, $data)) {
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