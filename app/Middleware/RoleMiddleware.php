<?php
namespace App\Middleware;

class RoleMiddleware
{
    /**
     * @var array des rôles authorisées à accéder à une route.
     */
    private array $allowedRoles;

    public function __construct(array|string $roles)
    {
        $this->allowedRoles = is_array($roles) ? $roles : [$roles];
    }

    public function handle(callable $next): void
    {
        if (isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], $this->allowedRoles, true)) {
            $next();
        } else {
            $_SESSION['flash']['error'] = 'Accès non autorisé.';
            http_response_code(403);
            require __DIR__ . '/../../views/errors/403.php';
            exit;
        }
    }
}