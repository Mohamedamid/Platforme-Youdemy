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
                <li class="hovered">
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
            <!-- ======================= Cards ================== -->
            <div class="cardBox">
                <div class="card">
                    <div>
                        <div class="numbers">
                            <?php
                            $enseignants = new enseignant(null, null, null, null);
                            $enseignants->affichagetotalenseignant($conn);
                            ?>
                            <div class="cardName">Les Enseignants</div>
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
                            $enseignants = new etudiant(null, null, null, null);
                            $enseignants->affichagetotaletudiant($conn);
                            ?>
                        </div>
                        <div class="cardName">Les etudiants</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="school-outline"></ion-icon>
                    </div>
                </div>
                <div class="card">
                    <div>
                        <div class="numbers">
                            <?php
                            $total = new Cours();
                            $total->affichagetotalcour($conn);
                            ?>
                        </div>
                        <div class="cardName">Les cours</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="library-outline"></ion-icon>
                    </div>
                </div>
                <div class="card">
                    <div>
                        <div class="numbers">
                            <?php
                            $categories = new Categorie(null, null);
                            $categories->affichagetotalCategorie($conn);
                            ?>
                            <div class="cardName">Les categories</div>
                        </div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="book-outline"></ion-icon>
                    </div>
                </div>
                <div class="card">
                    <div>
                        <div class="numbers">
                            <?php
                            $tags = new Tag(null);
                            $tags->affichagetotalTag($conn);
                            ?>
                            <div class="cardName">Les tags</div>
                        </div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="pricetags-outline"></ion-icon>
                    </div>
                </div>
            </div>
            <!-- ================ Order Details List ================= -->
            <div class="details">
                <div class="recentOrders">
                    <div class="cardHeader">
                        <h2>Statistics</h2>
                    </div>
                    <div class="statistique">
                        <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200">
                            <canvas id="platformStatsChart"></canvas>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200">
                            <canvas id="platformStatsChart1"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- =========== Scripts =========  -->
    <script>
        var ctx1 = document.getElementById('platformStatsChart').getContext('2d');
        var platformStatsChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Total enseignant', 'Total etudiant', 'Total cour', 'Total categorie', 'total tag'],
                datasets: [{
                    label: 'Platform Stats',
                    data: [<?php
                    $enseignants = new enseignant(null, null, null, null);
                    $enseignants->affichagetotalenseignant($conn);
                    ?>, <?php
                    $etudiants = new etudiant(null, null, null, null);
                    $etudiants->affichagetotaletudiant($conn);
                    ?>,<?php
                    $categories = new $categories(null, null);
                    $categories->affichagetotalCategorie($conn);
                    ?>,<?php
                    $tags = new Tag(null);
                    $tags->affichagetotalTag($conn);
                    ?>
                    ],
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#FF9F40', '#FF5733'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'none',
                    }
                }
            }
        });
        var ctx2 = document.getElementById('platformStatsChart1').getContext('2d');
        var platformStatsChart = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ['Total Users', 'Total Articles', 'Total Categories', 'Total categorie', 'total tag'],
                datasets: [{
                    label: 'Platform Stats',
                    data: [<?php
                    $enseignants = new enseignant(null, null, null, null);
                    $enseignants->affichagetotalenseignant($conn);
                    ?>, <?php
                    $etudiants = new etudiant(null, null, null, null);
                    $etudiants->affichagetotaletudiant($conn);
                    ?>,<?php
                    $categories = new $categories(null, null);
                    $categories->affichagetotalCategorie($conn);
                    ?>,<?php
                    $tags = new Tag(null);
                    $tags->affichagetotalTag($conn);
                    ?>
                    ],
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#FF9F40', '#FF5733'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    </script>
    <script src="assets/js/main.js?v=1"></script>
    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>