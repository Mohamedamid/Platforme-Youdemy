<?php

class User{
    protected $username;
    protected $email;
    protected $password;
    protected $role;
    // protected $statut;

    function __construct($username ,$email ,$password ,$role){
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    function login($conn)
    {
        $sql = "SELECT user_id, email, password, role FROM user WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':email' => $this->email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            if (password_verify($this->password, $user['password'])) {

                $_SESSION['userId'] = $user["user_id"];
                $_SESSION['user_email'] = $user['email'];

                if ($user['role'] == 'Admin') {
                    echo "Admin";
                    header("Location: ../dashboard.php");
                } elseif ($user['role'] == 'Etudiant') {
                    header("Location: ../home.php");
                } elseif ($user['role'] == 'Enseignant') {
                    echo "Enseignant";
                    header("Location: ../enseignant.php");
                } else {
                    header("Location: ../login.php?error=unknown_role");
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