<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Router;

class ErrorController extends Controller {

    public function __construct(Router $router)
    {
        parent::__construct($router);
    }

    /**
     * Gère l'erreur 400.
     * 
     * @param string|null
     * @return void
     */
    public function error400(?string $message = null): void
    {
        http_response_code(400);
        $this->view('errors/400', [
            'title' => '400 - Requête incorrecte',
            'message' => $message ?? 'La requête envoyée est incorrecte ou non supportée.'
        ]);
    }

    /**
     * Gère l'erreur 403.
     * 
     * @param string|null $message Message d'erreur personnalisé
     * @return void
     */
    public function error403(?string $message = null): void
    {
        http_response_code(403);
        $this->view('errors/403', [
            'title' => '403 - Accès interdit',
            'message' => $message ?? 'Vous n\'avez pas les permissions nécessaires pour accéder à cette ressource.'
        ]);
    }

    /**
     * Gère l'erreur 404.
     * 
     * @param string|null
     * @return void
     */
    public function error404(?string $message = null): void
    {
        http_response_code(404);
        $this->view('errors/404', [
            'title' => '404 - Page non trouvée',
            'message' => $message ?? 'La page ou la ressource demandée n\'existe pas.'
        ]);
    }

    /**
     * Gère l'erreur 500.
     * 
     * @param string|null
     * @return void
     */
    public function error500(?string $message = null): void
    {
        http_response_code(500);
        $this->view('errors/500', [
            'title' => '500 - Erreur serveur',
            'message' => $message ?? 'Une erreur interne est survenue. Veuillez réessayer plus tard.'
        ]);
    }
}