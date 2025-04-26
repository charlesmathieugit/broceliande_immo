<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/helpers.php';

$db = Core\Database::getInstance();

// Afficher toutes les annonces avec leurs informations d'image
$stmt = $db->query("SELECT id, title, image_path FROM annonces");
$annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h1>Diagnostic des chemins d'images des annonces</h1>";
echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
echo "<tr>";
echo "<th>ID</th>";
echo "<th>Titre</th>";
echo "<th>Chemin d'image</th>";
echo "<th>Chemin complet via url()</th>";
echo "<th>Image</th>";
echo "</tr>";

foreach ($annonces as $annonce) {
    echo "<tr>";
    echo "<td>{$annonce['id']}</td>";
    echo "<td>{$annonce['title']}</td>";
    echo "<td>" . ($annonce['image_path'] ? $annonce['image_path'] : "<span style='color:red'>VIDE</span>") . "</td>";
    
    if ($annonce['image_path']) {
        $fullPath = url($annonce['image_path']);
        echo "<td>{$fullPath}</td>";
        echo "<td><img src=\"{$fullPath}\" alt=\"Image de l'annonce\" style=\"max-width:200px; max-height:150px;\"></td>";
    } else {
        $defaultImagePath = url('public/img/default-property.jpg');
        echo "<td>{$defaultImagePath} (image par défaut)</td>";
        echo "<td><img src=\"{$defaultImagePath}\" alt=\"Image par défaut\" style=\"max-width:200px; max-height:150px;\"></td>";
    }
    
    echo "</tr>";
}

echo "</table>";

// Vérifier l'existence des fichiers d'images par défaut
echo "<h2>Vérification des fichiers d'images par défaut</h2>";
$defaultImagePaths = [
    __DIR__ . '/img/default-property.jpg',
    __DIR__ . '/assets/img/default-property.jpg',
    __DIR__ . '/assets/images/no-image.jpg'
];

echo "<ul>";
foreach ($defaultImagePaths as $path) {
    $exists = file_exists($path);
    $status = $exists ? "<span style='color:green'>EXISTE</span>" : "<span style='color:red'>N'EXISTE PAS</span>";
    echo "<li>{$path}: {$status}</li>";
    
    if ($exists) {
        $webPath = str_replace(__DIR__, '', $path);
        echo "<img src=\"" . url('public' . $webPath) . "\" alt=\"Image\" style=\"max-width:200px; border:1px solid blue;\">&nbsp;";
    }
}
echo "</ul>";
