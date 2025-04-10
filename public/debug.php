<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/helpers.php';

$db = Core\Database::getInstance();

// Vérifier les annonces
$stmt = $db->query("SELECT * FROM annonces LIMIT 1");
$annonce = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Annonce exemple :\n";
print_r($annonce);

// Vérifier les images
$stmt = $db->query("SELECT * FROM images");
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "\nImages :\n";
print_r($images);
