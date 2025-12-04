<?php
session_start();
require "db.php";

// Kontrola, zda je uživatel přihlášen
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$_SESSION["user"]]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style/index.css">
</head>
<body>
<main>
    <section>
        <div class="form">
            <div class="form-header">
                <h2>Welcome, <?= htmlspecialchars($user['username']) ?>!</h2>
            </div>

            <div class="profile-card">
                <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            </div>

            <a class="logout-btn" href="logout.php">Logout</a>
        </div>
    </section>
</main>
</body>
</html>
