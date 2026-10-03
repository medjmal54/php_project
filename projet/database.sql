-- Database Creation
CREATE DATABASE IF NOT EXISTS randonnee_tunisie CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE randonnee_tunisie;

-- User Table
CREATE TABLE IF NOT EXISTS user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    telephone VARCHAR(20),
    image_profile VARCHAR(255)
) ENGINE=InnoDB;

-- Bus Table
CREATE TABLE IF NOT EXISTS bus (
    id_bus INT AUTO_INCREMENT PRIMARY KEY,
    immat_bus VARCHAR(50) NOT NULL,
    nb_places INT NOT NULL,
    chauffeur VARCHAR(100) NOT NULL,
    tel_chauff VARCHAR(20) NOT NULL
) ENGINE=InnoDB;

-- Randonnee Table
CREATE TABLE IF NOT EXISTS randonnee (
    id_randonnee INT AUTO_INCREMENT PRIMARY KEY,
    categorie VARCHAR(50) NOT NULL,
    titre VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    date_debut DATETIME NOT NULL,
    date_fin DATETIME NOT NULL,
    region VARCHAR(100) NOT NULL,
    distance FLOAT NOT NULL,
    difficulte ENUM('Facile', 'Moyen', 'Difficile') NOT NULL,
    capacite INT NOT NULL,
    image VARCHAR(255),
    id_bus INT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('Prévue', 'En cours', 'Terminée', 'Annulée') DEFAULT 'Prévue',
    prix DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_bus) REFERENCES bus(id_bus) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Participation Table
CREATE TABLE IF NOT EXISTS participation (
    id_participation INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_randonnee INT NOT NULL,
    statut ENUM('en attente', 'acceptée', 'refusée') DEFAULT 'en attente',
    is_seen_by_user BOOLEAN DEFAULT FALSE,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_randonnee) REFERENCES randonnee(id_randonnee) ON DELETE CASCADE,
    UNIQUE (id_user, id_randonnee)
) ENGINE=InnoDB;

-- Favorites Table
CREATE TABLE IF NOT EXISTS favoris (
    id_user INT NOT NULL,
    id_randonnee INT NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_user, id_randonnee),
    FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_randonnee) REFERENCES randonnee(id_randonnee) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Sample Data (Admin Account: admin@randonnee.tn / password: password)
INSERT INTO user (nom, email, password, role, telephone) VALUES 
('Admin', 'admin@randonnee.tn', '$2y$10$W2Yk9GvJmY.V9p9z9z9z9eX2yX2yX2yX2yX2yX2yX2yX2yX2yX2y', 'admin', '22112233');

-- Sample Bus
INSERT INTO bus (immat_bus, nb_places, chauffeur, tel_chauff) VALUES 
('123 TUN 1234', 25, 'Salem Ben Ali', '55443322');

-- Sample Randonnee
INSERT INTO randonnee (categorie, titre, description, date_debut, date_fin, region, distance, difficulte, capacite, prix, id_bus) VALUES 
('Montagne', 'Djebel Zaghouan', 'Exploration du temple des eaux et randonnée vers le sommet.', '2026-04-10 07:00:00', '2026-04-10 18:00:00', 'Zaghouan', 12.5, 'Moyen', 20, 45.00, 1);
