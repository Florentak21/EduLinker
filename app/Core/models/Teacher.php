<?php

namespace App\Core\models;

use PDO;
$config = require __DIR__ . '/config/database.php';
$pdo    = $config; 

class Teacher
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function all()
    {
        $stmt = $this->pdo->query("SELECT * FROM teachers");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($phone, $user_id, $domain_id)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO teachers (phone, user_id, domain_id) 
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$phone, $user_id, $domain_id]);
    }
}
