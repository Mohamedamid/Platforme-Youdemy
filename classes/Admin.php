<?php
include_once("./classes/Users.php");
class admin extends User{
    function __construct($username, $email, $password)
    {
        parent::__construct($username, $email, $password, "admin");
    }
    
}

?>