<?php
session_start();

// Afficher toutes les erreurs en développement
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Charger les helpers
require_once __DIR__ . '/../core/helpers.php';

// Charger la configuration
$config = require __DIR__ . '/../config/config.php';

// Initialiser Twig
$twig = Core\Twig::getInstance();

// Charger les routes
require_once __DIR__ . '/../routes/web.php';

// Créer et configurer le routeur
$router = new Core\Router();
registerRoutes($router);

// Dispatcher les routes
$router->dispatch();