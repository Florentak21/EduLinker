<?php

namespace App\Traits;

trait FirstnameValidator {
    /**
     * Valide un prénom.
     * 
     * @param string $firstname - Valeur du champ firstname
     * 
     * @return string|null
     */
    public function validateFirstname(string $firstname): ?string
    {
        $firstname = trim($firstname);

        if (empty($firstname))
        {
            return "Le prénom est requis.";
        }

        if (strlen($firstname) < 2)
        {
            return "Le prénom doit contenir au moins 2 caractères.";
        }

        if (!preg_match('/^[a-zA-ZÀ-ÿ\s\'-]+$/', $firstname))
        {
            return "Le prénom doit contenir uniquement des lettres, espaces, apostrophes ou tirets.";
        }

        return null;
    }
}