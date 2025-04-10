<?php


$query = $pdo->query("SELECT * FROM broceliande_immo");
$annonces = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page pour prendre un Rendez-vous</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Prendre un Rendez-vous</h1>
        <div class="row">
            <?php foreach ($annonces as $annonce): ?>
                <div class="col-md-4 mb-4">
                    <div class=>
                        <div class=>
                            <h5 class=><?= htmlspecialchars($annonce['name']) ?></h5>
                            <form action="treatment_rendez_vous.php" method="POST" class="mt-2">
                                <div class="form-group">
                                    <label for="rendezvousDate-<?= $annonce['id'] ?>">Date de rendez-vous :</label>
                                    <input type="date" id="rendez_vousDate-<?= $annonce['id'] ?>" name="rendez_vousDate" required class="form-control">
                                </div>
                                <input type="hidden" name="id" value="<?= $annonce['id'] ?>">
                                <button type="submit" class="btn btn-success">Prendre rendez-vous</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
