<?php

try {

    if (getenv('JAWSDB_URL')) {

        // Connexion MySQL distante avec JawsDB sur Heroku
        $url = parse_url(getenv('JAWSDB_URL'));

        $DB_HOST = $url['host'];
        $DB_PORT = $url['port'] ?? 3306;
        $DB_NAME = ltrim($url['path'], '/');
        $DB_USER = $url['user'];
        $DB_PASSWORD = $url['pass'];

    } else {

        // Connexion MySQL locale avec le fichier .env
        $envPath = __DIR__ . '/../.env';

        if (!file_exists($envPath)) {
            die("Erreur : fichier .env introuvable.");
        }

        $env = parse_ini_file($envPath);

        $DB_HOST = $env['DB_HOST'];
        $DB_PORT = $env['DB_PORT'];
        $DB_NAME = $env['DB_NAME'];
        $DB_USER = $env['DB_USER'];
        $DB_PASSWORD = $env['DB_PASSWORD'];
    }

    // DSN = adresse complète de la base de données pour PDO
    $dsn = "mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_NAME;charset=utf8mb4";

    // Connexion à la base de données avec PDO
    $pdo = new PDO($dsn, $DB_USER, $DB_PASSWORD);

    // Active les erreurs PDO en mode exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    die("Erreur de connexion à la base de données : " . $e->getMessage());
}