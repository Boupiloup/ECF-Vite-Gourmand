<?php
session_start();

require_once __DIR__ . '/../includes/db.php';

$pageTitle = 'Modifier un horaire';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

$role = (int) $_SESSION['utilisateur_role'];

if ($role !== 2 && $role !== 1) {
    header('Location: connexion.php');
    exit();
}

$horaireId = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

if ($horaireId <= 0) {
    header('Location: gestion-horaires.php');
    exit();
}

$sql = "SELECT * FROM horaire WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$horaireId]);
$horaire = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$horaire) {
    header('Location: gestion-horaires.php');
    exit();
}

$messageErreur = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $heureOuverture = $_POST['heure_ouverture'] ?? '';
    $heureFermeture = $_POST['heure_fermeture'] ?? '';

    if (empty($heureOuverture) || empty($heureFermeture)) {
        $messageErreur = 'Veuillez renseigner les deux horaires.';
    } else {
        $sql = "
            UPDATE horaire
            SET heure_ouverture = ?, heure_fermeture = ?
            WHERE id = ?
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $heureOuverture,
            $heureFermeture,
            $horaireId
        ]);

        header('Location: gestion-horaires.php');
        exit();
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<main class="modifier-horaire-page">

    <h1>Modifier l'horaire du <?= htmlspecialchars($horaire['jour']) ?></h1>

    <?php if (!empty($messageErreur)) : ?>
        <p><?= htmlspecialchars($messageErreur) ?></p>
    <?php endif; ?>

    <form method="POST">

        <input type="hidden" name="id" value="<?= htmlspecialchars($horaire['id']) ?>">

        <label for="heure_ouverture">Heure d'ouverture</label>
        <input
            type="time"
            id="heure_ouverture"
            name="heure_ouverture"
            value="<?= htmlspecialchars($horaire['heure_ouverture']) ?>"
            required
        >

        <label for="heure_fermeture">Heure de fermeture</label>
        <input
            type="time"
            id="heure_fermeture"
            name="heure_fermeture"
            value="<?= htmlspecialchars($horaire['heure_fermeture']) ?>"
            required
        >

        <button type="submit">Modifier l'horaire</button>

    </form>

    <a href="gestion-horaires.php">Retour à la gestion des horaires</a>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>