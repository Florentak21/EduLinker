<?php
namespace App\Traits;

trait RoleValidator {
    /**
     * Valide le rôle.
     * 
     * @param string $value Valeur du champ role
     * 
     * @return string|null
     */
    public function validateRole(string $value): ?string {
        $value = trim($value);
        if (empty($value)) {
            return "Le rôle est requis.";
        }
        $allowedRoles = ['admin', 'teacher', 'student'];
        if (!in_array($value, $allowedRoles)) {
            return "Le rôle doit être l'une des valeurs suivantes : " . implode(', ', $allowedRoles) . ".";
        }
        return null;
    }
}