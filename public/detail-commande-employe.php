<?php
session_start();

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/mailer.php';

$pageTitle = 'Détail commande employé';

/* Vérification connexion */
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

/* Vérification rôle */
$role = (int) $_SESSION['utilisateur_role'];

if ($role !== 2 && $role !== 1) {
    header('Location: connexion.php');
    exit();
}

/* Vérification ID commande */
$commande_id = (int) ($_GET['id'] ?? 0);

if ($commande_id <= 0) {
    header('Location: employe-commandes.php');
    exit();
}

/* Message erreur éventuel */
$messageErreur = null;

/* Récupération de la commande */
$sql = "
    SELECT
        commande.*,
        utilisateur.nom,
        utilisateur.prenom,
        utilisateur.email,
        utilisateur.telephone,
        menu.titre AS menu_titre,
        menu.description AS menu_description,
        menu.condition_menu,
        menu.stock_disponible
    FROM commande
    JOIN utilisateur ON commande.utilisateur_id = utilisateur.id
    JOIN menu ON commande.menu_id = menu.id
    WHERE commande.id = ?
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$commande_id]);

$commande = $stmt->fetch(PDO::FETCH_ASSOC);

/* Si commande inexistante */
if (!$commande) {
    header('Location: employe-commandes.php');
    exit();
}

/* Labels statuts lisibles */
$statuts_labels = [
    'en_attente' => 'En attente',
    'accepte' => 'Accepté',
    'annulee' => 'Annulée',
    'en_preparation' => 'En préparation',
    'en_cours_de_livraison' => 'En cours de livraison',
    'livree' => 'Livrée',
    'en_attente_retour_materiel' => 'En attente du retour matériel',
    'terminee' => 'Terminée'
];

