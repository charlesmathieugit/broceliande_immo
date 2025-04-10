<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Contact.php';

class ContactController {
    private $contactModel;
    
    public function __construct() {
        global $pdo;
        $this->contactModel = new Contact($pdo);
    }
    
    public function index() {
        include __DIR__ . '/../views/contact/index.php';
    }
    
    public function send() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING),
                'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
                'message' => filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING)
            ];
            
            // Validation des données
            if (!$data['name'] || !$data['email'] || !$data['message']) {
                $_SESSION['error'] = "Tous les champs sont obligatoires.";
                header("Location: /contact");
                exit;
            }
            
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "L'adresse email n'est pas valide.";
                header("Location: /contact");
                exit;
            }
            
            // Envoi d'un email de notification (à configurer)
            $to = "admin@broceliande-immo.fr";
            $subject = "Nouveau message de contact";
            $message = "Nom: " . $data['name'] . "\n";
            $message .= "Email: " . $data['email'] . "\n\n";
            $message .= "Message:\n" . $data['message'];
            $headers = "From: " . $data['email'];
            
            mail($to, $subject, $message, $headers);
            
            // Sauvegarde dans la base de données
            if ($this->contactModel->create($data)) {
                $_SESSION['success'] = "Votre message a été envoyé avec succès.";
            } else {
                $_SESSION['error'] = "Une erreur est survenue lors de l'envoi du message.";
            }
            
            header("Location: /contact");
            exit;
        }
    }
    
    public function admin() {
        // Vérification des droits d'administration
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: /login");
            exit;
        }
        
        $messages = $this->contactModel->getAll();
        include __DIR__ . '/../views/contact/admin.php';
    }
}