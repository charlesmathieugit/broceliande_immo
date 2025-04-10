<?php
include 'inc/database.php'; 
session_start();

$stmt = $pdo->prepare("SELECT * FROM annonces WHERE category = 'location'");
$stmt->execute();
$annonces = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annonces de Location - Brocéliande Immo</title>
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
                    <a class="nav-link active" href="index.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="vente.php">Vente</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="location.php">Location</a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="manage.php">Gérer mes annonces</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Déconnexion</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register">Inscription</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>


    <div class="container mt-4">
        <h1>Annonces de Location</h1>
        <?php if (empty($annonces)): ?>
            <div class="alert alert-info">Aucune annonce de location disponible actuellement.</div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($annonces as $annonce): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="<?= htmlspecialchars($annonce['image']) ?>" class="card-img-top" alt="Image de l'annonce">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($annonce['title']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($annonce['description']) ?></p>
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <a href="contact.php?id=<?= $annonce['id'] ?>" class="btn btn-primary">Contacter le Loueur</a>
                                <?php else: ?>
                                    <p><a href="login.php">Connectez-vous</a> ou <a href="register">inscrivez-vous</a> pour contacter le loueur.</p>
                                <?php endif; ?>
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

