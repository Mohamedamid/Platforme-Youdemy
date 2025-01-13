<?php 

"CREATE DATABASE youdemy;
USE youdemy;

CREATE TABLE Utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    statut ENUM('Active', 'Desactive', 'deleted'),
    role ENUM('Enseignant', 'Administrateur', 'Etudiant') NOT NULL
);

CREATE TABLE Categorie (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

CREATE TABLE Cours (
    cours_id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    contenu TEXT NOT NULL,
    categorie_id INT,
    FOREIGN KEY (categorie_id) REFERENCES Categorie(id_categorie)
);

CREATE TABLE Tags (
    id_tag INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

CREATE TABLE Cours_Tags (
    cours_id INT,
    tag_id INT,
    FOREIGN KEY (cours_id) REFERENCES Cours(cours_id),
    FOREIGN KEY (tag_id) REFERENCES Tags(id_tag)
);

CREATE TABLE Inscription (
    id INT,
    cours_id INT,
    FOREIGN KEY (id) REFERENCES Utilisateur(id),
    FOREIGN KEY (cours_id) REFERENCES Cours(cours_id)
);"

?>
