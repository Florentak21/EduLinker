<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Teacher extends Model{

    /**
     * Récupère tous les professeurs.
     * 
     * @return array
     */
    public static function all(): array
    {
        $stmt = parent::$pdo->query("SELECT * FROM teachers");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($phone, $user_id, $domain_id)
    {
        $stmt = parent::$pdo->prepare("
            INSERT INTO teachers (phone, user_id, domain_id) 
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$phone, $user_id, $domain_id]);
    }
}
