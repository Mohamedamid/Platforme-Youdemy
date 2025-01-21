<?php
include_once("../classes/Users.php");
class admin extends User
{
    function __construct($username, $email, $password)
    {
        parent::__construct($username, $email, $password, "admin");
    }
    function affichageEnseignant($conn)
    {
        $sql = "SELECT * FROM user WHERE role = 'Enseignant' and (statut = 'Active' OR statut = 'Disactive')";
        $stmt = $conn->query($sql);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($users as $user) {
            echo '<tr>';
            echo '<td class="id idproduit">' . $user['user_id'] . '</td>';
            echo '<td style="width:150px">' . htmlspecialchars($user['username']) . '</td>';
            echo '<td style="width:150px">' . htmlspecialchars($user['email']) . '</td>';
            echo '<td style="width:100px">' . htmlspecialchars($user['statut']) . '</td>';
            echo '<td>' . htmlspecialchars($user['created_at']) . '</td>';
            if ($user['statut'] == 'Active') {
                echo '<td>
                    <a href="gestionEnseignant.php?idEdit=' . $user['user_id'] . '&statut=Disactive" class="status-link disactive-link">Disactive</a>
                </td>';
            } else {
                echo '<td>
                    <a href="gestionEnseignant.php?idEdit=' . $user['user_id'] . '&statut=Active" class="status-link active-link">Active</a>
                </td>';
            }
            '</td>';
            echo '</tr>';
        }
    }

    function affichageEnseignantVerif($conn)
    {
        $sql = "SELECT * FROM user WHERE role = 'Enseignant' and statut = 'Verification'";
        $stmt = $conn->query($sql);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($users as $user) {
            echo '<tr>';
            echo '<td class="id idproduit">' . $user['user_id'] . '</td>';
            echo '<td style="width:150px">' . htmlspecialchars($user['username']) . '</td>';
            echo '<td style="width:150px">' . htmlspecialchars($user['email']) . '</td>';
            echo '<td style="width:100px">' . htmlspecialchars($user['statut']) . '</td>';
            echo '<td>' . htmlspecialchars($user['created_at']) . '</td>';
            echo '<td>
                    <a href="dashboard.php?idEdit=' . $user['user_id'] . '&statut=Disactive" class="status-link disactive-link">Disactive</a>
                    <a href="dashboard.php?idEdit=' . $user['user_id'] . '&statut=Active" class="status-link active-link">Active</a>
                </td>';
            '</td>';
            echo '</tr>';
        }
    }

    function affichagetotalenseignant($conn)
    {
        $query = "SELECT COUNT(*) AS total_users FROM user WHERE role = 'enseignant' and (statut = 'Active' or statut = 'Disactive')";
        $stmt = $conn->query($query);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalusers = $row['total_users'];
        echo $totalusers;
    }
    function affichagetotalE($conn, $role, $statut)
    {
        $query = "SELECT COUNT(*) AS total_users FROM user WHERE role = :role AND statut = :statut";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        $stmt->bindParam(':statut', $statut, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalusers = $row['total_users'];

        echo $totalusers;
    }

    function affichageEtudiant($conn)
    {
        $sql = "SELECT * FROM user WHERE role = 'Etudiant' AND (statut = 'Active' OR statut = 'Disactive')";
        $stmt = $conn->query($sql);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($users as $user) {
            echo '<tr>';
            echo '<td class="id idproduit">' . htmlspecialchars($user['user_id']) . '</td>';
            echo '<td style="width:150px">' . htmlspecialchars($user['username']) . '</td>';
            echo '<td style="width:150px">' . htmlspecialchars($user['email']) . '</td>';
            echo '<td style="width:100px">' . htmlspecialchars($user['statut']) . '</td>';
            echo '<td>' . htmlspecialchars($user['created_at']) . '</td>';

            if ($user['statut'] == 'Active') {
                echo '<td>
                <a href="gestionEtudiant.php?idEdit=' . $user['user_id'] . '&statut=Disactive" class="status-link disactive-link">Disactive</a>
            </td>';
            } else {
                echo '<td>
                <a href="gestionEtudiant.php?idEdit=' . $user['user_id'] . '&statut=Active" class="status-link active-link">Active</a>
            </td>';
            }
            echo '</tr>';
        }
    }

    function affichagetotaletudiant($conn)
    {
        $query = "SELECT COUNT(*) AS total_users FROM user where role = 'etudiant'";
        $stmt = $conn->query($query);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalusers = $row['total_users'];
        echo $totalusers;
    }

    function updateStatut($conn, $id, $statut)
    {
        $userId = $id;
        $newstatut = $statut;
        $sql = "UPDATE user SET statut = :statut WHERE user_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':statut' => $newstatut,
            ':id' => $userId
        ]);
    }

}

?>