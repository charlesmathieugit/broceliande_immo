<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/helpers.php';

$db = Core\Database::getInstance();

// Collecter toutes les images disponibles dans le dossier des annonces
$imageDirectory = __DIR__ . '/uploads/annonces/';
$availableImages = [];

if (is_dir($imageDirectory)) {
    $files = scandir($imageDirectory);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..' && in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif'])) {
            $availableImages[] = 'public/uploads/annonces/' . $file;
        }
    }
}

echo "<h1>Mise à jour des chemins d'images pour toutes les annonces</h1>";

if (empty($availableImages)) {
    echo "<p style='color:red'>Aucune image n'a été trouvée dans le dossier {$imageDirectory}.</p>";
    echo "<p>Assurez-vous que ce dossier existe et contient des images.</p>";
    exit;
}

echo "<p>Images disponibles (". count($availableImages) .") :</p>";
echo "<div style='display:flex;flex-wrap:wrap;gap:10px;margin-bottom:20px'>";
foreach ($availableImages as $img) {
    echo "<div style='text-align:center'>";
    echo "<img src='" . url($img) . "' style='max-width:100px;max-height:100px;margin-bottom:5px'><br>";
    echo "<small>" . basename($img) . "</small>";
    echo "</div>";
}
echo "</div>";

// Mettre à jour TOUTES les annonces (pas seulement celles sans image)
$stmt = $db->query("SELECT id, title, type_bien FROM annonces");
$annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<p>Nombre total d'annonces: " . count($annonces) . "</p>";

$imageIndex = 0;
$updatedCount = 0;
foreach ($annonces as $annonce) {
    // Assigner une image différente à chaque annonce en rotation
    $imagePath = $availableImages[$imageIndex % count($availableImages)];
    $imageIndex++;
    
    // Mettre à jour l'annonce
    $updateStmt = $db->prepare("UPDATE annonces SET image_path = ? WHERE id = ?");
    if ($updateStmt->execute([$imagePath, $annonce['id']])) {
        $updatedCount++;
        echo "<p>✅ Mise à jour de l'annonce #{$annonce['id']} ({$annonce['title']}) avec l'image: {$imagePath}</p>";
        echo "<img src='" . url($imagePath) . "' style='max-width:100px;max-height:100px;margin:5px 0 15px 20px'><br>";
    } else {
        echo "<p>❌ Échec de la mise à jour pour l'annonce #{$annonce['id']}</p>";
    }
}

echo "<h2>Résumé</h2>";
echo "<p>{$updatedCount} annonces mises à jour avec succès.</p>";

echo "<p><a href='/' class='btn btn-primary'>Retour à l'accueil</a> <a href='/public/debug.php' class='btn btn-info'>Vérifier les images</a></p>";

echo "<style>
    body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; }
    h1, h2 { color: #2c3e50; }
    .btn { display: inline-block; padding: 10px 15px; background: #3498db; color: white; text-decoration: none; border-radius: 3px; margin-right: 10px; }
    .btn-info { background: #2ecc71; }
</style>";
