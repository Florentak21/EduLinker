<?php
namespace App\Traits;

trait PasswordValidator {
    /**
     * Valide un mot de passe.
     * 
     * @param string $value Valeur du champ password
     * 
     * @return string|null
     */
    public function validatePassword(string $value): ?string {
        $value = trim($value);
        if (empty($value)) {
            return "Le mot de passe est requis.";
        }
        if (strlen($value) < 8) {
            return "Le mot de passe doit contenir au moins 8 caractères.";
        }
        if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/', $value)) {
            return "Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.";
        }
        return null;
    }
}