<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class User extends Model {

    /**
     * Récupère tous les users.
     * 
     * @return array
     */
    public static function all(): array
    {
        $stmt = parent::$pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Recherche un user grâce à son id.
     * 
     * @param int $id
     * 
     * @return array
     */
    public static function find(int $id): array
    {
        $id = intval($id);
        $stmt = parent::$pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Créé un nouvel utilisateur.
     * 
     * @param array $data
     * 
     * @return bool
     */
    public static function create(array $data): bool
    {
        $stmt = parent::$pdo->prepare("
            INSERT INTO users (firstname, lastname, gender, email, role, password) 
            VALUES (:firstname, :lastname, :gender, :email, :role, :password)
        ");
        return $stmt->execute($data);
    }

    /**
     * Recherche un user grâce à son email.
     * 
     * @param string $email
     * 
     * @return array
     */
    public static function findByEmail(string $email): array
    {
        $stmt = parent::$pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Recherche un user grâce à son téléphone.
     * 
     * @param string $phone
     * 
     * @return array
     */
    public static function findByPhone(string $phone): array
    {
        $stmt = parent::$pdo->prepare("SELECT * FROM users WHERE phone = ?");
        $stmt->execute([$phone]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
