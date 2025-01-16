<?php
include_once("./config/config.php");
include_once("./classes/Users.php");
include_once("./classes/Etudiant.php");
include_once("./classes/Enseignant.php");
include_once("./classes/Cours.php");
include_once("./classes/Categorie.php");
include_once("./classes/Tag.php");

session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['user_email'];

$sql = "SELECT username, email, role FROM user WHERE email = :email";
$stmt = $conn->prepare($sql);
$stmt->execute([':email' => $user_email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || $user['role'] != 'Admin') {
    header("Location: login.php");
    exit();
}

if (isset($_POST["submit"])) {

    $title = $_POST["title"];
    $discription = $_POST["description"];
    $url = $_POST["url"];
    $categorie = $_POST["categorie"];

    $aj = new Cours($title, $discription, $url, $categorie);
    $aj->AjouterCours($conn);
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
    $url = $cours["conent_url"];
    $categorie = $cours["category_id"];
}

if (isset($_POST["Edit"])) {
    $id = $_GET["Edit"];
    $title = $_POST["title"];
    $discription = $_POST["description"];
    $url = $_POST["url"];
    $categorie = $_POST["categorie"];

    $edit = new Cours($title, $discription, $url, $categorie);
    // $edit->editProduit($conn, $id);
    header("location:gestionCour.php");
}

if (isset($_GET["Delet"])) {
    $id = $_GET["Delet"];
    $Delet = new Cours(null, null, null, null);
    // $Delet->deletProduit($conn, $id);
}

if (isset($_POST["reset"])) {
    header("location:gestionCour.php");
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
    <link rel="stylesheet" href="./assets/style/dashboard.css">
    <style>
        table * {
            text-align: center !important;
            border: 1px solid black;
        }

        .idproduit {
            display: none;
        }

        td {
            padding: 15px;
        }

        #platformStatsChart, #platformStatsChart1 {
            width: 100% !important;
            height: 300px !important;
        }

        .statistique {
            display: flex;
            justify-content: space-around;
            align-items: center;
            gap: 20px;
        }
        

.form-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

label {
    font-weight: bold;
    margin-bottom: 5px;
    font-size: 14px;
}

input[type="text"],
input[type="number"],
.btn {
    padding: 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 100%;
    margin: 10px 0;
}

input[type="text"]:focus,
input[type="number"]:focus {
    border-color: #007BFF;
    outline: none;
}

.btn {
    background-color: #007BFF;
    color: white;
    cursor: pointer;
    font-weight: bold;
    margin-top: 30px;
}

.btn:hover {
    background-color: #0056b3;
}


    </style>
</head>

<body>
    <!-- =============== Navigation ================ -->
    <div class="container">
        <div class="navigation">
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
                <li>
                    <a href="gestionCour.php">
                        <span class="icon">
                            <ion-icon name="library-outline"></ion-icon>
                        </span>
                        <span class="title">Gestion des cours</span>
                    </a>
                </li>
                <li>
                    <a href="gestionCategorie.php">
                        <span class="icon">
                            <ion-icon name="book-outline"></ion-icon>
                        </span>
                        <span class="title">Gestion des categories</span>
                    </a>
                </li>
                <li class="hovered">
                    <a href="gestionTag.php">
                        <span class="icon">
                            <ion-icon name="pricetags-outline"></ion-icon>
                        </span>
                        <span class="title">Gestion des tags</span>
                    </a>
                </li>
                <li>
                    <a href="logout.php">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="title">Sign Out</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- ========================= Main ==================== -->
        <div class="main">
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
            </div>
            <!-- ================ Details List ================= -->
            <div class="details">
            <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="title">title:</label>
                            <input type="text" id="title" name="title"
                                value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                        <?php
                            if (isset($_GET['Edit'])) {
                                echo '<input type="submit" name="Edit" value="Edit Cour" class="btn">';
                            } else {
                                echo '<input type="submit" name="submit" value="Submit Cour" class="btn">';
                            }
                            ?>
                        </div>
                    </div>
                </form>
                    <div class="cardHeader">
                        <h2>Les Tags</h2>
                    </div>
                    <!-- <table>
                        <thead>
                            <tr>
                                <td class="id">id</td>
                                <td>title</td>
                                <td>description</td>
                                <td>content url</td>
                                <td>Categorie</td>
                                <td>date</td>
                                <td>action</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $p = new Cours(null ,null ,null ,null);
                            $p->affichageCours($conn);
                            ?>
                        </tbody>
                    </table> -->
                </div>
            </div>
        </div>
    </div>
    <!-- =========== Scripts =========  -->
    <script src="./assets/js/script.js?v=1"></script>
    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>