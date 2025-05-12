<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class User extends Model {

    /**
     * Liste tous les users.
     * 
     * @return array
     */
    public static function all(): array
    {
        $stmt = parent::getPdo()->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Recherche un user grâce à son id.
     * 
     * @param int $id
     * 
     * @return array|null
     */
    public static function find(int $id): ?array
    {
        $id = intval($id);
        $stmt = parent::getPdo()->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Recherche un user grâce à son email.
     * 
     * @param string $email
     * @param int|null $excludeId  - L'ID de l'user en cours de modification
     * 
     * @return array|null
     */
    public static function findByEmail(string $email, ?int $excludeId = null): ?array
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $params = [':email' => $email];
        
        if ($excludeId !== null) {
            $sql .= " AND id != :excludeId";
            $params[':excludeId'] = $excludeId;
        }

        $stmt = parent::getPdo()->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Permet de récupérer le nombre total de users.
     */
    public static function count(): int
    {
        $stmt = parent::getPdo()->prepare("SELECT COUNT(*) FROM users");
        $stmt->execute();
        $result = $stmt->fetchColumn();
        return (int) $result;
    }

    /**
     * Permet de récupérer le nombre total d'utilisateur suivant le rôle.
     * 
     * @return int
     */
    public static function countUsersByRole(string $role): int
    {
        $stmt = parent::getPdo()->prepare("SELECT COUNT(*) FROM users WHERE role = ?");
        $stmt->execute([$role]);
        $result = $stmt->fetchColumn();
        return (int) $result;
    }

    /**
     * Créé un nouvel user.
     * 
     * @param array $data
     * 
     * @return bool
     */
    public static function create(array $data): bool
    {
        $stmt = parent::getPdo()->prepare("
            INSERT INTO users (firstname, lastname, gender, email, role, password) 
            VALUES (:firstname, :lastname, :gender, :email, :role, :password)
        ");
        return $stmt->execute($data);
    }

    /**
     * Met à jour un user existant.
     *
     * @param int $id
     * @param array $data
     * 
     * @return bool
     */
    public static function update(int $id, array $data): bool
    {
        $user = self::find($id);
        if (!$user) {
            return false;
        }

        $userFields = [];
        $userData = [];
        $updatableFields = ['firstname', 'lastname', 'email', 'gender', 'role'];

        foreach ($updatableFields as $field) {
            if (array_key_exists($field, $data)) {
                $userFields[] = "`$field` = :$field";
                $userData[$field] = $data[$field];
            }
        }

        if (!empty($userFields)) {
            $userFieldsList = implode(', ', $userFields);
            $userData['id'] = $id;
            $sql = "UPDATE users SET $userFieldsList, updated_at = NOW() WHERE id = :id";
            $stmt = parent::getPdo()->prepare($sql);
            return $stmt->execute($userData);
        }

        return true;
    }

    /**
     * Supprime un user.
     *
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $stmt = parent::getPdo()->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([intval($id)]);
    }
}