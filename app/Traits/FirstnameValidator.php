<?php
namespace App\Traits;

trait FirstnameValidator {
    /**
     * Valide un prénom.
     * 
     * @param string $value Valeur du champ firstname
     * @return string|null
     */
    public function validateFirstname(string $value): ?string {
        $value = trim($value);
        if (empty($value)) {
            return "Le prénom est requis.";
        }
        if (strlen($value) < 2) {
            return "Le prénom doit contenir au moins 2 caractères.";
        }
        if (!preg_match('/^[a-zA-ZÀ-ÿ\s\'-]+$/', $value)) {
            return "Le prénom doit contenir uniquement des lettres, espaces, apostrophes ou tirets.";
        }
        return null;
    }
}