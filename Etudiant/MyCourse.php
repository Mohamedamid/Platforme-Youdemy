<?php
include_once("../config/config.php");
include_once("../classes/Cours.php");

session_start();

// Check if user is logged in and email exists in session
if (isset($_SESSION['user_email'])) {
    $user_email = $_SESSION['user_email'];
    $role = 'Etudiant';
    $statut = 'Active';

    // Fetch the user from the database
    $sql = "SELECT user_id, email FROM user WHERE email = :email AND role = :role AND statut = :statut";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':email' => $user_email, ':role' => $role, ':statut' => $statut]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the user was found
    if ($user) {
        $userId = $user['user_id'];
        
        // Check if the course ID is posted
        if (isset($_POST["id"])) {
            $courseId = $_POST["id"];

            // Check if the user is already enrolled in the course
            $stmt = $conn->prepare("SELECT * FROM enrollment WHERE user_id = :user_id AND course_id = :course_id");
            $stmt->execute([':user_id' => $userId, ':course_id' => $courseId]);
            if ($stmt->rowCount() > 0) {
                echo "<script>alert('أنت مسجل بالفعل في هذه الدورة.');</script>";
            } else {
                // Insert the enrollment
                $stmt = $conn->prepare("INSERT INTO enrollment (user_id, course_id) VALUES (?, ?)");
                $stmt->bindParam(1, $userId, PDO::PARAM_INT);
                $stmt->bindParam(2, $courseId, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    echo "<script>alert('تم التسجيل بنجاح!');</script>";
                } else {
                    echo "<script>alert('حدث خطأ أثناء التسجيل.');</script>";
                }
            }
        }

        // Fetch the courses the user is enrolled in
        $sql = "SELECT c.* FROM course c
                JOIN enrollment e ON c.course_id = e.course_id
                WHERE e.user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } else {
        echo "<script>alert('لا يمكن العثور على المستخدم.');</script>";
    }
} else {
    echo "<script>alert('لا يوجد مستخدم مسجل.');</script>";
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style/home.css">
    <title>Plateforme d'Apprentissage</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: #f4f7fc;
        }

        .courses-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .course-card {
            display: flex;
            flex-direction: column;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
            height: 550px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .course-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.2);
        }

        .content-container {
            position: relative;
            padding: 15px;
        }

        .video-container iframe,
        .video-container video {
            width: 100%;
            height: 220px;
            border-radius: 10px;
        }

        .pdf-preview object {
            width: 100%;
            height: 220px;
            border-radius: 10px;
        }

        .course-content {
            padding: 20px;
            background-color: #f9f9f9;
            border-top: 2px solid #eee;
            flex-grow: 1;
            position: relative;
        }

        .course-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 12px;
            color: #333;
        }

        .course-description {
            font-size: 16px;
            color: #555;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .course-category {
            font-size: 14px;
            color: #007bff;
            font-weight: bold;
            margin-top: 10px;
        }

        .tags-container {
            margin-top: 15px;
        }

        .tag {
            display: inline-block;
            background-color: #e0e0e0;
            padding: 6px 12px;
            border-radius: 20px;
            margin: 8px 8px 0 0;
            font-size: 14px;
            color: #555;
        }

        .enroll-form {
            position: absolute;
            bottom: 10px;
            width: 85%;
            background-color: #fff;
            text-align: center;
            box-sizing: border-box;
        }

        .enroll-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 5px 0 10px 0;
        }

        .enroll-btn:hover {
            background-color: #0056b3;
        }

        .no-content {
            padding: 20px;
            background-color: #f8d7da;
            color: #721c24;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }

        .numberPage {
            width: 100%;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="#" class="logo">Académie d'Apprentissage</a>
            <button class="nav-toggle" onclick="toggleNav()">☰</button>
            <ul class="nav-links" id="navLinks">
                <li><a href="../home.php">Accueil</a></li>
                <li><a href="./MyCourse.php">Cours</a></li>
                <li class="nav-item"><a class="nav-link" href="../logout.php">Déconnexion</a></li>
            </ul>
        </div>
    </nav>

    <header class="header">
        <h1>Bienvenue dans la liste des cours auxquels vous êtes inscrit. </h1>
    </header>

    <div class="courses-container">
    <?php if (isset($courses) && count($courses) > 0): ?>
            <?php foreach ($courses as $course): ?>
                <?php 
                // جلب اسم الفئة الخاصة بكل دورة
                $categorySql = "SELECT name FROM category WHERE category_id = :category_id";
                $categoryStmt = $conn->prepare($categorySql);
                $categoryStmt->bindParam(':category_id', $course['category_id'], PDO::PARAM_INT);
                $categoryStmt->execute();
                $category = $categoryStmt->fetch(PDO::FETCH_ASSOC);
                ?>
                
                <?php if ($category): ?>
                    <div class="course-card">
                        <!-- عرض المحتوى (فيديو أو PDF أو محتوى آخر) -->
                        <?php if (!empty($course['content_url'])): ?>
                            <?php if ($course['content_type'] == 'video'): ?>
                                <?php 
                                $videoUrl = htmlspecialchars($course['content_url']);
                                if (strpos($videoUrl, 'youtube.com') !== false || strpos($videoUrl, 'youtu.be') !== false):
                                    $videoId = '';
                                    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $videoUrl, $matches)) {
                                        $videoId = $matches[1];
                                    }
                                    if ($videoId):
                                ?>
                                    <div class="content-container">
                                        <div class="video-container">
                                            <iframe src="https://www.youtube.com/embed/<?= $videoId ?>" frameborder="0" allowfullscreen></iframe>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php else: ?>
                                    <div class="content-container">
                                        <div class="video-container">
                                            <video controls>
                                                <source src="<?= $videoUrl ?>" type="video/mp4">
                                                <source src="<?= $videoUrl ?>" type="video/webm">
                                                Votre navigateur ne prend pas en charge la lecture de vidéos.
                                            </video>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php elseif ($course['content_type'] == 'pdf'): ?>
                                <div class="content-container content-container1">
                                    <div class="pdf-preview">
                                        <object data="<?= $course['content_url'] ?>" type="application/pdf" width="100%" height="219px">
                                            <p>Le navigateur n'a pas pu afficher le fichier. Vous pouvez le télécharger depuis <a href="<?= $course['content_url'] ?>">ici</a>.</p>
                                        </object>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="content-container">
                                    <div class="video-fallback">Contenu non pris en charge</div>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="content-container">
                                <div class="video-fallback">Contenu non disponible</div>
                            </div>
                        <?php endif; ?>

                        <!-- عرض معلومات الدورة (العنوان، الوصف، الفئة) -->
                        <div class="course-content">
                            <h2 class="course-title"><?= htmlspecialchars($course['title'] ?? 'Sans titre') ?></h2>
                            <p class="course-description"><?= htmlspecialchars($course['description'] ?? 'Pas de détails') ?></p>
                            <span class="course-category"><?= htmlspecialchars($category['name'] ?? 'Non défini') ?></span>

                            <!-- عرض التاجز (إذا كانت موجودة) -->
                            <?php if (!empty($course['tags'])): ?>
                                <?php 
                                $tags = explode(",", $course['tags']);
                                echo '<div class="tags-container">';
                                foreach ($tags as $tag):
                                    echo '<span class="tag">' . htmlspecialchars($tag) . '</span>';
                                endforeach;
                                echo '</div>';
                                ?>
                            <?php else: ?>
                                <div class="tags-container"><span class="tag">Pas de tags</span></div>
                            <?php endif; ?>

                            <!-- عرض زر التسجيل -->
                            <form action="" method="post" class="enroll-form">
                                <input type="hidden" name="id" value="<?= $course['course_id'] ?>">
                                <button class="enroll-btn" name="inscrire">S'inscrire</button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="no-content">La catégorie n'existe pas pour le cours : <?= htmlspecialchars($course['title']) ?></div>
                <?php endif; ?>
            <?php endforeach; ?>
    <?php else: ?>
        <p>لم تقم بالتسجيل في أي دورة بعد.</p>
    <?php endif; ?>
</div>


    <div class="numberPage">
        <?php
        $cnt = new Cours(null, null, null, null);

        $cntpage = ceil(intval($cnt->affichagetotal($conn)) / 3);
        $ofset = 0;
        for ($i = 1; $i <= $cntpage; $i++) {
            echo "<a href='?page=$i&ofset=$ofset' style='text-decoration: none;'>$i</a>";
            $ofset += 3;
        }
        ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var videos = document.querySelectorAll('video');
            videos.forEach(function (video) {
                video.addEventListener('error', function () {
                    this.parentElement.innerHTML = '<div class="video-fallback">Désolé, une erreur est survenue lors du chargement de la vidéo</div>';
                });
            });
        });
    </script>

    <script src="./assets/js/home.js"></script>
</body>

</html>
