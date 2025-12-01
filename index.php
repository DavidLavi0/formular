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
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                <label>Username</label>
                            </div>
                            <input name="username" type="text" placeholder="Username (alphanumeric only)" required>
                        </div>

                        <!-- EMAIL -->
                        <div class="form-main-inputs">
                            <div class="form-main-inputs-label">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zm-9 7.5h9a4.5 4.5 0 014.5 4.5H3a4.5 4.5 0 014.5-4.5z" />
                                </svg>
                                <label>Email</label>
                            </div>
                            <input name="email" type="email" placeholder="Your email" required>
                        </div>

                        <!-- PASSWORD -->
                        <div class="form-main-inputs">
                            <div class="form-main-inputs-label">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                                <label>Password</label>
                            </div>
                            <input name="password" type="password" placeholder="Enter your password (min 6 characters)" required minlength="6">
                        </div>

                        <!-- CONFIRM PASSWORD -->
                        <div class="form-main-inputs">
                            <div class="form-main-inputs-label">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                                <label>Confirm Password</label>
                            </div>
                            <input name="password_confirm" type="password" placeholder="Confirm your password" required>
                        </div>

                    </div>

                    <div class="form-buttons">
                        <button type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                            </svg>
                            Create Account
                        </button>
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
