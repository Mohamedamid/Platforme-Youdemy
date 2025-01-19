<?php
require_once("../config/config.php");
include_once("../classes/Users.php");
include_once("../classes/Etudiant.php");
include_once("../classes/Enseignant.php");
include_once("../classes/Cours.php");
include_once("../classes/Categorie.php");
include_once("../classes/Tag.php");

session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: ../logout.php");
    exit();
}

$user_email = $_SESSION['user_email'];

$sql = "SELECT username, email, role FROM user WHERE email = :email";
$stmt = $conn->prepare($sql);
$stmt->execute([':email' => $user_email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || $user['role'] != 'Enseignant') {
    header("Location: ../logout.php");
    exit();
}

if (isset($_POST["submit"])) {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $url = $_POST['url'];
    $type = $_POST['content_type'];
    $categorie = $_POST['categorie'];
    $tags = $_POST['tags'];
    // if (isset($_POST['tags']) && is_array($_POST['tags'])) {
    //     $tags = $_POST['tags'];
    // } else {
    //     $tags = [];
    // }

    $aj = new Cours($title, $description, $url, $categorie);
    $aj->AjouterCours($conn, $tags, $type);

    header("location:gestionCour.php");
}


if (isset($_GET["Edit"])) {
    $id = $_GET["Edit"];
    $query = "SELECT * FROM course WHERE course_id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $cours = $stmt->fetch(PDO::FETCH_ASSOC);

    $title = $cours["title"];
    $description = $cours["description"];
    $url = $cours["content_url"];
    $categorie = $cours["category_id"];
}

if (isset($_POST["Edit"])) {
    $id = $_GET["Edit"];
    $title = $_POST["title"];
    $description = $_POST["description"];
    $url = $_POST["url"];
    $categorie = $_POST["categorie"];

    // جلب التاجات المحددة من النموذج (مثال على اختيار عدة تاجات)
    $tags = isset($_POST['tags']) ? $_POST['tags'] : [];  // تأكد من أن 'tags' هو اسم الحقل في النموذج

    // إنشاء كائن جديد لدورة مع التاجات الجديدة
    $edit = new Cours($title, $description, $url, $categorie);
    // تمرير التاجات
    $edit->editCour($conn, $id, $tags);  // استدعاء دالة التعديل

    // إعادة التوجيه إلى صفحة إدارة الدورات
    header("location:gestionCour.php");
}


if (isset($_GET["Delet"])) {
    $id = $_GET["Delet"];
    $Delet = new Cours(null, null, null, null);
    $Delet->deletCour($conn, $id);  // استدعاء الدالة للحذف
    header("location:gestionCour.php");  // إعادة التوجيه بعد الحذف
}

if (isset($_POST["reset"])) {
    header("location:gestionCour.php");
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Académie d'Apprentissage</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .navbar {
            background-color: #2c3e50;
            padding: 15px 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            list-style: none;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .nav-links a:hover {
            background-color: #34495e;
        }

        .header {
            background-color: #34495e;
            color: white;
            padding: 60px 20px;
            text-align: center;
        }

        .header h1 {
            margin-bottom: 20px;
        }

        .header p {
            max-width: 800px;
            margin: 0 auto;
            font-size: 18px;
            line-height: 1.6;
        }

        .teachers-container {
            max-width: 90%;
            margin: 40px auto;
            padding: 0 20px;
        }

        .form-grid {
            display: flex;
            /* grid-template-columns: repeat(2, 1fr); */
            flex-wrap: wrap;
            gap: 20px;
            max-width: 100%;
            margin: 20px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group1 {
            width: 530px;
        }

        .form-group2 {
            width: 715px;
        }

        .form-group3 {
            width: 347.5px;
        }

        .form-group4 {
            width: 100%;
        }

        .form-group label {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #2c3e50;
            width: 95px;
            text-align: center;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
            width: 100%;
            margin-top: 5px;
            transition: border-color 0.3s;
        }

        .form-group input[type="text"],
        .form-group textarea,
        .form-group select {
            height: 60px;
        }

        .form-group input[type="submit"],
        .form-group button {
            background-color: #3498db;
            color: white;
            padding: 12px;
            border: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        .form-group input[type="submit"]:hover,
        .form-group button:hover {
            background-color: #2980b9;
        }

        textarea {
            resize: vertical;
            min-height: 60px;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #3498db;
            outline: none;
        }

        fieldset {
            margin-top: 20px;
            border: 1px solid #e1e4e8;
            /* Bordure plus subtile */
            padding: 20px;
            /* Plus d'espace intérieur */
            grid-column: span 2;
            /* Pour étendre le fieldset sur toute la largeur du formulaire */
            border-radius: 8px;
            /* Coins arrondis */
            background-color: #f8f9fa;
            /* Fond légèrement grisé */
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            /* Ombre subtile */
            width: 100%;
            /* Pour s'assurer que le fieldset prend toute la largeur */
        }

        legend {
            font-size: 16px;
            font-weight: 600;
            /* Un peu moins gras que bold */
            color: #2c3e50;
            padding: 0 10px;
            /* Espace autour du texte */
            background-color: white;
            /* Fond blanc pour la légende */
            border-radius: 4px;
            /* Coins arrondis pour la légende */
        }

        /* Style pour améliorer l'apparence des labels et inputs radio dans le fieldset */
        fieldset label {
            display: inline-block;
            margin: 8px 0px 8px 0;
            /* Espacement amélioré */
            padding: 6px 12px;
            /* Zone de clic plus grande */
            border-radius: 4px;
            /* Coins arrondis */
            transition: background-color 0.2s;
        }

        fieldset label:hover {
            background-color: #e9ecef;
            /* Effet hover */
            cursor: pointer;
        }

        fieldset input[type="radio"] {
            margin-right: 8px;
            /* Espace entre radio et texte */
            cursor: pointer;
        }

        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        table thead {
            background-color: #34495e;
            color: white;
            border-radius: 8px;
        }

        table th,
        table td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .id {
            width: 50px;
        }

        .cardHeader h2 {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="#" class="logo">Académie d'Apprentissage</a>
            <ul class="nav-links">
                <li><a href="index.php">Accueil</a></li>
                <li><a href="gestionCour.php">Cours</a></li>
                <li><a href="../logout.php">Déconnexion</a></li>
            </ul>
        </div>
    </nav>

    <header class="header">
        <h1>Bienvenue sur votre Tableau de Bord, <?php echo $user['username']; ?></h1>
        <p>Gérez vos cours, vos étudiants et vos ressources en ligne.</p>
    </header>

    <div class="teachers-container">
        <div class="details">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-group form-group1">
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title"
                            value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>" required>
                    </div>
                    <div class="form-group form-group2">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description"
                            required><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
                    </div>
                    <div class="form-group form-group1">
                        <label for="url">Content URL:</label>
                        <input type="text" id="url" name="url"
                            value="<?php echo isset($url) ? htmlspecialchars($url) : ''; ?>" required>
                    </div>
                    <div class="form-group form-group3">
                        <label for="url">Type URL:</label>
                        <select name="content_type" id="">
                            <option value="video">Video</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                    <div class="form-group form-group3">
                        <label for="categorie">Categorie:</label>
                        <?php
                        $sql = "SELECT category_id, name FROM category";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <select id="categorie" name="categorie" required>
                            <option value="" disabled selected>Choisissez la catégorie</option>
                            <?php
                            foreach ($categories as $category) {
                                $selected = (isset($categorie) && $categorie == $category['category_id']) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($category['category_id']) . '" ' . $selected . '>' . htmlspecialchars($category['name']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group form-group4">
                        <fieldset>
                            <legend>Choisissez vos tags :</legend>
                            <?php
                            $sql = "SELECT tag_id, name FROM tag";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <?php
                            foreach ($tags as $tag) {
                                echo '<label>';
                                echo '<input type="checkbox" name="tags[]" value="' . htmlspecialchars($tag['tag_id']) . '" ' . (isset($selected_tags) && in_array($tag['tag_id'], $selected_tags) ? 'checked' : '') . '> ';
                                echo htmlspecialchars($tag['name']);
                                echo '</label>';
                            }
                            ?>
                        </fieldset>
                    </div>
                    <div class="form-group">
                        <?php
                        if (isset($_GET['Edit'])) {
                            echo '<input type="submit" name="Edit" value="Edit Course" class="btn">';
                        } else {
                            echo '<input type="submit" name="submit" value="Submit Course" class="btn">';
                        }
                        ?>
                    </div>
                </div>
            </form>
            <div class="cardHeader">
                <h2>Les Cours</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th class="id">ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Content URL</th>
                        <th>Type URL</th>
                        <th>Categorie</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $p = new Cours(null, null, null, null);
                    $p->affichageCours($conn);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>