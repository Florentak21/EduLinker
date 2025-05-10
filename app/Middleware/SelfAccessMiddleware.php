<?php
namespace App\Middleware;

class SelfAccessMiddleware
{
    public function handle(callable $next, int $id): void
    {
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id) {
            $next();
        } else {
            $_SESSION['flash']['error'] = 'Accès non autorisé : vous ne pouvez pas modifier cette ressource.';
            http_response_code(403);
            require __DIR__ . '/../../views/errors/403.php';
            exit;
        }
    }
}