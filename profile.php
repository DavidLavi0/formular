<?php
session_start();
require "db.php";

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION["user"];

// Načtení uživatele
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

$msg = "";
$error = "";

/* ======================
    ZMĚNA PROFILU
====================== */
if (isset($_POST["update_profile"])) {
    $new_username = trim($_POST["username"]);
    $email = trim($_POST["email"]);

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $new_username)) {
        $error = "Username může obsahovat jen písmena, čísla a _.";
    } else {
        $check = $pdo->prepare("SELECT * FROM users WHERE username=? AND id != ?");
        $check->execute([$new_username, $user["id"]]);

        if ($check->rowCount() > 0) {
            $error = "Tento username už existuje.";
        } else {
            $update = $pdo->prepare("UPDATE users SET username=?, email=? WHERE id=?");
            $update->execute([$new_username, $email, $user["id"]]);

            $_SESSION["user"] = $new_username;
            $msg = "Profil byl aktualizován.";
        }
    }
}

/* ======================
    ZMĚNA HESLA
====================== */
if (isset($_POST["update_password"])) {
    $old = $_POST["old_password"];
    $new = $_POST["new_password"];

    if (!password_verify($old, $user["password"])) {
        $error = "Staré heslo není správné.";
    } elseif (strlen($new) < 6) {
        $error = "Nové heslo musí mít alespoň 6 znaků.";
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $q = $pdo->prepare("UPDATE users SET password=? WHERE id=?");
        $q->execute([$hash, $user["id"]]);
        $msg = "Heslo bylo změněno.";
    }
}

/* ======================
    ZMĚNA AVATARU PŘES URL
====================== */
if (isset($_POST["update_avatar_url"])) {
    $url = trim($_POST["avatar_url"]);

    if (filter_var($url, FILTER_VALIDATE_URL)) {
        $q = $pdo->prepare("UPDATE users SET avatar_url=? WHERE id=?");
        $q->execute([$url, $user["id"]]);
        $msg = "Avatar byl změněn.";
    } else {
        $error = "Neplatná URL adresa.";
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Můj profil</title>
    <link rel="stylesheet" href="style/profile.css">
</head>
<body>
<main>
<section>
<div class="form">
    <div class="form-header">
        <img src="images/apexlogo.png" alt="">
        <h2>Profile Settings</h2>
        <?php if ($msg): ?>
            <p class="msg-success"><?= $msg ?></p>
        <?php endif; ?>
    </div>

    <?php if ($error): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <!-- AVATAR -->
    <form method="POST">
        <div style="text-align:center;margin-bottom:20px;">
            <?php 
            $avatar = !empty($user["avatar_url"]) ? $user["avatar_url"] : "images/default.png";
            ?>
            <img src="<?= htmlspecialchars($avatar) ?>?v=<?= time() ?>" 
                 style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:2px solid #444;">
        </div>

        <label>Avatar URL</label>
        <input type="url" name="avatar_url" placeholder="https://example.com/avatar.png" required>

        <button type="submit" name="update_avatar_url">Change Avatar</button>
    </form>

    <hr style="margin:20px 0; border-color:#444;">

    <!-- PROFIL (username, email) -->
    <form method="POST">
        <div class="form-main">

            <div class="form-main-inputs">
                <label>Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user["username"]) ?>" required>
            </div>

            <div class="form-main-inputs">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user["email"]) ?>">
            </div>

        </div>

        <button type="submit" name="update_profile">Save Profile</button>
    </form>

    <hr style="margin:20px 0; border-color:#444;">

    <!-- ZMĚNA HESLA -->
    <form method="POST">
        <div class="form-main">

            <div class="form-main-inputs">
                <label>Old Password</label>
                <input type="password" name="old_password" required>
            </div>

            <div class="form-main-inputs">
                <label>New Password</label>
                <input type="password" name="new_password" required minlength="6">
            </div>

        </div>
        <button type="submit" name="update_password">Change Password</button>
    </form>

    <!-- FOOTER -->
<div class="form-footer">
    <div class="footer-links">
        <a href="index.php" class="back-btn">⬅ Back to Dashboard</a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>


</div>
</section>
</main>
</body>
</html>
