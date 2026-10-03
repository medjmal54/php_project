<?php
/*
 * Modèle Favoris
 */
class FavoriteModel {
    private $id_user;
    private $id_randonnee;
    private $date_creation;

    public function __construct($row) {
        $this->id_user = $row['id_user'] ?? null;
        $this->id_randonnee = $row['id_randonnee'] ?? null;
        $this->date_creation = $row['date_creation'] ?? null;
    }

    public static function toggle($id_user, $id_randonnee) {
        include 'config/db.php';
        
        // Check if already exists
        $stmt = $db->prepare("SELECT COUNT(*) FROM favoris WHERE id_user = ? AND id_randonnee = ?");
        $stmt->execute([$id_user, $id_randonnee]);
        $exists = $stmt->fetchColumn();

        if ($exists) {
            // Remove
            $stmt = $db->prepare("DELETE FROM favoris WHERE id_user = ? AND id_randonnee = ?");
            $stmt->execute([$id_user, $id_randonnee]);
            return ['status' => 'removed'];
        } else {
            // Add
            $stmt = $db->prepare("INSERT INTO favoris (id_user, id_randonnee) VALUES (?, ?)");
            $stmt->execute([$id_user, $id_randonnee]);
            return ['status' => 'added'];
        }
    }

    public static function isFavorite($id_user, $id_randonnee) {
        include 'config/db.php';
        $stmt = $db->prepare("SELECT COUNT(*) FROM favoris WHERE id_user = ? AND id_randonnee = ?");
        $stmt->execute([$id_user, $id_randonnee]);
        return (bool)$stmt->fetchColumn();
    }

    public static function getByUser($id_user) {
        include 'config/db.php';
        include_once 'models/RandonneeModel.php';
        
        $stmt = $db->prepare("
            SELECT r.* 
            FROM randonnee r
            JOIN favoris f ON r.id_randonnee = f.id_randonnee
            WHERE f.id_user = ?
            ORDER BY f.date_creation DESC
        ");
        $stmt->execute([$id_user]);
        
        $favorites = [];
        while ($row = $stmt->fetch()) {
            $favorites[] = new RandonneeModel($row);
        }
        return $favorites;
    }
}
