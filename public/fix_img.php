<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/helpers.php';

$db = Core\Database::getInstance();

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Correction des images</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; }
        h1, h2 { color: #2c3e50; }
        .image-box { border: 1px solid #ddd; padding: 10px; margin-bottom: 15px; border-radius: 5px; }
        .flex { display: flex; flex-wrap: wrap; gap: 20px; }
        img { max-height: 150px; border: 1px solid #eee; }
        .btn { display: inline-block; padding: 10px 15px; background: #3498db; color: white; text-decoration: none; border-radius: 3px; margin-right: 10px; }
    </style>
</head>
<body>
    <h1>Correction des images</h1>";

// 1. S'assurer que le dossier des images existe
$imageDir = __DIR__ . '/images/annonces/';
if (!is_dir($imageDir)) {
    mkdir($imageDir, 0777, true);
    echo "<p>✅ Dossier d'images créé: /images/annonces/</p>";
} else {
    echo "<p>✓ Le dossier d'images existe déjà</p>";
}

// 2. Trouver toutes les images disponibles dans les dossiers uploads et public
$availableImages = [];

// Chercher dans uploads/annonces
$uploadsDir = __DIR__ . '/uploads/annonces/';
if (is_dir($uploadsDir)) {
    $files = scandir($uploadsDir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..' && in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif'])) {
            $availableImages[] = [
                'source' => $uploadsDir . $file,
                'name' => $file
            ];
        }
    }
}

echo "<h2>Images disponibles (" . count($availableImages) . ")</h2>";
echo "<div class='flex'>";
foreach ($availableImages as $img) {
    echo "<div class='image-box'>";
    echo "<p>Nom: " . $img['name'] . "</p>";
    echo "<img src='/broceliande_immo/public/uploads/annonces/" . $img['name'] . "' alt='Image'>";
    echo "</div>";
}
echo "</div>";

// 3. Mettre à jour les annonces avec des chemins d'images corrects
$stmt = $db->query("SELECT id, title, type_bien, image_path FROM annonces");
$annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h2>État actuel des annonces</h2>";
echo "<table border='1' cellpadding='5' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>ID</th><th>Titre</th><th>Type</th><th>Chemin d'image actuel</th><th>Image actuelle</th></tr>";

foreach ($annonces as $annonce) {
    echo "<tr>";
    echo "<td>" . $annonce['id'] . "</td>";
    echo "<td>" . $annonce['title'] . "</td>";
    echo "<td>" . $annonce['type_bien'] . "</td>";
    echo "<td>" . ($annonce['image_path'] ?: "non défini") . "</td>";
    
    // Afficher l'image actuelle si elle existe
    if ($annonce['image_path']) {
        echo "<td><img src='" . url($annonce['image_path']) . "' style='max-height: 100px;'></td>";
    } else {
        echo "<td>Pas d'image</td>";
    }
    echo "</tr>";
}
echo "</table>";

// 4. Offrir des options de correction
echo "<h2>Options de correction</h2>";

// Option 1: Utiliser des chemins directs (sans public)
echo "<h3>Option 1: Corriger les chemins d'images (retirer 'public/')</h3>";
echo "<form method='post' action='fix_img.php'>";
echo "<input type='hidden' name='action' value='remove_public'>";
echo "<button type='submit' class='btn'>Appliquer la correction</button>";
echo "</form>";

// Option 2: Attribuer une image en fonction du type de bien
echo "<h3>Option 2: Assigner des images selon le type de bien</h3>";
echo "<form method='post' action='fix_img.php'>";
echo "<input type='hidden' name='action' value='assign_by_type'>";
echo "<button type='submit' class='btn'>Appliquer cette correction</button>";
echo "</form>";

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'remove_public') {
        // Corriger les chemins en retirant 'public/'
        $updateCount = 0;
        
        foreach ($annonces as $annonce) {
            if ($annonce['image_path']) {
                $newPath = $annonce['image_path'];
                
                // Vérifier si le chemin commence par 'public/'
                if (strpos($newPath, 'public/') === 0) {
                    $newPath = substr($newPath, 7); // Enlever 'public/'
                    
                    $stmt = $db->prepare("UPDATE annonces SET image_path = ? WHERE id = ?");
                    if ($stmt->execute([$newPath, $annonce['id']])) {
                        $updateCount++;
                    }
                }
            }
        }
        
        echo "<div style='background: #d4edda; color: #155724; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
        echo "<h3>Correction appliquée</h3>";
        echo "<p>{$updateCount} annonces ont été mises à jour.</p>";
        echo "<p><a href='/' class='btn'>Retour à l'accueil</a></p>";
        echo "</div>";
    }
    else if ($action === 'assign_by_type') {
        // Assigner une image en fonction du type de bien
        $typeMappings = [
            'maison' => 'maison.jpg',
            'appartement' => 'appartement.jpg',
            'terrain' => 'terrain.jpg',
            'commerce' => 'commerce.jpg',
            'studio' => 'studio.jpg',
            'villa' => 'maison.jpg',
            'immeuble' => 'immeuble.jpg'
        ];
        
        $defaultImages = ['maison.jpg', 'appartement.jpg', 'terrain.jpg'];
        $updateCount = 0;
        $imageIndex = 0;
        
        foreach ($annonces as $annonce) {
            $type = strtolower($annonce['type_bien']);
            $imageFile = '';
            
            if (isset($typeMappings[$type])) {
                $imageFile = $typeMappings[$type];
            } else {
                $imageFile = $defaultImages[$imageIndex % count($defaultImages)];
                $imageIndex++;
            }
            
            // Copier l'image si elle existe
            $sourcePath = __DIR__ . '/uploads/annonces/' . $imageFile;
            if (file_exists($sourcePath)) {
                $destPath = $imageDir . $imageFile;
                if (!file_exists($destPath)) {
                    copy($sourcePath, $destPath);
                }
                
                // Mettre à jour l'annonce
                $newPath = 'images/annonces/' . $imageFile;
                $stmt = $db->prepare("UPDATE annonces SET image_path = ? WHERE id = ?");
                if ($stmt->execute([$newPath, $annonce['id']])) {
                    $updateCount++;
                }
            }
        }
        
        echo "<div style='background: #d4edda; color: #155724; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
        echo "<h3>Correction appliquée</h3>";
        echo "<p>{$updateCount} annonces ont été mises à jour avec des images selon leur type.</p>";
        echo "<p><a href='/' class='btn'>Retour à l'accueil</a></p>";
        echo "</div>";
    }
}

echo "</body></html>";
