<?php

require_once __DIR__ . '/../Repository/MenuRepository.php';

class MenuService
{
    // Je stocke le Repository pour pouvoir lui demander les menus
    private MenuRepository $menuRepository;

    // Quand je crée le Service, je lui donne le Repository
    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }

    // Cette méthode reçoit les paramètres envoyés dans l'URL
    // Exemple : prix_max, theme, regime_alimentaire, etc.
    public function getFilteredMenus(array $queryParams): array
    {
        // Je prépare un tableau vide qui contiendra uniquement les filtres valides
        $filters = [];

        // Si le prix maximum existe et que c'est bien un nombre,
        // je l'ajoute dans les filtres
        if (isset($queryParams['prix_max']) && is_numeric($queryParams['prix_max'])) {
            $filters['prix_max'] = (float) $queryParams['prix_max'];
        }

        // Si une fourchette de prix est choisie, exemple : 200-500
        if (!empty($queryParams['fourchette_prix'])) {
            $prix = explode('-', $queryParams['fourchette_prix']);

            // Je vérifie que la fourchette contient bien deux nombres
            if (count($prix) === 2 && is_numeric($prix[0]) && is_numeric($prix[1])) {
                $filters['prix_min_fourchette'] = (float) $prix[0];
                $filters['prix_max_fourchette'] = (float) $prix[1];
            }
        }

        // Si le thème existe et que c'est bien un entier,
        // je l'ajoute dans les filtres
        if (isset($queryParams['theme']) && ctype_digit((string) $queryParams['theme'])) {
            $filters['theme'] = (int) $queryParams['theme'];
        }

        // Si le régime alimentaire existe et que c'est bien un entier,
        // je l'ajoute dans les filtres
        if (isset($queryParams['regime_alimentaire']) && ctype_digit((string) $queryParams['regime_alimentaire'])) {
            $filters['regime'] = (int) $queryParams['regime_alimentaire'];
        }

        // Si le nombre de personnes existe et que c'est bien un nombre,
        // je l'ajoute dans les filtres
        if (isset($queryParams['nombre_personnes']) && is_numeric($queryParams['nombre_personnes'])) {
            $filters['nombre_personnes'] = (int) $queryParams['nombre_personnes'];
        }

        // Une fois les filtres préparés, je demande au Repository
        // de récupérer les menus correspondants en base de données
        return $this->menuRepository->findByFilters($filters);
    }
}