<?php
include_once("../config/config.php");
include_once("../classes/Users.php");
include_once("../classes/Etudiant.php");
include_once("../classes/Enseignant.php");
include_once("../classes/Cours.php");
include_once("../classes/Categorie.php");
include_once("../classes/Tag.php");
include_once("../classes/Admin.php");

session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: ../login.php");
    exit();
}

$user_email = $_SESSION['user_email'];

$sql = "SELECT username, email, role FROM user WHERE email = :email";
$stmt = $conn->prepare($sql);
$stmt->execute([':email' => $user_email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || $user['role'] != 'Admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST["submit"])) {

    $name = $_POST["name"];
    $discription = $_POST["description"];

    $aj = new Categorie($name, $discription);
    $aj->AjouterCategorie($conn);
    header("location:gestionCategorie.php");
}

if (isset($_GET["Edit"])) {
    $id = $_GET["Edit"];
    $query = "SELECT * FROM category WHERE category_id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $categorie = $stmt->fetch(PDO::FETCH_ASSOC);

    $name = $categorie["name"];
    $description = $categorie["description"];

}

if (isset($_POST["Edit"])) {
    $id = $_GET["Edit"];
    $name = $_POST["name"];
    $discription = $_POST["description"];

    $edit = new Categorie($name, $discription);
    $edit->editCategorie($conn, $id);
    header("location:gestionCategorie.php");
}

if (isset($_GET["Delet"])) {
    $id = $_GET["Delet"];
    $Delet = new Categorie(null, null);
    $Delet->deletCategorie($conn, $id);
}

if (isset($_POST["reset"])) {
    header("location:gestionCategorie.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Académie d'Apprentissage</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="../assets/style/dashboard.css">
    <link rel="stylesheet" href="../assets/style/gestionCour.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;

            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);

        }

        thead {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        thead td {
            padding: 10px;
            text-align: center;
            font-size: 14px;
            color: #333;
        }

        tbody td {
            padding: 12px;
            text-align: center;
            font-size: 14px;
            color: #555;
            border-bottom: 1px solid #ddd;

        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }

        tbody .action-links a {
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 5px;
            margin: 0 5px;
            font-weight: bold;
        }

        .action-links .edit {
            background-color: #4CAF50;
            color: white;
        }

        .action-links .edit:hover {
            background-color: #45a049;
        }

        .action-links .delete {
            background-color: #f44336;
            color: white;
        }

        .action-links .delete:hover {
            background-color: #e53935;
        }

        .action-links {
            text-align: center;
        }
    </style>
</head>

<body>
    <!-- =============== Navigation ================ -->
    <div class="container">
        <div class="navigation active">
            <ul>
                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="business-outline"></ion-icon>
                        </span>
                        <span class="title">Académie d'Apprentissage</span>
                    </a>
                </li>
                <li>
                    <a href="dashboard.php">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="title">Accueil</span>
                    </a>
                </li>
                <li>
                    <a href="gestionEnseignant.php">
                        <span class="icon">
                            <ion-icon name="people-outline"></ion-icon>
                        </span>
                        <span class="title">Les enseignants</span>
                    </a>
                </li>
                <li>
                    <a href="gestionEtudiant.php">
                        <span class="icon">
                            <ion-icon name="school-outline"></ion-icon>
                        </span>
                        <span class="title">Les etudiants</span>
                    </a>
                </li>
                <li class="hovered">
                    <a href="gestionCategorie.php">
                        <span class="icon">
                            <ion-icon name="book-outline"></ion-icon>
                        </span>
                        <span class="title">Gestion des categories</span>
                    </a>
                </li>
                <li>
                    <a href="gestionTag.php">
                        <span class="icon">
                            <ion-icon name="pricetags-outline"></ion-icon>
                        </span>
                        <span class="title">Gestion des tags</span>
                    </a>
                </li>
                <li>
                    <a href="../logout.php">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="title">Sign Out</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- ========================= Main ==================== -->
        <div class="main active">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>
                <div class="search">
                    <label>
                        <input type="text" placeholder="Search here">
                        <ion-icon name="search-outline"></ion-icon>
                    </label>
                </div>
                <div style="display: flex;align-items: center;">
                    <p>Admin</p>
                    <img src="../assets/image/admin.jpg" style="width: 50px;height: 50px;" alt="">
                </div>
            </div>
            <!-- ================ Details List ================= -->
            <div class="details">
                <form action="" method="post">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Categorie:</label>
                            <input type="text" id="name" name="name"
                                value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea id="description" name="description" required>
                                <?php echo isset($description) ? htmlspecialchars($description) : ''; ?>
                            </textarea>
                        </div>
                        <div class="form-group">
                            <?php
                            if (isset($_GET['Edit'])) {
                                echo '<input type="submit" name="Edit" value="Edit Categorie" class="btn">';
                            } else {
                                echo '<input type="submit" name="submit" value="Submit Categorie" class="btn">';
                            }
                            ?>
                        </div>
                    </div>
                </form>
                <div class="cardHeader">
                    <h2>Les categories</h2>
                </div>
                <table>
                    <thead>
                        <tr>
                            <td class="idproduit">id</td>
                            <td>Categorie</td>
                            <td>Description</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $p = new Categorie(null, null);
                        $p->affichageCategorie($conn);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
    <!-- =========== Scripts =========  -->
    <script src="../assets/js/script.js?v=1"></script>
    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>