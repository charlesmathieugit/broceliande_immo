<?php
namespace Controllers;

use Core\Controller;
use Core\Database;
use Models\Contact;

class ContactController extends Controller {
    private $contactModel;
    
    public function __construct() {
        parent::__construct();
        $this->contactModel = new Contact(Database::getInstance());
    }
    
    public function showForm() {
        return $this->render('contact/index.html.twig');
    }
    
    public function send() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING)),
                'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
                'phone' => trim(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING)),
                'message' => trim(filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING))
            ];
            
            // Validation des données
            $errors = [];
            if (empty($data['name'])) {
                $errors[] = "Le nom est obligatoire";
            }
            
            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'adresse email n'est pas valide";
            }
            
            if (empty($data['phone'])) {
                $errors[] = "Le numéro de téléphone est obligatoire";
            }
            
            if (!empty($errors)) {
                $this->setFlash('error', implode('<br>', $errors));
                return $this->render('contact/index.html.twig', [
                    'formData' => $data,
                    'errors' => $errors
                ]);
            }
            
            // Traitement du message de contact
            try {
                // Enregistrement du message dans la base de données si nécessaire
                // $this->contactModel->create($data);
                
                // Envoi d'un email à l'administrateur (désactivé pour le développement)
                /*
                $to = "contact@broceliande-immo.fr";
                $subject = "Nouveau message de contact de " . $data['name'];
                $message = "Nom: " . $data['name'] . "\n";
                $message .= "Email: " . $data['email'] . "\n";
                $message .= "Téléphone: " . $data['phone'] . "\n\n";
                $message .= "Message:\n" . $data['message'];
                $headers = "From: " . $data['email'];
                
                mail($to, $subject, $message, $headers);
                */
                
                // Message de succès
                $this->setFlash('success', 'Merci pour votre message ! Un conseiller vous recontactera très rapidement.');
                return $this->redirect('contact');
                
            } catch (\Exception $e) {
                $this->setFlash('error', 'Une erreur est survenue lors de l\'envoi du message.');
                return $this->render('contact/index.html.twig', [
                    'formData' => $data
                ]);
            }
        }
        
        return $this->redirect('contact');
    }
    
    public function admin() {
        // Fonction admin sans vérification d'authentification
        // Note: cette fonction est accessible à tous les utilisateurs
        
        // Récupération des messages de contact
        // $messages = $this->contactModel->getAll();
        
        return $this->render('contact/admin.html.twig', [
            'messages' => []
        ]);
    }
}