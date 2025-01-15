<?php
include_once("./config/config.php");

session_start();

if (isset($_POST["inscrire"])) {
    $user_email = $_SESSION['user_email'];

    $sql = "SELECT user_id ,username ,email ,role FROM user WHERE email = :email ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':email' => $user_email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || $user['role'] != 'Etudiant') {
        header("Location: login.php");
        exit();
    }else{
        echo "id  etudiant".$userId = $user["user_id"];
        echo "<br> id cour".$idCoure = $_POST["id"];
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/style/home.css">
    <title>Plateforme d'Apprentissage</title>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="#" class="logo">Académie d'Apprentissage</a>
            <button class="nav-toggle" onclick="toggleNav()">☰</button>
            <ul class="nav-links" id="navLinks">
                <li><a href="./home.php">Accueil</a></li>
                <?php
                if (isset($_SESSION['user_email'])) {
                    echo '<li><a href="#">Cours</a></li>
                    <li><a href="#">Formateurs</a></li>
                    <li class="nav-item"><a class="nav-link" href="./logout.php">Déconnexion</a></li>';
                } else {
                    echo '<li class="nav-item"><a class="nav-link" href="./login.php">Connexion</a></li>';
                }
                ?>
            </ul>
        </div>
    </nav>

    <header class="header">
        <h1>Plateforme de Formation en Ligne</h1>
    </header>

    <div class="courses-container">
        <form action="" method="post">
        <div class="course-card">
        <input type="hidden" value="1" name="id">
            <div class="course-image html-bg">HTML</div>
            <div class="course-content">
                <h2 class="course-title">Introduction à HTML</h2>
                <p class="course-description">Apprenez les bases du HTML et comment </p>
                <div class="course-stats">
                    <span>⏱️ 8 heures</span>
                    <span>📚 12 leçons</span>
                </div>
                <button class="enroll-btn" name="inscrire">S'inscrire</button>
            </div>
        </div>
        </form>
        <!-- CSS Card -->
        <form action="" method="post">
        <div class="course-card">
            <input type="hidden" value="2" name="id">
            <div class="course-image css-bg">CSS</div>
            <div class="course-content">
                <h2 class="course-title">CSS Avancé</h2>
                <p class="course-description">Maîtrisez le style des pages web avec CSS</p>
                <div class="course-stats">
                    <span>⏱️ 10 heures</span>
                    <span>📚 15 leçons</span>
                </div>
                <button class="enroll-btn" name="inscrire">S'inscrire</button>
            </div>
        </div>
        </form>
        <!-- JavaScript Card -->
         <form action="" method="post">
        <div class="course-card">
            <div class="course-image js-bg">JS</div>
            <div class="course-content">
                <h2 class="course-title">JavaScript pour Débutants</h2>
                <p class="course-description">Bases de la programmation avec JavaScript</p>
                <div class="course-stats">
                    <span>⏱️ 12 heures</span>
                    <span>📚 18 leçons</span>
                </div>
                <button class="enroll-btn">S'inscrire</button>
            </div>
        </div>
        </form>

        <!-- UI Card -->
        <form action="" method="post">
        <div class="course-card">
            <div class="course-image ui-bg">UI</div>
            <div class="course-content">
                <h2 class="course-title">Développement d'Interfaces</h2>
                <p class="course-description">Créez des interfaces utilisateur interactives</p>
                <div class="course-stats">
                    <span>⏱️ 15 heures</span>
                    <span>📚 20 leçons</span>
                </div>
                <button class="enroll-btn">S'inscrire</button>
            </div>
        </div>
        </form>
    </div>

    <script src="./assets/js/home.js"></script>
</body>
</html>