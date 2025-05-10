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
            SELECT students.*, users.firstname, users.lastname, users.gender, users.email 
            FROM students 
            JOIN users ON students.user_id = users.id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Recherche un student grâce à son id.
     * 
     * @param int $id
     * 
     * @return array|null
     */
    public static function find(int $id): ?array
    {
        $stmt = parent::getPdo()->prepare("
            SELECT students.*, users.firstname, users.lastname, users.gender, users.email 
            FROM students 
            JOIN users ON students.user_id = users.id 
            WHERE students.id = ?
        ");
        $stmt->execute([intval($id)]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
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
            'firstname' => $data['firstname'] ?? null,
            'lastname' => $data['lastname'] ?? null,
            'gender' => $data['gender'] ?? null,
            'email' => $data['email'] ?? null,
            'role' => 'student',
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

        $studentData = [
            'matricule' => $data['matricule'] ?? '',
            'phone' => $data['phone'] ?? '',
            'has_binome' => $data['has_binome'] ?? 0,
            'matricule_binome' => $data['matricule_binome'] ?? null,
            'theme' => $data['theme'] ?? null,
            'theme_status' => $data['theme_status'] ?? 'non-soumis',
            'cdc' => $data['cdc'] ?? null,
            'user_id' => $userId,
            'domain_id' => $data['domain_id'] ?? null,
            'teacher_id' => $data['teacher_id'] ?? null
        ];

        if (empty($studentData['matricule']) || empty($studentData['phone']) || empty($studentData['domain_id'])) {
            return false;
        }

        $stmt = parent::getPdo()->prepare("
            INSERT INTO students (
                matricule, phone, has_binome, matricule_binome, theme,
                theme_status, cdc, user_id, domain_id, teacher_id
            ) VALUES (
                :matricule, :phone, :has_binome, :matricule_binome, :theme,
                :theme_status, :cdc, :user_id, :domain_id, :teacher_id
            )
        ");
        return $stmt->execute($studentData);
    }

    /**
     * Met à jour un student existant.
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
            return false;
        }

        /* Mis à jour du students la table users */
        $userFields = [];
        $userData = [];
        $userUpdatableFields = ['firstname', 'lastname', 'gender', 'email'];

        foreach ($userUpdatableFields as $field) {
            if (array_key_exists($field, $data)) {
                $userFields[] = "`$field` = :$field";
                $userData[$field] = $data[$field];
            }
        }

        if (!empty($userFields)) {
            $userFieldsList = implode(', ', $userFields);
            $userData['id'] = $student['user_id'];
            $userSql = "UPDATE users SET $userFieldsList WHERE id = :id";
            $userStmt = parent::getPdo()->prepare($userSql);
            if (!$userStmt->execute($userData)) {
                return false;
            }
        }

        /* Mis à jour du student dans la table students */
        $studentFields = [];
        $studentData = [];
        $studentUpdatableFields = ['phone', 'has_binome', 'matricule_binome', 'theme', 'theme_status', 'cdc', 'domain_id', 'teacher_id'];

        foreach ($studentUpdatableFields as $field) {
            if (array_key_exists($field, $data)) {
                $studentFields[] = "`$field` = :$field";
                $studentData[$field] = $data[$field];
            }
        }

        if (!empty($studentFields)) {
            $studentFieldsList = implode(', ', $studentFields);
            $studentData['id'] = $id;
            $sql = "UPDATE students SET $studentFieldsList, updated_at = NOW() WHERE id = :id";
            $stmt = parent::getPdo()->prepare($sql);
            return $stmt->execute($studentData);
        }

        return true;
    }

    /**
     * Supprime un student.
     * 
     * @param int $id
     * 
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $stmt = parent::getPdo()->prepare("DELETE FROM students WHERE id = ?");
        return $stmt->execute([intval($id)]);
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
     * Vérifie un numéro de téléphone déjà existant.
     * 
     * @param string $phone
     * @param int|null $excludeId
     * 
     * @return array|null
     */
    public static function findByPhone(string $phone, ?int $excludeId = null): ?array
    {
        $sql = "SELECT phone FROM students WHERE phone = :phone";
        $params = [':phone' => $phone];
        if ($excludeId !== null) {
            $sql .= " AND id != :excludeId";
            $params[':excludeId'] = $excludeId;
        }
        $stmt = parent::getPdo()->prepare($sql);

        $unionSql = " UNION SELECT phone FROM teachers WHERE phone = :phone2";
        if ($excludeId !== null) {
            $unionSql .= " AND id != :excludeId";
        }
        $sql .= $unionSql;
        $params[':phone2'] = $phone;

        $stmt = parent::getPdo()->prepare($sql);
        $stmt->execute($params);
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
        $stmt = parent::getPdo()->prepare("SELECT COUNT(theme) FROM students");
        $stmt->execute();
        $result = $stmt->fetchColumn();
        return (int) $result;
    }

    /**
     * Permet de compter le nombre de theme suivant le status.
     * 
     * @param string $theme
     * 
     * @return int
     */
    public static function countThemesByStatus(string $themeStatus): int
    {
        $stmt = parent::getPdo()->prepare("SELECT COUNT(*) FROM students WHERE theme_status = ?");
        $stmt->execute([$themeStatus]);
        $result = $stmt->fetchColumn();
        return (int) $result;
    }
}