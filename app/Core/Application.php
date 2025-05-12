<?php

namespace App\Core;

class Application {
    public Router $router;
    public Request $request;

    public function __construct()
    {
        $this->request = new Request();
        $this->router = new Router($this->request);
    }

    /**
     * Démarre l'application, ecoute et exécute les requests.
     * 
     * @return void
     */
    public function run(): void
    {
        $this->router->resolve();
    }
}