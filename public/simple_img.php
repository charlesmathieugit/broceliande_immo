<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/helpers.php';

$db = Core\Database::getInstance();

// Dossier où l'utilisateur placera manuellement les images
$imageFolder = 'annonces/images';
$fullImagePath = __DIR__ . '/' . $imageFolder;

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Système d'images simple</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        h1, h2 { color: #2c3e50; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 5px; overflow: auto; }
        .success { background: #d4edda; color: #155724; padding: 15px; margin: 20px 0; border-radius: 5px; }
        .warning { background: #fff3cd; color: #856404; padding: 15px; margin: 20px 0; border-radius: 5px; }
        .btn { display: inline-block; padding: 10px 15px; background: #3498db; color: white; text-decoration: none; border-radius: 3px; margin-right: 10px; }
    </style>
</head>
<body>
    <h1>Système d'images simple</h1>";

// Vérifier que le dossier existe
if (!is_dir($fullImagePath)) {
    echo "<div class='warning'>
        <h3>Attention</h3>
        <p>Le dossier pour les images n'existe pas encore. Nous allons le créer pour vous.</p>
    </div>";
    
    mkdir($fullImagePath, 0777, true);
}

// Mettre à jour toutes les annonces pour utiliser ce dossier
echo "<h2>Comment utiliser ce système :</h2>
<ol>
    <li>Placez vos images dans le dossier : <pre>public/annonces/images/</pre></li>
    <li>Nommez vos images selon l'ID de l'annonce : <pre>1.jpg, 2.jpg, 3.jpg, etc.</pre></li>
    <li>Pour les images par défaut, utilisez : <pre>default_maison.jpg, default_appartement.jpg, etc.</pre></li>
</ol>";

// Mettre à jour les annonces
$stmt = $db->query("SELECT id, title, type_bien FROM annonces");
$annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);
$updateCount = 0;

foreach ($annonces as $annonce) {
    // Chemin d'image standardisé : annonces/images/ID.jpg
    $newImagePath = $imageFolder . '/' . $annonce['id'] . '.jpg';
    
    $stmt = $db->prepare("UPDATE annonces SET image_path = ? WHERE id = ?");
    if ($stmt->execute([$newImagePath, $annonce['id']])) {
        $updateCount++;
    }
}

echo "<div class='success'>
    <h3>Mise à jour effectuée</h3>
    <p>{$updateCount} annonces ont été mises à jour.</p>
    <p>Chaque annonce utilisera maintenant l'image : <pre>annonces/images/ID.jpg</pre></p>
</div>";

// Images par défaut selon le type
echo "<h2>Images par défaut pour chaque type de bien</h2>
<p>Si vous n'avez pas encore d'image pour une annonce spécifique, créez ces images par défaut :</p>
<ul>";

$typesBien = ['maison', 'appartement', 'terrain', 'commerce', 'studio', 'villa', 'immeuble'];
foreach ($typesBien as $type) {
    echo "<li><pre>annonces/images/default_{$type}.jpg</pre></li>";
}

echo "</ul>";

// Créer un exemple de code HTML pour expliquer le fonctionnement
echo "<h2>Code dans les templates</h2>
<p>Dans vos fichiers .twig, les images sont maintenant affichées simplement :</p>
<pre>
&lt;img src=\"{{ url(annonce.image_path) }}\" alt=\"{{ annonce.title }}\" style=\"height: 200px; object-fit: cover;\"&gt;
</pre>

<p>Pour gérer les cas où l'image n'existe pas encore, modifiez vos templates comme ceci :</p>
<pre>
{% set imagePath = annonce.image_path %}
{% set imageUrl = url(imagePath) %}
{% set defaultImageUrl = url('annonces/images/default_' ~ annonce.type_bien|lower ~ '.jpg') %}

&lt;img src=\"{{ imageUrl }}\" 
     alt=\"{{ annonce.title }}\"
     style=\"height: 200px; object-fit: cover;\"
     onerror=\"this.onerror=null; this.src='{{ defaultImageUrl }}';\"&gt;
</pre>";

echo "<p><a href='/' class='btn'>Retour à l'accueil</a></p>";
echo "</body></html>";
