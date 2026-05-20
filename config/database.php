<?php

try {

    // HEROKU (JawsDB)
    if (getenv('JAWSDB_URL')) {

        $db = parse_url(getenv('JAWSDB_URL'));

        $DB_HOST = $db['host'];
        $DB_PORT = $db['port'];
        $DB_NAME = ltrim($db['path'], '/');
        $DB_USER = $db['user'];
        $DB_PASSWORD = $db['pass'];

    } else {

        // LOCAL (.env)
        $env = parse_ini_file(__DIR__ . '/../.env');

        $DB_HOST = $env['DB_HOST'];
        $DB_PORT = $env['DB_PORT'];
        $DB_NAME = $env['DB_NAME'];
        $DB_USER = $env['DB_USER'];
        $DB_PASSWORD = $env['DB_PASSWORD'];

    }

    $dsn = "mysql:
        host=$DB_HOST;
        port=$DB_PORT;
        dbname=$DB_NAME;
        charset=utf8mb4";

    $pdo = new PDO(
        $dsn,
        $DB_USER,
        $DB_PASSWORD,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

} catch (PDOException $e) {

    die('Erreur connexion BDD : ' . $e->getMessage());

}