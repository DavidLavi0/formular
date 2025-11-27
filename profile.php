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

// ZMĚNA NICKU A EMAILU
if (isset($_POST["update_profile"])) {
    $new_username = trim($_POST["username"]);
    $email = trim($_POST["email"]);

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $new_username)) {
        $error = "Username může obsahovat jen písmena, čísla a _.";
    } else {
        // Zkontrolovat jestli nick není obsazený
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

// ZMĚNA HESLA
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

// ZMĚNA AVATARU
if (isset($_POST["update_avatar"]) && isset($_FILES["avatar"])) {

    if ($_FILES["avatar"]["error"] === 0) {

        $ext = pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION);
        $allowed = ["jpg", "jpeg", "png", "gif"];

        if (!in_array(strtolower($ext), $allowed)) {
            $error = "Avatar musí být obrázek.";
        } else {
            $filename = "avatar_" . $user["id"] . "." . $ext;
            $path = "uploads/avatars/" . $filename;

            move_uploaded_file($_FILES["avatar"]["tmp_name"], $path);

            $q = $pdo->prepare("UPDATE users SET avatar=? WHERE id=?");
            $q->execute([$filename, $user["id"]]);

            $msg = "Avatar byl změněn.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Můj profil</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
<main>
<section>
<div class="form">
    <div class="form-header">
        <img src="images/apexlogo.png" alt="">
        <h2>Profile Settings</h2>
    </div>

    <?php if ($msg): ?>
        <p style="color:lightgreen;"><?= $msg ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <!-- AVATAR -->
    <form method="POST" enctype="multipart/form-data">
        <div style="text-align:center;margin-bottom:20px;">
            <img src="uploads/avatars/<?= $user["avatar"] ?? 'default.png' ?>" 
                 style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:2px solid #444;">
        </div>

        <input type="file" name="avatar" required>
        <button type="submit" name="update_avatar">Change Avatar</button>
    </form>

    <hr style="margin:20px 0; border-color:#444;">

    <!-- PROFIL (username, email) -->
    <form method="POST">
        <div class="form-main">

            <div class="form-main-inputs">
                <div class="form-main-inputs-label">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="21">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                              d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.118a7.5 7.5 0 0115 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.5-1.632z" />
                    </svg>
                    <label>Username</label>
                </div>
                <input type="text" name="username" value="<?= htmlspecialchars($user["username"]) ?>" required>
            </div>

            <div class="form-main-inputs">
                <div class="form-main-inputs-label">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="21">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                              d="M16.5 12a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zm-9 7.5h9a4.5 4.5 0 014.5 4.5H3a4.5 4.5 0 014.5-4.5z" />
                    </svg>
                    <label>Email</label>
                </div>
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

    <div class="form-footer">
        <a href="logout.php">Logout</a>
    </div>

</div>
</section>
</main>
</body>
</html>
