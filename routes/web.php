<?php

use Controllers\HomeController;
use Controllers\AnnoncesController;
use Controllers\ContactController;

function registerRoutes($router) {
    // Page d'accueil
    $router->get('/', [HomeController::class, 'index']);

    // Annonces
    $router->get('/annonces/vente', [AnnoncesController::class, 'vente']);
    $router->get('/annonces/location', [AnnoncesController::class, 'location']);
    $router->get('/annonce/{id}', [AnnoncesController::class, 'show']);

    // Authentification supprimée

    // Contact
    $router->get('/contact', [ContactController::class, 'showForm']);
    $router->post('/contact', [ContactController::class, 'send']);

    // Pages légales
    $router->get('/mentions-legales', [HomeController::class, 'mentionsLegales']);
    $router->get('/politique-confidentialite', [HomeController::class, 'politiqueConfidentialite']);
}