<?php
/*
 * Contrôleur Authentification
 */
class AuthController {

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = UserModel::findByEmail($_POST['email']);
            
            $isValid = false;
            if ($user) {
                // Vérifie si le mot de passe est haché ou en clair
                if (password_verify($_POST['password'], $user->getPassword())) {
                    $isValid = true;
                } elseif ($_POST['password'] === $user->getPassword()) {
                    $isValid = true;
                }
            }

            if ($isValid) {
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['user_name'] = $user->getNom();
                $_SESSION['email'] = $user->getEmail();
                $_SESSION['role'] = $user->getRole();
                $_SESSION['photo_url'] = $user->getPhotoUrl();
                
                if ($user->getRole() === 'admin') {
                    header("Location: " . 'index.php?page=admin&action=dashboard'); exit();
                } else {
                    header("Location: " . 'index.php?page=user&action=dashboard'); exit();
                }
            } else {
                $error = "Identifiants invalides.";
                require_once 'views/layout/header.php';
                require_once 'views/auth/login.php';
                require_once 'views/layout/footer.php';
                return;
            }
        }
        require_once 'views/layout/header.php';
        require_once 'views/auth/login.php';
        require_once 'views/layout/footer.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Passwords match check
            if (isset($_POST['password']) && isset($_POST['confirm_password'])) {
                if ($_POST['password'] !== $_POST['confirm_password']) {
                    $error = "Les mots de passe ne correspondent pas.";
                    require_once 'views/layout/header.php';
                    require_once 'views/auth/register.php';
                    require_once 'views/layout/footer.php';
                    return;
                }
            }

            // check if this user already exists
            if($_POST['email'] == UserModel::findByEmail($_POST['email'])){
                $error = "L'email existe déjà.";
                require_once 'views/layout/header.php';
                require_once 'views/auth/register.php';
                require_once 'views/layout/footer.php';
                return;
            }

            $_POST['role'] = 'user';
            
            if (empty($_POST['telephone'])) {
                $error = "Le numéro de téléphone est obligatoire!";
                require_once 'views/layout/header.php';
                require_once 'views/auth/register.php';
                require_once 'views/layout/footer.php';
                return;
            }

            if (empty($_POST['email'])) {
                $error = "Veuillez entrer votre email!";
                require_once 'views/layout/header.php';
                require_once 'views/auth/register.php';
                require_once 'views/layout/footer.php';
                return;
            }
            
            if (UserModel::create($_POST)) {
                header("Location: " . 'index.php?page=auth&action=login&success=registered'); exit();
            } else {
                $error = "Une erreur est survenue lors de l'inscription.";
                require_once 'views/layout/header.php';
                require_once 'views/auth/register.php';
                require_once 'views/layout/footer.php';
                return;
            }
        }
        require_once 'views/layout/header.php';
        require_once 'views/auth/register.php';
        require_once 'views/layout/footer.php';
    }

    public function logout() {
        session_destroy();
        header("Location: " . 'index.php'); exit();
    }
}
