<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Student extends Model{

    /**
     * Récupère tous les étudiants.
     * 
     * @return array
     */
    public static function all(): array
    {
        $stmt = parent::$pdo->query("SELECT * FROM students");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Créé un nouvel étudiant.
     * 
     * @param array $data
     * 
     * @return bool
     */
    public static function create(array $data): bool
    {
        $stmt = parent::$pdo->prepare("
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

    /**
     * Recherche un étudiant grâce à son matricule.
     * 
     * @param string $matricule
     * 
     * @return array
     */
    public static function findByMatricule(string $matricule): array
    {
        $stmt = parent::$pdo->prepare("SELECT * FROM students WHERE matricule = ?");
        $stmt->execute([$matricule]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
