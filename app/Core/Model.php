<?php

namespace App\Core;

use PDO;

abstract class Model {
    protected static ?PDO $pdo = null;

    /**
     * Initialise la connexion à la base de données.
     * 
     * @return void
     */
    public static function initPdo(): void
    {
        if (self::$pdo === null) {
            try {
                self::$pdo = require dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'database.php';
            } catch (\Exception $e) {
                throw new \RuntimeException("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        }
    }

    /**
     * Retourne le resultat issue de la connexion à la base de données.
     * 
     * @return PDO
     */
    public static function getPdo(): PDO
    {
        if (self::$pdo === null) {
            self::initPdo();
        }
        return self::$pdo;
    }
}