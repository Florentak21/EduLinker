<?php

namespace App\Traits;

trait RoleValidator {
    /**
     * Valide le rôle.
     * 
     * @param string $role - Valeur du champ role
     * 
     * @return string|null
     */
    public function validateRole(string $role): ?string
    {
        $role = trim($role);

        if (empty($role))
        {
            return "Le rôle est requis.";
        }

        $allowedRoles = ['admin', 'teacher', 'student'];

        if (!in_array($role, $allowedRoles))
        {
            return "Le rôle doit être l'une des valeurs suivantes : " . implode(', ', $allowedRoles) . ".";
        }
        
        return null;
    }
}