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