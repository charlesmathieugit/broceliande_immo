<?php

use Controllers\HomeController;
use Controllers\AnnoncesController;
use Controllers\AuthController;
use Controllers\ContactController;

function registerRoutes($router) {
    // Page d'accueil
    $router->get('/', [HomeController::class, 'index']);

    // Annonces
    $router->get('/annonces/vente', [AnnoncesController::class, 'vente']);
    $router->get('/annonces/location', [AnnoncesController::class, 'location']);
    $router->get('/annonce/{id}', [AnnoncesController::class, 'show']);

    // Authentification
    $router->get('/register', [AuthController::class, 'showRegisterForm']);
    $router->post('/register', [AuthController::class, 'register']);
    $router->get('/login', [AuthController::class, 'showLoginForm']);
    $router->post('/login', [AuthController::class, 'login']);
    $router->get('/logout', [AuthController::class, 'logout']);

    // Contact
    $router->get('/contact', [ContactController::class, 'showForm']);
    $router->post('/contact', [ContactController::class, 'send']);

    // Pages légales
    $router->get('/mentions-legales', [HomeController::class, 'mentionsLegales']);
    $router->get('/politique-confidentialite', [HomeController::class, 'politiqueConfidentialite']);
}