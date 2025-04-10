<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/RendezVous.php';
require_once __DIR__ . '/../models/Annonce.php';

class RendezVousController {
    private $rendezVousModel;
    private $annonceModel;
    
    public function __construct() {
        global $pdo;
        $this->rendezVousModel = new RendezVous($pdo);
        $this->annonceModel = new Annonce($pdo);
    }
    
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Vous devez être connecté pour voir vos rendez-vous.";
            header("Location: /login.php");
            exit;
        }
        
        $rendezVous = $this->rendezVousModel->getByUserId($_SESSION['user_id']);
        include __DIR__ . '/../views/rendez_vous/index.php';
    }
    
    public function create() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Vous devez être connecté pour prendre un rendez-vous.";
            header("Location: /login.php");
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'annonce_id' => $_POST['annonce_id'],
                'user_id' => $_SESSION['user_id'],
                'date_rendez_vous' => $_POST['date_rendez_vous']
            ];
            
            if ($this->rendezVousModel->create($data)) {
                $_SESSION['success'] = "Rendez-vous créé avec succès.";
                header("Location: /rendez-vous");
                exit;
            } else {
                $_SESSION['error'] = "Erreur lors de la création du rendez-vous.";
            }
        }
        
        $annonces = $this->annonceModel->getAll();
        include __DIR__ . '/../views/rendez_vous/create.php';
    }
    
    public function updateStatus($id) {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Vous devez être connecté pour modifier un rendez-vous.";
            header("Location: /login.php");
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
            if ($this->rendezVousModel->updateStatus($id, $_POST['status'])) {
                $_SESSION['success'] = "Statut du rendez-vous mis à jour.";
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour du statut.";
            }
        }
        
        header("Location: /rendez-vous");
        exit;
    }
}