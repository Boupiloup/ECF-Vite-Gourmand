<?php 
require_once __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;

$mongoClient = new Client("mongodb://localhost:27017");

$mongoDatabase = $mongoClient->vite_gourmand_nosql;
?>