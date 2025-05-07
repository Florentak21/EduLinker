<?php

namespace App\Core\models;

use PDO;
$config = require __DIR__ . '/config/database.php';
$pdo    = $config; 
   
class Domain
{
    
    private $pdo;
    
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function all()
    {
        $stmt = $this->pdo->query("SELECT * FROM domains");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($code, $label)
    {
        $stmt = $this->pdo->prepare("INSERT INTO domains (code, label) VALUES (?, ?)");
        return $stmt->execute([$code, $label]);
    }
}
