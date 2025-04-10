<?php require_once __DIR__ . '/../layouts/main.php'; ?>

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Messages de contact</h3>
        </div>
        <div class="card-body">
            <?php if (empty($messages)): ?>
                <p class="text-center">Aucun message reçu.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Message</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $message): ?>
                                <tr class="<?= $message['is_read'] ? '' : 'table-warning' ?>">
                                    <td><?= date('d/m/Y H:i', strtotime($message['created_at'])) ?></td>
                                    <td><?= htmlspecialchars($message['name']) ?></td>
                                    <td><?= htmlspecialchars($message['email']) ?></td>
                                    <td><?= nl2br(htmlspecialchars($message['message'])) ?></td>
                                    <td>
                                        <?php if (!$message['is_read']): ?>
                                            <form action="/contact/mark-as-read/<?= $message['id'] ?>" method="POST">
                                                <button type="submit" class="btn btn-sm btn-success">Marquer comme lu</button>
                                            </form>
                                        <?php else: ?>
                                            <span class="badge bg-success">Lu</span>
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