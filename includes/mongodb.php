<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;

/* Lecture du fichier .env en local, Config Vars en production */
$env = file_exists(__DIR__ . '/../.env')
    ? parse_ini_file(__DIR__ . '/../.env')
    : [];

function getMongoConfigValue($key, $env)
{
    return getenv($key) ?: ($env[$key] ?? null);
}

$mongoUri = getMongoConfigValue('MONGODB_URI', $env);
$mongoDatabaseName = getMongoConfigValue('MONGODB_DATABASE', $env) ?: 'vite_gourmand_nosql';

if (!$mongoUri || strpos($mongoUri, '<db_password>') !== false) {
    throw new RuntimeException('Configuration MongoDB manquante.');
}

/* Connexion a MongoDB */
$mongoClient = new Client($mongoUri);

/* Selection de la base MongoDB */
$mongoDatabase = $mongoClient->{$mongoDatabaseName};
