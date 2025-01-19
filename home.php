<?php
include_once("./config/config.php");

session_start();

if (isset($_POST["inscrire"])) {
    $user_email = $_SESSION['user_email'];
    $role = 'Etudiant';
    $statut = 'Active';
    $sql = "SELECT user_id, username, email, role, statut FROM user WHERE email = :email AND role = :role AND statut = :statut";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':email' => $user_email, ':role' => $role, ':statut' => $statut]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || $user['role'] != 'Etudiant' || $user['statut'] != 'Active') {
        header("Location: login.php");
        exit();
    } else {

        $userId = $user["user_id"];
        $idCoure = $_POST["id"];

        echo "id etudiant: " . $userId;
        echo "<br> id cours: " . $idCoure;
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

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            /* padding: 20px; */
        }

        .courses-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 50px auto;
        }

        .course-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .course-card:hover {
            transform: translateY(-5px);
        }

        .course-content {
            padding: 20px;
        }

        .content-container {
            position: relative;
            width: 100%;
            padding-top: 56.25%; /* 16:9 Aspect Ratio */
            background-color: #f8f9fa;
        }

        .video-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #000;
        }

        .video-container iframe,
        .video-container video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        .video-fallback {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #2c3e50;
            color: white;
            font-size: 1.2rem;
        }

        .video-play-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60px;
            height: 60px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            z-index: 2;
        }

        .video-play-button::after {
            content: '';
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 10px 0 10px 20px;
            border-color: transparent transparent transparent #2c3e50;
            margin-left: 5px;
        }

        .pdf-preview {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            color: #666;
        }

        .course-title {
            font-size: 1.25rem;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .course-description {
            color: #666;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .course-category {
            display: inline-block;
            padding: 4px 12px;
            background-color: #e9ecef;
            color: #495057;
            border-radius: 15px;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }

        .tags-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 15px;
        }

        .tag {
            padding: 4px 10px;
            background-color: #f0f0f0;
            color: #666;
            border-radius: 12px;
            font-size: 0.85rem;
        }

        .no-content {
            padding: 20px;
            text-align: center;
            color: #666;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
    </style>
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

                    $user_email = $_SESSION['user_email'];
                    $role = 'Etudiant';
                    $statut = 'Active';

                    $sql = "SELECT role, statut FROM user WHERE email = :email";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([':email' => $user_email]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($user && $user['role'] == $role && $user['statut'] == $statut) {
                        echo '<li><a href="#">Cours</a></li>
                            <li><a href="#">Formateurs</a></li>
                            <li class="nav-item"><a class="nav-link" href="./logout.php">Déconnexion</a></li>';
                    } else {
                        echo '<li class="nav-item"><a class="nav-link" href="./login.php">Connexion</a></li>';
                    }
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
    <?php
    $sql = "
    SELECT course.*, GROUP_CONCAT(tag.name) AS tags
    FROM course
    LEFT JOIN course_tag ON course.course_id = course_tag.course_id
    LEFT JOIN tag ON course_tag.tag_id = tag.tag_id
    GROUP BY course.course_id";

    $stmt = $conn->query($sql);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($courses) {
        foreach ($courses as $course) {
            $categorySql = "SELECT name FROM category WHERE category_id = :category_id";
            $categoryStmt = $conn->prepare($categorySql);
            $categoryStmt->bindParam(':category_id', $course['category_id'], PDO::PARAM_INT);
            $categoryStmt->execute();
            $category = $categoryStmt->fetch(PDO::FETCH_ASSOC);

            if ($category) {
                echo '<div class="course-card">';
                
                // Section de contenu
                if (!empty($course['content_url'])) {
                    if ($course['content_type'] == 'video') {
                        $videoUrl = htmlspecialchars($course['content_url']);
                        // Vérifier si c'est une URL YouTube
                        if (strpos($videoUrl, 'youtube.com') !== false || strpos($videoUrl, 'youtu.be') !== false) {
                            // Convertir l'URL en URL d'intégration si nécessaire
                            $videoId = '';
                            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $videoUrl, $matches)) {
                                $videoId = $matches[1];
                            }
                            if ($videoId) {
                                echo '<div class="content-container">
                                        <div class="video-container">
                                            <iframe src="https://www.youtube.com/embed/' . $videoId . '" 
                                                    allowfullscreen></iframe>
                                        </div>
                                      </div>';
                            }
                        } else {
                            // Pour les vidéos directes
                            echo '<div class="content-container">
                                    <div class="video-container">
                                        <video controls>
                                            <source src="' . $videoUrl . '" type="video/mp4">
                                            <source src="' . $videoUrl . '" type="video/webm">
                                            Votre navigateur ne prend pas en charge la lecture de vidéos.
                                        </video>
                                    </div>
                                  </div>';
                        }
                    } elseif ($course['content_type'] == 'pdf') {
                        echo '<div class="content-container">
                                <div class="pdf-preview">
                                    <embed type="application/x-google-chrome-pdf" src="chrome-extension://mhjfbmdgcfjbbpaeojofohoefgiehjai/2fbabbcf-39c4-4f86-8774-e8282491637a" original-url="https://www.aeee.in/wp-content/uploads/2020/08/Sample-pdf.pdf" background-color="4283586137" javascript="allow">
                                </div>
                              </div>';
                    } else {
                        echo '<div class="content-container">
                                <div class="video-fallback">Contenu non pris en charge</div>
                              </div>';
                    }
                } else {
                    echo '<div class="content-container">
                            <div class="video-fallback">Contenu non disponible</div>
                          </div>';
                }

                echo '<div class="course-content">';
                // Titre
                echo '<h2 class="course-title">' . htmlspecialchars($course['title'] ?? 'Sans titre') . '</h2>';
                
                // Description
                echo '<p class="course-description">' . htmlspecialchars($course['description'] ?? 'Pas de détails') . '</p>';
                
                // Catégorie
                echo '<span class="course-category">' . htmlspecialchars($category['name'] ?? 'Non défini') . '</span>';

                // Tags
                if (!empty($course['tags'])) {
                    $tags = explode(",", $course['tags']);
                    echo '<div class="tags-container">';
                    foreach ($tags as $tag) {
                        echo '<span class="tag">' . htmlspecialchars($tag) . '</span>';
                    }
                    echo '</div>';
                } else {
                    echo '<div class="tags-container"><span class="tag">Pas de tags</span></div>';
                }
                echo '<form action="" method="post">
                <input type="hidden" name="id" value="'.$course["course_id"].'">
                <button class="enroll-btn" name="inscrire">S\'inscrire</button>
                </form>';
                echo '</div></div>'; // Fermeture de course-content et course-card
            } else {
                echo '<div class="no-content">La catégorie n\'existe pas pour le cours : ' . 
                     htmlspecialchars($course['title']) . '</div>';
            }
        }
    } else {
        echo '<div class="no-content">Aucun cours disponible à afficher.</div>';
    }
    ?>
</div>

<script>
    // Amélioration du chargement des vidéos
    document.addEventListener('DOMContentLoaded', function() {
        var videos = document.querySelectorAll('video');
        videos.forEach(function(video) {
            video.addEventListener('error', function() {
                this.parentElement.innerHTML = '<div class="video-fallback">Désolé, une erreur est survenue lors du chargement de la vidéo</div>';
            });
        });
    });
</script>

    <script src="./assets/js/home.js"></script>
</body>

</html>