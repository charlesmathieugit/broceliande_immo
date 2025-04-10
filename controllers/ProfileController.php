<?php
namespace Controllers;

use Core\Controller;
use Models\User;

class ProfileController extends Controller {
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new User($this->pdo);
        
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
    }
    
    public function index() {
        $userId = $_SESSION['user']['id'];
        $user = $this->userModel->find($userId);
        
        return $this->render('profile/index.html.twig', [
            'user' => $user
        ]);
    }
    
    public function update() {
        $userId = $_SESSION['user']['id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom' => filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING),
                'prenom' => filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING),
                'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
                'telephone' => filter_input(INPUT_POST, 'telephone', FILTER_SANITIZE_STRING)
            ];
            
            // Validation
            $errors = [];
            if (!$data['nom'] || strlen($data['nom']) < 2) {
                $errors[] = "Le nom doit contenir au moins 2 caractères";
            }
            if (!$data['prenom'] || strlen($data['prenom']) < 2) {
                $errors[] = "Le prénom doit contenir au moins 2 caractères";
            }
            if (!$data['email'] || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'adresse email n'est pas valide";
            }
            
            // Vérifier si l'email existe déjà pour un autre utilisateur
            if ($this->userModel->emailExists($data['email'], $userId)) {
                $errors[] = "Cette adresse email est déjà utilisée";
            }
            
            // Gestion du mot de passe
            if (!empty($_POST['password'])) {
                if (strlen($_POST['password']) < 8) {
                    $errors[] = "Le mot de passe doit contenir au moins 8 caractères";
                } elseif ($_POST['password'] !== $_POST['confirm_password']) {
                    $errors[] = "Les mots de passe ne correspondent pas";
                } else {
                    $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                }
            }
            
            if (empty($errors)) {
                if ($this->userModel->update($userId, $data)) {
                    // Mettre à jour les données de session
                    $_SESSION['user'] = array_merge($_SESSION['user'], $data);
                    $_SESSION['flash']['success'] = "Profil mis à jour avec succès";
                } else {
                    $_SESSION['flash']['error'] = "Une erreur est survenue lors de la mise à jour du profil";
                }
            } else {
                $_SESSION['flash']['error'] = implode('<br>', $errors);
            }
        }
        
        return $this->redirect('/profile');
    }
}
