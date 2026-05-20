-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 20 mai 2026 à 06:08
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bdd_ecf_vite_et_gourmand`
--
CREATE DATABASE IF NOT EXISTS `bdd_ecf_vite_et_gourmand` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `bdd_ecf_vite_et_gourmand`;

-- --------------------------------------------------------

--
-- Structure de la table `allergene`
--

DROP TABLE IF EXISTS `allergene`;
CREATE TABLE `allergene` (
  `id` int(11) NOT NULL,
  `libelle` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `allergene`
--

INSERT INTO `allergene` (`id`, `libelle`) VALUES
(4, 'arachides'),
(7, 'crustaces'),
(5, 'fruits_a_coque'),
(1, 'gluten'),
(2, 'lactose'),
(8, 'mollusques'),
(3, 'oeufs'),
(6, 'poisson'),
(9, 'soja');

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

DROP TABLE IF EXISTS `avis`;
CREATE TABLE `avis` (
  `id` int(11) NOT NULL,
  `note` tinyint(1) NOT NULL,
  `commentaire` varchar(255) NOT NULL,
  `date_avis` datetime NOT NULL DEFAULT current_timestamp(),
  `valide` tinyint(1) NOT NULL DEFAULT 0,
  `utilisateur_id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`id`, `note`, `commentaire`, `date_avis`, `valide`, `utilisateur_id`, `commande_id`) VALUES
(38, 4, 'Excellent menu je recommande parfaitement !', '2026-05-20 04:01:32', 1, 23, 40),
(39, 3, 'très bien ! excellent service et prestation', '2026-05-20 04:22:50', 1, 23, 43),
(40, 5, 'Parfait !', '2026-05-20 04:23:26', 1, 23, 42);

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE `commande` (
  `id` int(11) NOT NULL,
  `date_commande` datetime NOT NULL DEFAULT current_timestamp(),
  `date_prestation` date NOT NULL,
  `heure_livraison` time NOT NULL,
  `adresse_livraison` varchar(255) NOT NULL,
  `ville_livraison` varchar(100) NOT NULL,
  `code_postal` varchar(10) NOT NULL,
  `distance_km` decimal(6,2) NOT NULL DEFAULT 0.00,
  `nb_personnes` int(11) NOT NULL,
  `prix_total` decimal(10,2) NOT NULL,
  `prix_livraison` decimal(10,2) NOT NULL DEFAULT 0.00,
  `utilisateur_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `statut_commande` enum('en_attente','accepte','annulee','en_preparation','en_cours_de_livraison','livree','en_attente_retour_materiel','terminee') NOT NULL DEFAULT 'en_attente',
  `mode_contact_annulation` varchar(50) DEFAULT NULL,
  `motif_annulation` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id`, `date_commande`, `date_prestation`, `heure_livraison`, `adresse_livraison`, `ville_livraison`, `code_postal`, `distance_km`, `nb_personnes`, `prix_total`, `prix_livraison`, `utilisateur_id`, `menu_id`, `statut_commande`, `mode_contact_annulation`, `motif_annulation`) VALUES
