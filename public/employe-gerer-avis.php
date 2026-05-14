<?php
session_start();

require_once __DIR__ . '/../includes/db.php';

$pageTitle = 'Gerer avis employé';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

$role = (int) $_SESSION['utilisateur_role'];

if ($role !== 2 && $role !== 1) {
    header('Location: connexion.php');
    exit();
}

$sql = "
    SELECT 
        avis.id,
        utilisateur.nom,
        utilisateur.prenom,
        avis.note,
        avis.commentaire,
        avis.date_avis,
        avis.valide
    FROM avis
    JOIN utilisateur ON avis.utilisateur_id = utilisateur.id
    ORDER BY avis.date_avis DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$avisClients = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

<main>
    <table>
        <thead>
            <tr>
                <th>Client</th>
                <th>Note</th>
                <th>Commentaire</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($avisClients as $avis) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($avis['nom'] . ' ' . $avis['prenom'])?></td>
                    <td><?php echo htmlspecialchars($avis['note'])?></td>
                    <td><?php echo htmlspecialchars($avis['commentaire'])?></td>
                    <td><?php echo htmlspecialchars($avis['date_avis'])?></td>
                    <td><?php echo htmlspecialchars($avis['valide'] ? 'Validé' : 'Non validé') ?></td>
                    <td><?php  if (!$avis['valide']) : ?>
                            <a href="valider-avis.php?id=<?= $avis['id'] ?>">Valider</a>
                            <a href="refuser-avis.php?id=<?= $avis['id'] ?>">Refuser</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>