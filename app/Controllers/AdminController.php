<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Router;
use App\Models\Domain;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;

class AdminController extends Controller {
    public function __construct(Router $router)
    {
        parent::__construct($router);
    }

    /**
     * Retourne les infos pour le dashboard de l'admin
     * 
     */

    public function index()
    {
        $data = [
            'domains' => Domain::count(),
            'users' => User::count(),
            'students' => User::countUsersByRole('student'),
            'teachers' => User::countUsersByRole('teacher'),
            'admins' => User::countUsersByRole('admin'),
            'themes' => Student::countThemes(),
            'not_submitted_themes' => Student::countThemesByStatus('non-soumis'),
            'pending_themes' => Student::countThemesByStatus('en-traitement'),
            'validated_themes' => Student::countThemesByStatus('valide'),
            'refused_themes' => Student::countThemesByStatus('rejete'),
        ];

        return $this->view('admin/dashboard', $data);

    }

    /**
     * Liste tous les thèmes en attente de validation.
     * 
     * @return void
     */
    public function pendingThemes(): void
    {
        $students = Student::findByThemeStatus('en-traitement');
        $this->view('admin/pending-themes', ['students' => $students]);
    }

    /**
     * Valide un thème soumis par un étudiant et affecte un encadreur.
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

        if ($student['theme_status'] !== 'en-traitement') {
            $this->redirect('admin/pending-themes');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $teacherId = isset($_POST['teacher_id']) ? intval($_POST['teacher_id']) : null;

            if (!$teacherId) {
                $this->redirect('admin/pending-themes');
                return;
            }

            $teacher = Teacher::find($teacherId);
            if (!$teacher) {
                $this->redirect('admin/pending-themes');
                return;
            }

            $data = [
                'theme_status' => 'validé',
                'teacher_id' => $teacherId
            ];

            if (Student::update($studentId, $data)) {
                $this->redirect('admin/pending-themes');
            } else {
                $this->redirect('admin/pending-themes');
            }
        } else {
            $teachers = Teacher::all();
            $this->view('admin/validate-theme', [
                'student' => $student,
                'teachers' => $teachers
            ]);
        }
    }

    /**
     * Annule un thème soumis par un étudiant.
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

        if ($student['theme_status'] !== 'en-traitement') {
            $this->redirect('admin/pending-themes');
            return;
        }

        $data = [
            'theme_status' => 'non-soumis',
            'theme' => null,
            'cdc' => null,
            'has_binome' => 0,
            'matricule_binome' => null,
            'description' => null,
            'submitted_at' => null
        ];

        if (Student::update($studentId, $data)) {
            $this->redirect('admin/pending-themes');
        } else {
            $this->redirect('admin/pending-themes');
        }
    }

    /**
     * Affecte un encadreur à un étudiant (pour un thème déjà validé).
     * 
     * @param int $studentId
     * @return void
     */
    public function assignSupervisor(int $studentId): void
    {
        $student = Student::find($studentId);
        if (!$student) {
            header('HTTP/1.1 404 Not Found');
            $this->view('errors/404', ['error' => 'Étudiant non trouvé.']);
            return;
        }

        if ($student['theme_status'] !== 'validé') {
            $this->redirect('admin/pending-themes');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $teacherId = isset($_POST['teacher_id']) ? intval($_POST['teacher_id']) : null;

            if (!$teacherId) {
                $this->redirect('admin/pending-themes');
                return;
            }

            $teacher = Teacher::find($teacherId);
            if (!$teacher) {
                $this->redirect('admin/pending-themes');
                return;
            }

            $data = [
                'teacher_id' => $teacherId
            ];

            if (Student::update($studentId, $data)) {
                $this->redirect('admin/pending-themes');
            } else {
                $this->redirect('admin/pending-themes');
            }
        } else {
            $teachers = Teacher::all();
            $this->view('admin/assign-supervisor', [
                'student' => $student,
                'teachers' => $teachers
            ]);
        }
    }
}