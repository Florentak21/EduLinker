<?php
namespace App\Traits;

use App\Models\Student;

trait MatriculeValidator {
    /**
     * Valide un matricule.
     * 
     * @param string $value Valeur du champ matricule
     * 
     * @return string|null
     */
    public function validateMatricule(string $value): ?string {
        $value = trim($value);
        if (empty($value)) {
            return "Le matricule est requis.";
        }
        if (!preg_match('/^[A-Z]{3}-[0-9]{4}-[A-Za-z0-9]{4}$/', $value)) {
            return "Le matricule doit respecter le format DOM-YYYY-XXXX.";
        }
        $result = Student::find('matricule', $value);
        if ($result) {
            return "Ce matricule est déjà utilisé.";
        }
        return null;
    }
}