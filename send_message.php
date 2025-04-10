<?php
include 'inc/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['annonce_id'])) {
        die("Erreur: L'ID de l'annonce est requis.");
    }

    $annonce_id = $_POST['annonce_id'];
    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($_POST['message']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("L'email fourni n'est pas valide.");
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO messages (annonce_id, name, email, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$annonce_id, $name, $email, $message]);
        echo "Message envoyé avec succès!";
    } catch (PDOException $e) {
        die("Erreur lors de l'envoi du message : " . $e->getMessage());
    }
} else {
    echo "Méthode HTTP non autorisée.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Envoyer un Message</title>
</head>
<body>
    <form method="POST" action="">
        <input type="hidden" name="annonce_id" value="0"> 
        <label for="name">Nom:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="message">Message:</label>
        <textarea id="message" name="message" required></textarea>
        <br>
        <input type="submit" value="Envoyer">
    </form>
</body>
</html>


    