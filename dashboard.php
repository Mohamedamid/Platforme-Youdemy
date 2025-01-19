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
        #platformStatsChart,
        #platformStatsChart1 {
            width: 100% !important;
            height: 300px !important;
        }

        .statistique {
            display: flex;
            justify-content: space-around;
            align-items: center;
            gap: 20px;
        }

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
                <!-- <li>
                    <a href="gestionCour.php">
                        <span class="icon">
                            <ion-icon name="library-outline"></ion-icon>
                        </span>
                        <span class="title">Gestion des cours</span>
                    </a>
                </li> -->
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
            <!-- ======================= Cards ================== -->
            <div class="cardBox">
                <div class="card">
                    <div>
                        <div class="numbers">
                            <?php
                            $enseignants = new Admin(null, null, null);
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
                            $enseignants = new Admin(null, null, null);
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
                            $total = new Cours(null, null, null, null);
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
            <div class="details">
                <div class="cardHeader">
                    <h2>Les Enseignants</h2>
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
                        $p = new Admin(null, null, null);
                        $p->affichageEnseignantVerif($conn);
                        ?>
                    </tbody>
                </table>
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
                        $enseignants = new Admin(null, null, null);
                        $enseignants->affichagetotalenseignant($conn);
                        ?>, <?php
                        $etudiants = new Admin(null, null, null);
                        $etudiants->affichagetotaletudiant($conn);
                        ?>, <?php
                        $cours = new Cours(null, null, null, null);
                        $cours->affichagetotalcour($conn);
                        ?>, <?php
                        $categories = new Categorie(null, null);
                        $categories->affichagetotalCategorie($conn);
                        ?>, <?php
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
                    labels: ['Total enseignant', 'Total etudiant', 'Total cour', 'Total categorie', 'total tag'],
                    datasets: [{
                        label: 'Platform Stats',
                        data: [<?php
                        $enseignants = new Admin(null, null, null);
                        $enseignants->affichagetotalenseignant($conn);
                        ?>, <?php
                        $etudiants = new Admin(null, null, null);
                        $etudiants->affichagetotaletudiant($conn);
                        ?>, <?php
                        $cours = new Cours(null, null, null, null);
                        $cours->affichagetotalcour($conn);
                        ?>, <?php
                        $categories = new Categorie(null, null);
                        $categories->affichagetotalCategorie($conn);
                        ?>, <?php
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

        <script src="./assets/js/script.js?v=1"></script>
        <!-- ====== ionicons ======= -->
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>