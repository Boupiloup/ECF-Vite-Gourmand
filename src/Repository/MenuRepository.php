<?php

require_once __DIR__ . '/../Entity/Menu.php';

class MenuRepository
{
    // Je stocke la connexion à la base de données
    private PDO $pdo;

    // Quand je crée mon Repository, je lui donne la connexion PDO
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Cette méthode récupère les menus en base selon les filtres reçus
    public function findByFilters(array $filters): array
    {
        // Je prépare la requête SQL de base
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

        // Ce tableau contiendra les valeurs des filtres
        // qui remplaceront les ? dans la requête SQL
        $params = [];

        // Si un prix maximum est présent, je filtre les menus par prix
        if (!empty($filters['prix_max'])) {
            $sql .= ' AND menu.prix_min <= ?';
            $params[] = $filters['prix_max'];
        }

        // Si une fourchette de prix est présente, je filtre entre deux prix
        if (!empty($filters['prix_min_fourchette']) && !empty($filters['prix_max_fourchette'])) {
            $sql .= ' AND menu.prix_min BETWEEN ? AND ?';
            $params[] = $filters['prix_min_fourchette'];
            $params[] = $filters['prix_max_fourchette'];
        }

        // Si un thème est présent, je filtre les menus par thème
        if (!empty($filters['theme'])) {
            $sql .= ' AND menu.theme_id = ?';
            $params[] = $filters['theme'];
        }

        // Si un régime alimentaire est présent, je filtre les menus par régime
        if (!empty($filters['regime'])) {
            $sql .= ' AND menu.regime_id = ?';
            $params[] = $filters['regime'];
        }

        // Si un nombre de personnes est présent,
        // je garde les menus dont le minimum demandé est inférieur ou égal
        if (!empty($filters['nombre_personnes'])) {
            $sql .= ' AND menu.nombre_personne_min <= ?';
            $params[] = $filters['nombre_personnes'];
        }

        // Je trie les menus par prix croissant
        $sql .= ' ORDER BY menu.prix_min ASC';

        // Je prépare et j'exécute la requête avec PDO
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        // Je prépare un tableau vide qui contiendra les objets Menu
        $menus = [];

        // Je parcours chaque ligne SQL trouvée
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            // Je transforme chaque ligne de la base en objet Menu
            $menus[] = new Menu(
                (int) $row['id'],
                $row['titre'],
                $row['description'],
                (int) $row['nombre_personne_min'],
                (float) $row['prix_min'],
                $row['image_url'],
                $row['image_alt']
            );
        }

        // Je retourne la liste des menus trouvés
        return $menus;
    }
}