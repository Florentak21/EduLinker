<?php

namespace App\Traits;

use App\Models\Student;

trait BinomeMatriculeValidator {
    /**
     * Valide un matricule de binôme.
     * 
     * @param string $matriculeBinome - Valeur du champ binome_matricule
     * @param bool $hasBinome - Indique si l’étudiant a un binôme
     * 
     * @return string|null
     */
    public function validateBinomeMatricule(string $matriculeBinome, bool $hasBinome): ?string
    {
        $matriculeBinome = trim($matriculeBinome);

        if (!$hasBinome)
        {
            return null;
        }

        if (empty($matriculeBinome))
        {
            return "Le matricule du binôme est requis si un binôme est indiqué.";
        }

        if (!preg_match('/^[A-Z]{2,}-[0-9]{4}-[A-Za-z0-9]{4}$/', $matriculeBinome))
        {
            return "Le matricule du binôme doit respecter le format DOM-YYYY-XXXX.";
        }

        $result = Student::findByMatricule($matriculeBinome);

        if (!$result)
        {
            return "Le matricule du binôme doit correspondre à un étudiant existant.";
        }
        
        return null;
    }
}