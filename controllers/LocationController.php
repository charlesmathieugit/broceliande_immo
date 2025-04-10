<?php
namespace Controllers;

use Core\Controller;
use Models\Annonce;

class LocationController extends Controller {
    private $annonceModel;
    
    public function __construct() {
        parent::__construct();
        $this->annonceModel = new Annonce($this->pdo);
    }
    
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 6;
        $offset = ($page - 1) * $limit;
        
        // Filtres
        $filters = [
            'category' => 'location',
            'price_min' => isset($_GET['prix_min']) ? (int)$_GET['prix_min'] : null,
            'price_max' => isset($_GET['prix_max']) ? (int)$_GET['prix_max'] : null,
            'surface_min' => isset($_GET['surface_min']) ? (int)$_GET['surface_min'] : null,
            'pieces' => isset($_GET['pieces']) ? (int)$_GET['pieces'] : null
        ];
        
        $annonces = $this->annonceModel->findByFilters($filters, $limit, $offset);
        
        // Récupérer les favoris si l'utilisateur est connecté
        $favoris = [];
        if (isset($_SESSION['user'])) {
            $favoris = $this->annonceModel->getFavorisByUser($_SESSION['user']['id']);
        }
        
        return $this->render('annonces/location.html.twig', [
            'annonces' => $annonces,
            'favoris' => $favoris,
            'pages' => ceil(count($annonces) / $limit),
            'current_page' => $page
        ]);
    }
}
