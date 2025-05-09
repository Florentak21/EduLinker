<?php
namespace App\Core;

abstract class Controller {
    protected Router $router;

    /**
     * Iniatlise le controller avec le Router
     * afin de gérer les rendus et redirections.
     * 
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Rend une vue avec des données.
     * 
     * @param string $view - Chemin complet de la vue.
     * @param array $data - Données à passer à la vue.
     * 
     * @return void
     */
    protected function view(string $view, array $data = []): void 
    {
        $this->router->render($view, $data);
    }

    /**
     * Redirige vers une autre URL.
     * 
     * @param string $url
     * 
     * @return void
     */
    protected function redirect(string $url): void
    {
        header("Location: /$url");
        exit;
    }
}