<?php
session_start();

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/mongodb.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

$role = (int) $_SESSION['utilisateur_role'];

if ($role !== 1) {
    header('Location: connexion.php');
    exit();
}

$sql = "SELECT menu.id AS menu_id, menu.titre AS menu_titre, COUNT(commande.id) AS nombre_commandes, SUM(commande.prix_total) AS chiffre_affaires
        FROM commande
        JOIN menu ON commande.menu_id = menu.id
        GROUP BY menu.id, menu.titre";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

$collection = $mongoDatabase->stats_menus;

$collection->deleteMany([]);

foreach ($stats as $stat) {
    $collection->insertOne([
        'menu_id' => $stat['menu_id'],
        'menu_titre' => $stat['menu_titre'],
        'nombre_commandes' => $stat['nombre_commandes'],
        'chiffre_affaires' => $stat['chiffre_affaires'],
        'date_generation' => date('d-m-Y H:i:s')
    ]);
}

header('Location: admin-stats.php');
exit();