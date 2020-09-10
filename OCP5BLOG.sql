-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : lun. 07 sep. 2020 à 23:46
-- Version du serveur :  8.0.20-0ubuntu0.19.10.1
-- Version de PHP : 7.3.11-0ubuntu0.19.10.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `OCP5BLOG`
--

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` int NOT NULL,
  `content` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `dateCreated` datetime NOT NULL,
  `dateModified` datetime DEFAULT NULL,
  `userId` int NOT NULL,
  `postId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

CREATE TABLE `post` (
  `id` int NOT NULL,
  `title` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `lead` varchar(200) COLLATE utf8mb4_bin NOT NULL,
  `content` text COLLATE utf8mb4_bin NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateModified` datetime NOT NULL,
  `userId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Structure de la table `profile`
--

CREATE TABLE `profile` (
  `id` int NOT NULL,
  `lastName` varchar(40) COLLATE utf8mb4_bin NOT NULL,
  `firstName` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `photoUrl` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `cvUrl` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `teasing` varchar(130) COLLATE utf8mb4_bin NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateModified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `profile`
--

INSERT INTO `profile` (`id`, `lastName`, `firstName`, `photoUrl`, `cvUrl`, `teasing`, `dateCreated`, `dateModified`) VALUES
(1, 'Ollagnier', 'Sébastien', 'https://p5-oc-blog.o-pa.fr/uploads/photo_trombi_200x200.png', 'https://p5-oc-blog.o-pa.fr/uploads/P1_02_CV.pdf', 'Responsable Informatique durant 10 ans je me forme actuellement en tant que Développeur PhP/Symfony chez OpenClassrooms', '2020-08-11 10:11:44', '2020-09-02 18:52:18');

-- --------------------------------------------------------

--
-- Structure de la table `socialNetwork`
--

CREATE TABLE `socialNetwork` (
  `id` int NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_bin NOT NULL,
  `iconClass` varchar(30) COLLATE utf8mb4_bin NOT NULL,
  `profileUrl` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateModified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `socialNetwork`
--

INSERT INTO `socialNetwork` (`id`, `name`, `iconClass`, `profileUrl`, `dateCreated`, `dateModified`) VALUES
(1, 'Facebook', 'fa fa-facebook-f', 'https://www.facebook.com/sollagnier', '2020-08-11 15:06:17', '2020-08-25 12:26:51'),
(4, 'Linkedin', 'fa fa-linkedin', 'https://www.linkedin.com/in/s%C3%A9bastien-ollagnier-pages-89bb7241/', '2020-08-11 15:09:08', '2020-08-25 12:26:07'),
(5, 'Twitter', 'fa fa-twitter', 'https://twitter.com/Yevy34', '2020-08-11 15:09:08', '2020-08-25 12:27:12'),
(6, 'GitHub', 'fa fa-github', 'https://github.com/OSEvohe', '2020-08-11 15:09:47', '2020-09-02 17:32:15');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `login` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `passwordHash` varchar(64) COLLATE utf8mb4_bin NOT NULL,
  `displayName` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `role` varchar(200) COLLATE utf8mb4_bin NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateModified` datetime DEFAULT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `tokenTime` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `login`, `passwordHash`, `displayName`, `role`, `dateCreated`, `dateModified`, `token`, `tokenTime`) VALUES
(1, 'sebastien@o-pa.fr', '$2y$10$z8PhouWVroRwJdOuEq/4S.L0YLhIMw2a238sje8lM60tCxhGE3vXO', 'Sollagnier', 'a:1:{i:0;s:5:\"admin\";}', '2020-08-07 10:14:12', NULL, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comment_ibfk_1` (`userId`),
  ADD KEY `comment_ibfk_2` (`postId`);

--
-- Index pour la table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_ibfk_1` (`userId`);

--
-- Index pour la table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `socialNetwork`
--
ALTER TABLE `socialNetwork`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `socialNetwork`
--
ALTER TABLE `socialNetwork`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`postId`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
