<?php
/*
 * Contrôleur Utilisateur
 */
class UserController {

    public function dashboard() {
        $id_user = $_SESSION['user_id'];
        $participations = ParticipationModel::getByUser($id_user);

        require_once 'views/layout/header.php';
        require_once 'views/user/dashboard.php';
        require_once 'views/layout/footer.php';
    }

    public function mark_read() {
        if (isset($_SESSION['user_id'])) {
            require_once 'models/ParticipationModel.php';
            ParticipationModel::markAsSeen($_SESSION['user_id']);
            echo json_encode(['status' => 'success']);
        }
        exit();
    }

    public function toggle_favorite() {
        if (isset($_SESSION['user_id']) && isset($_GET['id'])) {
            require_once 'models/FavoriteModel.php';
            $res = FavoriteModel::toggle($_SESSION['user_id'], $_GET['id']);
            echo json_encode($res);
        }
        exit();
    }

    public function favorites() {
        $id_user = $_SESSION['user_id'];
        require_once 'models/FavoriteModel.php';
        $favorites = FavoriteModel::getByUser($id_user);

        require_once 'views/layout/header.php';
        require_once 'views/user/favorites.php';
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
                header("Location: " . 'index.php?page=user&action=settings&success=profile'); exit();
            }
            elseif ($action_type === 'update_password') {
                $current = $_POST['current_password'] ?? '';
                $new = $_POST['new_password'] ?? '';
                $confirm = $_POST['confirm_password'] ?? '';
                
                if (password_verify($current, $user->getPassword()) && $new === $confirm) {
                    UserModel::updatePassword($id_user, password_hash($new, PASSWORD_DEFAULT));
                    header("Location: " . 'index.php?page=user&action=settings&success=password'); exit();
                } else {
                    header("Location: " . 'index.php?page=user&action=settings&error=password'); exit();
                }
            }
            elseif ($action_type === 'update_photo') {
                if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                    $filename = $_FILES['photo']['name'];
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    
                    if (in_array($ext, $allowed)) {
                        $newname = 'user_' . $id_user . '_' . time() . '.' . $ext;
                        $dest = 'public/uploads/profiles/' . $newname;
                        
                        if (move_uploaded_file($_FILES['photo']['tmp_name'], $dest)) {
                            $photo_url = '/projet/' . $dest;
                            UserModel::updatePhoto($id_user, $photo_url);
                            $_SESSION['photo_url'] = $photo_url;
                            header("Location: " . 'index.php?page=user&action=settings&success=photo'); exit();
                        }
                    }
                }
                header("Location: " . 'index.php?page=user&action=settings&error=photo'); exit();
            }
        }

        require_once 'views/layout/header.php';
        require_once 'views/user/settings.php';
        require_once 'views/layout/footer.php';
    }
}
