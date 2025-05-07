<?php

namespace App\Core\models;

use PDO;
$config = require __DIR__ . '/config/database.php';
$pdo    = $config; 

class Student
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function all()
    {
        $stmt = $this->pdo->query("SELECT * FROM students");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO students (
                matricule, phone, has_binome, matricule_binome, theme, cdc, 
                affectation_status, user_id, domain_id, teacher_id
            ) VALUES (
                :matricule, :phone, :has_binome, :matricule_binome, :theme, :cdc, 
                :affectation_status, :user_id, :domain_id, :teacher_id
            )
        ");
        return $stmt->execute($data);
    }

    public function findByMatricule($matricule)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM students WHERE matricule = ?");
        $stmt->execute([$matricule]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
