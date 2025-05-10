<?php

namespace App\Core;

class Router {
    protected array $routes = [];
    public Request $request;

    /**
     * Initialise le routeur en lui passant la request.
     * 
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Défini la structure d'une route en GET.
     * 
     * @param string $path
     * @param string $controller
     * @param string $method
     * @param array $middleware Liste optionnelle de middlewares.
     * 
     * @return void
     */
    public function get(string $path, string $controller, string $method, array $middleware = []): void
    {
        $this->routes['get'][$path] = [
            'controller' => $controller,
            'method' => $method,
            'middleware' => $middleware
        ];
    }

    /**
     * Défini la structure d'une route qui est en POST.
     * 
     * @param string $path
     * @param string $controller
     * @param string $method
     * @param array $middleware Liste optionnelle de middlewares.
     * 
     * @return void
     */
    public function post(string $path, string $controller, string $method, array $middleware = []): void
    {
        $this->routes['post'][$path] = [
            'controller' => $controller,
            'method' => $method,
            'middleware' => $middleware
        ];
    }

    /**
     * Exécute une request effectué par l'user.
     * 
     * @return mixed
     */
    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();

        foreach ($this->routes[$method] as $routePath => $route) {
            $routeParts = explode('/', trim($routePath, '/'));
            $pathParts = explode('/', trim($path, '/'));

            if (count($routeParts) !== count($pathParts)) {
                continue;
            }

            $params = [];
            $match = true;

            for ($i = 0; $i < count($routeParts); $i++) {
                if (preg_match('/^{.*}$/', $routeParts[$i])) {
                    $paramName = trim($routeParts[$i], '{}');
                    $params[$paramName] = $pathParts[$i];
                } elseif ($routeParts[$i] !== $pathParts[$i]) {
                    $match = false;
                    break;
                }
            }

            if ($match) {
                $controllerClass = $route['controller'];
                $controllerMethod = $route['method'];
                $middleware = $route['middleware'] ?? [];

                if (!class_exists($controllerClass)) {
                    throw new \Exception("Controller $controllerClass not found.");
                }

                $controller = new $controllerClass($this);
                if (!method_exists($controller, $controllerMethod)) {
                    throw new \Exception("Method $controllerMethod not found in $controllerClass.");
                }

                // Chaînage des middlewares
                $next = function () use ($controller, $controllerMethod, $params) {
                    if (!empty($params)) {
                        return call_user_func_array([$controller, $controllerMethod], array_values($params));
                    }
                    return call_user_func([$controller, $controllerMethod]);
                };

                // Exécuter les middlewares dans l'ordre inverse
                foreach (array_reverse($middleware) as $mw) {
                    if (method_exists($mw, 'handle')) {
                        $next = function () use ($mw, $next, $params) {
                            return $mw->handle($next, ...array_values($params));
                        };
                    }
                }

                return $next();
            }
        }

        http_response_code(404);
        require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'errors' . DIRECTORY_SEPARATOR . '404.php';
        exit;
    }

    /**
     * Rend une view en lui passant les données.
     * 
     * @param string $view
     * @param array $data
     * 
     * @return void
     */
    public function render(string $view, array $data = []): void
    {
        extract($data);
        $viewPath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $view . '.php';
        if (!file_exists($viewPath)) {
            throw new \Exception("View $viewPath not found.");
        }
        require_once $viewPath;
    }
}