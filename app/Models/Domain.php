<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Domain extends Model {

    /**
     * Récupère tous les domaines.
     * 
     * @return array
     */
    public static function all(): array
    {
        $stmt = parent::$pdo->query("SELECT * FROM domains");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Créé un nouveau domaine.
     * 
     * @param string $code
     * @param string $label
     * 
     * @return bool
     */
    public static function create(string $code, string $label): bool
    {
        $stmt = parent::$pdo->prepare("INSERT INTO domains (code, label) VALUES (?, ?)");
        return $stmt->execute([$code, $label]);
    }

    /**
     * Recherche un domaine avec son code et son label en simultané.
     * 
     * @param string $code
     * @param string $label
     * 
     * @return array|null
     */
    public static function findByCodeAndLabel(string $code, string $label): ?array
    {
        $stmt = parent::$pdo->prepare("SELECT * FROM domains WHERE code = ? AND label = ?");
        $stmt->execute([$code, $label]);
        return $stmt->fetch();
    }
}
