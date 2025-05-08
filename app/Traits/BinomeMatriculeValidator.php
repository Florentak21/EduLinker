<?php
namespace App\Traits;

use App\Models\Student;

trait BinomeMatriculeValidator {
    /**
     * Valide un matricule de binôme.
     * 
     * @param string $value Valeur du champ binome_matricule
     * @param bool $hasBinome Indique si l’étudiant a un binôme
     * 
     * @return string|null
     */
    public function validateBinomeMatricule(string $value, bool $hasBinome): ?string {
        $value = trim($value);
        if (!$hasBinome) {
            return null;
        }

        if (empty($value)) {
            return "Le matricule du binôme est requis si un binôme est indiqué.";
        }
        if (!preg_match('/^[A-Z]{3}-[0-9]{4}-[A-Za-z0-9]{4}$/', $value)) {
            return "Le matricule du binôme doit respecter le format DOM-YYYY-XXXX.";
        }
        $result = Student::find('matricule', $value);
        if (!$result) {
            return "Le matricule du binôme doit correspondre à un étudiant existant.";
        }
        return null;
    }
}