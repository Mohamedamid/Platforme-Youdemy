<?php
include_once("Users.php");

class etudiant extends User
{
    function __construct($username, $email, $password, $role)
    {
        parent::__construct($username, $email, $password, $role);
    }
    function addEtudiant($conn)
    {
        $sql = "SELECT email FROM user WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header("location:../login.php?msg=email_exists");
            exit();
        }
        $statut = 'Active';
        $sql = "INSERT INTO user (username, email, password, role ,statut) VALUES (:name, :email, :password, :role, :statut)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $this->username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $this->password, PDO::PARAM_STR);
        $stmt->bindParam(':role', $this->role, PDO::PARAM_STR);
        $stmt->bindParam(':statut', $statut, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $_SESSION['userId'] = $conn->lastInsertId();
            $_SESSION['user_email'] = $this->email;
            $_SESSION['role'] = $this->role;
            header("Location: ../home.php");
            exit();
        } else {
            header("location:../login.php?msg=registration_failed");
            exit();
        }
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