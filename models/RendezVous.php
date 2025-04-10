<?php
class RendezVous {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function create($data) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO rendez_vous (annonce_id, user_id, date_rendez_vous, statut) 
             VALUES (?, ?, ?, 'en_attente')"
        );
        return $stmt->execute([
            $data['annonce_id'],
            $data['user_id'],
            $data['date_rendez_vous']
        ]);
    }
    
    public function getByUserId($userId) {
        $stmt = $this->pdo->prepare(
            "SELECT rv.*, a.title as annonce_title 
             FROM rendez_vous rv 
             JOIN annonces a ON rv.annonce_id = a.id 
             WHERE rv.user_id = ? 
             ORDER BY rv.date_rendez_vous DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function getByAnnonceId($annonceId) {
        $stmt = $this->pdo->prepare(
            "SELECT rv.*, u.email as user_email 
             FROM rendez_vous rv 
             JOIN users u ON rv.user_id = u.id 
             WHERE rv.annonce_id = ? 
             ORDER BY rv.date_rendez_vous DESC"
        );
        $stmt->execute([$annonceId]);
        return $stmt->fetchAll();
    }
    
    public function updateStatus($id, $status) {
        $stmt = $this->pdo->prepare(
            "UPDATE rendez_vous SET statut = ? WHERE id = ?"
        );
        return $stmt->execute([$status, $id]);
    }
    
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM rendez_vous WHERE id = ?");
        return $stmt->execute([$id]);
    }
}