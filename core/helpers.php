<?php

/**
 * Génère une URL absolue
 */
function url($path = '') {
    $basePath = '/broceliande_immo';
    $path = trim($path, '/');
    return $basePath . ($path ? '/' . $path : '');
}

/**
 * Génère une URL pour un asset
 */
function asset($path) {
    // Éviter le double préfixe 'public/'
    $path = ltrim($path, '/');
    if (strpos($path, 'public/') === 0) {
        return url($path);
    } else {
        return url('public/' . $path);
    }
}

/**
 * Redirige vers une URL
 */
function redirect($path = '') {
    header('Location: ' . url($path));
    exit();
}

/**
 * Affiche une vue avec les données fournies
 */
function view($template, $data = []) {
    $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/views');
    $twig = new \Twig\Environment($loader, [
        'cache' => false,
        'debug' => true,
    ]);
    
    // Ajouter les fonctions personnalisées
    $twig->addFunction(new \Twig\TwigFunction('url', 'url'));
    $twig->addFunction(new \Twig\TwigFunction('asset', 'asset'));
    
    // Ajouter les variables globales
    $twig->addGlobal('app', [
        'user' => isset($_SESSION['user']) ? $_SESSION['user'] : null,
        'flash' => isset($_SESSION['flash']) ? $_SESSION['flash'] : []
    ]);
    
    // Vider les messages flash après les avoir récupérés
    if (isset($_SESSION['flash'])) {
        unset($_SESSION['flash']);
    }
    
    echo $twig->render($template, $data);
}

function old($key, $default = '') {
    return $_POST[$key] ?? $default;
}

function flash($type) {
    return $_SESSION['flashes'][$type] ?? [];
}
