<?php
include 'inc/database.php'; 
session_start();

$stmt = $pdo->prepare("SELECT * FROM annonces WHERE category = 'vente'");
$stmt->execute();
$annonces = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annonces de Vente - Brocéliande Immo</title>
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
                        <a class="nav-link active" href="vente.php">Vente</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="location.php">Location</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="manage.php">Mes annonces</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Déconnexion</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="">Inscription</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Annonces de Vente</h1>
        <?php if (empty($annonces)): ?>
            <div class="alert alert-info">Aucune annonce de vente disponible actuellement.</div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($annonces as $annonce): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="<?= htmlspecialchars($annonce['image']) ?>" class="card-img-top" alt="Image de l'annonce">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($annonce['title']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($annonce['description']) ?></p>
                                <a href="contact.php?id=<?= $annonce['id'] ?>" class="btn btn-primary">Contacter le Vendeur</a>
                                <form action="add_rendez_vous" method="POST" class="mt-2">
                                    <div class="form-group">
                                        <label for="rendez_vousDate-<?= $annonce['id'] ?>">Date de rendez-vous :</label>
                                        <input type="date" id="rendez_vousDate-<?= $annonce['id'] ?>" name="rendez_vousDate" required class="form-control">
                                    </div>
                                    <input type="hidden" name="propertyId" value="<?= $annonce['id'] ?>">
                                    <button type="submit" class="btn btn-success mt-2">Prendre rendez-vous</button>
                                </form>

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

