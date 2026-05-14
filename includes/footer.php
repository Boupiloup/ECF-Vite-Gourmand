<?php
require_once __DIR__ . '/db.php';

$jours = [
    'Monday' => 'lundi',
    'Tuesday' => 'mardi',
    'Wednesday' => 'mercredi',
    'Thursday' => 'jeudi',
    'Friday' => 'vendredi',
    'Saturday' => 'samedi',
    'Sunday' => 'dimanche'
];

$jourActuel = $jours[date('l')];

/* Récupération de tous les horaires */
$sql = "
    SELECT jour, heure_ouverture, heure_fermeture
    FROM horaire
    ORDER BY FIELD(jour, 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'), heure_ouverture ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$horaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Regroupe les horaires par jour */
$horairesParJour = [];

foreach ($horaires as $horaire) {
    $jour = $horaire['jour'];

    $creneau = substr($horaire['heure_ouverture'], 0, 5)
        . ' - '
        . substr($horaire['heure_fermeture'], 0, 5);

    if (isset($horairesParJour[$jour])) {
        $horairesParJour[$jour] .= ' / ' . $creneau;
    } else {
        $horairesParJour[$jour] = $creneau;
    }
}
?>

<footer class="site-footer">
    <div class="footer-container">

        <div class="footer-info">
            <p><strong>Horaires aujourd’hui</strong></p>

            <p>
                <?= htmlspecialchars(ucfirst($jourActuel)) ?> :
                <?= htmlspecialchars($horairesParJour[$jourActuel] ?? 'Fermé') ?>
            </p>

            <details>
                <summary>Voir tous les horaires</summary>

                <?php foreach ($horairesParJour as $jour => $horaire) : ?>
                    <p>
                        <?= htmlspecialchars(ucfirst($jour)) ?> :
                        <?= htmlspecialchars($horaire) ?>
                    </p>
                <?php endforeach; ?>
            </details>
        </div>

        <nav class="footer-links">
            <ul>
                <li><a href="mentions_legal.php">Mentions légales</a></li>
                <li><a href="cgv.php">CGV</a></li>
            </ul>
        </nav>

    </div>
</footer>

</body>
</html>