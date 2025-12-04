<?php
session_start();
require "db.php"; // Připojení k databázi

$error = ""; // Proměnná pro chybové hlášení

// Zpracování formuláře při odeslání
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Získání hodnot z formuláře
    $login = $_POST["login"];  // login = buď uživatelské jméno nebo email
    $password = $_POST["password"];

    // SQL dotaz pro ověření uživatele podle uživatelského jména nebo emailu
    $q = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $q->execute([$login, $login]);  // Prohledáme jak uživatelské jméno, tak email
    $user = $q->fetch(); // Vyhledáme odpovídajícího uživatele

    // Kontrola, jestli uživatel existuje a heslo je správné
    if (!$user || !password_verify($password, $user["password"])) {
        $error = "Špatné přihlašovací údaje.";  // Chybová zpráva
    } else {
        // Pokud přihlášení proběhne úspěšně, uložíme uživatelské jméno do session
        $_SESSION["user"] = $user["username"];
        header("Location: profile.php");  // Přesměrování na hlavní stránku
        exit;  // Zastavení dalšího vykonávání skriptu
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Přihlášení</title>
    <link rel="stylesheet" href="style/login.css">
</head>
<body>

<main>
    <section>
        <div class="form">
            <div class="form-header">
                <img src="images/apexlogo.png" alt="LogoApex">
                <h2>Sign in</h2>
            </div>

            <!-- Zobrazí chybovou zprávu, pokud nastane problém s přihlášením -->
            <?php if ($error): ?>
                <p class="msg-error"><?= $error ?></p>
            <?php endif; ?>

            <!-- Přihlašovací formulář -->
            <form method="POST">
                <div class="form-main">

                    <!-- Login (uživatelské jméno nebo email) -->
                    <div class="form-main-inputs">
                        <div class="form-main-inputs-label">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <label>Username or Email</label>
                        </div>
                        <input name="login" type="text" placeholder="Enter your username or email" required>
                    </div>

                    <!-- Heslo -->
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

                <!-- Tlačítko pro odeslání formuláře -->
                <div class="form-buttons">
                    <button type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0-6-6m6 6-6 6" />
                        </svg>
                        Sign in
                    </button>
                </div>
            </form>

            <!-- Odkaz na stránku pro registraci -->
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
