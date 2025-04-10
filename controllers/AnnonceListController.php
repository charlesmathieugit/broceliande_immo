<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Search.php';

class AnnonceListController {
    private $searchModel;
    
    public function __construct() {
        global $pdo;
        $this->searchModel = new Search($pdo);
    }
    
    public function vente() {
        $criteria = [
            'category' => 'vente'
        ];
        
        // Récupérer les filtres de recherche
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (!empty($_GET['type_bien'])) {
                $criteria['type_bien'] = $_GET['type_bien'];
            }
            if (!empty($_GET['price_min'])) {
                $criteria['price_min'] = (float)$_GET['price_min'];
            }
            if (!empty($_GET['price_max'])) {
                $criteria['price_max'] = (float)$_GET['price_max'];
            }
            if (!empty($_GET['surface_min'])) {
                $criteria['surface_min'] = (float)$_GET['surface_min'];
            }
            if (!empty($_GET['pieces_min'])) {
                $criteria['pieces_min'] = (int)$_GET['pieces_min'];
            }
            if (!empty($_GET['city'])) {
                $criteria['city'] = $_GET['city'];
            }
        }
        
        $annonces = $this->searchModel->searchAnnonces($criteria);
        include __DIR__ . '/../views/annonces/vente.php';
    }
    
    public function location() {
        $criteria = [
            'category' => 'location'
        ];
        
        // Récupérer les filtres de recherche
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (!empty($_GET['type_bien'])) {
                $criteria['type_bien'] = $_GET['type_bien'];
            }
            if (!empty($_GET['price_min'])) {
                $criteria['price_min'] = (float)$_GET['price_min'];
            }
            if (!empty($_GET['price_max'])) {
                $criteria['price_max'] = (float)$_GET['price_max'];
            }
            if (!empty($_GET['surface_min'])) {
                $criteria['surface_min'] = (float)$_GET['surface_min'];
            }
            if (!empty($_GET['pieces_min'])) {
                $criteria['pieces_min'] = (int)$_GET['pieces_min'];
            }
            if (!empty($_GET['city'])) {
                $criteria['city'] = $_GET['city'];
            }
        }
        
        $annonces = $this->searchModel->searchAnnonces($criteria);
        include __DIR__ . '/../views/annonces/location.php';
    }
    public function detail($id) {
        $annonce = $this->annonceModel->getById($id);
        
        if (!$annonce) {
            $_SESSION['error'] = "Cette annonce n'existe pas.";
            header("Location: /");
            exit;
        }
        
        include __DIR__ . '/../views/annonces/detail.php';
    }

}
