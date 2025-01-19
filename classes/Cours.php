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

    function AjouterCours($conn, $tags ,$type)
{
    // إدخال الدورة في جدول الـ course
    $sql = "INSERT INTO course (title, description, content_url, content_type, category_id) VALUES (:title, :description, :content_url, :type, :categorie)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':title', $this->title, PDO::PARAM_STR);
    $stmt->bindParam(':description', $this->description, PDO::PARAM_STR);
    $stmt->bindParam(':content_url', $this->url, PDO::PARAM_STR);
    $stmt->bindParam(':type', $type, PDO::PARAM_STR);
    $stmt->bindParam(':categorie', $this->categorie, PDO::PARAM_INT);
    $stmt->execute();
    
    // الحصول على الـ course_id الذي تم إدخاله
    $idCour = $conn->lastInsertId();
    
    // إعداد الإدخال الجماعي لربط الـ tags بالدورة
    if (!empty($tags)) {
        $values = [];
        foreach ($tags as $idTag) {
            $values[] = "($idCour, $idTag)";
        }
        
        $sql1 = "INSERT INTO course_tag (course_id, tag_id) VALUES " . implode(", ", $values);
        $conn->exec($sql1);  // تنفيذ الإدخال الجماعي
    }
}


    function affichageCours($conn)
    {
        $sql = "SELECT course.*, category.name AS name 
        FROM course 
        INNER JOIN category ON course.category_id = category.category_id";

        $stmt = $conn->query($sql);
        $course = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($course as $cour) {
            echo '<tr>';
            echo '<td class="id">' . $cour['course_id'] . '</td>';
            echo '<td style="width:150px">' . htmlspecialchars($cour['title']) . '</td>';
            echo '<td style="width:800px !important">' . htmlspecialchars($cour['description']) . '</td>';
            echo '<td style="width:400px !important">' . htmlspecialchars($cour['content_url']) . '</td>';
            echo '<td>' . htmlspecialchars($cour['content_type']) . '</td>';
            echo '<td style="width:150px !important">' . htmlspecialchars($cour['name']) . '</td>';
            echo '<td style="width:180px !important">' . htmlspecialchars($cour['created_at']) . '</td>';
            echo '<td class="idproduit action-buttons" style="width:150px !important">
                    <a href="gestionCour.php?Edit=' . $cour["course_id"] . '" class="edit">Edit</a>
                    <a href="gestionCour.php?Delet=' . $cour["course_id"] . '" class="delete">Delet</a>
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
    function editCour($conn, $id ,$tags)
{
    // 1. تحديث بيانات الدورة في جدول course
    $sql = "UPDATE course 
            SET title = :title, description = :description, content_url = :content_url, category_id = :categorie 
            WHERE course_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':title', $this->title, PDO::PARAM_STR);
    $stmt->bindParam(':description', $this->description, PDO::PARAM_STR);
    $stmt->bindParam(':content_url', $this->url, PDO::PARAM_STR);
    $stmt->bindParam(':categorie', $this->categorie, PDO::PARAM_INT);  // تأكد من النوع المناسب
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    // 2. حذف التاجات القديمة المرتبطة بالدورة
    $sqlDeleteTags = "DELETE FROM course_tag WHERE course_id = :id";
    $stmtDeleteTags = $conn->prepare($sqlDeleteTags);
    $stmtDeleteTags->bindParam(':id', $id, PDO::PARAM_INT);
    $stmtDeleteTags->execute();
    
    // 3. إضافة التاجات الجديدة إلى جدول course_tag
    if (isset($tags) && is_array($tags)) {
        $values = [];
        foreach ($tags as $tagId) {
            $values[] = "($id, $tagId)";
        }
        if (!empty($values)) {
            $sqlInsertTags = "INSERT INTO course_tag (course_id, tag_id) VALUES " . implode(", ", $values);
            $conn->exec($sqlInsertTags);  // تنفيذ الاستعلام لإدخال التاجات الجديدة
        }
    }
}

function deletCour($conn, $id)
{
    $sql = "DELETE FROM course WHERE course_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}


}

?>