<?php
session_start();

require_once __DIR__ . '/../includes/db.php';

$pageTitle = 'Gestion des employés';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

$role = (int) $_SESSION['utilisateur_role'];

if ($role !== 1) {
    header('Location: connexion.php');
    exit();
}

$sql = "SELECT * FROM utilisateur WHERE role_id =2 ORDER BY nom ASC";
$stmt = $pdo->query($sql);
$employes = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

<main class="gestion-employes">
    <h1>Gestion des employés</h1>

    <a href="creer-employe.php" class="gestion-employes-ajouter">Créer un compte employé</a>

    <?php if (empty($employes)) : ?>
        <p class="gestion-employes-vide">Aucun employé trouvé.</p>
    <?php else : ?>
        <table class="gestion-employes-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($employes as $employe) : ?>
                    <tr>
                        <td><?= htmlspecialchars($employe['nom']) ?></td>
                        <td><?= htmlspecialchars($employe['prenom']) ?></td>
                        <td><?= htmlspecialchars($employe['email']) ?></td>
                        <td><?= htmlspecialchars($employe['telephone']) ?></td>
                        <td>
                            <?= $employe['actif'] ? 'Actif' : 'Inactif' ?>
                        </td>
                        <td>
                            <?php if ($employe['actif']) : ?>
                                <a href="desactiver-employe.php?id=<?= $employe['id'] ?>" class="gestion-employes-desactiver">
                                    Désactiver
                                </a>
                            <?php else : ?>
                                <span class="gestion-employes-inactif">Compte désactivé</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
