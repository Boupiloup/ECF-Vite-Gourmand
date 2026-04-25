<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Vite & Gourmand' ?></title>

    <!-- Ajout de la fonts "inter" " Playfair Display " & " Playfair"  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Playfair:ital,opsz,wght@0,5..1200,300..900;1,5..1200,300..900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/menus.css">
    <link rel="stylesheet" href="assets/css/vuedetail.css"> 
    
</head>

<body>
    <header class="site-header">
        <div class="header-container">

            <img class="site-logo" src="assets/img/logo/Logo_ViteEtGourmand.png" alt="Logo Vite &amp; Gourmand">

            <nav class="site-nav">
                <ul class="site-nav-list">
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="menus.php">Menus</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <?php if (isset($_SESSION['utilisateur_id'])): ?>
                        <li><a href="mes-commandes.php">Mes commandes</a></li>
                        <li><a href="deconnexion.php">Déconnexion</a></li>
                    <?php else: ?>
                        <li><a href="connexion.php">Connexion</a></li>
                    <?php endif; ?>
                </ul>
            </nav>

            <div class="header-right"></div> <!-- bloc vide à droite pour équilibrer -->

        </div>
    </header>
