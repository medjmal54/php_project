<?php
/*
 * Point d'entrée unique de l'application (Front Controller)
 */
session_start();

// Importer les modéles et les connections
require_once 'config/db.php';
require_once 'models/UserModel.php';
require_once 'models/RandonneeModel.php';
require_once 'models/ParticipationModel.php';
require_once 'models/BusModel.php';

// Importer les controlleurs      
require_once 'controllers/HomeController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/RandonneeController.php';
require_once 'controllers/UserController.php';
require_once 'controllers/AdminController.php';

// Récupération de la page et de l'action depuis l'URL (ex: ?page=auth&action=login)
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Fonction simple pour vérifier si l'utilisateur est connecté et a le bon rôle
function verifierAcces($roleAttendu = null) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=auth&action=login');
        exit();
    }
    if ($roleAttendu && $_SESSION['role'] !== $roleAttendu) {
        header('Location: index.php?page=home&error=acces_refuse');
        exit();
    }
}

// Router : Switch simple pour diriger vers le bon contrôleur
switch ($page) {
    case 'home':
        $ctrl = new HomeController();
        $ctrl->index();
        break;
        
    case 'auth':
        $ctrl = new AuthController();
        if ($action == 'login') $ctrl->login();
        elseif ($action == 'register') $ctrl->register();
        elseif ($action == 'logout') $ctrl->logout();
        break;

    case 'randonnee':
        $ctrl = new RandonneeController();
        if ($action == 'index') $ctrl->index();
        elseif ($action == 'details') $ctrl->details();
        elseif ($action == 'participate') $ctrl->participate();
        break;

    case 'user':
        // Favorites and toggle are accessible to both roles
        if ($action == 'favorites' || $action == 'toggle_favorite') {
            verifierAcces(); // Only check if logged in
        } else {
            verifierAcces('user');
        }
        $ctrl = new UserController();
        if ($action == 'dashboard') $ctrl->dashboard();
        elseif ($action == 'settings') $ctrl->settings();
        elseif ($action == 'favorites') $ctrl->favorites();
        elseif ($action == 'toggle_favorite') $ctrl->toggle_favorite();
        break;

    case 'admin':
        verifierAcces('admin');
        $ctrl = new AdminController();
        if ($action == 'dashboard') $ctrl->dashboard();
        elseif ($action == 'manage_randonnees') $ctrl->manageRandonnees();
        elseif ($action == 'delete_randonnee') $ctrl->deleteRandonnee();
        elseif ($action == 'manage_participations') $ctrl->manageParticipations();
        elseif ($action == 'update_participation_status') $ctrl->updateParticipationStatus();
        elseif ($action == 'manage_buses') $ctrl->manageBuses();
        elseif ($action == 'settings') $ctrl->settings();
        break;

    default:
        die("Erreur 404 : Page non trouvée.");
}
