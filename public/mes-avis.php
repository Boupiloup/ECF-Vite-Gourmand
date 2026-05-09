<?php
session_start();
$pageTitle = "Mes avis";

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id'];
$message = null;

require_once '../includes/db.php';

/* Traitement envoi avis */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $commande_id = (int) ($_POST['commande_id'] ?? 0);
    $note = (int) ($_POST['note'] ?? 0);
    $commentaire = trim($_POST['commentaire'] ?? '');

    if (
        empty($commande_id) ||
        empty($note) ||
        empty($commentaire)
    ) {
        $message = "Tous les champs sont obligatoires.";
    } elseif ($note < 1 || $note > 5) {
        $message = "La note doit être comprise entre 1 et 5.";
    } else {

        $sqlCheck = "SELECT * FROM avis 
                     WHERE commande_id = ? 
                     AND utilisateur_id = ?";

        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute([$commande_id, $utilisateur_id]);

        $avisExistant = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($avisExistant) {
            $message = "Vous avez déjà laissé un avis pour cette commande.";
        } else {

            $sqlInsert = "INSERT INTO avis (
                            note,
                            commentaire,
                            utilisateur_id,
                            commande_id
                          ) VALUES (?, ?, ?, ?)";

            $stmtInsert = $pdo->prepare($sqlInsert);
            $stmtInsert->execute([
                $note,
                $commentaire,
                $utilisateur_id,
                $commande_id
            ]);

            $message = "Votre avis a bien été enregistré.";
        }
    }
}

/* Commandes terminées sans avis */
$sql = "
    SELECT 
        commande.id,
        menu.titre
    FROM commande
    INNER JOIN menu ON commande.menu_id = menu.id
    WHERE commande.utilisateur_id = ?
    AND commande.statut_commande = 'terminee'
    AND commande.id NOT IN (
        SELECT commande_id 
        FROM avis 
        WHERE utilisateur_id = ?
    )
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$utilisateur_id, $utilisateur_id]);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once __DIR__ . '/../includes/header.php';
?>

<main class="mes-avis-page">

    <h1>Mes avis</h1>

    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if (empty($commandes)): ?>

        <p>Aucune commande terminée en attente d'avis.</p>

    <?php else: ?>

        <?php foreach ($commandes as $commande): ?>

            <section class="avis-card">

                <h2><?= htmlspecialchars($commande['titre']) ?></h2>

                <form method="post">

                    <input type="hidden" name="commande_id" value="<?= htmlspecialchars($commande['id']) ?>">

                    <div class="form-group">
                        <label for="note">Note (1 à 5)</label>
                        <input type="number" name="note" min="1" max="5" required>
                    </div>

                    <div class="form-group">
                        <label for="commentaire">Commentaire</label>
                        <textarea name="commentaire" required></textarea>
                    </div>

                    <button type="submit">
                        Envoyer mon avis
                    </button>

                </form>

            </section>

        <?php endforeach; ?>

    <?php endif; ?>

</main>

<?php
include_once __DIR__ . '/../includes/footer.php';
?>