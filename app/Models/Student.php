<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class Student extends Model {
    
   /**
     * Liste tous les students.
     * 
     * @return array
     */
    public static function all(): array
    {
        $stmt = parent::getPdo()->query("
            SELECT 
                students.id, 
                students.matricule, 
                students.has_binome, 
                students.matricule_binome, 
                students.theme, 
                students.description, 
                students.theme_status, 
                students.cdc, 
                students.submitted_at, 
                students.last_reminder_at, 
                students.assigned_at, 
                students.user_id, 
                students.domain_id, 
                students.teacher_id,
                users.firstname AS student_firstname, 
                users.lastname AS student_lastname, 
                users.gender AS student_gender, 
                users.email AS student_email,
                domains.label AS domain_label, 
                domains.code AS domain_code,
                users_teacher.firstname AS teacher_firstname, 
                users_teacher.lastname AS teacher_lastname, 
                users_teacher.email AS teacher_email
            FROM students 
            JOIN users ON students.user_id = users.id
            JOIN domains ON students.domain_id = domains.id
            LEFT JOIN teachers ON students.teacher_id = teachers.id
            LEFT JOIN users AS users_teacher ON teachers.user_id = users_teacher.id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Recherche un étudiant grâce à son student_id.
     * 
     * @param int $id
     * @return array|null
     */
    public static function find(int $id): ?array
    {
        $stmt = parent::getPdo()->prepare("
            SELECT 
                students.*,
                users.firstname AS student_firstname,
                users.lastname AS student_lastname,
                users.gender AS student_gender,
                users.email AS student_email,
                domains.label AS domain_label,
                domains.code AS domain_code,
                users_teacher.firstname AS teacher_firstname,
                users_teacher.lastname AS teacher_lastname,
                users_teacher.email AS teacher_email,
                binome.firstname AS binome_firstname,
                binome.lastname AS binome_lastname,
                binome.email AS binome_email,
                binome.created_at AS binome_created_at
            FROM students
            JOIN users ON students.user_id = users.id
            JOIN domains ON students.domain_id = domains.id
            LEFT JOIN teachers ON students.teacher_id = teachers.id
            LEFT JOIN users AS users_teacher ON teachers.user_id = users_teacher.id
            LEFT JOIN students AS binome_students ON students.matricule_binome = binome_students.matricule
            LEFT JOIN users AS binome ON binome_students.user_id = binome.id
            WHERE students.id = ?
        ");
        $stmt->execute([intval($id)]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $student;
    }

    /**
     * Recherche un student grâce à son user_id.
     * 
     * @param int $id
     * 
     * @return array|null
     */
    public static function findByUserId(int $userId): ?array
    {
        $stmt = parent::getPdo()->prepare("
            SELECT 
                students.*,
                users.firstname AS student_firstname,
                users.lastname AS student_lastname,
                users.gender AS student_gender,
                users.email AS student_email,
                domains.label AS domain_label,
                domains.code AS domain_code,
                users_teacher.firstname AS teacher_firstname,
                users_teacher.lastname AS teacher_lastname,
                users_teacher.email AS teacher_email,
                binome.firstname AS binome_firstname,
                binome.lastname AS binome_lastname
            FROM students
            JOIN users ON students.user_id = users.id
            JOIN domains ON students.domain_id = domains.id
            LEFT JOIN teachers ON students.teacher_id = teachers.id
            LEFT JOIN users AS users_teacher ON teachers.user_id = users_teacher.id
            LEFT JOIN students AS binome_students ON students.matricule_binome = binome_students.matricule
            LEFT JOIN users AS binome ON binome_students.user_id = binome.id
            WHERE students.user_id = ?
        ");
        $stmt->execute([intval($userId)]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        return $student;
    }

        /**
     * Recherche un student grâce à son matricule.
     * 
     * @param string $matricule
     * 
     * @return array|null
     */
    public static function findByMatricule(string $matricule): ?array
    {
        $stmt = parent::getPdo()->prepare("SELECT * FROM students WHERE matricule = ?");
        $stmt->execute([$matricule]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Récupère tous les étudiants avec un statut de thème donné.
     * 
     * @param string $themeStatus
     * @return array
     */
    public static function findByThemeStatus(string $themeStatus): array
    {
        $stmt = parent::getPdo()->prepare("
            SELECT students.*, users.firstname, users.lastname, users.email 
            FROM students 
            JOIN users ON students.user_id = users.id 
            WHERE students.theme_status = ?
        ");
        $stmt->execute([$themeStatus]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Permet de récupérer le nombre total de students.
     */
    public static function count(): int
    {
        $stmt = parent::getPdo()->prepare("SELECT COUNT(*) FROM students");
        $stmt->execute();
        $result = $stmt->fetchColumn();
        return (int) $result;
    }

    /**
     * Permet de récupérer le nombre total de thème.
     * 
     * @return int
     */
    public static function countThemes(): int
    {
        $stmt = parent::getPdo()->prepare("SELECT COUNT(DISTINCT theme) FROM students");
        $stmt->execute();
        $result = $stmt->fetchColumn();
        return (int) $result;
    }

    /**
     * Permet de compter le nombre de thèmes suivant le statut.
     * 
     * @param string $themeStatus
     * 
     * @return int
     */
    public static function countThemesByStatus(string $themeStatus): int
    {
        $stmt = parent::getPdo()->prepare("SELECT COUNT(DISTINCT theme) FROM students WHERE theme_status = ?");
        $stmt->execute([$themeStatus]);
        $result = $stmt->fetchColumn();
        return (int) $result;
    }

    /**
     * Retourne les récentes affectations.
     * 
     * @param int $limit
     * 
     * @return array
     */
    public static function getRecentAssignments(): array
    {
        $stmt = parent::getPdo()->prepare("
            SELECT 
                students.*, 
                users.firstname AS student_firstname, 
                users.lastname AS student_lastname, 
                users.gender AS student_gender, 
                users.email AS student_email,
                users_teacher.firstname AS teacher_firstname,
                users_teacher.lastname AS teacher_lastname
            FROM students
            JOIN users ON students.user_id = users.id
            JOIN teachers ON students.teacher_id = teachers.id
            JOIN users AS users_teacher ON teachers.user_id = users_teacher.id
            WHERE students.teacher_id IS NOT NULL
            ORDER BY students.created_at DESC
            LIMIT 4
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Créé un nouvel student.
     * 
     * @param array $data
     * 
     * @return bool
     */
    public static function create(array $data): bool
    {
        $userData = [
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'gender' => $data['gender'],
            'email' => $data['email'],
            'role' => 'student',
            'password' => $data['password']
        ];

        $userStmt = parent::getPdo()->prepare("
            INSERT INTO users (firstname, lastname, gender, email, role, password)
            VALUES (:firstname, :lastname, :gender, :email, :role, :password)
        ");
        if (!$userStmt->execute($userData)) {
            return false;
        }

        $userId = parent::getPdo()->lastInsertId();

        $studentData = [
            'matricule' => $data['matricule'],
            'has_binome' => 0,
            'matricule_binome' => null,
            'theme' => null,
            'description' => null,
            'theme_status' => 'non-soumis',
            'cdc' => null,
            'submitted_at' => null,
            'last_reminder_at' => null,
            'assigned_at' => null,
            'user_id' => $userId,
            'domain_id' => $data['domain_id'] ?? null,
            'teacher_id' => $data['teacher_id'] ?? null
        ];

        if (empty($studentData['matricule']) || empty($studentData['domain_id'])) {
            return false;
        }

        $stmt = parent::getPdo()->prepare("
            INSERT INTO students (
                matricule, has_binome, matricule_binome, theme, description,
                theme_status, cdc, submitted_at, last_reminder_at, assigned_at, user_id, domain_id, teacher_id
            ) VALUES (
                :matricule, :has_binome, :matricule_binome, :theme, :description,
                :theme_status, :cdc, :submitted_at, :last_reminder_at, :assigned_at, :user_id, :domain_id, :teacher_id
            )
        ");
        return $stmt->execute($studentData);
    }

    /**
     * Met à jour un étudiant existant.
     * 
     * @param int $id
     * @param array $data
     * 
     * @return bool
     */
    public static function update(int $id, array $data): bool
    {
        $student = self::find($id);
        if (!$student) {
            error_log("Étudiant ID $id non trouvé.");
            return false;
        }

        // Mise à jour de la table users (champs communs)
        $userFields = [];
        $userData = [];
        $updatableUserFields = ['firstname', 'lastname', 'email', 'gender'];
        foreach ($updatableUserFields as $field) {
            if (array_key_exists($field, $data)) {
                $userFields[] = "`$field` = :$field";
                $userData[$field] = $data[$field];
            }
        }

        if (!empty($userFields)) {
            $userFieldsList = implode(', ', $userFields);
            $userData['id'] = $student['user_id'];
            $sql = "UPDATE users SET $userFieldsList, updated_at = NOW() WHERE id = :id";
            $stmt = parent::getPdo()->prepare($sql);
            if (!$stmt->execute($userData)) {
                error_log("Erreur lors de la mise à jour de la table users : " . json_encode($stmt->errorInfo()));
                return false;
            }
        }

        $studentFields = [];
        $studentData = [];
        $updatableStudentFields = [
            'theme',
            'description',
            'theme_status',
            'cdc',
            'has_binome',
            'matricule_binome',
            'submitted_at',
            'teacher_id',
            'domain_id',
            'last_reminder_at',
            'assigned_at'
        ];
        foreach ($updatableStudentFields as $field) {
            if (array_key_exists($field, $data)) {
                $studentFields[] = "`$field` = :$field";
                $studentData[$field] = $data[$field];
            }
        }

        if (!empty($studentFields)) {
            $studentFieldsList = implode(', ', $studentFields);
            $studentData['id'] = $id;
            $sql = "UPDATE students SET $studentFieldsList, updated_at = NOW() WHERE id = :id";
            
            // Débogage : afficher la requête SQL et les paramètres
            error_log("Requête SQL : $sql");
            error_log("Paramètres : " . json_encode($studentData));

            $stmt = parent::getPdo()->prepare($sql);
            if (!$stmt->execute($studentData)) {
                error_log("Erreur lors de la mise à jour de la table students : " . json_encode($stmt->errorInfo()));
                return false;
            }
        }

        return true;
    }

    /**
     * Supprime un student.
     * 
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $stmt = parent::getPdo()->prepare("SELECT user_id FROM students WHERE id = ?");
        $stmt->execute([intval($id)]);
        $userId = $stmt->fetchColumn();

        if (!$userId) {
            return false;
        } else {
            if (User::delete($userId)) {
                $stmt = parent::getPdo()->prepare("DELETE FROM students WHERE id = ?");
                $stmt->execute([intval($id)]);
            }
        }
        return true;
    }
}