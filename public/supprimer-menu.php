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

$menuId = (int) ($_GET['id'] ?? 0);

if ($menuId <= 0) {
    header('Location: gestion-menus.php');
    exit();
}

/* Supprime d'abord les associations avec les plats */
$sql = "DELETE FROM menu_plat WHERE menu_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$menuId]);

/* Supprime ensuite le menu */
$sql = "DELETE FROM menu WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$menuId]);

header('Location: gestion-menus.php');
exit();
?>