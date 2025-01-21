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

    function AjouterCours($conn, $tags, $type)
    {

        $sql = "INSERT INTO course (title, description, content_url, content_type, category_id) VALUES (:title, :description, :content_url, :type, :categorie)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':title', $this->title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $this->description, PDO::PARAM_STR);
        $stmt->bindParam(':content_url', $this->url, PDO::PARAM_STR);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':categorie', $this->categorie, PDO::PARAM_INT);
        $stmt->execute();

        $idCour = $conn->lastInsertId();

        if (!empty($tags)) {
            $values = [];
            foreach ($tags as $idTag) {
                $values[] = "($idCour, $idTag)";
            }

            $sql1 = "INSERT INTO course_tag (course_id, tag_id) VALUES " . implode(", ", $values);
            $conn->exec($sql1);
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

    function affichagetotal($conn)
    {
        $query = "SELECT COUNT(*) AS total_cours FROM course";
        $stmt = $conn->query($query);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalcours = $row['total_cours'];
        return $totalcours;
    }

    function affichagetotalcour($conn)
    {
        $query = "SELECT COUNT(*) AS total_cours FROM course";
        $stmt = $conn->query($query);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalcours = $row['total_cours'];
        echo $totalcours;
    }
    function editCour($conn, $id, $tags)
    {

        $sql = "UPDATE course 
            SET title = :title, description = :description, content_url = :content_url, category_id = :categorie 
            WHERE course_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':title', $this->title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $this->description, PDO::PARAM_STR);
        $stmt->bindParam(':content_url', $this->url, PDO::PARAM_STR);
        $stmt->bindParam(':categorie', $this->categorie, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();


        $sqlDeleteTags = "DELETE FROM course_tag WHERE course_id = :id";
        $stmtDeleteTags = $conn->prepare($sqlDeleteTags);
        $stmtDeleteTags->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtDeleteTags->execute();


        if (isset($tags) && is_array($tags)) {
            $values = [];
            foreach ($tags as $tagId) {
                $values[] = "($id, $tagId)";
            }
            if (!empty($values)) {
                $sqlInsertTags = "INSERT INTO course_tag (course_id, tag_id) VALUES " . implode(", ", $values);
                $conn->exec($sqlInsertTags);
            }
        }
    }

    function pagenation($conn, $ofset)
    {

        $ofset = (int) $ofset;

        $sql = "
    SELECT course.*, GROUP_CONCAT(tag.name) AS tags
        FROM course
        LEFT JOIN course_tag ON course.course_id = course_tag.course_id
        LEFT JOIN tag ON course_tag.tag_id = tag.tag_id
        GROUP BY course.course_id
        LIMIT 3 OFFSET :ofset";


        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ofset', $ofset, PDO::PARAM_INT);
        $stmt->execute();

        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($courses) {
            foreach ($courses as $course) {
                $categorySql = "SELECT name FROM category WHERE category_id = :category_id";
                $categoryStmt = $conn->prepare($categorySql);
                $categoryStmt->bindParam(':category_id', $course['category_id'], PDO::PARAM_INT);
                $categoryStmt->execute();
                $category = $categoryStmt->fetch(PDO::FETCH_ASSOC);

                if ($category) {
                    echo '<div class="course-card">';


                    if (!empty($course['content_url'])) {
                        if ($course['content_type'] == 'video') {
                            $videoUrl = htmlspecialchars($course['content_url']);
                            if (strpos($videoUrl, 'youtube.com') !== false || strpos($videoUrl, 'youtu.be') !== false) {
                                $videoId = '';

                                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $videoUrl, $matches)) {
                                    $videoId = $matches[1];
                                }
                                if ($videoId) {
                                    if (isset($_SESSION["user_email"])) {
                                    echo '<div class="content-container">
                                    <div class="video-container">
                                        <iframe src="https://www.youtube.com/embed/' . $videoId . '" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                    </div>';
                                }
                                }
                            } else {

                                if (isset($_SESSION["user_email"])) {
                                echo '<div class="content-container">

                                    <div class="video-container">
                                    <video controls>
                                        <source src="' . $videoUrl . '" type="video/mp4">
                                        <source src="' . $videoUrl . '" type="video/webm">
                                        Votre navigateur ne prend pas en charge la lecture de vidéos.
                                    </video>
                                </div>
                                </div>';
                            }
                            }
                        } elseif ($course['content_type'] == 'pdf') {
                            if(isset($_SESSION["user_email"])){
                            echo '<div class="content-container content-container1">
                            <div class="pdf-preview">
                                <object data="' . $course['content_url'] . '" type="application/pdf" width="100%" height="219px">
                                    <p>Le navigateur n\'a pas pu afficher le fichier. Vous pouvez le télécharger depuis <a href="' . $course['content_url'] . '">ici</a>.</p>
                                </object>
                            </div>
                        </div>';
                            }
                        } else {

                            echo '<div class="content-container">
                            <div class="video-fallback">Contenu non pris en charge</div>
                          </div>';
                        }
                    } else {

                        echo '<div class="content-container">
                        <div class="video-fallback">Contenu non disponible</div>
                      </div>';
                    }

                    echo '<div class="course-content">';
                    echo '<h2 class="course-title">' . htmlspecialchars($course['title'] ?? 'Sans titre') . '</h2>';
                    echo '<p class="course-description">' . htmlspecialchars($course['description'] ?? 'Pas de détails') . '</p>';
                    echo '<span class="course-category">' . htmlspecialchars($category['name'] ?? 'Non défini') . '</span>';


                    if (!empty($course['tags'])) {
                        $tags = explode(",", $course['tags']);
                        echo '<div class="tags-container">';
                        foreach ($tags as $tag) {
                            echo '<span class="tag">' . htmlspecialchars($tag) . '</span>';
                        }
                        echo '</div>';
                    } else {
                        echo '<div class="tags-container"><span class="tag">Pas de tags</span></div>';
                    }


                    echo '<form action="" method="post" class="enroll-form">
                    <input type="hidden" name="id" value="' . $course["course_id"] . '">
                    <button class="enroll-btn" name="inscrire">S\'inscrire</button>
                </form>';
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo '<div class="no-content">La catégorie n\'existe pas pour le cours : ' . htmlspecialchars($course['title']) . '</div>';
                }
            }
        } else {
            echo '<div class="no-content">Aucun cours disponible à afficher.</div>';
        }
    }

    function pagination1($conn, $ofset, $userId)
{
    $limit = 3;  // Nombre de cours par page
    $offset = $ofset;  // Calculer l'offset

    $sql = "SELECT c.*, e.* 
            FROM course c
            JOIN enrollment e ON c.course_id = e.course_id
            WHERE e.user_id = :user_id
            LIMIT :limit OFFSET :offset";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($courses) {
        foreach ($courses as $course) {
            // Récupérer le nom de la catégorie pour chaque cours
            $categorySql = "SELECT name FROM category WHERE category_id = :category_id";
            $categoryStmt = $conn->prepare($categorySql);
            $categoryStmt->bindParam(':category_id', $course['category_id'], PDO::PARAM_INT);
            $categoryStmt->execute();
            $category = $categoryStmt->fetch(PDO::FETCH_ASSOC);

            if ($category) {
                echo '<div class="course-card">';
                
                // Vérifier et afficher le contenu (vidéo, PDF, ou autre)
                if (!empty($course['content_url'])) {
                    if ($course['content_type'] == 'video') {
                        $videoUrl = htmlspecialchars($course['content_url']);
                        
                        // Si le lien est une vidéo YouTube
                        if (strpos($videoUrl, 'youtube.com') !== false || strpos($videoUrl, 'youtu.be') !== false) {
                            $videoId = '';
                            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $videoUrl, $matches)) {
                                $videoId = $matches[1];
                            }
                            if ($videoId) {
                                echo '<div class="content-container">';
                                if ($_SESSION["user_email"]) {
                                    echo '<div class="video-container">
                                            <iframe src="https://www.youtube.com/embed/' . $videoId . '" frameborder="0" allowfullscreen></iframe>
                                          </div>';
                                }
                                echo '</div>';
                            }
                        } else {
                            // Si ce n'est pas une vidéo YouTube, afficher une vidéo ordinaire
                            echo '<div class="content-container">
                                    <div class="video-container">
                                        <video controls>
                                            <source src="' . $videoUrl . '" type="video/mp4">
                                            <source src="' . $videoUrl . '" type="video/webm">
                                            Votre navigateur ne prend pas en charge la lecture de vidéos.
                                        </video>
                                      </div>
                                  </div>';
                        }
                    } elseif ($course['content_type'] == 'pdf') {
                        // Si le contenu est un fichier PDF
                        echo '<div class="content-container content-container1">
                                <div class="pdf-preview">
                                    <object data="' . $course['content_url'] . '" type="application/pdf" width="100%" height="219px">
                                        <p>Le navigateur n\'a pas pu afficher le fichier. Vous pouvez le télécharger depuis <a href="' . $course['content_url'] . '">ici</a>.</p>
                                    </object>
                                </div>
                              </div>';
                    } else {
                        // Si le type de contenu n'est pas pris en charge
                        echo '<div class="content-container">
                                <div class="video-fallback">Contenu non pris en charge</div>
                              </div>';
                    }
                } else {
                    echo '<div class="content-container">
                            <div class="video-fallback">Contenu non disponible</div>
                          </div>';
                }

                // Afficher les informations du cours (titre, description, catégorie)
                echo '<div class="course-content">';
                echo '<h2 class="course-title">' . htmlspecialchars($course['title'] ?? 'Sans titre') . '</h2>';
                echo '<p class="course-description">' . htmlspecialchars($course['description'] ?? 'Pas de détails') . '</p>';
                echo '<span class="course-category">' . htmlspecialchars($category['name'] ?? 'Non défini') . '</span>';

                // Afficher les tags (s’il y en a)
                if (!empty($course['tags'])) {
                    $tags = explode(",", $course['tags']);
                    echo '<div class="tags-container">';
                    foreach ($tags as $tag) {
                        echo '<span class="tag">' . htmlspecialchars($tag) . '</span>';
                    }
                    echo '</div>';
                } else {
                    echo '<div class="tags-container"><span class="tag">Pas de tags</span></div>';
                }

                
                echo '<form action="" method="post" class="enroll-form">
                        <input type="hidden" name="id" value="' . $course["course_id"] . '">
                        <button class="enroll-btn" name="inscrire">S\'inscrire</button>
                    </form>';
                echo '</div>';
                echo '</div>';
            } else {
                echo '<div class="no-content">La catégorie n\'existe pas pour le cours : ' . htmlspecialchars($course['title']) . '</div>';
            }
        }
    } else {
        echo '<div class="no-content">Aucun cours disponible à afficher.</div>';
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