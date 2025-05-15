<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class Teacher extends Model {

    /**
     * Liste tous les teachers.
     * 
     * @return array
     */
    public static function all(): array
    {
        $stmt = parent::getPdo()->query("
            SELECT DISTINCT teachers.*, users.firstname, users.lastname, users.gender, users.email
            FROM teachers 
            JOIN users ON teachers.user_id = users.id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Recherche un teacher grâce à son teacher_id.
     * 
     * @param int $id
     * 
     * @return array|null
     */
    public static function find(int $id): ?array
    {
        $stmt = parent::getPdo()->prepare("
            SELECT teachers.*, users.firstname, users.lastname, users.gender, users.email
            FROM teachers 
            JOIN users ON teachers.user_id = users.id
            WHERE teachers.id = ?
        ");
        $stmt->execute([intval($id)]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    
    /**
     * Recherche un teacher grâce à son user_id.
     * 
     * @param int $id
     * 
     * @return array|null
     */
    public static function findByUserId(int $userId): ?array
    {
        $stmt = parent::getPdo()->prepare("
            SELECT teachers.*, users.firstname, users.lastname, users.gender, users.email
            FROM teachers 
            JOIN users ON teachers.user_id = users.id
            WHERE teachers.user_id = ?
        ");
        $stmt->execute([intval($userId)]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    
    /**
     * Recherche des teachers suivant un domaine.
     * 
     * @param int $id
     * 
     * @return array|null
     */
    public static function findByDomains(int $id): ?array
    {
        $stmt = parent::getPdo()->prepare("
            SELECT teachers.*, users.firstname, users.lastname, users.email, domains.*
            FROM teachers 
            JOIN users ON teachers.user_id = users.id
            JOIN domains ON teachers.domain_id = domains.id
            WHERE domains.id = ?
        ");
        $stmt->execute([intval($id)]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Permet de récupérer le nombre total de teachers.
     */
    public static function count(): int
    {
        $stmt = parent::getPdo()->prepare("SELECT COUNT(DISTINCT user_id) FROM teachers");
        $stmt->execute();
        $result = $stmt->fetchColumn();
        return (int) $result;
    }

    /**
     * Compte le nombre d'étudiants qu'un professeur doit encadrer.
     * 
     * @param int $id
     * 
     * @return int
     */
    public static function countAssignedStudents(int $id): int
    {
        $stmt = parent::getPdo()->prepare("
            SELECT COUNT(*)
            FROM students 
            WHERE students.teacher_id = ?
        ");
        $stmt->execute([$id]);
        $result = $stmt->fetchColumn();
        return (int) $result;
    }

    /**
     * Compte le nombre d'étudiants assignés à un enseignant pour un domaine spécifique.
     * 
     * @param int $teacherUserId L'ID de l'utilisateur de l'enseignant
     * @param int $domainId L'ID du domaine
     * @return int
     */
    public static function countStudentsByDomain(int $teacherUserId, int $domainId): int
    {
        $stmt = parent::getPdo()->prepare("
            SELECT COUNT(*) 
            FROM students 
            JOIN teachers ON students.teacher_id = teachers.id
            WHERE teachers.user_id = ? AND students.domain_id = ?
        ");
        $stmt->execute([intval($teacherUserId), intval($domainId)]);
        $result = $stmt->fetchColumn();
        return (int) $result;
    }

    /**
     * Récupère les étudiants qu'un professeur doit encadrer.
     * 
     * @param int $id
     * 
     * @return array
     */
    public static function getAssignedStudents(int $id): array
    {
        $stmt = parent::getPdo()->prepare("
            SELECT students.*, users.firstname, users.lastname, users.gender, users.email 
            FROM students 
            JOIN users ON students.user_id = users.id 
            WHERE students.teacher_id = ?
        ");
        $stmt->execute([intval($id)]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Récupère tous les domaines associés à un enseignant (via son user_id).
     * 
     * @param int $userId L'ID de l'utilisateur associé à l'enseignant
     * @return array
     */
    public static function getDomainsByTeacher(int $userId): array
    {
        $stmt = parent::getPdo()->prepare("
            SELECT domains.id, domains.code, domains.label
            FROM teachers 
            JOIN domains ON teachers.domain_id = domains.id 
            WHERE teachers.user_id = ?
        ");
        $stmt->execute([intval($userId)]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Récupère tous les domaines sauf ceux déjà associés à l'enseignant (via son user_id).
     * 
     * @param int $userId L'ID de l'utilisateur associé à l'enseignant
     * @return array
     */
    public static function getDomainsWithoutMine(int $userId): array
    {
        $stmt = parent::getPdo()->prepare("
            SELECT domains.*
            FROM domains
            WHERE domains.id NOT IN (
                SELECT domain_id 
                FROM teachers 
                WHERE teachers.user_id = ?
            )
        ");
        $stmt->execute([intval($userId)]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Rajoute un domaine à un teacher.
     * 
     * @param int $userId
     * @param int $domainId
     * 
     * @return bool
     */
    public static function addDomain(int $userId, int $domainId): bool
    {
        $checkStmt = parent::getPdo()->prepare("
            SELECT COUNT(*) 
            FROM teachers 
            WHERE user_id = ? AND domain_id = ?
        ");
        $checkStmt->execute([intval($userId), intval($domainId)]);
        if ($checkStmt->fetchColumn() > 0) {
            return false;
        }

        $stmt = parent::getPdo()->prepare("
            INSERT INTO teachers (user_id, domain_id)
            VALUES (:user_id, :domain_id)
        ");
        return $stmt->execute([
            ':user_id' => $userId,
            ':domain_id' => $domainId
        ]);
    }

    /**
     * Supprime un domaine associé à un enseignant (via son user_id).
     * 
     * @param int $userId L'ID de l'utilisateur associé à l'enseignant
     * @param int $domainId L'ID du domaine à supprimer
     * @return bool
     */
    public static function removeDomain(int $userId, int $domainId): bool
    {
        $stmt = parent::getPdo()->prepare("
            DELETE FROM teachers
            WHERE user_id = :user_id AND domain_id = :domain_id
        ");
        return $stmt->execute([
            ':user_id' => $userId,
            ':domain_id' => $domainId
        ]);
    }

    /**
     * Crée un nouveau teacher.
     * 
     * @param array $data
     * 
     * @return bool
     */
    public static function create(array $data): bool
    {
        $userData = [
            'firstname' => $data['firstname'] ?? null,
            'lastname' => $data['lastname'] ?? null,
            'gender' => $data['gender'] ?? null,
            'email' => $data['email'] ?? null,
            'role' => 'teacher',
            'password' => $data['password'] ?? null
        ];

        if (empty($userData['password'])) {
            return false;
        }

        $userStmt = parent::getPdo()->prepare("
            INSERT INTO users (firstname, lastname, gender, email, role, password)
            VALUES (:firstname, :lastname, :gender, :email, :role, :password)
        ");
        if (!$userStmt->execute($userData)) {
            return false;
        }

        $userId = parent::getPdo()->lastInsertId();

        $teacherData = [
            'user_id' => $userId,
            'domain_id' => $data['domain_id']
        ];

        if (empty($teacherData['domain_id'])) {
            return false;
        }

        $stmt = parent::getPdo()->prepare("
            INSERT INTO teachers (user_id, domain_id)
            VALUES (:user_id, :domain_id)
        ");
        return $stmt->execute($teacherData);
    }

    /**
     * Met à jour un teacher existant.
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function update(int $id, array $data): bool
    {
        $teacher = self::find($id);
        if (!$teacher) {
            return false;
        }

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
            $userData['id'] = $teacher['user_id'];
            $sql = "UPDATE users SET $userFieldsList, updated_at = NOW() WHERE id = :id";
            $stmt = parent::getPdo()->prepare($sql);
            if (!$stmt->execute($userData)) {
                return false;
            }
        }

        $teacherFields = [];
        $teacherData = [];
        $updatableTeacherFields = ['domain_id'];
        foreach ($updatableTeacherFields as $field) {
            if (array_key_exists($field, $data)) {
                $teacherFields[] = "`$field` = :$field";
                $teacherData[$field] = $data[$field];
            }
        }

        if (!empty($teacherFields)) {
            $teacherFieldsList = implode(', ', $teacherFields);
            $teacherData['id'] = $id;
            $sql = "UPDATE teachers SET $teacherFieldsList, updated_at = NOW() WHERE id = :id";
            $stmt = parent::getPdo()->prepare($sql);
            return $stmt->execute($teacherData);
        }

        return true;
    }

    /**
     * Supprime un teacher.
     * 
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $stmt = parent::getPdo()->prepare("SELECT user_id FROM teachers WHERE id = ?");
        $stmt->execute([intval($id)]);
        $userId = $stmt->fetchColumn();

        if ($userId === false) {
            return false;
        }

        return User::delete($userId);
    }
}