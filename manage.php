<?php
session_start();
include 'inc/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['delete'])) {
    $annonce_id = $_GET['delete'];
    $deleteStmt = $pdo->prepare("DELETE FROM annonces WHERE id = ? AND user_id = ?");
    $deleteStmt->execute([$annonce_id, $user_id]);
    header("Location: manage.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM annonces WHERE user_id = ?");
$stmt->execute([$user_id]);
$annonces = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer mes annonces - Brocéliande Immo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Brocéliande Immo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="vente.php">Vente</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="location.php">Location</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="manage.php">Mes annonces</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Gérer mes annonces</h1>
        <a href="add_annonce.php" class="btn btn-success mb-3">Ajouter une nouvelle annonce</a>
        <?php if (empty($annonces)): ?>
            <div class="alert alert-info">Vous n'avez pas encore posté d'annonces.</div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($annonces as $annonce): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="<?= htmlspecialchars($annonce['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($annonce['title']) ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($annonce['title']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($annonce['description']) ?></p>
                                <a href="edit_annonce.php?id=<?= $annonce['id'] ?>" class="btn btn-primary">Modifier</a>
                                <a href="?delete=<?= $annonce['id'] ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?');">Supprimer</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
