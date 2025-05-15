<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Router;

class HomeController extends Controller
{
    public function __construct(Router $router)
    {
        parent::__construct($router);
    }

    /**
     * Page d'accueil.
     */
    public function index(): void
    {
        $this->view('public/home', [
            'title' => 'Bienvenue sur EduLinker'
        ]);
    }
}
