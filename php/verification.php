<?php

require_once("../config/config.php");
require_once("../classes/Enseignant.php");
require_once("../classes/Etudiant.php");

session_start();

if (isset($_POST["login"])) {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
  
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../login.php?error=invalid_email");
        exit();
    }
  
    $password = $_POST["password"];

    $Acount = new User(null ,$email ,$password ,null);
    $Acount->login($conn);

    
} elseif (isset($_POST["sign_up"])) {
    $name = $_POST["name"];
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $role = $_POST["role"];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("location:../login.php?msg=invalid_email");
        exit();
    }

    if ($password !== $confirm_password) {
        header("location:../login.php?msg=password_mismatch");
        exit();
    }
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    if($role == "Etudiant"){
        $Acount = new etudiant($name ,$email ,$hashedPassword ,"Etudiant");
        $Acount->addEtudiant($conn);
    }elseif($role == "Enseignant"){
        $Acount = new enseignant($name ,$email ,$hashedPassword ,"Enseignant");
        $Acount->addEnseignant($conn);
    }
}

?>