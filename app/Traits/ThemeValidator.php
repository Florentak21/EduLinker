<?php

namespace App\Traits;

trait ThemeValidator {
    /**
     * Valide un thème.
     * 
     * @param string $theme - Valeur du champ theme
     * 
     * @return string|null
     */
    public function validateTheme(string $field, $value): ?string
    {
        $value = trim($value);

        if (empty($value))
        {
            return "Le champ $field est requis.";
        }

        if (strlen($value) < 5)
        {
            return "Le champ $field doit contenir au moins 5 caractères.";
        }
        
        return null;
    }
}