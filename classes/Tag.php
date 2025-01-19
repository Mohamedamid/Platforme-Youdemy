<?php

class Tag
{
    protected $name;

    function __construct($name)
    {
        $this->name = $name;
    }

    function affichagetotalTag($conn)
    {
        $query = "SELECT COUNT(*) AS total_tag FROM tag";
        $stmt = $conn->query($query);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $totaltags = $row['total_tag'];
        echo $totaltags;
    }

    function affichageTag($conn)
    {
        $sql = "SELECT * FROM tag";
        $stmt = $conn->query($sql);
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($categories as $categorie) {
            echo '<tr>';
            echo '<td class="id idproduit">' . $categorie['tag_id'] . '</td>';
            echo '<td style="width:150px">' . htmlspecialchars($categorie['name']) . '</td>';
            echo '<td class="action-links">
                <a href="gestionTag.php?Edit=' . $categorie["tag_id"] . '" class="edit">Edit</a>
                <a href="gestionTag.php?Delet=' . $categorie["tag_id"] . '" class="delete">Delete</a>
            </td>';
            echo '</tr>';
        }
    }

    function AjouterTag($conn)
    {
        $sql = "INSERT INTO tag (name) VALUES (:name)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
        $stmt->execute();
    }

    function editTag($conn, $id)
    {
        $id = $_GET["Edit"];
        $updateQuery = "UPDATE tag SET name = :name WHERE tag_id = :id ";
        $stmt = $conn->prepare($updateQuery);
        $stmt->execute([
            ':name' => $this->name,
            ':id' => $id
        ]);
    }

    function deletTag($conn, $id)
    {
        $query = "DELETE FROM tag WHERE tag_id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
?>