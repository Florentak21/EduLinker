<?php
namespace App\Core;

abstract class Controller {
    
    /**
     * Rend une vue avec des données.
     * 
     * @param string $view - Nom de la vue 
     * @param array $data - Données à passer à la vue
     * 
     * @return void
     */
    protected function view(string $view, array $data = []): void {
        extract($data);

        $viewFile = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . "{$view}.php";
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("Vue {$view} introuvable.");
        }
    }

    /**
     * Redirige vers une autre URL.
     * 
     * @param string $url
     * 
     * @return void
     */
    protected function redirect(string $url): void {
        header("Location: {$url}");
        exit;
    }

    /**
     * Récupère les données d'une requête POST.
     * 
     * @param array $fields - Champs à récupérer
     * 
     * @return array
     */
    protected function getPostData(array $fields): array {
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $_POST[$field] ?? null;
        }
        return $data;
    }
}