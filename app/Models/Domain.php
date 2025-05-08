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
     * Recherche un domaine grâce à son id.
     *
     * @param int $id
     * @return array|null
     */
    public static function find(int $id): ?array
    {
        $stmt = parent::$pdo->prepare("SELECT * FROM domains WHERE id = ?");
        $stmt->execute([intval($id)]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
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
     * Met à jour un domaine existant.
     *
     * @param int   $id
     * @param array $data  
     * @return bool
     */
    public static function update(int $id, array $data): bool
    {
        $fields = [];
        foreach ($data as $key => $_) {
            $fields[] = "`$key` = :$key";
        }
        $data['id'] = $id;
        $sql = "UPDATE domains
                SET " . implode(', ', $fields) . ", updated_at = NOW()
                WHERE id = :id";
        $stmt = parent::$pdo->prepare($sql);
        return $stmt->execute($data);
    }
      /**
     * Supprime un domaine par son id.
     *
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $stmt = parent::$pdo->prepare("DELETE FROM domains WHERE id = ?");
        return $stmt->execute([intval($id)]);
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
