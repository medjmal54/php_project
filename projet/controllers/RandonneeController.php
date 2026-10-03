<?php
/*
 * Contrôleur Randonnée
 */
class RandonneeController {

    // Afficher toutes les randonnées
    public function index() {
        // Collect all potential filters
        $filters = [
            'region'        => $_GET['region'] ?? '',
            'region_filter' => $_GET['region_filter'] ?? '',
            'categorie'     => $_GET['categorie'] ?? '',
            'difficulte'    => $_GET['difficulte'] ?? '',
            'prix_min'      => $_GET['prix_min'] ?? '',
            'prix_max'      => $_GET['prix_max'] ?? ''
        ];

        // Unify region: Prioritize specific dropdown over keyword search if both are provided, 
        // or prioritize whichever is non-empty.
        $searchFilters = $filters;
        if (!empty($filters['region_filter'])) {
            $searchFilters['region'] = $filters['region_filter'];
        }

        // Check if we have active filters
        $hasFilters = false;
        foreach ($searchFilters as $val) {
            if ($val !== '') { $hasFilters = true; break; }
        }

        if ($hasFilters) {
            $randonnees = RandonneeModel::search($searchFilters);
        } else {
            $randonnees = RandonneeModel::getAll();
        }

        require_once 'views/layout/header.php';
        require_once 'views/randonnee/index.php';
        require_once 'views/layout/footer.php';
    }

    public function details() {
        if (!isset($_GET['id'])) {
            header("Location: " . 'index.php?page=randonnee&action=index'); exit();
        }

        $id = $_GET['id'];
        $randonnee = RandonneeModel::findById($id);

        if (!$randonnee) {
            die("Randonnée non trouvée.");
        }

        $count = ParticipationModel::getCountByRandonnee($id);
        $alreadyParticipated = null;
        if (isset($_SESSION['user_id'])) {
            $alreadyParticipated = ParticipationModel::findByUserAndRandonnee($_SESSION['user_id'], $id);
        }

        require_once 'views/layout/header.php';
        require_once 'views/randonnee/details.php';
        require_once 'views/layout/footer.php';
    }

    // Demander une participation
    public function participate() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . 'index.php?page=auth&action=login'); exit();
        }

        $id_randonnee = $_GET['id'];
        $id_user = $_SESSION['user_id'];

        $randonnee = RandonneeModel::findById($id_randonnee);
        
        // Vérification capacité
        $currentCount = ParticipationModel::getCountByRandonnee($id_randonnee);
        if ($currentCount >= $randonnee->getCapacite()) {
            header("Location: " . "index.php?page=randonnee&action=details&id=$id_randonnee&error=complet"); exit();
        }

        // Vérification déjà inscrit
        if (ParticipationModel::findByUserAndRandonnee($id_user, $id_randonnee)) {
            header("Location: " . "index.php?page=user&action=dashboard&error=deja_inscrit"); exit();
        }

        //  Vérification conflit de date
        if (ParticipationModel::hasDateOverlap($id_user, $randonnee->getDateDebut(), $randonnee->getDateFin())) {
            header("Location: " . "index.php?page=randonnee&action=details&id=$id_randonnee&error=conflit_date"); exit();
        }

        ParticipationModel::create($id_user, $id_randonnee);
        header("Location: " . 'index.php?page=user&action=dashboard&success=participation_envoyee'); exit();
    }
}
