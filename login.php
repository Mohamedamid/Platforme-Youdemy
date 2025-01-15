<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/style/login.css?v=1">
    <title>Connexion - Plateforme d'Apprentissage</title>
    <style>

    </style>
</head>

<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="#" class="logo">Académie d'Apprentissage</a>
            <ul class="nav-links" id="navLinks">
                <li><a href="./home.php">Accueil</a></li>
            </ul>
        </div>
    </nav>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>Connexion</h1>
            </div>
            <form action="./php/verification.php" method="POST" class="login">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="Votre adresse email">
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="login-password" name="password" required
                        placeholder="Votre mot de passe">
                </div>
                <div class="remember-forgot">
                    <label class="remember-me">
                        <input type="checkbox" name="remember">
                        Se souvenir de moi
                    </label>
                    <a href="#" class="forgot-password">Mot de passe oublié?</a>
                </div>
                <button type="submit" class="login-btn" name="login">Se connecter</button>
                <div class="register-link">
                    Pas encore de compte? <a href="#" class="switch-to-register">S'inscrire</a>
                </div>
            </form>
            <form action="./php/verification.php" method="POST" class="register" style="display: none;">
                <div class="form-group">
                    <label for="name">name</label>
                    <input type="text" id="register-name" name="name" required placeholder="Votre name">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="register-email" name="email" required placeholder="Votre adresse email">
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select name="role" id="role">
                        <option value="Etudiant">Etudiant</option>
                        <option value="Enseignant">Enseignant</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="register-password">Mot de passe</label>
                    <input type="password" id="register-password" name="password" required
                        placeholder="Votre mot de passe">
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirmer le mot de passe</label>
                    <input type="password" id="confirm-password" name="confirm_password" required
                        placeholder="Confirmez votre mot de passe">
                </div>
                <div class="remember-forgot">
                    <label class="remember-me">
                        <input type="checkbox" name="remember">
                        Se souvenir de moi
                    </label>
                    <a href="#" class="forgot-password">Mot de passe oublié?</a>
                </div>
                <button type="submit" class="login-btn" name="sign_up">S'inscrire</button>
                <div class="register-link">
                    Déjà un compte? <a href="#" class="switch-to-login">Se connecter</a>
                </div>
            </form>
        </div>
    </div>
    <?php
    if (isset($_GET['msg'])) {
        $msg = $_GET['msg'];
        echo '<script>';
        if ($msg == 'invalid_email') {
            echo 'alert("Invalid email format. Please try again.");';
        } elseif ($msg == 'password_mismatch') {
            echo 'alert("Passwords do not match. Please try again.");';
        } elseif ($msg == 'email_exists') {
            echo 'alert("This email is already registered. Please use a different email.");';
        } elseif ($msg == 'registration_failed') {
            echo 'alert("Registration failed. Please try again later.");';
        }
        echo '</script>';
    }
    if (isset($_GET['error'])) {
        $error = $_GET['error'];
        echo "<script>";
        if ($error == 'incorrect_password') {
            echo "alert('Incorrect password. Please try again.');";
        } elseif ($error == 'user_not_found') {
            echo "alert('User not found. Please check your email.');";
        } elseif ($error == 'invalid_email') {
            echo "alert('The email address is invalid. Please enter a valid email.');";
        }
        echo "</script>";
    }
    ?>
    <script>
        const switchToRegisterLinks = document.querySelectorAll('.switch-to-register');
        const switchToLoginLinks = document.querySelectorAll('.switch-to-login');
        const loginForm = document.querySelector('.login');
        const registerForm = document.querySelector('.register');

        switchToLoginLinks.forEach(link => {
            link.addEventListener("click", () => {
                loginForm.style.display = 'block';
                registerForm.style.display = 'none';
            });
        });

        switchToRegisterLinks.forEach(link => {
            link.addEventListener("click", () => {
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
            });
        });
    </script>
</body>

</html>