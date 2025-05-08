<?php

namespace App\Traits;

trait LastnameValidator {
    /**
     * Valide un nom de famille.
     * 
     * @param string $lastname Valeur du champ lastname
     * 
     * @return string|null
     */
    public function validateLastname(string $lastname): ?string
    {
        $lastname = trim($lastname);

        if (empty($lastname))
        {
            return "Le nom de famille est requis.";
        }

        if (strlen($lastname) < 2)
        {
            return "Le nom de famille doit contenir au moins 2 caractères.";
        }

        if (!preg_match('/^[a-zA-ZÀ-ÿ\s\'-]+$/', $lastname))
        {
            return "Le nom de famille doit contenir uniquement des lettres, espaces, apostrophes ou tirets.";
        }

        return null;
    }
}