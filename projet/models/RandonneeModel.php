<?php
/*
 * Modèle Randonnée
 */
class RandonneeModel {
    private $id_randonnee;
    private $categorie;
    private $titre;
    private $description;
    private $date_debut;
    private $date_fin;
    private $region;
    private $distance;
    private $difficulte;
    private $capacite;
    private $image;
    private $id_bus;
    private $statut;
    private $prix;

    public function __construct($row) {
        $this->id_randonnee = $row['id_randonnee'] ?? null;
        $this->categorie = $row['categorie'] ?? null;
        $this->titre = $row['titre'] ?? null;
        $this->description = $row['description'] ?? null;
        $this->date_debut = $row['date_debut'] ?? null;
        $this->date_fin = $row['date_fin'] ?? null;
        $this->region = $row['region'] ?? null;
        $this->distance = $row['distance'] ?? null;
        $this->difficulte = $row['difficulte'] ?? null;
        $this->capacite = $row['capacite'] ?? null;
        $this->image = $row['image'] ?? null;
        $this->id_bus = $row['id_bus'] ?? null;
        $this->statut = $row['statut'] ?? null;
        $this->prix = $row['prix'] ?? null;
    }

    // Getters
    public function getId() { return $this->id_randonnee; }
    public function getCategorie() { return $this->categorie; }
    public function getTitre() { return $this->titre; }
    public function getDescription() { return $this->description; }
    public function getDateDebut() { return $this->date_debut; }
    public function getDateFin() { return $this->date_fin; }
    public function getRegion() { return $this->region; }
    public function getDistance() { return $this->distance; }
    public function getDifficulte() { return $this->difficulte; }
    public function getCapacite() { return $this->capacite; }
    public function getImage() { return $this->image; }
    public function getIdBus() { return $this->id_bus; }
    public function getStatut() { return $this->statut; }
    public function getPrix() { return $this->prix; }

    public static function getAll() {
        include 'config/db.php';
        $stmt = $db->query("SELECT * FROM randonnee ORDER BY date_debut ASC");
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = new RandonneeModel($row);
        }
        return $results;
    }

    public static function getLatest() {
        include 'config/db.php';
        $stmt = $db->prepare("SELECT * FROM randonnee WHERE date_debut > NOW() ORDER BY date_debut ASC LIMIT 3");
        $stmt->execute();
        $results = [];
        while ($row = $stmt->fetch()) {
            $results[] = new RandonneeModel($row);
        }
        return $results;
    }

    public static function findById($id) {
        include 'config/db.php';
        $stmt = $db->prepare("SELECT * FROM randonnee WHERE id_randonnee = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? new RandonneeModel($row) : null;
    }

    public static function search($filters) {
        include 'config/db.php';
        $query = "SELECT * FROM randonnee WHERE 1=1";
        $params = [];
        
        if (!empty($filters['region'])) {
            $query .= " AND region LIKE ?";
            $params[] = '%' . $filters['region'] . '%';
        }
        
        if (!empty($filters['categorie'])) {
            $query .= " AND categorie = ?";
            $params[] = $filters['categorie'];
        }
        
        if (!empty($filters['difficulte'])) {
            $query .= " AND difficulte = ?";
            $params[] = $filters['difficulte'];
        }
        
        if (isset($filters['prix_min']) && $filters['prix_min'] !== '') {
            $query .= " AND prix >= ?";
            $params[] = $filters['prix_min'];
        }
        
        if (isset($filters['prix_max']) && $filters['prix_max'] !== '') {
            $query .= " AND prix <= ?";
            $params[] = $filters['prix_max'];
        }
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $results = [];
        while ($row = $stmt->fetch()) {
            $results[] = new RandonneeModel($row);
        }
        return $results;
    }

    public static function create($data) {
        include 'config/db.php';
        $stmt = $db->prepare("INSERT INTO randonnee (titre, categorie, description, region, date_debut, date_fin, distance, difficulte, capacite, prix, id_bus, image) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        return $stmt->execute([
            $data['titre'], $data['categorie'], $data['description'], $data['region'], 
            $data['date_debut'], $data['date_fin'], $data['distance'], $data['difficulte'], 
            $data['capacite'], $data['prix'], $data['id_bus'], $data['image'] ?? null
        ]);
    }

    public static function update($id, $data) {
        include 'config/db.php';
        $sql = "UPDATE randonnee SET titre=?, categorie=?, description=?, region=?, date_debut=?, date_fin=?, distance=?, difficulte=?, capacite=?, prix=?, id_bus=?";
        $params = [
            $data['titre'], $data['categorie'], $data['description'], $data['region'], 
            $data['date_debut'], $data['date_fin'], $data['distance'], $data['difficulte'], 
            $data['capacite'], $data['prix'], $data['id_bus']
        ];

        if (isset($data['image']) && $data['image'] !== null) {
            $sql .= ", image=?";
            $params[] = $data['image'];
        }

        $sql .= " WHERE id_randonnee=?";
        $params[] = $id;

        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

    public static function delete($id) {
        include 'config/db.php';
        $stmt = $db->prepare("DELETE FROM randonnee WHERE id_randonnee = ?");
        return $stmt->execute([$id]);
    }

    public static function getTotalRandonnees() {
        include 'config/db.php';
        $stmt = $db->query("SELECT COUNT(*) as total FROM randonnee");
        return $stmt->fetch()['total'] ?? 0;
    }
}
