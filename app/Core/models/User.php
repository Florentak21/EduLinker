<?php

namespace App\Core\models;

use PDO;
$config = require __DIR__ . '/config/database.php';
$pdo    = $config; 

class User
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function all()
    {
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO users (firstname, lastname, gender, email, role, password) 
            VALUES (:firstname, :lastname, :gender, :email, :role, :password)
        ");
        return $stmt->execute($data);
    }
}
