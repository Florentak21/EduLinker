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
        $stmt = parent::getPdo()->query("
            SELECT 
                domains.id,
                domains.label,
                domains.code,
                domains.created_at,
                domains.updated_at,
                (SELECT COUNT(*) FROM students WHERE students.domain_id = domains.id) AS student_count,
                (SELECT COUNT(*) FROM teachers WHERE teachers.domain_id = domains.id) AS teacher_count
            FROM domains
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Recherche un domaine grâce à son id.
     *
     * @param int $id
     * 
     * @return array|null
     */
    public static function find(int $id): ?array
    {
        $stmt = parent::getPdo()->prepare("SELECT * FROM domains WHERE id = ?");
        $stmt->execute([intval($id)]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Recherche un domaine avec son code et son label en simultané.
     * 
     * @param string $code
     * @param string $label
     * @param int|null $excludeId (représente l'ID du domaine en cours de modification)
     * @return array|null
     */
    public static function findByCodeAndLabel(string $code, string $label, ?int $excludeId = null): ?array
    {
        $sql = "SELECT * FROM domains WHERE code = ? AND label = ?";
        $params = [$code, $label];
        if ($excludeId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        $stmt = parent::getPdo()->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
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
        $stmt = parent::getPdo()->prepare("INSERT INTO domains (code, label) VALUES (?, ?)");
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
        $stmt = parent::getPdo()->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Permet de récupérer le nombre total de domaine.
     * 
     * @return int
     */
    public static function count(): int
    {
        $stmt = parent::getPdo()->prepare("SELECT COUNT(*) FROM domains");
        $stmt->execute();
        $result = $stmt->fetchColumn();
        return (int) $result;
    }

    /**
     * Supprime un domaine par son id.
     *
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $stmt = parent::getPdo()->prepare("DELETE FROM domains WHERE id = ?");
        return $stmt->execute([intval($id)]);
    }
}