/* Mise à jour du statut */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nouveau_statut = $_POST['statut_commande'] ?? '';

    $statuts_autorises = [
        'accepte',
        'en_preparation',
        'en_cours_de_livraison',
        'livree',
        'en_attente_retour_materiel',
        'terminee'
    ];

    if (in_array($nouveau_statut, $statuts_autorises, true)) {

        /*
            Si la commande passe de :
            en_attente -> accepte
            alors on retire le nombre de personnes du stock
        */
        if ($commande['statut_commande'] === 'en_attente' && $nouveau_statut === 'accepte') {

            $stockDisponible = (int) $commande['stock_disponible'];
            $nbPersonnes = (int) $commande['nb_personnes'];

            /* Vérification stock suffisant */
            if ($stockDisponible < $nbPersonnes) {

                $messageErreur = "Impossible d'accepter cette commande : stock insuffisant.";

            } else {

                $stmtStock = $pdo->prepare("
                    UPDATE menu
                    SET stock_disponible = stock_disponible - ?
                    WHERE id = ?
                ");

                $stmtStock->execute([
                    $nbPersonnes,
                    $commande['menu_id']
                ]);
            }
        }

        /* Si pas d'erreur, mise à jour du statut */
        if ($messageErreur === null) {

            $stmtUpdate = $pdo->prepare("
                UPDATE commande
                SET statut_commande = ?
                WHERE id = ?
            ");

            $stmtUpdate->execute([
                $nouveau_statut,
                $commande_id
            ]);

            $statutLisible = $statuts_labels[$nouveau_statut] ?? $nouveau_statut;

            if ($nouveau_statut === 'en_attente_retour_materiel') {
                $sujetMail = 'retour matériel pour votre commande';

                $messageMail = "
                <p>Bonjour " . htmlspecialchars($commande['prenom']) . ",</p>

                <p>Votre commande est en attente du retour du matériel.</p>

                <p>Conformément à nos conditions générales de vente, le matériel doit être restitué sous 10 jours ouvrés.</p>

                <p>Sans retour du matériel dans ce délai, des frais de 600 € pourront être appliqués.</p>

                <p>Merci de prendre contact avec Vite et Gourmand afin d'organiser la restitution du matériel.</p>

                <p>L'équipe Vite et Gourmand</p>
                ";
            } else {
                $sujetMail = 'Mise à jour de votre commande';

                $messageMail = "
                            <p>Bonjour " . htmlspecialchars($commande['prenom']) . ",</p>

                            <p>Le statut de votre commande a été mis à jour.</p>

                            <p><strong>Nouveau statut :</strong> " . htmlspecialchars($statutLisible) . "</p>

                            <p><strong>Menu :</strong> " . htmlspecialchars($commande['menu_titre']) . "</p>

                            <p>Merci pour votre confiance.</p>

                            <p>L'équipe Vite et Gourmand</p>";
            }

            envoyerEmail($commande['email'], $sujetMail, $messageMail);

            header('Location: detail-commande-employe.php?id=' . $commande_id);
            exit();
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<main class="detail-commande-employe">

    <h1>Détail de la commande</h1>

    <?php if ($messageErreur): ?>
        <p class="message-erreur">
            <?= htmlspecialchars($messageErreur) ?>
        </p>
    <?php endif; ?>

    <section>
        <h2>Client</h2>

        <p>
            <strong>Nom :</strong>
            <?= htmlspecialchars($commande['prenom'] . ' ' . $commande['nom']) ?>
        </p>

        <p>
            <strong>Email :</strong>
            <?= htmlspecialchars($commande['email']) ?>
        </p>

        <p>
            <strong>Téléphone :</strong>
            <?= htmlspecialchars($commande['telephone']) ?>
        </p>
    </section>

    <section>
        <h2>Commande</h2>

        <p>
            <strong>Menu :</strong>
            <?= htmlspecialchars($commande['menu_titre']) ?>
        </p>

        <p>
            <strong>Description :</strong>
            <?= htmlspecialchars($commande['menu_description']) ?>
        </p>

        <p>
            <strong>Date de commande :</strong>
            <?= htmlspecialchars($commande['date_commande']) ?>
        </p>

        <p>
            <strong>Date de prestation :</strong>
            <?= htmlspecialchars($commande['date_prestation']) ?>
        </p>

        <p>
            <strong>Heure de livraison :</strong>
            <?= htmlspecialchars($commande['heure_livraison']) ?>
        </p>

        <p>
            <strong>Nombre de personnes :</strong>
            <?= htmlspecialchars($commande['nb_personnes']) ?>
        </p>

        <p>
            <strong>Prix total :</strong>
            <?= htmlspecialchars($commande['prix_total']) ?> €
        </p>

        <p>
            <strong>Prix livraison :</strong>
            <?= htmlspecialchars($commande['prix_livraison']) ?> €
        </p>

        <p>
            <strong>Stock restant :</strong>
            <?= htmlspecialchars($commande['stock_disponible']) ?>
        </p>

        <p>
            <strong>Statut actuel :</strong>
            <?= htmlspecialchars(
                $statuts_labels[$commande['statut_commande']]
                ?? $commande['statut_commande']
            ) ?>
        </p>
    </section>

    <section>
        <h2>Modifier le statut</h2>

        <form method="POST">

            <label for="statut_commande">
                Nouveau statut
            </label>

            <select name="statut_commande" id="statut_commande" required>
                <option value="">
                    -- Choisir un statut --
                </option>

                <option value="accepte">
                    Accepté
                </option>

                <option value="en_preparation">
                    En préparation
                </option>

                <option value="en_cours_de_livraison">
                    En cours de livraison
                </option>

                <option value="livree">
                    Livrée
                </option>

                <option value="en_attente_retour_materiel">
                    En attente du retour matériel
                </option>

                <option value="terminee">
                    Terminée
                </option>

            </select>

            <button type="submit">
                Mettre à jour
            </button>

        </form>
    </section>

    <section>
        <h2>Adresse de livraison</h2>

        <p>
            <?= htmlspecialchars($commande['adresse_livraison']) ?><br>

            <?= htmlspecialchars($commande['code_postal']) ?>
            <?= htmlspecialchars($commande['ville_livraison']) ?>
        </p>
    </section>

    <section>
        <h2>Conditions du menu</h2>

        <p>
            <?= nl2br(htmlspecialchars($commande['condition_menu'])) ?>
        </p>
    </section>

    <div class="actions">

        <a href="employe-commandes.php">
            Retour aux commandes
        </a>

        <?php if (
            $commande['statut_commande'] !== 'terminee'
            && $commande['statut_commande'] !== 'annulee'
        ): ?>

            <a href="annuler-commande-employe.php?id=<?= $commande['id'] ?>">
                Annuler la commande
            </a>

        <?php endif; ?>

    </div>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>