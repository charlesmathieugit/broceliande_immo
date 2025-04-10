<?php
namespace Core;

use Middleware\MiddlewareStack;

class Router {
    private $routes = [];
    private $middlewareStack;
    private $groupMiddleware = [];
    private $params = [];

    public function __construct() {
        $this->middlewareStack = new MiddlewareStack();
    }

    public function get($path, $handler) {
        $this->addRoute('GET', $path, $handler);
        return $this;
    }

    public function post($path, $handler) {
        $this->addRoute('POST', $path, $handler);
        return $this;
    }

    private function addRoute($method, $path, $handler) {
        // Assurez-vous que le chemin commence par /
        $path = '/' . ltrim($path, '/');
        
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middleware' => $this->groupMiddleware
        ];
    }

    public function group($options, $callback) {
        $previousMiddleware = $this->groupMiddleware;
        
        if (isset($options['middleware'])) {
            $this->groupMiddleware = array_merge(
                $this->groupMiddleware,
                (array) $options['middleware']
            );
        }

        $callback($this);
        
        $this->groupMiddleware = $previousMiddleware;
    }

    private function matchRoute($route, $method, $path) {
        if ($route['method'] !== $method) {
            return false;
        }

        $routePath = preg_replace('/\{[^}]+\}/', '([^/]+)', $route['path']);
        $routePath = str_replace('/', '\/', $routePath);
        $routePath = '/^' . $routePath . '$/';

        if (preg_match($routePath, $path, $matches)) {
            array_shift($matches);
            $this->params = $matches;
            return true;
        }

        return false;
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Retirer le chemin de base de l'application
        $basePath = '/broceliande_immo';
        if (strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }
        
        // S'assurer que le chemin commence par /
        $path = '/' . ltrim($path, '/');
        
        // Si le chemin est vide ou juste /, utiliser /
        if (empty($path) || $path === '//') {
            $path = '/';
        }

        foreach ($this->routes as $route) {
            if ($this->matchRoute($route, $method, $path)) {
                return $this->handleRoute($route);
            }
        }

        // Route non trouvée
        header("HTTP/1.0 404 Not Found");
        echo "<h1>Page non trouvée</h1>";
        echo "<p>L'URL demandée n'existe pas : " . htmlspecialchars($path) . "</p>";
        echo "<p>Méthode HTTP : " . htmlspecialchars($method) . "</p>";
        echo "<pre>Routes disponibles :\n";
        foreach ($this->routes as $route) {
            echo htmlspecialchars("{$route['method']} {$route['path']}\n");
        }
        echo "</pre>";
        exit();
    }

    private function handleRoute($route) {
        $handler = $route['handler'];
        
        // Gérer le nouveau format [Controller::class, 'method']
        if (is_array($handler)) {
            $controller = new $handler[0]();
            $method = $handler[1];
            
            // Créer une closure qui appelle la méthode du contrôleur
            $action = function($params) use ($controller, $method) {
                return call_user_func_array([$controller, $method], $params);
            };
            
            // Préparer les middlewares
            $stack = new MiddlewareStack();
            foreach ($route['middleware'] as $middleware) {
                $stack->add($middleware);
            }
            
            // Exécuter la pile de middleware
            return $stack->handle($this->params, $action);
        }
        
        // Gérer l'ancien format "Controller@method" pour la rétrocompatibilité
        if (is_string($handler)) {
            list($controller, $method) = explode('@', $handler);
            $controller = "Controllers\\$controller";
            $controller = new $controller();
            
            // Créer une closure qui appelle la méthode du contrôleur
            $action = function($params) use ($controller, $method) {
                return call_user_func_array([$controller, $method], $params);
            };
            
            // Préparer les middlewares
            $stack = new MiddlewareStack();
            foreach ($route['middleware'] as $middleware) {
                $stack->add($middleware);
            }
            
            // Exécuter la pile de middleware
            return $stack->handle($this->params, $action);
        }
        
        throw new \RuntimeException("Invalid route handler");
    }
}
