<?php require_once __DIR__ . '/../layouts/main.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Ajouter une nouvelle annonce</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="/annonces/create" method="POST">
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="category" class="form-label">Catégorie</label>
                            <select class="form-control" id="category" name="category" required>
                                <option value="vente">Vente</option>
                                <option value="location">Location</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="pieces" class="form-label">Nombre de pièces</label>
                            <input type="number" class="form-control" id="pieces" name="pieces" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="surface" class="form-label">Surface (m²)</label>
                            <input type="number" class="form-control" id="surface" name="surface" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="image_url" class="form-label">URL de l'image</label>
                            <input type="url" class="form-control" id="image_url" name="image_url" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Créer l'annonce</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>