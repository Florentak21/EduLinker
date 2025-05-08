<?php
namespace App\Traits;

trait GenderValidator {
    /**
     * Valide le genre.
     * 
     * @param string $value Valeur du champ gender
     * @return string|null
     */
    public function validateGender(string $value): ?string {
        $value = trim($value);
        if (empty($value)) {
            return "Le genre est requis.";
        }
        $allowedGenders = ['M', 'F'];
        if (!in_array($value, $allowedGenders)) {
            return "Le genre doit être l'une des valeurs suivantes : " . implode(', ', $allowedGenders) . ".";
        }
        return null;
    }
}