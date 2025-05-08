<?php
namespace App\Traits;

use App\Models\User;

trait EmailValidator {
    /**
     * Valide un email.
     * 
     * @param string $value Valeur du champ email
     * 
     * @return string|null
     */
    public function validateEmail(string $value): ?string {
        $value = trim($value);
        if (empty($value)) {
            return "L'email est requis.";
        }
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return "L'email est invalide.";
        }
        $result = User::findByEmail($value);
        if ($result) {
            return "Cet email est déjà utilisé par un autre utilisateur.";
        }
        return null;
    }
}