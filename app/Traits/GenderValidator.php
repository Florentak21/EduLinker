<?php

namespace App\Traits;

trait GenderValidator {
    /**
     * Valide le genre.
     * 
     * @param string $gender - Valeur du champ gender
     * 
     * @return string|null
     */
    public function validateGender(string $gender): ?string
    {
        $gender = trim($gender);

        if (empty($gender)) {
            return "Le genre est requis.";
        }

        $allowedGenders = ['M', 'F'];

        if (!in_array($gender, $allowedGenders))
        {
            return "Le genre doit être l'une des valeurs suivantes : " . implode(', ', $allowedGenders) . ".";
        }

        return null;
    }
}