<?php
session_start();

require_once __DIR__ . '/../includes/db.php';

$pageTitle = 'Annuler commande employé';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

$role = (int) $_SESSION['utilisateur_role'];

if ($role !== 2 && $role !== 1) {
    header('Location: connexion.php');
    exit();
}

$commande_id = (int) ($_GET['id'] ?? 0);

if ($commande_id <= 0) {
    header('Location: employe-commandes.php');
    exit();
}

$sql = "
    SELECT
        commande.*,
        utilisateur.nom,
        utilisateur.prenom,
        utilisateur.email
    FROM commande
    JOIN utilisateur ON commande.utilisateur_id = utilisateur.id
    WHERE commande.id = ?
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$commande_id]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commande) {
    header('Location: employe-commandes.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $modeContactAnnulation = $_POST['mode_contact_annulation'] ?? '';
    $motifAnnulation = $_POST['motif_annulation'] ?? '';
    
    if (empty($modeContactAnnulation) || empty($motifAnnulation)) {
        $messageErreur = 'veuillez remplir tous les champs.';
    } else {
        $sql="UPDATE commande SET statut_commande = 'annulee', mode_contact_annulation = ?, motif_annulation = ? WHERE id = ?";
        
        $stmtUpdate = $pdo->prepare($sql);

        $stmtUpdate->execute([$modeContactAnnulation, $motifAnnulation, $commande_id]);
        
        header('Location: detail-commande-employe.php?id=' . $commande_id);
        exit();
    }
} 

require_once __DIR__ . '/../includes/header.php';
?>

<main class="annuler-commande-employe">
    <h1>Annuler la commande</h1>

    <p>
        Commande de <?= htmlspecialchars($commande['prenom'] . ' ' . $commande['nom']) ?>
    </p>

    <form method="POST">
        <label for="mode_contact_annulation">Mode de contact utilisé</label>

        <select name="mode_contact_annulation" id="mode_contact_annulation" required>
            <option value="">-- Choisir --</option>
            <option value="telephone">Téléphone</option>
            <option value="email">Email</option>
        </select>

        <label for="motif_annulation">Motif d'annulation</label>

        <textarea name="motif_annulation" id="motif_annulation" required></textarea>

        <button type="submit">Confirmer l'annulation</button>
    </form>

    <a href="detail-commande-employe.php?id=<?= $commande_id ?>">Retour</a>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>