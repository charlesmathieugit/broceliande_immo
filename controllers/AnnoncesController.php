<?php
namespace Controllers;

use Core\Controller;
use Core\Database;
use Models\Annonce;

class AnnoncesController extends Controller {
    private $annonceModel;
    
    public function __construct() {
        parent::__construct();
        $this->annonceModel = new Annonce(Database::getInstance());
    }
    
    public function show($id) {
        $annonce = $this->annonceModel->getById($id);
        
        if (!$annonce) {
            header('HTTP/1.0 404 Not Found');
            return $this->render('errors/404.html.twig');
        }
        
        return $this->render('annonces/show.html.twig', [
            'annonce' => $annonce
        ]);
    }
    
    public function vente() {
        // Récupération des paramètres de filtre
        $filters = [
            'category' => 'vente',
            'prix_min' => filter_input(INPUT_GET, 'prix_min', FILTER_VALIDATE_INT),
            'prix_max' => filter_input(INPUT_GET, 'prix_max', FILTER_VALIDATE_INT),
            'surface_min' => filter_input(INPUT_GET, 'surface_min', FILTER_VALIDATE_INT),
            'pieces' => filter_input(INPUT_GET, 'pieces', FILTER_VALIDATE_INT)
        ];
        
        // Nettoyage des filtres vides
        $filters = array_filter($filters, function($value) {
            return $value !== false && $value !== null && $value !== '';
        });
        
        $annonces = $this->annonceModel->findByFilters($filters);
        
        return $this->render('annonces/vente.html.twig', [
            'annonces' => $annonces,
            'filters' => $filters
        ]);
    }
    
    public function location() {
        // Récupération des paramètres de filtre
        $filters = [
            'category' => 'location',
            'prix_min' => filter_input(INPUT_GET, 'prix_min', FILTER_VALIDATE_INT),
            'prix_max' => filter_input(INPUT_GET, 'prix_max', FILTER_VALIDATE_INT),
            'surface_min' => filter_input(INPUT_GET, 'surface_min', FILTER_VALIDATE_INT),
            'pieces' => filter_input(INPUT_GET, 'pieces', FILTER_VALIDATE_INT)
        ];
        
        // Nettoyage des filtres vides
        $filters = array_filter($filters, function($value) {
            return $value !== false && $value !== null && $value !== '';
        });
        
        $annonces = $this->annonceModel->findByFilters($filters);
        
        return $this->render('annonces/location.html.twig', [
            'annonces' => $annonces,
            'filters' => $filters
        ]);
    }
}
