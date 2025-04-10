<?php
namespace Models;

use PDO;
use PDOException;
use Exception;

class User {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    public function authenticate($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
    
    public function emailExists($email) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }
 
    public function create($userData) {
        $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
        $stmt = $this->pdo->prepare($sql);
        
        try {
            $success = $stmt->execute([
                'email' => $userData['email'],
                'password' => $userData['password']
            ]);
            
            if (!$success) {
                throw new Exception("Échec de l'insertion en base de données");
            }
            
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur SQL lors de la création de l'utilisateur : " . $e->getMessage());
            throw new Exception("Erreur lors de la création du compte : " . $e->getMessage());
        }
    }
    
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}