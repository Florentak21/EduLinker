<?php
namespace App\Traits;

use App\Models\Domain;

trait DomainValidator {
    /**
     * Valide les champs code et label d’un domaine.
     * 
     * @param string $code Valeur du champ code.
     * @param string $label Valeur du champ label.
     * @param int|null $id L'ID du domaine (optionnel, utilisé pour exclure ce domaine lors de la vérification d'unicité).
     * @return array Tableau d’erreurs (clé => message).
     */
    public function validateDomain(string $code, string $label, ?int $id = null): array
    {
        $errors = [];
        $codeLength = 2;
        $labelLength = 5;

        if (empty(trim($code))) {
            $errors['code'] = "Le code du domaine d'étude est requis.";
        } elseif (strlen($code) < $codeLength) {
            $errors['code'] = "Le code du domaine d'étude doit contenir au moins $codeLength caractères.";
        }

        if (empty(trim($label))) {
            $errors['label'] = "Le libellé du domaine d'étude est requis.";
        } elseif (strlen($label) < $labelLength) {
            $errors['label'] = "Le libellé du domaine d'étude doit contenir au moins $labelLength caractères.";
        }

        if (empty($errors)) {
            $existingDomain = Domain::findByCodeAndLabel($code, $label, $id);
            if ($existingDomain) {
                $errors['domain'] = "Ce domaine d'étude existe déjà.";
            }
        }

        return $errors;
    }
}