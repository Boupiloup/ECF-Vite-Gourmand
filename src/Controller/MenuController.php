<?php

require_once __DIR__ . '/../Service/MenuService.php';

class MenuController
{
    // Je stocke le Service pour pouvoir récupérer les menus filtrés
    private MenuService $menuService;

    // Quand je crée le Controller, je lui donne le Service
    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    // Cette méthode reçoit les paramètres de l'URL
    // puis renvoie les menus au format JSON
    public function filter(array $queryParams): void
    {
        // Je précise que la réponse envoyée sera du JSON
        header('Content-Type: application/json; charset=utf-8');

        // Je demande au Service de récupérer les menus filtrés
        $menus = $this->menuService->getFilteredMenus($queryParams);

        // Je transforme chaque objet Menu en tableau
        $menusArray = [];

        foreach ($menus as $menu) {
            $menusArray[] = $menu->toArray();
        }

        // J'envoie le tableau final en JSON pour le JavaScript
        echo json_encode($menusArray);
    }
}