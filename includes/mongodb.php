<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;

/* Lecture du fichier .env */
$env = parse_ini_file(__DIR__ . '/../.env');

/* Connexion à MongoDB */
$mongoClient = new Client($env['MONGODB_URI']);

/* Sélection de la base MongoDB */
$mongoDatabase = $mongoClient->{$env['MONGODB_DATABASE']};