<?php 
session_start();

$pageTitle = "Admin Dashboard";

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: connexion.php");
    exit();
}

$role = $_SESSION['utilisateur_role'];

if ($role !== 1) {
    header('Location: connexion.php');
    exit();
}

require_once __DIR__ . '/../includes/header.php';
?> 

<main class="admin-dashboard">

    <h1>Espace administrateur</h1>

    <section>
        <h2>Fonctionnalités employé</h2>

        <a href="employe-commandes.php">Gestion des commandes</a>
        <a href="gestion-menus.php">Gestion des menus</a>
        <a href="gestion-plats.php">Gestion des plats</a>
        <a href="gestion-horaires.php">Gestion des horaires</a>
        <a href="employe-gerer-avis.php">Gestion des avis clients</a>
    </section>

    <section>
        <h2>Fonctionnalités administrateur</h2>

        <a href="gestion-employes.php">Gestion des employés</a>
        <a href="admin-stats.php">Statistiques commandes</a>
        <a href="admin-ca.php">Chiffre d’affaires</a>
    </section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>