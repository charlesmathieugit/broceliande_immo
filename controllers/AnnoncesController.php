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
        $annonces = $this->annonceModel->findByFilters(['category' => 'vente']);
        return $this->render('annonces/vente.html.twig', [
            'annonces' => $annonces
        ]);
    }
    
    public function location() {
        $annonces = $this->annonceModel->findByFilters(['category' => 'location']);
        return $this->render('annonces/location.html.twig', [
            'annonces' => $annonces
        ]);
    }
}
