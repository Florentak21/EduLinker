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
     * Recherche un étudiant grâce à son id.
     *
     * @param int $id
     * @return array|null
     */
    public static function find(int $id): ?array
    {
        $stmt = parent::$pdo->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([intval($id)]);                            
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;       
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
     * Met à jour un étudiant existant.
     *
     * @param int   $id
     * @param array $data  Clé/valeur des colonnes à mettre à jour
     * @return bool
     */
    public static function update(int $id, array $data): bool
    {
        // Construire dynamiquement la clause SET
        $fields = [];
        foreach ($data as $key => $_) {
            $fields[] = "`$key` = :$key";
        }
        $fields_list = implode(', ', $fields);

        // On ajoute updated_at et l’id au jeu de paramètres
        $data['id'] = $id;
        $sql = "UPDATE students
                SET $fields_list, updated_at = NOW()
                WHERE id = :id";

        $stmt = parent::$pdo->prepare($sql);
        return $stmt->execute($data);                             
    }
    /**
     * Supprime un étudiant par son id.
     *
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $stmt = parent::$pdo->prepare("DELETE FROM students WHERE id = ?");
        return $stmt->execute([intval($id)]);                     // exécute la suppression sécurisée :contentReference[oaicite:8]{index=8}
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
