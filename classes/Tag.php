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

    function affichagetotalTag($conn){
        $query = "SELECT COUNT(*) AS total_tag FROM tag";
        $stmt = $conn->query($query);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $totaltags = $row['total_tag'];
        echo $totaltags;
    }
}
?>