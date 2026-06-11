<?php

// Je récupère ma connexion à la base de données
require_once __DIR__ . '/../../includes/db.php';

// Je charge le Controller.
// Le Controller chargera ensuite le Service, le Repository et l'Entity.
require_once __DIR__ . '/../../src/Controller/MenuController.php';

// Je crée le Repository en lui donnant la connexion PDO
$menuRepository = new MenuRepository($pdo);

// Je crée le Service en lui donnant le Repository
$menuService = new MenuService($menuRepository);

// Je crée le Controller en lui donnant le Service
$menuController = new MenuController($menuService);

// Je lance le filtrage avec les paramètres envoyés dans l'URL
$menuController->filter($_GET);