<?php
require_once __DIR__ . '/../config/database.php';

try {
    // Lire le contenu du fichier SQL
    $sql = file_get_contents(__DIR__ . '/insert_data.sql');
    
    // Exécuter les requêtes
    $pdo->exec($sql);
    
    echo "Les données ont été insérées avec succès !\n";
} catch (PDOException $e) {
    echo "Erreur lors de l'insertion des données : " . $e->getMessage() . "\n";
}
