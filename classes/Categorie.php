<?php

class Categorie
{
    protected $name;
    protected $description;

    function __construct($name, $description)
    {
        $this->name = $name;
        $this->description = $description;
    }
    function affichageCategorie($conn)
    {
        $sql = "SELECT * FROM Category";
        $stmt = $conn->query($sql);
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($categories as $categorie) {
            echo '<tr>';
            echo '<td class="id idproduit">' . $categorie['category_id'] . '</td>';
            echo '<td style="width:150px">' . htmlspecialchars($categorie['name']) . '</td>';
            echo '<td style="width:150px">' . htmlspecialchars($categorie['description']) . '</td>';
            echo '<td>
                <a href="gestionCategorie.php?Edit=' . $categorie["category_id"] . '" class="edit">Edit</a>
                <a href="gestionCategorie.php?Delet=' . $categorie["category_id"] . '" class="delete">Delet</a>
            </td>';
            echo '</tr>';
        }
    }
    function affichagetotalCategorie($conn)
    {
        $query = "SELECT COUNT(*) AS total_categorie FROM category";
        $stmt = $conn->query($query);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalcategories = $row['total_categorie'];
        echo $totalcategories;
    }

    function AjouterCategorie($conn)
    {
        $sql = "INSERT INTO category (name, description) VALUES (:name, :description)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $this->description, PDO::PARAM_STR);
        $stmt->execute();
    }


    function editCategorie($conn, $id)
    {
        $id = $_GET["Edit"];
        $updateQuery = "UPDATE category SET name = :name,description = :description WHERE category_id = :id ";
        $stmt = $conn->prepare($updateQuery);
        $stmt->execute([
            ':name' => $this->name,
            ':description' => $this->description,
            ':id' => $id
        ]);
    }

    function deletCategorie($conn, $id)
    {
        $query = "DELETE FROM category WHERE category_id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}

?>