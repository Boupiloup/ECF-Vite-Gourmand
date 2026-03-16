<?php

// Lire le fichier .env (contient les infos de connexion à la bdd)
$env = parse_ini_file(__DIR__ . '/../.env');


// On récupère les valeurs du .env pour les mettre dans des variables plus simples
$DB_HOST = $env['DB_HOST'];          // serveur MySQL
$DB_PORT = $env['DB_PORT'];          // port MySQL
$DB_NAME = $env['DB_NAME'];          // nom de la bdd
$DB_USER = $env['DB_USER'];          // utilisateur
$DB_PASSWORD = $env['DB_PASSWORD'];  // mot de passe


// DSN = adresse complète de la bdd pour PDO
$dsn = "mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_NAME;charset=utf8mb4";


// Connexion à la bdd avec PDO
try {

    $pdo = new PDO($dsn, $DB_USER, $DB_PASSWORD);

    // Active les erreurs PDO en mode exception (plus facile pour debug)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
catch (PDOException $e) {

    // Si la connexion échoue, on affiche l'erreur et on arrête le script
    die("Erreur de connexion à la base de données : " . $e->getMessage());

}