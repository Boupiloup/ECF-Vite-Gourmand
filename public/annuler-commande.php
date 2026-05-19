<?php
$pageTitle = "Annuler une commande";

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';

// Si l'utilisateur n'est pas connecté, on le renvoie vers la connexion
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id'];

// On vérifie qu'on reçoit bien une commande à annuler
if (isset($_POST['commande_id'])) {
    $commande_id = $_POST['commande_id'];

    // On vérifie que la commande appartient bien à l'utilisateur connecté
    $sql = "SELECT * FROM commande 
            WHERE id = ? 
            AND utilisateur_id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$commande_id, $utilisateur_id]);

    $commande = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($commande) {

        // On annule seulement si la commande est encore en attente
        if ($commande['statut_commande'] === 'en_attente') {

            $sqlUpdate = "UPDATE commande 
                          SET statut_commande = 'annulee'
                          WHERE id = ?";

            $stmtUpdate = $pdo->prepare($sqlUpdate);
            $stmtUpdate->execute([$commande_id]);
        }
    }
}

// Dans tous les cas, on retourne vers la liste des commandes
header('Location: mes-commandes.php');
exit;