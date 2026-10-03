<?php
/*
 * Modèle Bus
 */
class BusModel {
    private $id_bus;
    private $immat_bus;
    private $nb_places;
    private $chauffeur;
    private $tel_chauff;

    public function __construct($row) {
        $this->id_bus = $row['id_bus'] ?? null;
        $this->immat_bus = $row['immat_bus'] ?? null;
        $this->nb_places = $row['nb_places'] ?? null;
        $this->chauffeur = $row['chauffeur'] ?? null;
        $this->tel_chauff = $row['tel_chauff'] ?? null;
    }

    // Getters
    public function getId() { return $this->id_bus; }
    public function getImmatBus() { return $this->immat_bus; }
    public function getNbPlaces() { return $this->nb_places; }
    public function getChauffeur() { return $this->chauffeur; }
    public function getTelChauff() { return $this->tel_chauff; }
    
    public static function getAll() {
        include 'config/db.php';    
        $stmt = $db->query("SELECT * FROM bus");
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = new BusModel($row);
        }
        return $results;
    }

    public static function findById($id) {
        include 'config/db.php';
        $stmt = $db->prepare("SELECT * FROM bus WHERE id_bus = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? new BusModel($row) : null;
    }

    public static function create($data) {
        include 'config/db.php';
        $stmt = $db->prepare("INSERT INTO bus (immat_bus, nb_places, chauffeur, tel_chauff) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['immat_bus'],
            $data['nb_places'],
            $data['chauffeur'],
            $data['tel_chauff']
        ]);
    }

    public static function update($id, $data) {
        include 'config/db.php';
        $stmt = $db->prepare("UPDATE bus SET immat_bus = ?, nb_places = ?, chauffeur = ?, tel_chauff = ? WHERE id_bus = ?");
        return $stmt->execute([
            $data['immat_bus'],
            $data['nb_places'],
            $data['chauffeur'],
            $data['tel_chauff'],
            $id
        ]);
    }

    public static function delete($id) {
        include 'config/db.php';
        $stmt = $db->prepare("DELETE FROM bus WHERE id_bus = ?");
        return $stmt->execute([$id]);
    }
}
