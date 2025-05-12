<?php
namespace App\Middleware;

class AuthMiddleware
{
    /**
     * @var string L'URL vers laquelle redirigé si la request ne passe pas le middleware.
     */
    private string $redirectUrl;

    public function __construct(string $redirectUrl = '/login')
    {
        $this->redirectUrl = $redirectUrl;
    }

    public function handle(callable $next): void
    {
        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $next();
        } else {
            $_SESSION['flash']['error'] = 'Vous devez vous connecter pour accéder à cette page.';
            header("Location: {$this->redirectUrl}");
            exit;
        }
    }
}