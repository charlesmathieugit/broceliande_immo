<?php
class Contact {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function create($data) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO contacts (name, email, message, created_at) 
             VALUES (?, ?, ?, NOW())"
        );
        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['message']
        ]);
    }
    
    public function getAll() {
        $stmt = $this->pdo->query(
            "SELECT * FROM contacts 
             ORDER BY created_at DESC"
        );
        return $stmt->fetchAll();
    }
    
    public function markAsRead($id) {
        $stmt = $this->pdo->prepare(
            "UPDATE contacts SET is_read = 1 WHERE id = ?"
        );
        return $stmt->execute([$id]);
    }
}