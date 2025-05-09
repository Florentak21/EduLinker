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
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "`$key` = :$key";
        }
        $sql = "UPDATE users SET " . implode(', ', $fields) . ", updated_at = NOW() WHERE id = :id";
        $stmt = parent::getPdo()->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
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