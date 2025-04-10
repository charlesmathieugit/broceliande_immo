<?php require_once __DIR__ . '/../layouts/main.php'; ?>

<div class="container mt-5">
    <div class="row">
        <!-- Galerie d'images -->
        <div class="col-md-8">
            <div id="carouselAnnonce" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php 
                    $images = explode(',', $annonce['images']);
                    foreach ($images as $index => $image): 
                    ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <img src="<?= htmlspecialchars($image) ?>" class="d-block w-100" alt="Image <?= $index + 1 ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($images) > 1): ?>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselAnnonce" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Précédent</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselAnnonce" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Suivant</span>
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Informations principales -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h2><?= htmlspecialchars($annonce['title']) ?></h2>
                    <h3 class="text-primary">
                        <?= number_format($annonce['price'], 0, ',', ' ') ?> €
                        <?= $annonce['category'] === 'location' ? '/ mois' : '' ?>
                    </h3>
                    <?php if ($annonce['category'] === 'location' && $annonce['charges'] > 0): ?>
                        <p>Charges : <?= number_format($annonce['charges'], 0, ',', ' ') ?> € / mois</p>
                    <?php endif; ?>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <p><i class="bi bi-house-door"></i> <?= ucfirst($annonce['type_bien']) ?></p>
                        <p><i class="bi bi-rulers"></i> <?= $annonce['surface'] ?> m²</p>
                        <p><i class="bi bi-door-open"></i> <?= $annonce['pieces'] ?> pièces</p>
                        <p><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($annonce['address']) ?></p>
                        <p><?= htmlspecialchars($annonce['postal_code']) ?> <?= htmlspecialchars($annonce['city']) ?></p>
                    </div>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <button class="btn btn-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#modalRdv">
                            Prendre rendez-vous
                        </button>
                        <button class="btn btn-outline-danger w-100 toggle-favorite" data-annonce-id="<?= $annonce['id'] ?>">
                            <i class="bi bi-heart"></i> Ajouter aux favoris
                        </button>
                    <?php else: ?>
                        <a href="/login" class="btn btn-primary w-100">Connectez-vous pour prendre rendez-vous</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Description et caractéristiques -->
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4>Description</h4>
                    <p><?= nl2br(htmlspecialchars($annonce['description'])) ?></p>
                    
                    <?php if ($annonce['features']): ?>
                        <h4 class="mt-4">Caractéristiques</h4>
                        <ul class="list-unstyled row">
                            <?php foreach (json_decode($annonce['features'], true) as $feature): ?>
                                <li class="col-md-6"><i class="bi bi-check2"></i> <?= htmlspecialchars($feature) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    
                    <?php if ($annonce['dpe_rating'] || $annonce['ges_rating']): ?>
                        <h4 class="mt-4">Diagnostic énergétique</h4>
                        <div class="row">
                            <?php if ($annonce['dpe_rating']): ?>
                                <div class="col-md-6">
                                    <p>DPE : <?= $annonce['dpe_rating'] ?></p>
                                </div>
                            <?php endif; ?>
                            <?php if ($annonce['ges_rating']): ?>
                                <div class="col-md-6">
                                    <p>GES : <?= $annonce['ges_rating'] ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Rendez-vous -->
<?php if (isset($_SESSION['user_id'])): ?>
    <div class="modal fade" id="modalRdv" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Prendre rendez-vous</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="/rendez-vous/create" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="annonce_id" value="<?= $annonce['id'] ?>">
                        
                        <div class="mb-3">
                            <label for="date_rendez_vous" class="form-label">Date et heure souhaitées</label>
                            <input type="datetime-local" class="form-control" id="date_rendez_vous" name="date_rendez_vous" required
                                   min="<?= date('Y-m-d\TH:i') ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="type_visite" class="form-label">Type de visite</label>
                            <select class="form-select" id="type_visite" name="type_visite" required>
                                <option value="presentiel">Présentiel</option>
                                <option value="virtuel">Virtuel</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="commentaire" class="form-label">Commentaire (optionnel)</label>
                            <textarea class="form-control" id="commentaire" name="commentaire" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Confirmer le rendez-vous</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>