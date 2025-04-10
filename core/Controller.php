<?php
namespace Core;

use Twig\Environment;

abstract class Controller {
    protected $twig;
    protected $basePath = '/broceliande_immo';

    public function __construct() {
        $this->twig = Twig::getInstance();
    }
    
    protected function render(string $template, array $data = []): string {
        try {
            // Ajouter les messages flash aux données
            $data['flash'] = [
                'success' => $_SESSION['flashes']['success'] ?? null,
                'error' => $_SESSION['flashes']['error'] ?? null,
                'info' => $_SESSION['flashes']['info'] ?? null
            ];
            
            // Nettoyer les messages flash après les avoir récupérés
            unset($_SESSION['flashes']);
            
            // Rendre le template
            $content = $this->twig->render($template, $data);
            
            // Afficher le contenu
            echo $content;
            return $content;
        } catch (\Exception $e) {
            // En développement, afficher l'erreur
            echo '<pre>';
            echo $e->getMessage();
            echo '\n';
            echo $e->getTraceAsString();
            echo '</pre>';
            return '';
        }
    }
    
    protected function redirect(string $path) {
        $url = $this->url($path);
        header("Location: $url");
        exit;
    }
    
    protected function json($data, int $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    protected function setFlash(string $type, string $message) {
        if (!isset($_SESSION['flashes'])) {
            $_SESSION['flashes'] = [];
        }
        $_SESSION['flashes'][$type][] = $message;
    }

    protected function getFlashes() {
        $flashes = $_SESSION['flashes'] ?? [];
        unset($_SESSION['flashes']);
        return $flashes;
    }
    
    protected function url($path = '') {
        $path = trim($path, '/');
        return $this->basePath . ($path ? '/' . $path : '');
    }
}
