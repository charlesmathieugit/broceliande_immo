<?php
namespace Models;

use PDO;

class Annonce {
    private $pdo;
    
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    
    public function create($data) {
        $this->pdo->beginTransaction();
        
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO annonces (user_id, title, category, price, charges, pieces, surface, description, address, postal_code, city, type_bien, dpe_rating, ges_rating, features) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            
            $success = $stmt->execute([
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
                json_encode($data['features'] ?? [])
            ]);
            
            if ($success) {
                $annonceId = $this->pdo->lastInsertId();
                
                // Si des images ont été uploadées, les ajouter
                if (isset($data['images']) && is_array($data['images'])) {
                    foreach ($data['images'] as $image) {
                        $this->addImage($annonceId, $image);
                    }
                }
                
                $this->pdo->commit();
                return $annonceId;
            }
            
            $this->pdo->rollBack();
            return false;
            
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
    
    public function addImage($annonceId, $image) {
        // Vérifier si c'est un fichier uploadé
        if (is_array($image) && isset($image['tmp_name'])) {
            // Générer un nom unique pour l'image
            $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid() . '.' . $extension;
            $uploadPath = 'uploads/annonces/' . $newFileName;
            
            // Déplacer le fichier uploadé
            if (move_uploaded_file($image['tmp_name'], 'public/' . $uploadPath)) {
                $stmt = $this->pdo->prepare(
                    "INSERT INTO images (annonce_id, file_path) VALUES (?, ?)"
                );
                return $stmt->execute([$annonceId, $uploadPath]);
            }
            return false;
        }
        
        // Si c'est déjà un chemin de fichier
        if (is_string($image)) {
            $stmt = $this->pdo->prepare(
                "INSERT INTO images (annonce_id, file_path) VALUES (?, ?)"
            );
            return $stmt->execute([$annonceId, $image]);
        }
        
        return false;
    }
    
    public function getById($id) {
        $sql = "SELECT a.*, i.file_path 
                FROM annonces a 
                LEFT JOIN images i ON a.id = i.annonce_id 
                WHERE a.id = ?";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        
        $annonce = null;
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (!$annonce) {
                $annonce = [
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'description' => $row['description'],
                    'price' => $row['price'],
                    'surface' => $row['surface'],
                    'pieces' => $row['pieces'],
                    'city' => $row['city'],
                    'type_bien' => $row['type_bien'],
                    'category' => $row['category'],
                    'charges' => $row['charges'] ?? null,
                    'created_at' => $row['created_at'],
                    'images' => []
                ];
            }
            
            if ($row['file_path']) {
                $annonce['images'][] = $row['file_path'];
            }
        }
        
        return $annonce;
    }
    
    public function getAll() {
        return $this->pdo->query("SELECT * FROM annonces ORDER BY created_at DESC")->fetchAll();
    }
    
    public function update($id, $userId, $data) {
        $this->pdo->beginTransaction();
        
        try {
            $stmt = $this->pdo->prepare(
                "UPDATE annonces SET 
                    title = ?, 
                    category = ?,
                    price = ?,
                    charges = ?,
                    pieces = ?,
                    surface = ?,
                    description = ?,
                    address = ?,
                    postal_code = ?,
                    city = ?,
                    type_bien = ?,
                    dpe_rating = ?,
                    ges_rating = ?,
                    features = ?
                WHERE id = ? AND user_id = ?"
            );
            
            $success = $stmt->execute([
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
                $id,
                $userId
            ]);
            
            if ($success && isset($data['images']) && is_array($data['images'])) {
                // Supprimer les anciennes images
                $this->deleteImages($id);
                
                // Ajouter les nouvelles images
                foreach ($data['images'] as $image) {
                    $this->addImage($id, $image);
                }
            }
            
            $this->pdo->commit();
            return $success;
            
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
    
    public function delete($id, $userId) {
        $this->pdo->beginTransaction();
        
        try {
            // Supprimer d'abord les images
            $this->deleteImages($id);
            
            // Puis supprimer l'annonce
            $stmt = $this->pdo->prepare("DELETE FROM annonces WHERE id = ? AND user_id = ?");
            $success = $stmt->execute([$id, $userId]);
            
            $this->pdo->commit();
            return $success;
            
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
    
    private function deleteImages($annonceId) {
        // Récupérer les chemins des images
        $stmt = $this->pdo->prepare("SELECT file_path FROM images WHERE annonce_id = ?");
        $stmt->execute([$annonceId]);
        $images = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Supprimer les fichiers physiques
        foreach ($images as $image) {
            $filePath = 'public/' . $image;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        // Supprimer les entrées dans la base de données
        $stmt = $this->pdo->prepare("DELETE FROM images WHERE annonce_id = ?");
        return $stmt->execute([$annonceId]);
    }
    
    public function findByFilters($filters = [], $limit = null) {
        $sql = "SELECT DISTINCT a.* FROM annonces a WHERE 1=1";
        $params = [];
        
        if (isset($filters['category'])) {
            $sql .= " AND a.category = ?";
            $params[] = $filters['category'];
        }
        
        if (isset($filters['type_bien'])) {
            $sql .= " AND a.type_bien = ?";
            $params[] = $filters['type_bien'];
        }
        
        if (isset($filters['ville'])) {
            $sql .= " AND a.city LIKE ?";
            $params[] = '%' . $filters['ville'] . '%';
        }
        
        if (isset($filters['prix_min'])) {
            $sql .= " AND a.price >= ?";
            $params[] = $filters['prix_min'];
        }
        
        if (isset($filters['prix_max'])) {
            $sql .= " AND a.price <= ?";
            $params[] = $filters['prix_max'];
        }
        
        // Trier par date de création décroissante
        $sql .= " ORDER BY a.created_at DESC";
        
        // Ajouter la limite si spécifiée
        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $annonces = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Récupérer les images de l'annonce
            $stmtImages = $this->pdo->prepare("SELECT file_path FROM images WHERE annonce_id = ?");
            $stmtImages->execute([$row['id']]);
            $images = array_map(function($path) {
                return 'uploads/annonces/' . $path;
            }, $stmtImages->fetchAll(PDO::FETCH_COLUMN));
            
            $annonces[] = [
                'id' => $row['id'],
                'title' => $row['title'],
                'description' => $row['description'],
                'price' => $row['price'],
                'surface' => $row['surface'],
                'pieces' => $row['pieces'],
                'city' => $row['city'],
                'type_bien' => $row['type_bien'],
                'category' => $row['category'],
                'charges' => $row['charges'] ?? null,
                'created_at' => $row['created_at'],
                'images' => $images
            ];
        }
        
        return $annonces;
    }
}