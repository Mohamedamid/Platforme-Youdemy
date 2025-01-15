<?php
// Configuration de la base de données
class Database {
    private $host = "localhost";
    private $db_name = "youdemy_a";
    private $username = "root";
    private $password = "";
    private $conn = null;

    // Connexion à la base de données
    public function getConnection() {
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch(PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
            return null;
        }
    }
}

// Classe User pour gérer les utilisateurs
class User {
    private $conn;
    private $table = "user";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Créer un nouvel utilisateur
    public function create($username, $email, $password, $role) {
        $query = "INSERT INTO " . $this->table . " 
                 (username, email, password, role) 
                 VALUES (:username, :email, :password, :role)";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            // Hash du mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $hashed_password);
            $stmt->bindParam(":role", $role);

            return $stmt->execute();
        } catch(PDOException $e) {
            echo "Erreur lors de la création : " . $e->getMessage();
            return false;
        }
    }

    // Lire un utilisateur
    public function read($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :id";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Erreur lors de la lecture : " . $e->getMessage();
            return false;
        }
    }

    // Mettre à jour un utilisateur
    public function update($id, $username, $email, $role) {
        $query = "UPDATE " . $this->table . "
                 SET username = :username, 
                     email = :email, 
                     role = :role
                 WHERE user_id = :id";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":role", $role);
            $stmt->bindParam(":id", $id);

            return $stmt->execute();
        } catch(PDOException $e) {
            echo "Erreur lors de la mise à jour : " . $e->getMessage();
            return false;
        }
    }

    // Supprimer un utilisateur
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE user_id = :id";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch(PDOException $e) {
            echo "Erreur lors de la suppression : " . $e->getMessage();
            return false;
        }
    }
}

// Classe Course pour gérer les cours
class Course {
    private $conn;
    private $table = "course";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Créer un nouveau cours
    public function create($title, $description, $content_url, $category_id) {
        $query = "INSERT INTO " . $this->table . " 
                 (title, description, content_url, category_id) 
                 VALUES (:title, :description, :content_url, :category_id)";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":content_url", $content_url);
            $stmt->bindParam(":category_id", $category_id);

            return $stmt->execute();
        } catch(PDOException $e) {
            echo "Erreur lors de la création du cours : " . $e->getMessage();
            return false;
        }
    }

    // Lire un cours avec sa catégorie
    public function read($id) {
        $query = "SELECT c.*, cat.name as category_name 
                 FROM " . $this->table . " c
                 LEFT JOIN category cat ON c.category_id = cat.category_id
                 WHERE c.course_id = :id";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Erreur lors de la lecture du cours : " . $e->getMessage();
            return false;
        }
    }

    // Mettre à jour un cours
    public function update($id, $title, $description, $content_url, $category_id) {
        $query = "UPDATE " . $this->table . "
                 SET title = :title, 
                     description = :description,
                     content_url = :content_url,
                     category_id = :category_id
                 WHERE course_id = :id";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":content_url", $content_url);
            $stmt->bindParam(":category_id", $category_id);
            $stmt->bindParam(":id", $id);

            return $stmt->execute();
        } catch(PDOException $e) {
            echo "Erreur lors de la mise à jour du cours : " . $e->getMessage();
            return false;
        }
    }

    // Supprimer un cours
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE course_id = :id";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch(PDOException $e) {
            echo "Erreur lors de la suppression du cours : " . $e->getMessage();
            return false;
        }
    }

    // Ajouter un tag à un cours
    public function addTag($course_id, $tag_id) {
        $query = "INSERT INTO Course_Tag (course_id, tag_id) 
                 VALUES (:course_id, :tag_id)";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":course_id", $course_id);
            $stmt->bindParam(":tag_id", $tag_id);
            return $stmt->execute();
        } catch(PDOException $e) {
            echo "Erreur lors de l'ajout du tag : " . $e->getMessage();
            return false;
        }
    }
}

// Classe Enrollment pour gérer les inscriptions
class Enrollment {
    private $conn;
    private $table = "enrollment";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Inscrire un étudiant à un cours
    public function enroll($user_id, $course_id) {
        $query = "INSERT INTO " . $this->table . " 
                 (user_id, course_id) 
                 VALUES (:user_id, :course_id)";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":user_id", $user_id);
            $stmt->bindParam(":course_id", $course_id);

            return $stmt->execute();
        } catch(PDOException $e) {
            echo "Erreur lors de l'inscription : " . $e->getMessage();
            return false;
        }
    }

    // Vérifier si un étudiant est inscrit à un cours
    public function isEnrolled($user_id, $course_id) {
        $query = "SELECT COUNT(*) FROM " . $this->table . " 
                 WHERE user_id = :user_id AND course_id = :course_id";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->bindParam(":course_id", $course_id);
            $stmt->execute();
            
            return $stmt->fetchColumn() > 0;
        } catch(PDOException $e) {
            echo "Erreur lors de la vérification : " . $e->getMessage();
            return false;
        }
    }
}

// Exemple d'utilisation :
try {
    // Création d'une instance de la base de données
    $database = new Database();
    $db = $database->getConnection();

    // Création d'une instance de User
    $user = new User($db);
    
    // Création d'une instance de Course
    $course = new Course($db);
    
    // Création d'une instance de Enrollment
    $enrollment = new Enrollment($db);

    // Exemple de création d'un utilisateur
    $user->create("John Doe", "john@example.com", "password123", "Etudiant");
    
    // Exemple de création d'un cours
    $course->create("Introduction à PHP", "Un cours complet sur PHP", "url/to/content", 1);
    
    // Exemple d'inscription à un cours
    $enrollment->enroll(1, 1);
    
} catch(Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>