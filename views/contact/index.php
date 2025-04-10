<?php require_once __DIR__ . '/../layouts/main.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Contactez-nous</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="/contact/send" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Votre nom</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Votre email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">Votre message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Envoyer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4>Nos coordonnées</h4>
                    <p><i class="bi bi-geo-alt"></i> 123 Rue de Brocéliande, 35000 Rennes</p>
                    <p><i class="bi bi-telephone"></i> 02 99 XX XX XX</p>
                    <p><i class="bi bi-envelope"></i> contact@broceliande-immo.fr</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4>Horaires d'ouverture</h4>
                    <p>Lundi - Vendredi : 9h00 - 18h00</p>
                    <p>Samedi : 9h00 - 12h00</p>
                    <p>Dimanche : Fermé</p>
                </div>
            </div>
        </div>
    </div>
</div>