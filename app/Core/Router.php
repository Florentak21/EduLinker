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
     * 
     * @return void
     */
    public function get(string $path, string $controller, string $method): void
    {
        $this->routes['get'][$path] = [
            'controller' => $controller,
            'method' => $method
        ];
    }

    /**
     * Défini la structure d'une route qui est en POST.
     * 
     * @param string $path
     * @param string $controller
     * @param string $method
     * 
     * @return void
     */
    public function post(string $path, string $controller, string $method): void
    {
        $this->routes['post'][$path] = [
            'controller' => $controller,
            'method' => $method
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

                if (!class_exists($controllerClass)) {
                    throw new \Exception("Controller $controllerClass not found.");
                }

                $controller = new $controllerClass($this);
                if (!method_exists($controller, $controllerMethod)) {
                    throw new \Exception("Method $controllerMethod not found in $controllerClass.");
                }

                if (!empty($params)) {
                    return call_user_func_array([$controller, $controllerMethod], array_values($params));
                }

                return call_user_func([$controller, $controllerMethod]);
            }
        }

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