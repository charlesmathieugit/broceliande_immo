<?php
namespace Models;

use PDO;

class Annonce {
    private $pdo;
    
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    
    public function create($data) {
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO annonces (user_id, title, category, price, charges, pieces, surface, description, address, postal_code, city, type_bien, dpe_rating, ges_rating, features, image_path) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            
            return $stmt->execute([
                $data['user_id'],
                $data['title'],
                $data['category'],
                $data['price'],
                $data['charges'] ?? null,
                $data['pieces'],
                $data['surface'],
                $data['description'],
                $data['address'],
                $data['postal_code'],
                $data['city'],
                $data['type_bien'],
                $data['dpe_rating'] ?? null,
                $data['ges_rating'] ?? null,
                json_encode($data['features'] ?? []),
                $data['image_path'] ?? null
            ]);
            
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM annonces WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM annonces ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function update($id, $userId, $data) {
        try {
            $stmt = $this->pdo->prepare(
                "UPDATE annonces 
                 SET title = ?, category = ?, price = ?, charges = ?, pieces = ?, 
                     surface = ?, description = ?, address = ?, postal_code = ?, 
                     city = ?, type_bien = ?, dpe_rating = ?, ges_rating = ?, 
                     features = ?, image_path = ?
                 WHERE id = ? AND user_id = ?"
            );
            
            return $stmt->execute([
                $data['title'],
                $data['category'],
                $data['price'],
                $data['charges'] ?? null,
                $data['pieces'],
                $data['surface'],
                $data['description'],
                $data['address'],
                $data['postal_code'],
                $data['city'],
                $data['type_bien'],
                $data['dpe_rating'] ?? null,
                $data['ges_rating'] ?? null,
                json_encode($data['features'] ?? []),
                $data['image_path'] ?? null,
                $id,
                $userId
            ]);
            
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
    public function delete($id, $userId) {
        $stmt = $this->pdo->prepare("DELETE FROM annonces WHERE id = ? AND user_id = ?");
        return $stmt->execute([$id, $userId]);
    }
    
    public function findByFilters($filters = [], $limit = null) {
        $sql = "SELECT * FROM annonces WHERE 1=1";
        $params = [];
        
        if (isset($filters['category'])) {
            $sql .= " AND category = ?";
            $params[] = $filters['category'];
        }
        
        if (isset($filters['type_bien'])) {
            $sql .= " AND type_bien = ?";
            $params[] = $filters['type_bien'];
        }
        
        if (isset($filters['ville'])) {
            $sql .= " AND city LIKE ?";
            $params[] = '%' . $filters['ville'] . '%';
        }
        
        if (isset($filters['prix_min'])) {
            $sql .= " AND price >= ?";
            $params[] = $filters['prix_min'];
        }
        
        if (isset($filters['prix_max'])) {
            $sql .= " AND price <= ?";
            $params[] = $filters['prix_max'];
        }
        
        if (isset($filters['surface_min'])) {
            $sql .= " AND surface >= ?";
            $params[] = $filters['surface_min'];
        }
        
        if (isset($filters['pieces'])) {
            $sql .= " AND pieces >= ?";
            $params[] = $filters['pieces'];
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}