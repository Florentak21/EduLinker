<?php
namespace App\Traits;

use App\Models\Student;

trait PhoneValidator {
    /**
     * Valide un numéro de téléphone.
     * 
     * @param string $phone Valeur du champ téléphone
     * @param int|null $excludeId L'ID de l'étudiant ou enseignant à exclure (optionnel, utilisé pour update)
     * @return string|null
     */
    public function validatePhone(string $phone, ?int $excludeId = null): ?string
    {
        $phone = trim($phone);

        if (empty($phone)) {
            return "Le numéro de téléphone est requis.";
        }

        if (!preg_match('/^[0-9+]{8,15}$/', $phone)) {
            return "Le numéro de téléphone doit contenir entre 8 et 15 chiffres.";
        }

        $result = Student::findByPhone($phone, $excludeId);
        if ($result) {
            return "Ce numéro est déjà utilisé par un autre utilisateur.";
        }

        return null;
    }
}