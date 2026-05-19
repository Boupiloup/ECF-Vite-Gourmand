<?php
session_start();

require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

$role = (int) $_SESSION['utilisateur_role'];

if ($role !== 1) {
    header('Location: connexion.php');
    exit();
}

$employe_id = (int) ($_GET['id'] ?? 0);

if ($employe_id <= 0) {
    header('Location: gestion-employes.php');
    exit();
}

$sql = "UPDATE utilisateur SET actif = 0 WHERE id = ? AND role_id = 2";
$stmt = $pdo->prepare($sql);
$stmt->execute([$employe_id]);

header('Location: gestion-employes.php');
exit();