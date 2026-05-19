<?php
session_start();

require_once __DIR__ . '/../includes/mailer.php';

$pageTitle = 'Contact';

$messageSucces = null;
$messageErreur = null;
$titre = '';
$email = '';
$description = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (empty($titre) || empty($email) || empty($description)) {
        $messageErreur = 'Tous les champs sont requis.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $messageErreur = 'L\'adresse email n\'est pas valide.';
    } else {
        $sujetMail = "Nouveau message de contact : " . $titre;

        $messageMail = "
            <h1>Nouveau message de contact</h1>

            <p><strong>Email :</strong> " . htmlspecialchars($email) . "</p>

            <p><strong>Sujet :</strong> " . htmlspecialchars($titre) . "</p>

            <p><strong>Message :</strong></p>

            <p>" . nl2br(htmlspecialchars($description)) . "</p>
        ";

        $emailEntreprise = "contact@viteetgourmand.fr";

        if (envoyerEmail($emailEntreprise, $sujetMail, $messageMail)) {
            $messageSucces = 'Votre message a été envoyé avec succès.';

            $titre = '';
            $email = '';
            $description = '';
        } else {
            $messageErreur = 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer plus tard.';
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<main class="page-contact">
    <h1>Contactez-nous</h1>

    <p>Vous pouvez nous contacter à travers ce formulaire :</p>

    <?php if ($messageSucces): ?>
        <div class="message-succes">
            <?= htmlspecialchars($messageSucces) ?>
        </div>
    <?php endif; ?>

    <?php if ($messageErreur): ?>
        <div class="message-erreur">
            <?= htmlspecialchars($messageErreur) ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label for="titre">Sujet</label>
        <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($titre ?? '') ?>" required>
        <label for="email">Votre email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>
        <label for="description">Message</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($description ?? '') ?></textarea>

        <button type="submit">Envoyer</button>
    </form>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>