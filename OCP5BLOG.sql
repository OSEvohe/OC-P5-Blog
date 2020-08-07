SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `comment` (
  `id` int NOT NULL,
  `content` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `dateCreated` datetime NOT NULL,
  `dateModified` datetime NOT NULL,
  `userId` int NOT NULL,
  `postId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `post` (
  `id` int NOT NULL,
  `title` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  `lead` varchar(200) COLLATE utf8mb4_bin NOT NULL,
  `content` text COLLATE utf8mb4_bin NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateModified` datetime NOT NULL,
  `userId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `profile` (
  `id` int NOT NULL,
  `lastName` varchar(40) COLLATE utf8mb4_bin NOT NULL,
  `firstName` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `photoUrl` varchar(30) COLLATE utf8mb4_bin DEFAULT NULL,
  `cvUrl` varchar(30) COLLATE utf8mb4_bin DEFAULT NULL,
  `teasing` varchar(130) COLLATE utf8mb4_bin NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateModified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `socialNetwork` (
  `id` int NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_bin NOT NULL,
  `iconClass` varchar(30) COLLATE utf8mb4_bin NOT NULL,
  `profileUrl` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateModified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `user` (
  `id` int NOT NULL,
  `login` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `passwordHash` varchar(64) COLLATE utf8mb4_bin NOT NULL,
  `displayName` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `role` varchar(200) COLLATE utf8mb4_bin NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateModified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;


ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comment_ibfk_1` (`userId`),
  ADD KEY `postId` (`postId`);

ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_ibfk_1` (`userId`);

ALTER TABLE `profile`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `socialNetwork`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);


ALTER TABLE `comment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `post`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `socialNetwork`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;


ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`postId`) REFERENCES `post` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
