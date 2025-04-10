<?php
namespace Controllers;

use Core\Controller;
use Models\User;

class AuthController extends Controller {
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }
    
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $errors = [];
            
            // Validation de l'email
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'adresse email n'est pas valide";
            }
            
            // Vérification si l'email existe déjà
            if ($this->userModel->emailExists($email)) {
                $errors[] = "Cette adresse email est déjà utilisée";
            }
            
            // Validation du mot de passe
            if (empty($password) || strlen($password) < 6) {
                $errors[] = "Le mot de passe doit contenir au moins 6 caractères";
            }
            
            if (empty($errors)) {
                try {
                    $userId = $this->userModel->create([
                        'email' => $email,
                        'password' => $password
                    ]);

                    if ($userId) {
                        $this->setFlash('success', "Inscription réussie ! Vous pouvez maintenant vous connecter.");
                        return $this->redirect('login');
                    } else {
                        throw new \Exception("Échec de la création de l'utilisateur");
                    }
                } catch (\Exception $e) {
                    error_log($e->getMessage());
                    $errors[] = "Une erreur est survenue lors de l'inscription";
                }
            }
            
            // En cas d'erreur, on réaffiche le formulaire avec les erreurs
            return $this->render('auth/register.html.twig', [
                'errors' => $errors,
                'old' => compact('email')
            ]);
        }
        
        return $this->render('auth/register.html.twig');
    }
    
    public function login() {
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            return $this->render('auth/login.html.twig');
        }
        
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? null;
        
        if (!$email || !$password) {
            $this->setFlash('error', "Veuillez remplir tous les champs.");
            return $this->redirect('login');
        }
        
        try {
            $user = $this->userModel->authenticate($email, $password);
            
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                
                $this->setFlash('success', "Connexion réussie !");
                return $this->redirect('');
            }
            
            $this->setFlash('error', "Identifiants incorrects.");
            return $this->redirect('login');
        } catch (\Exception $e) {
            error_log("Erreur lors de l'authentification : " . $e->getMessage());
            $this->setFlash('error', "Une erreur est survenue lors de la connexion.");
            return $this->redirect('login');
        }
    }
    
    public function logout() {
        session_destroy();
        $this->setFlash('info', "Vous avez été déconnecté.");
        return $this->redirect('');
    }
}