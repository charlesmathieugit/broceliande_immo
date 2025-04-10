<?php
namespace Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class Twig {
    private static $instance = null;
    private $twig;
    private $basePath = '/broceliande_immo';

    private function __construct() {
        $loader = new FilesystemLoader(dirname(__DIR__) . '/views');
        $this->twig = new Environment($loader, [
            'cache' => false,
            'debug' => true,
            'auto_reload' => true
        ]);

        // Ajouter les fonctions personnalisées
        $this->addCustomFunctions();

        // Ajouter la variable globale app
        $this->addGlobalVariables();
    }

    private function addCustomFunctions() {
        // Fonction asset pour les ressources statiques
        $this->twig->addFunction(new TwigFunction('asset', function ($path) {
            return $this->basePath . '/' . ltrim($path, '/');
        }));

        // Fonction url pour les liens
        $this->twig->addFunction(new TwigFunction('url', function ($path = '') {
            return $this->basePath . '/' . trim($path, '/');
        }));

        // Fonction old
        $this->twig->addFunction(new TwigFunction('old', function($key, $default = '') {
            return $_POST[$key] ?? $default;
        }));

        // Fonction flash
        $this->twig->addFunction(new TwigFunction('flash', function($type) {
            return $_SESSION['flashes'][$type] ?? [];
        }));

        // Fonction pour vérifier si l'utilisateur est connecté
        $this->twig->addFunction(new TwigFunction('is_logged_in', function () {
            return isset($_SESSION['user_id']);
        }));
    }

    private function addGlobalVariables() {
        $this->twig->addGlobal('app', [
            'user' => [
                'id' => $_SESSION['user_id'] ?? null,
                'role' => $_SESSION['user_role'] ?? null
            ],
            'request' => [
                'get' => $_GET,
                'post' => $_POST
            ],
            'flashes' => $_SESSION['flashes'] ?? []
        ]);
    }

    public static function getInstance(): Environment {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->twig;
    }
}
