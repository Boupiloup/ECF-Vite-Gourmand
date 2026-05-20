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

$sql = "SELECT
            menu.id AS menu_id,
            menu.titre AS menu_titre,
            COUNT(commande.id) AS nombre_commandes,
            COALESCE(SUM(commande.prix_total), 0) AS chiffre_affaires
        FROM menu
        LEFT JOIN commande ON commande.menu_id = menu.id
        GROUP BY menu.id, menu.titre
        ORDER BY menu.titre ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

try {
    require_once __DIR__ . '/../includes/mongodb.php';

    $collection = $mongoDatabase->stats_menus;
    $collection->deleteMany([]);

    foreach ($stats as $stat) {
        $collection->insertOne([
            'menu_id' => (int) $stat['menu_id'],
            'menu_titre' => $stat['menu_titre'],
            'nombre_commandes' => (int) $stat['nombre_commandes'],
            'chiffre_affaires' => (float) $stat['chiffre_affaires'],
            'date_generation' => date('d-m-Y H:i:s')
        ]);
    }
} catch (Throwable $e) {
    error_log('Generation stats MongoDB indisponible : ' . $e->getMessage());
}

header('Location: admin-stats.php');
exit();
