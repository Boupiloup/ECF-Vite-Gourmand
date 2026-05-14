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

$avis_id = (int) ($_GET['id'] ?? 0);

if ($avis_id <= 0) {
    header('Location: employe-gerer-avis.php');
    exit();
}

$sql = "DELETE FROM avis WHERE id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$avis_id]);

header('Location: employe-gerer-avis.php');
exit();
?>