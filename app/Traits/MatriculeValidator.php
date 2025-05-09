<?php
namespace App\Traits;

use App\Models\Student;

trait MatriculeValidator {
    /**
     * Valide un matricule.
     * 
     * @param string $matricule - Valeur du champ matricule
     * 
     * @return string|null
     */
    public function validateMatricule(string $matricule): ?string
    {
        $matricule = trim($matricule);

        if (empty($matricule))
        {
            return "Le matricule est requis.";
        }

        if (!preg_match('/^[A-Z]{3}-[0-9]{4}-[A-Za-z0-9]{4}$/', $matricule))
        {
            return "Le matricule doit respecter le format DOM-YYYY-XXXX.";
        }

        $result = Student::findByMatricule($matricule);

        if ($result)
        {
            return "Ce matricule est déjà utilisé.";
        }

        return null;
    }
}