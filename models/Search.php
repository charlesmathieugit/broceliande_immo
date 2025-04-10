<?php
class Search {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function searchAnnonces($criteria) {
        $conditions = [];
        $params = [];
        
        $sql = "SELECT a.*, GROUP_CONCAT(i.file_path) as images 
                FROM annonces a 
                LEFT JOIN images i ON a.id = i.annonce_id 
                WHERE a.is_active = 1";
        
        if (!empty($criteria['category'])) {
            $conditions[] = "a.category = ?";
            $params[] = $criteria['category'];
        }
        
        if (!empty($criteria['type_bien'])) {
            $conditions[] = "a.type_bien = ?";
            $params[] = $criteria['type_bien'];
        }
        
        if (!empty($criteria['price_min'])) {
            $conditions[] = "a.price >= ?";
            $params[] = $criteria['price_min'];
        }
        
        if (!empty($criteria['price_max'])) {
            $conditions[] = "a.price <= ?";
            $params[] = $criteria['price_max'];
        }
        
        if (!empty($criteria['surface_min'])) {
            $conditions[] = "a.surface >= ?";
            $params[] = $criteria['surface_min'];
        }
        
        if (!empty($criteria['pieces_min'])) {
            $conditions[] = "a.pieces >= ?";
            $params[] = $criteria['pieces_min'];
        }
        
        if (!empty($criteria['city'])) {
            $conditions[] = "a.city LIKE ?";
            $params[] = "%{$criteria['city']}%";
        }
        
        if (!empty($conditions)) {
            $sql .= " AND " . implode(" AND ", $conditions);
        }
        
        $sql .= " GROUP BY a.id ORDER BY a.created_at DESC";
        
        if (!empty($criteria['limit'])) {
            $sql .= " LIMIT " . (int)$criteria['limit'];
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}