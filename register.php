<?php
session_start();
require "db.php";

$error = "";

// Kontrola odeslání formuláře
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $password_confirm = $_POST["password_confirm"];

    // Validace hesel
    if ($password !== $password_confirm) {
        $error = "Hesla se neshodují.";
    } else {
        // Kontrola, zda uživatel nebo email už existuje
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        $existing = $stmt->fetch();

        if ($existing) {
            $error = "Uživatel nebo email již existuje.";
        } else {
            // Hash hesla
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Vložení do databáze
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashed_password])) {
                // Přesměrování na login po úspěšné registraci
                header("Location: login.php");
                exit;
            } else {
                $error = "Chyba při registraci, zkuste to znovu.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrace</title>
    <link rel="stylesheet" href="style/profile.css">
</head>
<body>
    <main>
        <section>
            <div class="form">

                <div class="form-header">
                    <img src="images/apexlogo.png" alt="LogoApex">
                    <h2>Create an account</h2>
                </div>

                <?php if ($error): ?>
                    <p style="color:red; margin-bottom:10px;"><?= $error ?></p>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-main">

                        <!-- USERNAME -->
                        <div class="form-main-inputs">
                            <div class="form-main-inputs-label">
                                <label>Username</label>
                            </div>
                            <input name="username" type="text" placeholder="Enter your username" required>
                        </div>

                        <!-- EMAIL -->
                        <div class="form-main-inputs">
                            <div class="form-main-inputs-label">
                                <label>Email</label>
                            </div>
                            <input name="email" type="email" placeholder="Enter your email" required>
                        </div>

                        <!-- PASSWORD -->
                        <div class="form-main-inputs">
                            <div class="form-main-inputs-label">
                                <label>Password</label>
                            </div>
                            <input name="password" type="password" placeholder="Enter your password" required minlength="6">
                        </div>

                        <!-- CONFIRM PASSWORD -->
                        <div class="form-main-inputs">
                            <div class="form-main-inputs-label">
                                <label>Confirm Password</label>
                            </div>
                            <input name="password_confirm" type="password" placeholder="Confirm your password" required>
                        </div>

                    </div>

                    <div class="form-buttons">
                        <button type="submit">Create Account</button>
                    </div>       
                </form>

                <div class="form-footer">
                    <a href="login.php">
                        <span>Already have an account?</span>
                        Sign in
                    </a>
                </div> 
            </div>
        </section>
    </main>

</body>
</html>
