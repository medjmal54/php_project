<?php
/*
 * Modèle Participation
 */
class ParticipationModel {
    private $id_participation;
    private $id_user;
    private $id_randonnee;
    private $statut;
    private $date_creation;
    private $titre;
    private $date_debut;
    private $region;
    private $prix;
    private $difficulte;
    private $user_name;
    private $email;
    private $photo_url;
    private $is_seen_by_user;

    public function __construct($row) {
        $this->id_participation = $row['id_participation'] ?? null;
        $this->id_user = $row['id_user'] ?? null;
        $this->id_randonnee = $row['id_randonnee'] ?? null;
        $this->statut = $row['statut'] ?? null;
        $this->date_creation = $row['date_creation'] ?? null;
        $this->titre = $row['titre'] ?? null;
        $this->date_debut = $row['date_debut'] ?? null;
        $this->region = $row['region'] ?? null;
        $this->prix = $row['prix'] ?? null;
        $this->difficulte = $row['difficulte'] ?? null;
        $this->user_name = $row['user_name'] ?? null;
        $this->email = $row['email'] ?? null;
        $this->photo_url = $row['image_profile'] ?? '/projet/public/uploads/profiles/default.png';
        $this->is_seen_by_user = (bool)($row['is_seen_by_user'] ?? false);
    }

    // Getters
    public function getId() { return $this->id_participation; }
    public function getIdUser() { return $this->id_user; }
    public function getIdRandonnee() { return $this->id_randonnee; }
    public function getStatut() { return $this->statut; }
    public function getDateCreation() { return $this->date_creation; }
    public function getTitre() { return $this->titre; }
    public function getDateDebut() { return $this->date_debut; }
    public function getRegion() { return $this->region; }
    public function getPrix() { return $this->prix; }
    public function getDifficulte() { return $this->difficulte; }
    public function getUserName() { return $this->user_name; }
    public function getEmail() { return $this->email; }
    public function getPhotoUrl() { return $this->photo_url; }
    public function getIsSeen() { return $this->is_seen_by_user; }

    public static function create($id_user, $id_randonnee) {
        include 'config/db.php';
        $stmt = $db->prepare("INSERT INTO participation (id_user, id_randonnee, statut) VALUES (?, ?, 'en attente')");
        return $stmt->execute([$id_user, $id_randonnee]);
    }

    public static function findById($id_participation) {
        include 'config/db.php';
        $stmt = $db->prepare("SELECT * FROM participation WHERE id_participation = ?");
        $stmt->execute([$id_participation]);
        $row = $stmt->fetch();
        return $row ? new ParticipationModel($row) : null;
    }

    public static function findByUserAndRandonnee($id_user, $id_randonnee) {
        include 'config/db.php';
        $stmt = $db->prepare("SELECT * FROM participation WHERE id_user = ? AND id_randonnee = ?");
        $stmt->execute([$id_user, $id_randonnee]);
        $row = $stmt->fetch();
        return $row ? new ParticipationModel($row) : null;
    }

    public static function getCountByRandonnee($id_randonnee) {
        include 'config/db.php';
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM participation WHERE id_randonnee = ? AND statut = 'acceptée'");
        $stmt->execute([$id_randonnee]);
        $result = $stmt->fetch();
        return (int)$result['total'];
    }

