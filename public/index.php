<?php
declare(strict_types=1);

// 1) Autoload (Composer)
require __DIR__ . '/../vendor/autoload.php';

// 2) Inclure la config et récupérer le PDO
$pdo = require __DIR__ . '/../config/database.php'; 
var_dump($pdo instanceof PDO);// doit afficher bool(true)

