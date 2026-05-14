<?php
session_start();

require_once __DIR__ . '/../includes/db.php';

$pageTitle = 'Laisser un avis';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

$utilisateur_id = (int) $_SESSION['utilisateur_id'];
$commande_id = (int) ($_GET['commande_id'] ?? $_POST['commande_id'] ?? 0);

if ($commande_id <= 0) {
    header('Location: mes-commandes.php');
    exit();
}

/* Vérifie que la commande appartient bien à l'utilisateur
   et qu'elle est terminée */
$sql = "
    SELECT id, statut_commande
    FROM commande
    WHERE id = ? AND utilisateur_id = ?
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$commande_id, $utilisateur_id]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commande || $commande['statut_commande'] !== 'terminee') {
    header('Location: mes-commandes.php');
    exit();
}

$messageErreur = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $note = (int) ($_POST['note'] ?? 0);
    $commentaire = trim($_POST['commentaire'] ?? '');

    /* Vérifie les champs */
    if ($note < 1 || $note > 5 || empty($commentaire)) {

        $messageErreur = "Veuillez remplir correctement tous les champs.";

    } else {

        /* Vérifie si un avis existe déjà pour cette commande */
        $sql = "SELECT id FROM avis WHERE commande_id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$commande_id]);

        $avisExistant = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($avisExistant) {

            $messageErreur = "Vous avez déjà laissé un avis pour cette commande.";

        } else {

            /* Insère le nouvel avis */
            $sql = "
                INSERT INTO avis (
                    note,
                    commentaire,
                    date_avis,
                    valide,
                    utilisateur_id,
                    commande_id
                )
                VALUES (?, ?, NOW(), 0, ?, ?)
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $note,
                $commentaire,
                $utilisateur_id,
                $commande_id
            ]);

            header('Location: mes-commandes.php');
            exit();
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<main class="laisser-avis-page">

    <h1>Laisser un avis</h1>

    <?php if (!empty($messageErreur)) : ?>
        <p><?= htmlspecialchars($messageErreur) ?></p>
    <?php endif; ?>

    <form method="POST">

        <input
            type="hidden"
            name="commande_id"
            value="<?= htmlspecialchars($commande_id) ?>"
        >

        <label for="note">Note :</label>
        <select name="note" id="note" required>
            <option value="">-- Choisir une note --</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>

        <label for="commentaire">Commentaire :</label>
        <textarea
            name="commentaire"
            id="commentaire"
            required
        ></textarea>

        <button type="submit">
            Envoyer mon avis
        </button>

    </form>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>