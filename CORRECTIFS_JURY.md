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

