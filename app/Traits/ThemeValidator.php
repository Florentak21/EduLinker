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
    public function validateTheme(string $theme): ?string
    {
        $theme = trim($theme);

        if (empty($theme))
        {
            return "Le thème est requis.";
        }

        if (strlen($theme) < 5)
        {
            return "Le thème doit contenir au moins 5 caractères.";
        }

        if (strlen($theme) > 255)
        {
            return "Le thème ne doit pas dépasser 255 caractères.";
        }
        
        return null;
    }
}