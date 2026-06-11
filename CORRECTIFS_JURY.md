# Correctifs apportés suite aux observations du formateur

## 03/06/2026 — Mise en place de Docker

Suite aux recommandations formulées lors de l'évaluation, un environnement Docker a été ajouté au projet.

Travaux réalisés :

* Création d'un `Dockerfile` pour l'application PHP 8.2 / Apache.
* Création d'un fichier `docker-compose.yml`.
* Mise en place des services :

  * PHP / Apache
  * MariaDB
  * phpMyAdmin
  * MongoDB
  * Mailpit
* Adaptation du fichier `.env` pour le fonctionnement sous Docker.
* Vérification du bon démarrage de l'ensemble des conteneurs.
* Import de la base de données dans l'environnement Docker.

Le projet peut désormais être lancé via :

```bash
docker compose up -d --build
```

---

## 08/06/2026 — Suppression des utilisations de `innerHTML`

Suite aux recommandations formulées lors de l'évaluation, les manipulations du DOM ont été sécurisées afin d'éviter l'utilisation de `innerHTML`.

Travaux réalisés :

- Remplacement de `innerHTML = ''` par `replaceChildren()` dans `filtresMenus.js`.
- Remplacement des mises à jour dynamiques utilisant `innerHTML` par `textContent` dans `calculTempReel.js`.
- Modification de `commande.php` afin de séparer les libellés fixes et les valeurs dynamiques avec des balises `<span>`.
- Vérification du bon fonctionnement du calcul dynamique de la commande.

Cette modification permet de ne plus injecter de HTML depuis JavaScript et améliore la sécurité ainsi que la maintenabilité du projet.

---

## 08/06/2026 — Vérification et documentation de l'appel asynchrone `fetch()`

Suite aux observations du formateur, le fonctionnement asynchrone du filtrage des menus a été vérifié et documenté.

Après analyse du projet, il a été constaté qu'un appel asynchrone utilisant l'API JavaScript `fetch()` était déjà présent et pleinement fonctionnel.

Travaux réalisés :

- Vérification de l'appel `fetch()` présent dans `public/assets/js/filtresMenus.js`.
- Vérification de l'endpoint PHP `public/api/menus-filtre.php`.
- Confirmation que les filtres sont envoyés en méthode `GET`.
- Confirmation que l'endpoint retourne les menus au format JSON.
- Vérification de la mise à jour des cartes menus sans rechargement complet de la page.
- Documentation de cette fonctionnalité dans le projet.

Cette vérification a permis de confirmer que la communication asynchrone entre le front-end JavaScript et le back-end PHP était déjà implémentée dans le projet, conformément aux attentes exprimées lors de l'évaluation.

---

## 11/06/2026 — Mise en place d'une architecture en couches (POO)

Suite aux recommandations formulées lors de l'évaluation concernant la programmation orientée objet et la séparation des responsabilités, une architecture en couches a été mise en place sur la fonctionnalité de filtrage des menus.

Travaux réalisés :

* Création d'une entité `Menu` représentant un menu de l'application.
* Création d'un `MenuRepository` contenant les accès aux données et les requêtes SQL.
* Création d'un `MenuService` chargé de préparer et valider les filtres reçus depuis la requête HTTP.
* Création d'un `MenuController` chargé de recevoir la requête, appeler les services nécessaires et retourner la réponse JSON.
* Simplification du fichier `public/api/menus-filtre.php` afin qu'il joue uniquement le rôle de point d'entrée de la requête.

Architecture mise en place :

```text
menus-filtre.php
        ↓
MenuController
        ↓
MenuService
        ↓
MenuRepository
        ↓
Menu (Entity)
```

Cette séparation permet de distinguer clairement :

* la gestion de la requête HTTP (Controller) ;
* la logique métier et la préparation des filtres (Service) ;
* l'accès aux données (Repository) ;
* la représentation des données métier (Entity).

Cette modification améliore la lisibilité, la maintenabilité et l'évolutivité du projet tout en répondant aux recommandations formulées lors de l'évaluation.
