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

        $sql = "INSERT INTO user (username, email, password, role) VALUES (:name, :email, :password, :role)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $this->username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $this->password, PDO::PARAM_STR);
        $stmt->bindParam(':role', $this->role, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $_SESSION['userId'] = $conn->lastInsertId();
            $_SESSION['user_email'] = $this->email;
            $_SESSION['role'] = $this->role;
            header("Location: ../index.php");
            exit();
        } else {
            header("location:../login.php?msg=registration_failed");
            exit();
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
}

?>