(39, '2026-05-20 03:57:46', '2026-05-26', '10:00:00', '6 rue des cerisier', 'bordeaux', '33000', 0.00, 25, 70.88, 0.00, 23, 1, 'en_attente', NULL, NULL),
(40, '2026-05-20 03:58:05', '2026-06-27', '05:00:00', '6 rue des cerisier', 'bordeaux', '33000', 0.00, 150, 425.25, 0.00, 23, 1, 'terminee', NULL, NULL),
(41, '2026-05-20 03:58:46', '2026-12-24', '06:00:00', '6 rue des cerisier', 'bordeaux', '33000', 0.00, 125, 420.47, 0.00, 23, 2, 'accepte', NULL, NULL),
(42, '2026-05-20 04:21:03', '2026-05-30', '09:00:00', '6 rue des cerisier', 'bordeaux', '33000', 0.00, 26, 87.46, 0.00, 23, 2, 'terminee', NULL, NULL),
(43, '2026-05-20 04:21:23', '2026-05-28', '09:00:00', '6 rue des cerisier', 'bordeaux', '33000', 0.00, 60, 295.65, 0.00, 23, 3, 'terminee', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `horaire`
--

DROP TABLE IF EXISTS `horaire`;
CREATE TABLE `horaire` (
  `id` int(11) NOT NULL,
  `jour` enum('lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche') NOT NULL,
  `heure_ouverture` time NOT NULL,
  `heure_fermeture` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `horaire`
--

INSERT INTO `horaire` (`id`, `jour`, `heure_ouverture`, `heure_fermeture`) VALUES
(1, 'lundi', '10:00:00', '14:00:00'),
(3, 'lundi', '19:00:00', '23:00:00'),
(4, 'mardi', '10:00:00', '14:00:00'),
(5, 'mardi', '19:00:00', '23:00:00'),
(6, 'mercredi', '10:00:00', '14:00:00'),
(7, 'mercredi', '19:00:00', '23:00:00'),
(8, 'jeudi', '10:00:00', '14:00:00'),
(9, 'jeudi', '19:00:00', '23:00:00'),
(10, 'vendredi', '10:00:00', '14:00:00'),
(11, 'vendredi', '19:00:00', '23:00:00'),
(12, 'samedi', '10:00:00', '14:00:00'),
(13, 'samedi', '19:00:00', '23:00:00'),
(14, 'dimanche', '10:00:00', '14:00:00'),
(15, 'dimanche', '19:00:00', '23:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `alt` varchar(255) NOT NULL,
  `menu_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`id`, `url`, `alt`, `menu_id`) VALUES
(1, 'assets/img/image/plat/buffet.jpg', 'Menu Classique - photo 1', 1),
(2, 'assets/img/image/plat/cuisine.png', 'Menu Classique - photo 2', 4),
(3, 'assets/img/image/plat/plat1.jpg', 'Menu Noël - photo 1', 5),
(4, 'assets/img/image/plat/plat2.png', 'Menu Noël - photo 2', 2),
(6, 'assets/img/image/plat/plat3.png', 'Menu Vegan - photo 2', 3),
(7, 'assets/img/image/plat/plat4.jpg', 'Photo de plat ', 7),
(8, 'assets/img/image/plat/plat5.jpg', 'Photo de plat ', 8),
(12, 'assets/img/image/plat/plat6.png', 'Photo de plat ', 8),
(13, 'assets/img/image/plat/plat7.png', 'Photo de plat ', 9),
(14, 'assets/img/image/plat/plat8.png', 'Photo de plat ', 10),
(15, 'assets/img/image/plat/plat9.png', 'Photo de plat ', 1);

-- --------------------------------------------------------

--
-- Structure de la table `menu`
--

DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `nombre_personne_min` int(11) NOT NULL,
  `prix_min` decimal(10,2) NOT NULL,
  `condition_menu` varchar(255) NOT NULL,
  `stock_disponible` int(11) NOT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT 1,
  `theme_id` int(11) NOT NULL,
  `regime_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `menu`
--

INSERT INTO `menu` (`id`, `titre`, `description`, `nombre_personne_min`, `prix_min`, `condition_menu`, `stock_disponible`, `actif`, `theme_id`, `regime_id`) VALUES
(1, 'Le Gourmand Tradition', 'Menu convivial et généreux pour un repas en famille ou entre amis.', 6, 18.90, 'Commande minimum 48h avant la prestation. Conservation au frais recommandée.', 415, 1, 3, 1),
(2, 'Magie de Noël', 'Menu festif pour les fêtes de fin d\'année avec des plats traditionnels.', 8, 29.90, 'Commande minimum 7 jours avant la prestation.', 580, 1, 1, 1),
(3, 'Évasion Végétale', 'Menu 100% végétal sans produits d\'origine animale.', 4, 21.90, 'Commande minimum 48h avant la prestation.', 365, 1, 3, 3),
(4, 'Saveurs du Terroir', 'Un menu convivial et généreux inspiré des recettes traditionnelles françaises.', 6, 18.90, 'Commande minimum 48h avant la prestation.', 5, 1, 3, 1),
(5, 'Festin de Fêtes', 'Un menu raffiné pour célébrer vos événements avec élégance et gourmandise.', 8, 29.90, 'Commande minimum 7 jours avant la prestation.', 3, 1, 1, 1),
(7, 'Délice Méditerranéen', 'Un voyage gustatif aux accents du sud avec des plats ensoleillés.', 6, 24.90, 'Commande minimum 72h avant la prestation.', 5, 1, 2, 1),
(8, 'Gourmandise Chic', 'Un menu élégant et moderne pour des prestations haut de gamme.', 10, 34.90, 'Commande minimum 5 jours avant la prestation.', 2, 1, 1, 1),
(9, 'Tradition & Convivialité', 'Des plats simples et savoureux pour partager un moment chaleureux.', 5, 17.90, 'Commande minimum 48h avant la prestation.', 6, 1, 3, 1),
(10, 'Plaisir Sans Gluten', 'Un menu spécialement conçu pour les personnes intolérantes au gluten.', 4, 23.90, 'Commande minimum 72h avant la prestation.', 3, 1, 3, 2);

-- --------------------------------------------------------

--
-- Structure de la table `menu_plat`
--

DROP TABLE IF EXISTS `menu_plat`;
CREATE TABLE `menu_plat` (
  `menu_id` int(11) NOT NULL,
  `plat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `menu_plat`
--

INSERT INTO `menu_plat` (`menu_id`, `plat_id`) VALUES
(1, 5),
(1, 7),
(1, 8),
(1, 13),
(2, 4),
(2, 5),
(2, 9),
(3, 3),
(3, 7),
(3, 10);

-- --------------------------------------------------------

--
-- Structure de la table `plat`
--

DROP TABLE IF EXISTS `plat`;
CREATE TABLE `plat` (
  `id` int(11) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `type_plat` enum('entrée','plat','dessert') NOT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `plat`
--

INSERT INTO `plat` (`id`, `nom`, `description`, `type_plat`, `actif`) VALUES
(3, 'Veloute de potimarron\r\n', 'Veloute onctueux de potimarron, creme fraiche et croutons.\r\n', 'entrée', 1),
(4, 'Tartare de saumon\r\n', 'Saumon frais coupe au couteau, citron, huile d\'olive et herbes.\r\n', 'entrée', 1),
(5, 'Poulet basquaise', 'Poulet mijote avec tomates, poivrons et epices du sud.\r\n', 'plat', 1),
(6, 'Lasagnes vegetariennes\r\n', 'Lasagnes aux legumes de saison, sauce tomate et fromage.\r\n', 'plat', 1),
(7, 'Curry de legumes\r\n', 'Melange de legumes mijotes dans une sauce curry parfumee.\r\n', 'plat', 1),
(8, 'Tarte aux pommes\r\n', 'Tarte traditionnelle aux pommes, pate croustillante et compote maison.\r\n', 'dessert', 1),
(9, 'Mousse au chocolat\r\n', 'Mousse aerienne au chocolat noir preparee maison.\r\n', 'dessert', 1),
(10, 'Salade de fruits\r\n', 'Assortiment de fruits frais de saison.\r\n', 'dessert', 1),
(13, 'Salade de chevre chaud', 'Salade verte, tomates cerises, toast de chevre chaud et vinaigrette maison.', 'entrée', 1);

-- --------------------------------------------------------

--
-- Structure de la table `plat_allergene`
--

DROP TABLE IF EXISTS `plat_allergene`;
CREATE TABLE `plat_allergene` (
  `plat_id` int(11) NOT NULL,
  `allergene_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `plat_allergene`
--

INSERT INTO `plat_allergene` (`plat_id`, `allergene_id`) VALUES
(3, 2),
(4, 6),
(5, 6),
(6, 9),
(7, 9),
(8, 1),
(8, 2),
(9, 2);

-- --------------------------------------------------------

--
-- Structure de la table `regime`
--

DROP TABLE IF EXISTS `regime`;
CREATE TABLE `regime` (
  `id` int(11) NOT NULL,
  `libelle` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `regime`
--

INSERT INTO `regime` (`id`, `libelle`) VALUES
(1, 'Standard'),
(2, 'Végétarien'),
(3, 'Vegan');

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `libelle` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id`, `libelle`) VALUES
(1, 'administrateur'),
(2, 'employe'),
(3, 'utilisateur');

-- --------------------------------------------------------

--
-- Structure de la table `theme`
--

DROP TABLE IF EXISTS `theme`;
CREATE TABLE `theme` (
  `id` int(11) NOT NULL,
  `libelle` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `theme`
--

INSERT INTO `theme` (`id`, `libelle`) VALUES
(1, 'Noël'),
(2, 'Pâques'),
(3, 'Classique'),
(4, 'Événement');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `code_postal` varchar(10) NOT NULL,
  `ville` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `actif` tinyint(1) NOT NULL DEFAULT 1,
  `role_id` int(11) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expire` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `nom`, `prenom`, `email`, `telephone`, `adresse`, `code_postal`, `ville`, `mot_de_passe`, `date_creation`, `actif`, `role_id`, `reset_token`, `reset_token_expire`) VALUES
(21, 'Admin', 'Admin', 'admin@test.fr', '0606060606', '6 rue des cerisier', '33000', 'Bordeaux', '$2y$10$bHfFdepeajRe93w7D5TvluLHVe5jZgSYrYnrT3d.QVQVMPxvPbp7S', '2026-05-20 03:18:10', 1, 1, NULL, NULL),
(22, 'Employe', 'Employe', 'employe@test.fr', '0505050505', '6 rue des cerisier', '33000', 'Bordeaux', '$2y$10$oxok.cUyk1WcrDuaghdrAuotHE4deGNH/sf0mGOCiR11k98sYvgTK', '2026-05-20 03:18:10', 1, 2, NULL, NULL),
(23, 'Dupont', 'Jean', 'user@test.fr', '0102030405', '6 rue des cerisier', '33000', 'Bordeaux', '$2y$10$Uc6ZdYjlZcfpw.bgoOhg9.niUoR7/3/hREmWuGqpYtWTrrT4T9.lW', '2026-05-20 03:18:10', 1, 3, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `allergene`
--
ALTER TABLE `allergene`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `libelle` (`libelle`);

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `commande_id_2` (`commande_id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Index pour la table `horaire`
--
ALTER TABLE `horaire`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Index pour la table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `theme_id` (`theme_id`),
  ADD KEY `regime_id` (`regime_id`);

--
-- Index pour la table `menu_plat`
--
ALTER TABLE `menu_plat`
  ADD PRIMARY KEY (`menu_id`,`plat_id`),
  ADD KEY `fk_menu_plat_plat` (`plat_id`);

--
-- Index pour la table `plat`
--
ALTER TABLE `plat`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `plat_allergene`
--
ALTER TABLE `plat_allergene`
  ADD PRIMARY KEY (`plat_id`,`allergene_id`),
  ADD KEY `fk_plat_allergene_allergene` (`allergene_id`);

--
-- Index pour la table `regime`
--
ALTER TABLE `regime`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `theme`
--
ALTER TABLE `theme`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `utilisateur_ibfk_1` (`role_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `allergene`
--
ALTER TABLE `allergene`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `horaire`
--
ALTER TABLE `horaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `plat`
--
ALTER TABLE `plat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `regime`
--
ALTER TABLE `regime`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `theme`
--
ALTER TABLE `theme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `commande_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `image_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`theme_id`) REFERENCES `theme` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `menu_ibfk_2` FOREIGN KEY (`regime_id`) REFERENCES `regime` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `menu_plat`
--
ALTER TABLE `menu_plat`
  ADD CONSTRAINT `fk_menu_plat_menu` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_menu_plat_plat` FOREIGN KEY (`plat_id`) REFERENCES `plat` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `plat_allergene`
--
ALTER TABLE `plat_allergene`
  ADD CONSTRAINT `fk_plat_allergene_allergene` FOREIGN KEY (`allergene_id`) REFERENCES `allergene` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_plat_allergene_plat` FOREIGN KEY (`plat_id`) REFERENCES `plat` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
