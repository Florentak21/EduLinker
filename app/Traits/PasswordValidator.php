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

        if (empty($password))
        {
            return "Le mot de passe est requis.";
        }

        if (strlen($password) < 8)
        {
            return "Le mot de passe doit contenir au moins 8 caractères.";
        }
        
        if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/', $password))
        {
            return "Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.";
        }

        return null;
    }
}