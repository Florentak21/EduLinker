<?php

namespace App\Traits;

use App\Models\Domain;

trait DomainValidator {
    /**
     * Valide les champs code et label d’un domaine.
     * 
     * @param string $codeValue - Valeur du champ code
     * @param string $labelValue - Valeur du champ label
     * 
     * @return array Tableau d’erreurs (clé => message)
     */
    public function validateDomain(string $code, string $label): array
    {
        $errors = [];
        $codeLength = 2;
        $labelLength = 5;

        if (empty(trim($code)))
        {
            $errors['code'] = "Le code du domaine d'étude est requis.";
        }
        elseif (strlen($code) < $codeLength)
        {
            $errors['code'] = "Le code du domaine d'étude doit contenir au moins $codeLength caractères.";
        }

        if (empty(trim($label)))
        {
            $errors['label'] = "Le libellé du domaine d'étude est requis.";
        }
        elseif (strlen($label) < $labelLength)
        {
            $errors['label'] = "Le libellé du domaine d'étude doit contenir au moins $labelLength caractères.";
        }

        if (empty($errors))
        {
            $existingDomain = Domain::findByCodeAndLabel($code, $label);
            if ($existingDomain)
            {
                $errors['domain'] = "Ce domaine d'étude existe déjà.";
            }
        }

        return $errors;
    }
}