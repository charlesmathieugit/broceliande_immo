<?php require_once __DIR__ . '/../layouts/main.php'; ?>

<div class="container mt-5">
    <!-- Filtres de recherche -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="/vente" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="type_bien" class="form-label">Type de bien</label>
                    <select class="form-select" id="type_bien" name="type_bien">
                        <option value="">Tous les types</option>
                        <option value="appartement" <?= isset($_GET['type_bien']) && $_GET['type_bien'] == 'appartement' ? 'selected' : '' ?>>Appartement</option>
                        <option value="maison" <?= isset($_GET['type_bien']) && $_GET['type_bien'] == 'maison' ? 'selected' : '' ?>>Maison</option>
                        <option value="terrain" <?= isset($_GET['type_bien']) && $_GET['type_bien'] == 'terrain' ? 'selected' : '' ?>>Terrain</option>
                        <option value="commerce" <?= isset($_GET['type_bien']) && $_GET['type_bien'] == 'commerce' ? 'selected' : '' ?>>Commerce</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="price_min" class="form-label">Prix min</label>
                    <input type="number" class="form-control" id="price_min" name="price_min" value="<?= $_GET['price_min'] ?? '' ?>">
                </div>
                
                <div class="col-md-2">
                    <label for="price_max" class="form-label">Prix max</label>
                    <input type="number" class="form-control" id="price_max" name="price_max" value="<?= $_GET['price_max'] ?? '' ?>">
                </div>
                
                <div class="col-md-2">
                    <label for="surface_min" class="form-label">Surface min (m²)</label>
                    <input type="number" class="form-control" id="surface_min" name="surface_min" value="<?= $_GET['surface_min'] ?? '' ?>">
                </div>
                
                <div class="col-md-2">
                    <label for="pieces_min" class="form-label">Pièces min</label>
                    <input type="number" class="form-control" id="pieces_min" name="pieces_min" value="<?= $_GET['pieces_min'] ?? '' ?>">
                </div>
                
                <div class="col-md-3">
                    <label for="city" class="form-label">Ville</label>
                    <input type="text" class="form-control" id="city" name="city" value="<?= $_GET['city'] ?? '' ?>">
                </div>
                
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                    <a href="/vente" class="btn btn-secondary">Réinitialiser</a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Liste des annonces -->
    <div class="row">
        <?php if (empty($annonces)): ?>
            <div class="col-12">
                <div class="alert alert-info">
                    Aucune annonce ne correspond à vos critères de recherche.
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($annonces as $annonce): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php 
                        $images = explode(',', $annonce['images']);
                        if (!empty($images[0])): 
                        ?>
                            <img src="<?= htmlspecialchars($images[0]) ?>" class="card-img-top" alt="<?= htmlspecialchars($annonce['title']) ?>">
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($annonce['title']) ?></h5>
                            <p class="card-text">
                                <strong><?= number_format($annonce['price'], 0, ',', ' ') ?> €</strong><br>
                                <?= htmlspecialchars($annonce['city']) ?><br>
                                <?= $annonce['surface'] ?> m² - <?= $annonce['pieces'] ?> pièces
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="/annonce/<?= $annonce['id'] ?>" class="btn btn-primary">Voir détails</a>
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <button class="btn btn-outline-danger btn-sm toggle-favorite" data-annonce-id="<?= $annonce['id'] ?>">
                                        <i class="bi bi-heart"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
document.querySelectorAll('.toggle-favorite').forEach(button => {
    button.addEventListener('click', function() {
        const annonceId = this.dataset.annonceId;
        fetch('/favoris/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ annonce_id: annonceId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const icon = this.querySelector('i');
                icon.classList.toggle('bi-heart');
                icon.classList.toggle('bi-heart-fill');
            }
        });
    });
});
</script>