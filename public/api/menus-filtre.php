<?php

// Je récupère ma connexion à la base de données
require_once __DIR__ . '/../../includes/db.php';

// Je précise que ce fichier ne renvoie pas une page HTML,
// mais des données au format JSON pour mon JavaScript
header('Content-Type: application/json; charset=utf-8');

// Je récupère les filtres envoyés dans l'URL par le fetch()
// Exemple : ?prix_max=500&theme=2&regime_alimentaire=1
// Si un filtre n'est pas envoyé, je mets une chaîne vide
$prixMax = $_GET['prix_max'] ?? '';
$fourchettePrix = $_GET['fourchette_prix'] ?? '';
$theme = $_GET['theme'] ?? '';
$regime = $_GET['regime_alimentaire'] ?? '';
$nombrePersonnes = $_GET['nombre_personnes'] ?? '';

// Je prépare ma requête SQL de base.
// Elle récupère les infos principales des menus,
// avec la première image liée à chaque menu.
$sql = '
    SELECT 
        menu.id,
        menu.titre,
        menu.description,
        menu.nombre_personne_min,
        menu.prix_min,
        image.url AS image_url,
        image.alt AS image_alt
    FROM menu
    LEFT JOIN image 
        ON image.id = (
            SELECT image2.id 
            FROM image AS image2 
            WHERE image2.menu_id = menu.id 
            ORDER BY image2.id ASC 
            LIMIT 1
        )
    WHERE 1 = 1
';

// Je prépare un tableau vide.
// Il va contenir les valeurs qui remplaceront les ? dans ma requête.
$params = [];

// Si l'utilisateur a renseigné un prix maximum,
// je garde seulement les menus dont le prix est inférieur ou égal.
if ($prixMax !== '' && is_numeric($prixMax)) {
    $sql .= ' AND menu.prix_min <= ?';
    $params[] = (float) $prixMax;
}

// Si l'utilisateur a choisi une fourchette de prix,
// exemple : 0-200, je coupe la valeur en deux parties : minimum et maximum.
if ($fourchettePrix !== '') {
    $prix = explode('-', $fourchettePrix);

    if (count($prix) === 2 && is_numeric($prix[0]) && is_numeric($prix[1])) {
        $prixMin = (float) $prix[0];
        $prixMaxFourchette = (float) $prix[1];

        $sql .= ' AND menu.prix_min BETWEEN ? AND ?';
        $params[] = $prixMin;
        $params[] = $prixMaxFourchette;
    }
}

// Si l'utilisateur a choisi un thème,
// je garde seulement les menus liés à ce thème.
if ($theme !== '' && ctype_digit((string) $theme)) {
    $sql .= ' AND menu.theme_id = ?';
    $params[] = (int) $theme;
}

// Si l'utilisateur a choisi un régime alimentaire,
// je garde seulement les menus liés à ce régime.
if ($regime !== '' && ctype_digit((string) $regime)) {
    $sql .= ' AND menu.regime_id = ?';
    $params[] = (int) $regime;
}

// Si l'utilisateur renseigne un nombre de personnes,
// je garde les menus dont le minimum demandé est inférieur ou égal.
// Exemple : s'il met 6, un menu minimum 4 personnes peut être proposé.
if ($nombrePersonnes !== '' && is_numeric($nombrePersonnes)) {
    $sql .= ' AND menu.nombre_personne_min <= ?';
    $params[] = (int) $nombrePersonnes;
}

// Je trie les menus par prix croissant pour avoir un affichage plus propre
$sql .= ' ORDER BY menu.prix_min ASC';

// Je prépare la requête SQL avec PDO.
// Comme les valeurs viennent de l'utilisateur, je ne les mets pas directement dans le SQL.
$stmt = $pdo->prepare($sql);

// J'exécute la requête.
// Les ? seront remplacés proprement par les valeurs du tableau $params.
$stmt->execute($params);

// Je récupère tous les menus trouvés
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Je transforme le tableau PHP en JSON pour que JavaScript puisse le lire
echo json_encode($menus);