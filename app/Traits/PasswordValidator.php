<?php

namespace App\Traits;

trait PasswordValidator {
    /**
     * Valide un mot de passe.
     * 
     * @param string $password - Valeur du champ password
     * 
     * @return string|null
     */
    public function validatePassword(string $password): ?string
    {
        $password = trim($password);

        if (empty($password)) {
            return "Le mot de passe est requis.";
        }

        if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/', $password)) {
            return "Le mot de passe ne respecte pas les standards définis.";
        }

        return null;
    }
}