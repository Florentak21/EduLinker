<?php

namespace App\Core;
use PDO;

abstract class Model {
    protected PDO $pdo;

    public function __construct()
    {
        $this->pdo = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'database.php';
    }
}