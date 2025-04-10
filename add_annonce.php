<?php
session_start();
include 'inc/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $pieces = $_POST['pieces'];
    $surface = $_POST['surface'];
    $description = $_POST['description'];
    $image = $_POST['image_url']; 


    $stmt = $pdo->prepare("INSERT INTO annonces (user_id, title, category, image, pieces, surface, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $title, $category, $image, $pieces, $surface, $description]);

    header("Location: manage.php");  
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une annonce - Brocéliande Immo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Ajouter une nouvelle annonce</h2>
        <form action="add_annonce.php" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Titre:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Catégorie:</label>
                <select class="form-control" id="category" name="category" required>
                    <option value="vente">Vente</option>
                    <option value="location">Location</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="image_url" class="form-label">URL de l'image:</label>
                <input type="text" class="form-control" id="image_url" name="image_url">
            </div>
            <div class="mb-3">
                <label for="pieces" class="form-label">Nombre de pièces:</label>
                <input type="number" class="form-control" id="pieces" name="pieces" required>
            </div>
            <div class="mb-3">
                <label for="surface" class="form-label">Surface (en m²):</label>
                <input type="number" class="form-control" id="surface" name="surface" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>
</body>
</html>

