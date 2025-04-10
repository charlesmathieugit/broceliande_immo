<?php
namespace Controllers;

use Core\Controller;
use Core\Database;
use Models\Annonce;

class HomeController extends Controller {
    private $annonceModel;
    
    public function __construct() {
        parent::__construct();
        $this->annonceModel = new Annonce(Database::getInstance());
    }
    
    public function index() {
        // Récupérer les dernières annonces en vente et en location
        $ventesRecentes = $this->annonceModel->findByFilters(['category' => 'vente'], 3);
        $locationsRecentes = $this->annonceModel->findByFilters(['category' => 'location'], 3);
        
        return $this->render('home/index.html.twig', [
            'ventes' => $ventesRecentes,
            'locations' => $locationsRecentes
        ]);
    }
}
