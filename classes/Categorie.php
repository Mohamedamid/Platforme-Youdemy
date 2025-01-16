<?php

class Categorie{
    protected $name;
    protected $description;

    function __construct($name ,$description){
        $this->name = $name;
        $this->description = $description;
    }

    function AjouterCategorie($conn){
        $sql = "INSERT INTO category (name ,description) VALUES (:name, :description)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $this->description, PDO::PARAM_STR);
        $stmt->execute();
    }
}

?>