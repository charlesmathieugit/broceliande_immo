<?php
namespace Controllers;

use Core\Controller;
use Models\Annonce;

class AnnonceController extends Controller {
    private $annonceModel;
    
    public function __construct() {
        parent::__construct();
        global $pdo;
        $this->annonceModel = new Annonce($pdo);
    }
    
    public function vente() {
        $annonces = $this->annonceModel->getByCategory('vente');
        return $this->render('annonces/vente.html.twig', [
            'annonces' => $annonces
        ]);
    }
    
    public function location() {
        $annonces = $this->annonceModel->getByCategory('location');
        return $this->render('annonces/location.html.twig', [
            'annonces' => $annonces
        ]);
    }
    
    public function show($id) {
        $annonce = $this->annonceModel->getById($id);
        
        if (!$annonce) {
            $this->setFlash('error', "Cette annonce n'existe pas.");
            return $this->redirect('/');
        }
        
        return $this->render('annonces/show.html.twig', [
            'annonce' => $annonce
        ]);
    }
}