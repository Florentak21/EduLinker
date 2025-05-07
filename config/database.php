<?php
namespace config;

use PDO;
use PDOException;

return (function(): PDO {
    $host     = 'localhost';    // même que dans phinx.yml
    $dbname   = 'mvc';          // nom de la base
    $username = 'root';         // utilisateur MySQL
    $password = '';             // mot de passe MySQL
    $charset  = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

    try {
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);                            // crée la connexion PDO :contentReference[oaicite:1]{index=1}
        return $pdo;
    } catch (PDOException $e) {
        // En production, tu loggues l’erreur plutôt que de die()
        die("Connexion échouée : " . $e->getMessage());
    }
})();
