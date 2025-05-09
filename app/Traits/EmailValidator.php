<?php
namespace App\Traits;

use App\Models\User;

trait EmailValidator {
    /**
     * Valide un email.
     * 
     * @param string $email Valeur du champ email
     * 
     * @return string|null
     */
    public function validateEmail(string $email): ?string
    {
        $email = trim($email);

        if (empty($email))
        {
            return "L'email est requis.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            return "L'email est invalide.";
        }

        $result = User::findByEmail($email);

        if ($result)
        {
            return "Cet email est déjà utilisé par un autre utilisateur.";
        }

        return null;
    }
}