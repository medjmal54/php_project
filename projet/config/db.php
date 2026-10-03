<?php
$host = 'localhost';
$dbname = 'randonnee_tunisie';
$user = 'root';
$pass = '';

try {
    // Création de la connexion PDO
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    
} catch (PDOException $e) {
    // Si la connexion échoue
    die("Erreur de connexion : " . $e->getMessage());
}
