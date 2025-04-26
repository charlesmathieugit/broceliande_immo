<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/helpers.php';

$db = Core\Database::getInstance();

// 1. Simplifier la structure des images - Seulement 1 image par annonce avec un nom standard
// Format: annonce_{id}.jpg 

// Récupérer toutes les annonces
$stmt = $db->query("SELECT id, title, type_bien FROM annonces");
$annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h1>Simplification du système d'images</h1>";

// Vérifier si le dossier d'images existe
$imageDir = __DIR__ . '/images/annonces/';
if (!is_dir($imageDir)) {
    mkdir($imageDir, 0777, true);
    echo "<p>Dossier d'images créé.</p>";
}

// Collecter les types de biens disponibles
$typesBien = [];
foreach ($annonces as $annonce) {
    $type = strtolower($annonce['type_bien']);
    if (!in_array($type, $typesBien) && !empty($type)) {
        $typesBien[] = $type;
    }
}

echo "<p>Types de biens trouvés : " . implode(', ', $typesBien) . "</p>";

// Images pour chaque type de bien
$typesImages = [
    'maison' => 'maison.jpg',
    'appartement' => 'appartement.jpg',
    'terrain' => 'terrain.jpg',
    'commerce' => 'commerce.jpg',
    'studio' => 'studio.jpg',
    'villa' => 'maison.jpg',
    'immeuble' => 'immeuble.jpg'
];

// Images par défaut si le type n'est pas reconnu
$defaultImages = ['default.jpg', 'default2.jpg', 'default3.jpg'];

// Copier les images d'exemple depuis les uploads si elles existent
$sourceDir = __DIR__ . '/uploads/annonces/';
if (is_dir($sourceDir)) {
    foreach ($typesImages as $type => $imageName) {
        $sourcePath = $sourceDir . $type . '.jpg';
        $destPath = $imageDir . $imageName;
        
        if (file_exists($sourcePath)) {
            copy($sourcePath, $destPath);
            echo "<p>Image copiée pour : $type</p>";
        }
    }
    
    // Copier quelques images par défaut
    for ($i = 0; $i < count($defaultImages); $i++) {
        $sourcePath = $sourceDir . 'maison' . ($i+1) . '.jpg';
        $destPath = $imageDir . $defaultImages[$i];
        
        if (file_exists($sourcePath)) {
            copy($sourcePath, $destPath);
            echo "<p>Image par défaut copiée : {$defaultImages[$i]}</p>";
        }
    }
}

// Mise à jour de toutes les annonces
$updatedCount = 0;
foreach ($annonces as $annonce) {
    // Nom standardisé pour l'image de cette annonce
    $imageFilename = 'annonce_' . $annonce['id'] . '.jpg';
    $imagePath = '/images/annonces/' . $imageFilename;
    
    // Déterminer quelle image source utiliser
    $type = strtolower($annonce['type_bien']);
    $sourceImage = '';
    
    if (isset($typesImages[$type])) {
        $sourceImage = $imageDir . $typesImages[$type];
    } else {
        // Utiliser une image par défaut en rotation
        $sourceImage = $imageDir . $defaultImages[$updatedCount % count($defaultImages)];
    }
    
    // Copier l'image source avec le nouveau nom standardisé
    if (file_exists($sourceImage)) {
        copy($sourceImage, $imageDir . $imageFilename);
        echo "<p>✅ Image créée pour l'annonce #{$annonce['id']} ({$annonce['title']}) : $imageFilename</p>";
    }
    
    // Mettre à jour l'annonce avec le nouveau chemin d'image
    $updateStmt = $db->prepare("UPDATE annonces SET image_path = ? WHERE id = ?");
    if ($updateStmt->execute([$imagePath, $annonce['id']])) {
        $updatedCount++;
    } else {
        echo "<p>❌ Échec de la mise à jour pour l'annonce #{$annonce['id']}</p>";
    }
}

echo "<h2>Résumé</h2>";
echo "<p>{$updatedCount} annonces mises à jour avec succès.</p>";
echo "<p>Nouveau système d'image : <strong>Une seule image par annonce, avec un nom standardisé (annonce_ID.jpg)</strong></p>";
echo "<p><a href='/' class='btn btn-primary'>Retour à l'accueil</a></p>";

echo "<style>
    body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
    h1, h2 { color: #2c3e50; }
    .btn { display: inline-block; padding: 10px 15px; background: #3498db; color: white; text-decoration: none; border-radius: 3px; }
</style>";
