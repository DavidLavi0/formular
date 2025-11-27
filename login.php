<?php
session_start();
require "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $q = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $q->execute([$username]);
    $user = $q->fetch();

    if (!$user || !password_verify($password, $user["password"])) {
        $error = "Špatné přihlašovací údaje.";
    } else {
        $_SESSION["user"] = $user["username"];
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Přihlášení</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>

<main>
    <section>
        <div class="form">
            <div class="form-header">
                <img src="images/apexlogo.png" alt="LogoApex">
                <h2>Sign in</h2>
            </div>

            <?php if ($error): ?>
                <p style="color:red;"><?= $error ?></p>
            <?php endif; ?>

            <form method="POST">
                <div class="form-main">

                    <!-- USERNAME -->
                    <div class="form-main-inputs">
                        <div class="form-main-inputs-label">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <label>Username</label>
                        </div>
                        <input name="username" type="text" placeholder="Enter your username" required>
                    </div>

                    <!-- PASSWORD -->
                    <div class="form-main-inputs">
                        <div class="form-main-inputs-label">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            <label>Password</label>
                        </div>
                        <input name="password" type="password" placeholder="Enter your password" required>
                    </div>

                </div>

                <div class="form-buttons">
                    <button type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0-6-6m6 6-6 6" />
                        </svg>
                        Sign in
                    </button>
                </div>
            </form>

            <div class="form-footer">
                <a href="register.php">
                    <span>Nemáš účet?</span>
                    Create account
                </a>
            </div>
        </div>
    </section>
</main>

</body>
</html>
