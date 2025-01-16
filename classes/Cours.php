<?php

class Cours
{
    private $title;
    private $description;
    private $url;
    private $categorie;

    function __construct($title, $description, $url, $categorie)
    {
        $this->title = $title;
        $this->description = $description;
        $this->url = $url;
        $this->categorie = $categorie;
    }

    function AjouterCours($conn)
    {$c = 1;
        $sql = "INSERT INTO course (title, description, content_url, categorie_id) VALUES (:title, :description, :content_url, :categorie)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':title', $this->title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $this->description, PDO::PARAM_STR);
        $stmt->bindParam(':content_url', $this->url, PDO::PARAM_STR);
        $stmt->bindParam(':categorie',$c, PDO::PARAM_STR);
        $stmt->execute();
    }

    function affichageCours($conn)
    {
        $sql = "SELECT * FROM course";
        $stmt = $conn->query($sql);
        $course = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($course as $cour) {
            echo '<tr>';
            echo '<td class="id">' . $cour['course_id'] . '</td>';
            echo '<td style="width:150px">' . htmlspecialchars($cour['title']) . '</td>';
            echo '<td>' . htmlspecialchars($cour['description']) . '</td>';
            echo '<td style="width:100px">' . htmlspecialchars($cour['content_url']) . '</td>';
            echo '<td>' . htmlspecialchars($cour['created_at']) . '</td>';
            echo '<td class="idproduit">
                    <a href="gCour.php?Edit=' . $cour["course_id"] . '" class="edit">Edit</a>
                    <a href="gCour.php?Delet=' . $cour["course_id"] . '" class="delete">Delet</a>
                </td>';
            echo '</tr>';
        }
    }

    function affichagetotalcour($conn)
    {
        $query = "SELECT COUNT(*) AS total_cours FROM course";
        $stmt = $conn->query($query);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalcours = $row['total_cours'];
        echo $totalcours;
    }


}

?>