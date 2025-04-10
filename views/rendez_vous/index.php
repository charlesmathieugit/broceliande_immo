<?php require_once __DIR__ . '/../layouts/main.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Mes rendez-vous</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (empty($rendezVous)): ?>
                        <p class="text-center">Vous n'avez aucun rendez-vous programmé.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Annonce</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rendezVous as $rdv): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($rdv['annonce_title']) ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($rdv['date_rendez_vous'])) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $rdv['statut'] === 'en_attente' ? 'warning' : 
                                                    ($rdv['statut'] === 'confirme' ? 'success' : 'danger') ?>">
                                                    <?= ucfirst($rdv['statut']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($rdv['statut'] === 'en_attente'): ?>
                                                    <form action="/rendez-vous/cancel/<?= $rdv['id'] ?>" method="POST" class="d-inline">
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                                onclick="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')">
                                                            Annuler
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>