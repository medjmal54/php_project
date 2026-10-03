<?php
/*
 * Contrôleur de la Page d'Accueil
 */
class HomeController {

    public function index() {
        $randonnees = RandonneeModel::getLatest();
        
        // Afficher la vue
        require_once 'views/layout/header.php';
        require_once 'views/home.php';
        require_once 'views/layout/footer.php';
    }
}
