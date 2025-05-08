<?php
namespace App\Traits;

trait LastnameValidator {
    /**
     * Valide un nom de famille.
     * 
     * @param string $value Valeur du champ lastname
     * 
     * @return string|null
     */
    public function validateLastname(string $value): ?string {
        $value = trim($value);
        if (empty($value)) {
            return "Le nom de famille est requis.";
        }
        if (strlen($value) < 2) {
            return "Le nom de famille doit contenir au moins 2 caractères.";
        }
        if (!preg_match('/^[a-zA-ZÀ-ÿ\s\'-]+$/', $value)) {
            return "Le nom de famille doit contenir uniquement des lettres, espaces, apostrophes ou tirets.";
        }
        return null;
    }
}