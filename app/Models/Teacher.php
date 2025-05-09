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
            SELECT teachers.*, users.firstname, users.lastname, users.gender, users.email 
            FROM teachers 
            JOIN users ON teachers.user_id = users.id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Recherche un teacher grâce à son id.
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
     * Créé un nouveau teacher.
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
            'phone' => $data['phone'] ?? '',
            'user_id' => $userId,
            'domain_id' => $data['domain_id'] ?? null
        ];

        if (empty($teacherData['phone']) || empty($teacherData['domain_id'])) {
            return false;
        }

        $stmt = parent::getPdo()->prepare("
            INSERT INTO teachers (phone, user_id, domain_id)
            VALUES (:phone, :user_id, :domain_id)
        ");
        return $stmt->execute($teacherData);
    }

    /**
     * Met à jour un teacher existant.
     * 
     * @param int $id
     * @param array $data
     * 
     * @return bool
     */
    public static function update(int $id, array $data): bool
    {
        $teacher = self::find($id);
        if (!$teacher) {
            return false;
        }

        /* Mis à jour du teacher dans la table users */
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
            $userData['id'] = $teacher['user_id'];
            $userSql = "UPDATE users SET $userFieldsList WHERE id = :id";
            $userStmt = parent::getPdo()->prepare($userSql);
            if (!$userStmt->execute($userData)) {
                return false;
            }
        }

        /* Mis à jour du teacher dans la table teachers */
        $teacherFields = [];
        $teacherData = [];
        $teacherUpdatableFields = ['phone', 'domain_id'];

        foreach ($teacherUpdatableFields as $field) {
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
     * 
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $stmt = parent::getPdo()->prepare("DELETE FROM teachers WHERE id = ?");
        return $stmt->execute([intval($id)]);
    }
}