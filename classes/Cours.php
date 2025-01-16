<?php

    class Cours{


        function affichagetotalcour($conn){
            $query = "SELECT COUNT(*) AS total_cours FROM course";
            $stmt = $conn->query($query);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $totalcours = $row['total_cours'];
            echo $totalcours;
        }


    }

?>