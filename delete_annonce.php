<?php
include 'inc/DATABASE.sql';

$id = $_GET['id'] ?? null;
if (!$id) {
    exit('Annonce non spécifiée');
}

$stmt = $pdo->prepare("DELETE FROM annonces WHERE id = ?");
$stmt->execute([$id]);

header("Location: manage.php");  
exit;
?>
<?php
include 'inc/database.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    exit('Annonce non spécifiée');
}

$stmt = $pdo->prepare("DELETE FROM annonces WHERE id = ?");
$stmt->execute([$id]);

header("Location: manage.php"); 
exit;
?>
