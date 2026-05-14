<?php
session_start();

require_once __DIR__ . '/../includes/db.php';

$pageTitle = 'Gestion des commandes';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

$role = (int) $_SESSION['utilisateur_role'];

if ($role !== 2 && $role !== 1) {
    header('Location: connexion.php');
    exit();
}

$statut = $_GET['statut'] ?? '';
$client = trim($_GET['client'] ?? '');

$sql = "SELECT
    commande.*, 
    utilisateur.nom,
    utilisateur.prenom,
    utilisateur.email,
    menu.titre AS menu_titre
    FROM commande
    JOIN utilisateur ON commande.utilisateur_id = utilisateur.id 
    JOIN menu ON commande.menu_id = menu.id
    WHERE 1=1
";

$params = [];

if (!empty($statut)) {
    $sql .= " AND commande.statut_commande = ?";
    $params[] = $statut;
}

if (!empty($client)) {
    $sql .= " AND (
        utilisateur.nom LIKE ?
        OR utilisateur.prenom LIKE ?
        OR utilisateur.email LIKE ?
    )";

    $rechercheClient = '%' . $client . '%';

    $params[] = $rechercheClient;
    $params[] = $rechercheClient;
    $params[] = $rechercheClient;
}

$sql .= " ORDER BY commande.date_commande DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$statuts_labels = [
    'en_attente' => 'En attente',
    'accepte' => 'Accepté',
    'annulee' => 'Annulée',
    'en_preparation' => 'En préparation',
    'en_cours_de_livraison' => 'En cours de livraison',
    'livree' => 'Livrée',
    'en_attente_retour_materiel' => 'En attente retour matériel',
    'terminee' => 'Terminée'
];

require_once __DIR__ . '/../includes/header.php';
?>

<main class="employe-commandes">
    <h1>Gestion des commandes</h1>

    <form method="GET" class="filtres-commandes">
        <div>
            <label for="statut">Filtrer par statut</label>
            <select name="statut" id="statut">
                <option value="">Tous les statuts</option>

                <?php foreach ($statuts_labels as $valeur => $label): ?>
                    <option value="<?= htmlspecialchars($valeur) ?>" <?= $statut === $valeur ? 'selected' : '' ?>>
                        <?= htmlspecialchars($label) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="client">Rechercher un client</label>
            <input 
                type="text" 
                name="client" 
                id="client" 
                placeholder="Nom, prénom ou email"
                value="<?= htmlspecialchars($client) ?>"
            >
        </div>

        <button type="submit">Filtrer</button>

        <a href="employe-commandes.php">Réinitialiser</a>
    </form>

    <table>
        <thead>
            <tr>
                <th>Client</th>
                <th>Menu</th>
                <th>Date commande</th>
                <th>Statut</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php if (empty($commandes)): ?>
                <tr>
                    <td colspan="6">Aucune commande trouvée.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($commandes as $commande): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($commande['prenom'] . ' ' . $commande['nom']) ?><br>
                            <?= htmlspecialchars($commande['email']) ?>
                        </td>

                        <td><?= htmlspecialchars($commande['menu_titre']) ?></td>

                        <td><?= htmlspecialchars($commande['date_commande']) ?></td>

                        <td>
                            <?= htmlspecialchars($statuts_labels[$commande['statut_commande']] ?? $commande['statut_commande']) ?>
                        </td>

                        <td><?= htmlspecialchars($commande['prix_total']) ?> €</td>

                        <td>
                            <a href="detail-commande-employe.php?id=<?= $commande['id'] ?>">Voir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>