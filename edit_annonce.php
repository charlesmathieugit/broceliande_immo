<?php
session_start();
include 'inc/database.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$annonce_id = $_GET['id'] ?? null;
if (!$annonce_id) {
    header("Location: manage.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare("SELECT * FROM annonces WHERE id = ? AND user_id = ?");
    $stmt->execute([$annonce_id, $_SESSION['user_id']]);
    $annonce = $stmt->fetch();

    if (!$annonce) {
        header("Location: manage.php");
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $pieces = $_POST['pieces'];
    $surface = $_POST['surface'];
    $description = $_POST['description'];
    $image = $_POST['image_url']; 

    $stmt = $pdo->prepare("UPDATE annonces SET title = ?, category = ?, image = ?, pieces = ?, surface = ?, description = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$title, $category, $image, $pieces, $surface, $description, $annonce_id, $_SESSION['user_id']]);

    header("Location: manage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'annonce - Brocéliande Immo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Modifier une annonce</h2>
        <form action="edit_annonce.php?id=<?= htmlspecialchars($annonce_id) ?>" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Titre:</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($annonce['title']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Catégorie:</label>
                <select class="form-control" id="category" name="category" required>
                    <option value="vente" <?= $annonce['category'] == 'vente' ? 'selected' : '' ?>>Vente</option>
                    <option value="location" <?= $annonce['category'] == 'location' ? 'selected' : '' ?>>Location</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="image_url" class="form-label">URL de l'image:</label>
                <input type="text" class="form-control" id="image_url" name="image_url" value="<?= htmlspecialchars($annonce['image']) ?>">
            </div>
            <div class="mb-3">
                <label for="pieces" class="form-label">Nombre de pièces:</label>
                <input type="number" class="form-control" id="pieces" name="pieces" value="<?= htmlspecialchars($annonce['pieces']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="surface" class="form-label">Surface (en m²):</label>
                <input type="number" class="form-control" id="surface" name="surface" value="<?= htmlspecialchars($annonce['surface']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?= htmlspecialchars($annonce['description']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
