<?php
$host = 'localhost';
$dbname = 'broceliande_immo';
$username = 'root'; 
$password = '';  

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Récupérer toutes les images des annonces
    $query = "SELECT i.id, i.file_path, i.annonce_id, a.title 
              FROM images i 
              LEFT JOIN annonces a ON i.annonce_id = a.id";
    
    $stmt = $pdo->query($query);
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "=== Vérification des chemins d'images ===\n\n";
    
    foreach ($images as $image) {
        echo "Image ID: " . $image['id'] . "\n";
        echo "Annonce: " . $image['title'] . " (ID: " . $image['annonce_id'] . ")\n";
        echo "Chemin: " . $image['file_path'] . "\n";
        
        // Vérifier si le fichier existe physiquement
        $fullPath = __DIR__ . '/../public/uploads/annonces/' . $image['file_path'];
        if (file_exists($fullPath)) {
            echo "✅ Le fichier existe\n";
        } else {
            echo "❌ Le fichier n'existe pas !\n";
            echo "Chemin complet: $fullPath\n";
        }
        echo "\n";
    }

} catch (PDOException $e) {
    echo "Erreur de base de données : " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}
