<?php
namespace App\Traits;

trait CdcValidator {
    /**
     * Valide un fichier de cahier des charges (PDF).
     * 
     * @param array $file Données du fichier ($_FILES['cdc'])
     * 
     * @return string|null
     */
    public function validateCdc(array $file): ?string {
        if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return "Le fichier cahier des charges est requis.";
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return "Une erreur est survenue lors de l'upload du fichier.";
        }

        $fileType = mime_content_type($file['tmp_name']);
        if ($fileType !== 'application/pdf') {
            return "Le fichier doit être un PDF.";
        }

        $maxSize = 2048 * 1024;
        if ($file['size'] > $maxSize) {
            return "Le fichier ne doit pas dépasser 2 Mo.";
        }

        return null;
    }
}