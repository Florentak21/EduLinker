<?php
namespace config;

use PDO;
use PDOException;

return (
    function(): PDO {
        $host     = 'localhost';
        $dbname   = 'edu_linker';
        $username = 'florentak';
        $password = 'florentak@localHost@2003';
        $charset  = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

        try {
            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            return $pdo;
        } catch (PDOException $e) {
            die("Erreur de connexion: " . $e->getMessage());
        }
    }
)();
