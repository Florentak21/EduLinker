<?php
namespace App\Traits;

use App\Models\User;

trait PhoneValidator {
    /**
     * Valide un numéro de téléphone.
     * 
     * @param string $value Valeur du champ téléphone
     * 
     * @return string|null
     */
    public function validatePhone(string $value): ?string {
        $value = trim($value);
        if (empty($value)) {
            return "Le numéro de téléphone est requis.";
        }
        if (!preg_match('/^[0-9+]{8,15}$/', $value)) {
            return "Le numéro de téléphone doit contenir entre 8 et 15 chiffres.";
        }
        $result = User::findByPhone($value);
        if ($result) {
            return "Ce numéro est déjà utilisé par un autre utilisateur.";
        }
        return null;
    }
}