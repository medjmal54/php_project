<?php
/*
 * Contrôleur Administration
 */
class AdminController {

    public function dashboard() {
        // Date range filter — defaults to last 30 days
        $date_to   = isset($_GET['date_to'])   && $_GET['date_to']   !== '' ? $_GET['date_to']   : date('Y-m-d');
        $date_from = isset($_GET['date_from']) && $_GET['date_from'] !== '' ? $_GET['date_from'] : date('Y-m-d', strtotime('-30 days'));

        // Global stats (all time)
        $total_randonnees = RandonneeModel::getTotalRandonnees();
        $total_users      = UserModel::getTotalUsers();

        // Filtered stats (for the selected date range)
        $filtered_stats  = ParticipationModel::getFilteredStats($date_from, $date_to);
        $filtered_revenu = ParticipationModel::getFilteredRevenue($date_from, $date_to);
        $chart_data      = ParticipationModel::getChartData($date_from, $date_to);

        $total_part = (int)($filtered_stats['total']    ?? 0);
        $acc        = (int)($filtered_stats['accepted'] ?? 0);
        $refused    = (int)($filtered_stats['refused']  ?? 0);
        $pending    = (int)($filtered_stats['pending']  ?? 0);
        $taux_acc   = ($total_part > 0) ? round(($acc / $total_part) * 100, 1) : 0;

        require_once 'views/layout/header.php';
        require 'views/admin/dashboard.php';
        require_once 'views/layout/footer.php';
    }

    public function manageRandonnees() {
        // On recupére l'action à effectuer
        $action = $_GET['subaction'] ?? 'list';
        
        if ($action == 'add' && $_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $allowed = ['jpg', 'jpeg', 'png'];
                // On recupére l'extension du fichier
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                // in_array() : vérifie si une valeur existe dans un tableau
                if (in_array($ext, $allowed)) {
                    $newname = 'rando_' . time() . '_' . rand(100, 999) . '.' . $ext;
                    $dest = 'public/uploads/randonnees/' . $newname;
                    // move_uploaded_file() : déplace un fichier téléchargé 
                    // $_FILES['image']['tmp_name'] : chemin temporaire du fichier
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                        $data['image'] = '/projet/' . $dest;
                    }
                }
            }
            RandonneeModel::create($data);
            header("Location: " . 'index.php?page=admin&action=manage_randonnees'); exit();
        }
        if ($action == 'edit' && $_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $allowed = ['jpg', 'jpeg', 'png'];
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, $allowed)) {
                    $newname = 'rando_' . time() . '_' . rand(100, 999) . '.' . $ext;
                    $dest = 'public/uploads/randonnees/' . $newname;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                        $data['image'] = '/projet/' . $dest;
                    }
                }
            }
            RandonneeModel::update($_POST['id_randonnee'], $data);
            header("Location: " . 'index.php?page=admin&action=manage_randonnees'); exit();
        }

        $randonnees = RandonneeModel::getAll();
        $buses = BusModel::getAll();
        require_once 'views/layout/header.php';
        require_once 'views/admin/manage_randonnees.php';
        require_once 'views/layout/footer.php';
    }

    public function deleteRandonnee() {
        if (!isset($_GET['id'])) {
            header("Location: " . 'index.php?page=admin&action=manage_randonnees'); exit();
        }
        $id = $_GET['id'];
        RandonneeModel::delete($id);
        header("Location: " . 'index.php?page=admin&action=manage_randonnees'); exit();
    }

    public function manageParticipations() {
        $participations = ParticipationModel::getRandonneeParticipations();
        require_once 'views/layout/header.php';
        require_once 'views/admin/manage_participations.php';
        require_once 'views/layout/footer.php';
    }

    //Avec ce methode on peut accepter ou refuser une demande de participation
    public function updateParticipationStatus() {
        $id = $_GET['id'];
        $status = $_GET['status'];
        
        if ($status == 'acceptée') {
            $participation = ParticipationModel::findById($id);
            
            $r = RandonneeModel::findById($participation->getIdRandonnee());
            $currentCount = ParticipationModel::getCountByRandonnee($participation->getIdRandonnee());
            
            if ($currentCount >= $r->getCapacite()) {
                header("Location: " . 'index.php?page=admin&action=manage_participations&error=complet'); exit();
            }
        }

        ParticipationModel::updateStatus($id, $status);
        header("Location: " . 'index.php?page=admin&action=manage_participations'); exit();
    }

    public function manageBuses() {
        $action = $_GET['subaction'] ?? 'list';
        if ($action == 'add' && $_SERVER['REQUEST_METHOD'] == 'POST') {
            BusModel::create($_POST);
            header("Location: " . 'index.php?page=admin&action=manage_buses'); exit();
        }
        if ($action == 'edit' && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_bus'])) {
            BusModel::update($_POST['id_bus'], $_POST);
            header("Location: " . 'index.php?page=admin&action=manage_buses'); exit();
        }
        if ($action == 'delete' && isset($_GET['id'])) {
            BusModel::delete($_GET['id']);
            header("Location: " . 'index.php?page=admin&action=manage_buses'); exit();
        }
        $buses = BusModel::getAll();
        require_once 'views/layout/header.php';
        require_once 'views/admin/manage_buses.php';
        require_once 'views/layout/footer.php';
    }
    public function settings() {
        $id_user = $_SESSION['user_id'];
        $user = UserModel::findById($id_user);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action_type = $_POST['action_type'] ?? '';
            
            if ($action_type === 'update_profile') {
                $nom = $_POST['nom'] ?? '';
                $email = $_POST['email'] ?? '';
                $telephone = $_POST['telephone'] ?? '';
                UserModel::updateProfile($id_user, $nom, $email, $telephone);
                $_SESSION['user_name'] = $nom;
                header("Location: " . 'index.php?page=admin&action=settings&success=profile'); exit();
            }
            elseif ($action_type === 'update_password') {
                $current = $_POST['current_password'] ?? '';
                $new = $_POST['new_password'] ?? '';
                $confirm = $_POST['confirm_password'] ?? '';
                
                if (password_verify($current, $user->getPassword()) && $new === $confirm) {
                    UserModel::updatePassword($id_user, password_hash($new, PASSWORD_DEFAULT));
                    header("Location: " . 'index.php?page=admin&action=settings&success=password'); exit();
                } else {
                    header("Location: " . 'index.php?page=admin&action=settings&error=password'); exit();
                }
            }
            elseif ($action_type === 'update_photo') {
                if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                    $allowed = ['jpg', 'jpeg', 'png'];
                    $filename = $_FILES['photo']['name'];
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    
                    if (in_array($ext, $allowed)) {
                        $newname = 'user_' . $id_user . '_' . time() . '.' . $ext;
                        $dest = 'public/uploads/profiles/' . $newname;
                        
                        if (move_uploaded_file($_FILES['photo']['tmp_name'], $dest)) {
                            $photo_url = '/projet/' . $dest;
                            UserModel::updatePhoto($id_user, $photo_url);
                            $_SESSION['photo_url'] = $photo_url;
                            header("Location: " . 'index.php?page=admin&action=settings&success=photo'); exit();
                        }
                    }
                }
                header("Location: " . 'index.php?page=admin&action=settings&error=photo'); exit();
            }
        }

        require_once 'views/layout/header.php';
        require_once 'views/admin/settings.php';
        require_once 'views/layout/footer.php';
    }
}