    public static function hasDateOverlap($id_user, $date_debut, $date_fin) {
        include 'config/db.php';
        $stmt = $db->prepare("
            SELECT COUNT(*) as overlap 
            FROM participation p 
            JOIN randonnee r ON p.id_randonnee = r.id_randonnee 
            WHERE p.id_user = ? 
            AND p.statut = 'acceptée' 
            AND (
                (r.date_debut BETWEEN ? AND ?) OR 
                (r.date_fin BETWEEN ? AND ?) OR 
                (? BETWEEN r.date_debut AND r.date_fin)
            )
        ");
        $stmt->execute([$id_user, $date_debut, $date_fin, $date_debut, $date_fin, $date_debut]);
        $result = $stmt->fetch();
        return $result['overlap'] > 0;
    }

    public static function getByUser($id_user) {
        include 'config/db.php';
        $stmt = $db->prepare("
            SELECT p.*, r.titre, r.date_debut, r.region, r.prix 
            FROM participation p 
            JOIN randonnee r ON p.id_randonnee = r.id_randonnee 
            WHERE p.id_user = ? 
            ORDER BY p.date_creation DESC
        ");
        $stmt->execute([$id_user]);
        $results = [];
        while ($row = $stmt->fetch()) {
            $results[] = new ParticipationModel($row);
        }
        return $results;
    }

    public static function updateStatus($id, $status) {
        include 'config/db.php';
        $stmt = $db->prepare("UPDATE participation SET statut = ?, is_seen_by_user = 0 WHERE id_participation = ?");
        return $stmt->execute([$status, $id]);
    }

    public static function getTotalCount() {
        include 'config/db.php';
        $stmt = $db->query("SELECT COUNT(*) as total FROM participation");
        return $stmt->fetch()['total'] ?? 0;
    }

    public static function getPendingParticipations($limit = 5) {
        include 'config/db.php';
        $stmt = $db->prepare("
            SELECT p.*, r.titre, u.nom as user_name, u.image_profile 
            FROM participation p 
            JOIN randonnee r ON p.id_randonnee = r.id_randonnee 
            JOIN user u ON p.id_user = u.id_user 
            WHERE p.statut = 'en attente'
            ORDER BY p.date_creation DESC LIMIT " . (int)$limit . "
        ");
        $stmt->execute();
        $pending = [];
        while ($row = $stmt->fetch()) {
            $pending[] = new ParticipationModel($row);
        }
        return $pending;
    }

    public static function getLatestStatusUpdates($id_user, $limit = 5) {
        include 'config/db.php';
        $stmt = $db->prepare("
            SELECT p.*, r.titre, r.image as rando_image 
            FROM participation p 
            JOIN randonnee r ON p.id_randonnee = r.id_randonnee 
            WHERE p.id_user = ? 
            AND p.statut IN ('acceptée', 'refusée') 
            ORDER BY p.date_creation DESC LIMIT " . (int)$limit . "
        ");
        $stmt->execute([$id_user]);
        $updates = [];
        while ($row = $stmt->fetch()) {
            $updates[] = new ParticipationModel($row);
        }
        return $updates;
    }

    public static function markAsSeen($id_user) {
        include 'config/db.php';
        $stmt = $db->prepare("UPDATE participation SET is_seen_by_user = 1 WHERE id_user = ? AND statut IN ('acceptée', 'refusée')");
        return $stmt->execute([$id_user]);
    }

    public static function getUnseenCount($id_user) {
        include 'config/db.php';
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM participation WHERE id_user = ? AND statut IN ('acceptée', 'refusée') AND is_seen_by_user = 0");
        $stmt->execute([$id_user]);
        return (int)$stmt->fetch()['total'];
    }

    public static function getLatestParticipations() {
        include 'config/db.php';
        $stmt = $db->prepare("
            SELECT p.*, r.titre, r.difficulte, u.nom as user_name, u.image_profile 
            FROM participation p 
            JOIN randonnee r ON p.id_randonnee = r.id_randonnee 
            JOIN user u ON p.id_user = u.id_user 
            ORDER BY p.date_creation DESC LIMIT 5
        ");
        $stmt->execute();
        $latest = [];
        while ($row = $stmt->fetch()) {
            $latest[] = new ParticipationModel($row);
        }
        return $latest;
    }

    public static function getTotalRevenue() {
        include 'config/db.php';
        $stmt = $db->query("SELECT SUM(r.prix) as revenu FROM participation p JOIN randonnee r ON p.id_randonnee = r.id_randonnee WHERE p.statut = 'Acceptée'");
        return $stmt->fetch()['revenu'] ?? 0;
    }

    public static function getAcceptedCount() {
        include 'config/db.php';
        $stmt = $db->query("SELECT COUNT(*) as acc FROM participation WHERE statut = 'Acceptée'");
        return $stmt->fetch()['acc'] ?? 0;
    }

    public static function getRandonneeParticipations(){
        include 'config/db.php';
        $stmt = $db->query("
            SELECT p.*, r.titre, u.nom as user_name, u.email, u.image_profile 
            FROM participation p 
            JOIN randonnee r ON p.id_randonnee = r.id_randonnee 
            JOIN user u ON p.id_user = u.id_user 
            ORDER BY p.date_creation DESC
        ");
        $participations = [];
        while ($row = $stmt->fetch()) {
            $participations[] = new ParticipationModel($row);
        }
        return $participations;
    }

    // ---- Dashboard filtered methods ----

    // Returns total, accepted, refused, pending counts for a date range
    public static function getFilteredStats($from, $to) {
        include 'config/db.php';
        $stmt = $db->prepare("
            SELECT
                COUNT(*) as total,
                SUM(statut = 'acceptée') as accepted,
                SUM(statut = 'refusée') as refused,
                SUM(statut = 'en attente') as pending
            FROM participation
            WHERE date_creation BETWEEN ? AND ?
        ");
        $stmt->execute([$from . ' 00:00:00', $to . ' 23:59:59']);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Returns revenue for a date range (accepted only)
    public static function getFilteredRevenue($from, $to) {
        include 'config/db.php';
        $stmt = $db->prepare("
            SELECT COALESCE(SUM(r.prix), 0) as revenu
            FROM participation p
            JOIN randonnee r ON p.id_randonnee = r.id_randonnee
            WHERE p.statut = 'acceptée'
              AND p.date_creation BETWEEN ? AND ?
        ");
        $stmt->execute([$from . ' 00:00:00', $to . ' 23:59:59']);
        return $stmt->fetch()['revenu'] ?? 0;
    }

    // Returns daily participation counts for chart (grouped by day)
    public static function getChartData($from, $to) {
        include 'config/db.php';
        $stmt = $db->prepare("
            SELECT
                DATE(date_creation) as day,
                COUNT(*) as total,
                SUM(statut = 'acceptée') as accepted
            FROM participation
            WHERE date_creation BETWEEN ? AND ?
            GROUP BY DATE(date_creation)
            ORDER BY day ASC
        ");
        $stmt->execute([$from . ' 00:00:00', $to . ' 23:59:59']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
