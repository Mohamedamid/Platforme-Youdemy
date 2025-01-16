<?php
include_once("./config/config.php");
include_once("./classes/Users.php");
include_once("./classes/Etudiant.php");
include_once("./classes/Enseignant.php");
include_once("./classes/Cours.php");
include_once("./classes/Categorie.php");
include_once("./classes/Tag.php");
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
                <li class="hovered">
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
                <div class="cardHeader">
                    <h2>Les Etudiants</h2>
                </div>
                <table>
                    <thead>
                        <tr>
                            <td>id</td>
                            <td>Name</td>
                            <td>email</td>
                            <td>statut</td>
                            <td>date inscription</td>
                            <td>action</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // $p = new Product(null, null, null, null, null);
                        // $p->affichage($conn);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
    <!-- =========== Scripts =========  -->
    <script src="assets/js/main.js?v=1"></script>
    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>