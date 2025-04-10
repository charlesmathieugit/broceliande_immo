<?php
session_start();

if (isset($_SESSION['login_success'])) {
    echo '<div class="alert alert-success" role="alert">' . $_SESSION['login_success'] . '</div>';
    unset($_SESSION['login_success']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brocéliande Immo - Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .card-img-top {
            width: 100%;
            height: 250px; /* Hauteur fixe pour toutes les images */
            object-fit: cover; /* Pour garder les proportions tout en remplissant l'espace */
        }
    </style>
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
                        <li class="nav-item">
                            <a class="nav-link" href="rendez_vous.php">Prendre un rendez_vous</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <header class="py-5 bg-light border-bottom mb-4">
        <div class="container">
            <div class="text-center">
                <h1 class="fw-bolder">Bienvenue chez Brocéliande Immo</h1>
                <p class="lead">Découvrez nos services et offres exclusives sur les biens en vente et en location.</p>
            </div>
        </div>
    </header>

    <div class="container">
    <div class="row">
        <div class="col-md-4 mb-5">
            <div class="card h-100">
                <img src="public\img\maison_moderne.jpg" class="card-img-top" alt="Maison moderne">
                <div class="card-body">
                    <h5 class="card-title">Propriétés Modernes</h5>
                    <p class="card-text">Explorez notre collection de propriétés modernes à la vente.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-5">
            <div class="card h-100">
                <img src="public/img/location_cosy.jpg" class="card-img-top" alt="Appartement cosy">
                <div class="card-body">
                    <h5 class="card-title">Locations Cosy</h5>
                    <p class="card-text">Découvrez nos appartements les plus confortables pour une location parfaite.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-5">
            <div class="card h-100">
                <img src="public/img/consultation.jpg" class="card-img-top" alt="Consultation">
                <div class="card-body">
                    <h5 class="card-title">Consultation Gratuite</h5>
                    <p class="card-text">Profitez d'une consultation gratuite pour trouver votre maison de rêve.</p>
                </div>
            </div>
        </div>
    </div>
</div>

    <footer class="py-3 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white"> Brocéliande Immo - Tous droits réservés</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
