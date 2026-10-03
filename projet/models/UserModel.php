<?php
/*
 * Modèle Utilisateur
 */
class UserModel {
    private $id_user;
    private $nom;
    private $email;
    private $password;
    private $role;
    private $telephone;
    private $photo_url;

    public function __construct($row) {
        $this->id_user = $row['id_user'] ?? null;
        $this->nom = $row['nom'] ?? null;
        $this->email = $row['email'] ?? null;
        $this->password = $row['password'] ?? null;
        $this->role = $row['role'] ?? null;
        $this->telephone = $row['telephone'] ?? null;
        $this->photo_url = $row['image_profile'] ?? '/projet/public/uploads/profiles/default.png';
    }

    public function getId() { return $this->id_user; }
    public function getNom() { return $this->nom; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getRole() { return $this->role; }
    public function getTelephone() { return $this->telephone; }
    public function getPhotoUrl() { return $this->photo_url; }

    public static function findByEmail($email) {
        include 'config/db.php';
        $stmt = $db->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        return $row ? new UserModel($row) : null;
    }

    public static function findById($id) {
        include 'config/db.php';
        $stmt = $db->prepare("SELECT * FROM user WHERE id_user = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? new UserModel($row) : null;
    }

    public static function create($data) {
        include 'config/db.php';
        $stmt = $db->prepare("INSERT INTO user (nom, email, password, role, telephone) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['nom'],
            $data['email'],
            $data['password'],
            $data['role'],
            $data['telephone']
        ]);
    }

    public static function updateProfile($id_user, $nom, $email, $telephone) {
        include 'config/db.php';
        $stmt = $db->prepare("UPDATE user SET nom = ?, email = ?, telephone = ? WHERE id_user = ?");
        return $stmt->execute([$nom, $email, $telephone, $id_user]);
    }

    public static function updatePassword($id_user, $new_password) {
        include 'config/db.php';
        $stmt = $db->prepare("UPDATE user SET password = ? WHERE id_user = ?");
        return $stmt->execute([$new_password, $id_user]);
    }

    public static function updatePhoto($id_user, $photo_url) {
        include 'config/db.php';
        $stmt = $db->prepare("UPDATE user SET image_profile = ? WHERE id_user = ?");
        return $stmt->execute([$photo_url, $id_user]);
    }

    public static function getTotalUsers() {
        include 'config/db.php';
        $stmt = $db->query("SELECT COUNT(*) as total FROM user WHERE role = 'user'");
        return $stmt->fetch()['total'] ?? 0;
    }
}
