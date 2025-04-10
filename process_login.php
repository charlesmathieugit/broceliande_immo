<?php
include 'inc/database.php'; 

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!$email || !$password) {
        echo "Veuillez remplir tous les champs.";
        exit;
    }

    $stmt = $pdo->prepare("SELECT id, email, password, role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        
        if ($user['role'] == 'loueur') {
            header("Location: location.php");
        } else {
            header("Location: vente.php");
        }
        exit;
    } else {
        echo "Identifiants incorrects ou l'utilisateur n'existe pas.";
    }
} else {
    echo "Méthode HTTP non autorisée.";
}
?>
