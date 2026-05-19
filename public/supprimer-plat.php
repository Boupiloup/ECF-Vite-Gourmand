<?php
session_start();

require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

$role = (int) $_SESSION['utilisateur_role'];

if ($role !== 2 && $role !== 1) {
    header('Location: connexion.php');
    exit();
}

$platId = (int) ($_GET['id'] ?? 0);

if ($platId <= 0) {
    header('Location: gestion-plats.php');
    exit();
}

/* Supprime d'abord les liens entre ce plat et les menus */
$sql = "DELETE FROM menu_plat WHERE plat_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$platId]);

/* Supprime ensuite le plat */
$sql = "DELETE FROM plat WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$platId]);

header('Location: gestion-plats.php');
exit();
?>