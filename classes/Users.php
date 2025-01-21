<?php

class User
{
    protected $username;
    protected $email;
    protected $password;
    protected $role;
    
    function __construct($username, $email, $password, $role)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    function login($conn)
    {
        $sql = "SELECT user_id, email, password, role ,statut FROM user WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':email' => $this->email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            if (password_verify($this->password, $user['password'])) {

                $_SESSION['userId'] = $user["user_id"];
                $_SESSION['user_email'] = $user['email'];

                if ($user['role'] == 'Admin') {

                    header("Location: ../Admin/dashboard.php");
                    exit();
                } elseif ($user['role'] == 'Etudiant') {
                    header("Location: ../home.php");
                    exit();
                } elseif ($user['role'] == 'Enseignant') {
                    if ($user['statut'] == 'Active') {
                        header("Location: ../Enseignant/index.php");
                        exit();
                    } elseif ($user['statut'] == 'Verification') {
                        header("Location: ../Enseignant/PageVerification.php");
                        exit();
                    } elseif ($user['statut'] == 'Disactive') {
                        header("Location: ../Enseignant/PageBloque.php");
                        exit();
                    }
                }
                exit();
            } else {
                header("Location: ../login.php?error=incorrect_password");
                exit();
            }
        } else {
            header("Location: ../login.php?error=user_not_found");
            exit();
        }
    }
}
?>