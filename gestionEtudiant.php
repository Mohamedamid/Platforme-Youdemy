<?php
include_once("./config/config.php");
include_once("./classes/Users.php");
include_once("./classes/Etudiant.php");
include_once("./classes/Enseignant.php");
include_once("./classes/Cours.php");
include_once("./classes/Categorie.php");
include_once("./classes/Tag.php");
include_once("./classes/Admin.php");

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

if (isset($_GET["idEdit"])) {
    $idd = $_GET["idEdit"];
    $statut = $_GET["statut"];

    $Stat = new Admin(null, null, null);
    $Stat->updateStatut($conn, $idd, $statut);
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e9e9e9;
        }

        .status-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .status-link {
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .status-link:hover {
            opacity: 0.8;
        }

        .active-link {
            background-color: #4CAF50;
            color: white;
        }

        .active-link:hover {
            background-color: #45a049;
        }

        .disactive-link {
            background-color: #f44336;
            color: white;
        }

        .disactive-link:hover {
            background-color: #e53935;
        }

        th {
            background-color: #f1f1f1;
            font-weight: bold;
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
                <li class="hovered">
                    <a href="gestionEtudiant.php">
                        <span class="icon">
                            <ion-icon name="school-outline"></ion-icon>
                        </span>
                        <span class="title">Les etudiants</span>
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
                <li>
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
                    <img src="./assets/image/admin.jpg" style="width: 50px;height: 50px;" alt="">
                </div>
            </div>
            <div class="cardBox">
                <div class="card">
                    <div>
                        <div class="numbers">
                            <?php
                            $enseignants = new Admin(null, null, null);
                            $enseignants->affichagetotalE($conn ,'Etudiant' ,'Disactive');
                            ?>
                            <div class="cardName">Les Disactives</div>
                        </div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="people-outline"></ion-icon>
                    </div>
                </div>
                <div class="card">
                    <div>
                        <div class="numbers">
                            <?php
                            $enseignants = new Admin(null, null, null);
                            $enseignants->affichagetotalE($conn ,'Etudiant' ,'Active');
                            ?>
                        </div>
                        <div class="cardName">Les Actives</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="school-outline"></ion-icon>
                    </div>
                </div>
            </div>
            <!-- ================ Details List ================= -->
            <div class="details">
                <div class="cardHeader">
                    <h2>Les Etudiants</h2>
                </div>
                <table>
                    <thead>
                        <tr>
                            <!-- <td>id</td> -->
                            <td>Name</td>
                            <td>email</td>
                            <td>statut</td>
                            <td>date inscription</td>
                            <td>action</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $etudiant = new Admin(null, null, null);
                        $etudiant->affichageEtudiant($conn);
                        ?>
                    </tbody>
                </table>
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