<?php require_once __DIR__ . '/../layouts/main.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Prendre un rendez-vous</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="row">
                        <?php foreach ($annonces as $annonce): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <?php if ($annonce['image']): ?>
                                        <img src="<?= htmlspecialchars($annonce['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($annonce['title']) ?>">
                                    <?php endif; ?>
                                    
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($annonce['title']) ?></h5>
                                        <p class="card-text">
                                            <?= htmlspecialchars($annonce['pieces']) ?> pièces - 
                                            <?= htmlspecialchars($annonce['surface']) ?> m²
                                        </p>
                                        
                                        <form action="/rendez-vous/create" method="POST">
                                            <input type="hidden" name="annonce_id" value="<?= $annonce['id'] ?>">
                                            
                                            <div class="mb-3">
                                                <label for="date_rendez_vous_<?= $annonce['id'] ?>" class="form-label">Date du rendez-vous</label>
                                                <input type="datetime-local" 
                                                       class="form-control" 
                                                       id="date_rendez_vous_<?= $annonce['id'] ?>" 
                                                       name="date_rendez_vous"
                                                       min="<?= date('Y-m-d\TH:i') ?>"
                                                       required>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-primary w-100">Prendre rendez-vous</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>