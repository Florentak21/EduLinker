<?php
namespace App\Traits;

trait ThemeValidator {
    /**
     * Valide un thème.
     * 
     * @param string $value Valeur du champ theme
     * 
     * @return string|null
     */
    public function validateTheme(string $value): ?string {
        $value = trim($value);
        if (empty($value)) {
            return "Le thème est requis.";
        }
        if (strlen($value) < 5) {
            return "Le thème doit contenir au moins 5 caractères.";
        }
        if (strlen($value) > 255) {
            return "Le thème ne doit pas dépasser 255 caractères.";
        }
        return null;
    }
}