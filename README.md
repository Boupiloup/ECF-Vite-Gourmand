# ECF-Vite-Gourmand

## Projet ECF – TP Développeur Web et Web Mobile

Application Vite & Gourmand

Projet réalisé dans le cadre de l’ECF Développeur Web et Web Mobile.

Application web de réservation de prestations culinaires avec gestion des utilisateurs, commandes, espace employé, espace administrateur, notifications email et statistiques.

---

## Technologies utilisées

### Front-end

- HTML5
- CSS3
- JavaScript

### Back-end

- PHP natif (PHP 8+)
- PDO

### Base de données

- MySQL / MariaDB
- MongoDB

### Outils de développement

- Visual Studio Code
- XAMPP
- Composer
- Mailpit
- Git
- GitHub

---

## Installation du projet en local

### 1. Cloner le projet

```bash
git clone https://github.com/Boupiloup/ECF-Vite-Gourmand.git
```

---

### 2. Installer les dépendances

Installer Composer puis exécuter :

```bash
composer install
```

---

### 3. Configurer le fichier .env

Créer :

```text
.env
```

à partir du fichier :

```text
.env.example
```

Exemple :

```env
# Connexion MySQL
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=bdd_ecf_vite_et_gourmand
DB_USER=root
DB_PASSWORD=

# Configuration emails (Mailpit)
MAIL_HOST=localhost
MAIL_PORT=1025
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM=noreply@viteetgourmand.fr
MAIL_FROM_NAME=Vite et Gourmand

# Connexion MongoDB
MONGODB_URI=mongodb://localhost:27017
MONGODB_DATABASE=vite_gourmand_nosql
```

---

### 4. Importer la base de données

Ouvrir :

```text
http://localhost/phpmyadmin
```

Créer la base :

```text
bdd_ecf_vite_et_gourmand
```

Importer :

```text
bdd_ecf_vite_et_gourmand.sql
```

---

### 5. Installer MongoDB

Installer MongoDB Community Server.

Vérifier l’installation :

```bash
mongod --version
```

Ouvrir le shell :

```bash
mongosh
```

---

### 6. Démarrer Mailpit

Ouvrir un terminal.

Se placer dans le dossier Mailpit :

```bash
cd chemin/vers/mailpit
```

Exemple :

```bash
cd C:\xampp\htdocs\ECF_Vite_et_Gourmand\mailpit
```

Lancer :

```bash
mailpit.exe
```

Interface :

```text
http://localhost:8025
```

SMTP utilisé :

```text
localhost:1025
```

---

### 7. Lancer le projet

Démarrer :

```text
Apache
MySQL
```

depuis XAMPP.

Vérifier également :

```text
MongoDB
Mailpit
```

Accéder au projet :

```text
http://localhost/ECF_Vite_et_Gourmand/public/
```

---

## Comptes de démonstration

### Administrateur

```text
Email : admin@test.fr
Mot de passe : Admin123+
```

### Employé

```text
Email : employe@test.fr
Mot de passe : Employe123+
```

### Utilisateur

```text
Email : user@test.fr
Mot de passe : User123+
```

---

## Fonctionnalités

- authentification
- menus et filtres
- commandes
- espace utilisateur
- espace employé
- espace administrateur
- emails
- statistiques MongoDB

---

## Sécurité

- PDO
- requêtes préparées
- protection XSS
- password_hash()
- password_verify()
- contrôle des rôles

---

## Gestion Git

```text
feature → develop → main
```

---

## Livrables

Le dépôt contient :

- code source
- README
- fichiers SQL
- manuel utilisateur
- documentation technique
- documentation gestion projet
- charte graphique

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
