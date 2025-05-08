<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Teacher extends Model{

    /**
     * Récupère tous les professeurs.
     * 
     * @return array
     */
    public static function all(): array
    {
        $stmt = parent::$pdo->query("SELECT * FROM teachers");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
      /**
     * Recherche un teacher grâce à son id.
     *
     * @param int $id
     * @return array|null
     */
    public static function find(int $id): ?array
    {
        $stmt = parent::$pdo->prepare("SELECT * FROM teachers WHERE id = ?");
        $stmt->execute([intval($id)]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function create($phone, $user_id, $domain_id)
    {
        $stmt = parent::$pdo->prepare("
            INSERT INTO teachers (phone, user_id, domain_id) 
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$phone, $user_id, $domain_id]);
    }
     /**
     * Met à jour un enseignant existant.
     *
     * @param int   $id
     * @param array $data  Clé/valeur des colonnes à mettre à jour
     * @return bool
     */
    public static function update(int $id, array $data): bool
    {
        // Construction dynamique de la partie SET
        $fields = [];
        foreach ($data as $key => $_) {
            $fields[] = "`$key` = :$key";
        }
        $fields_list = implode(', ', $fields);

        // On ajoute updated_at et l'id au jeu de données
        $data['id'] = $id;
        $sql = "UPDATE teachers
                SET $fields_list, updated_at = NOW()
                WHERE id = :id";

        $stmt = parent::$pdo->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Supprime un enseignant par son id.
     *
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $stmt = parent::$pdo->prepare("DELETE FROM teachers WHERE id = ?");
        return $stmt->execute([intval($id)]);
    }
}

