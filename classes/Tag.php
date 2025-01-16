<?php 

class Tag{
    protected $name;

    function __construct($name){
        $this->name = $name;
    }

    function AjouterTag($conn){
        $sql = "INSERT INTO tag (name) VALUES (:name)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
        $stmt->execute();
    }
}
?>