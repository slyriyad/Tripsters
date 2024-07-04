-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour tripsters
CREATE DATABASE IF NOT EXISTS `tripsters` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `tripsters`;

-- Listage de la structure de table tripsters. activity
CREATE TABLE IF NOT EXISTS `activity` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_activity_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `cost` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_AC74095A365B22FD` (`category_activity_id`),
  CONSTRAINT `FK_AC74095A365B22FD` FOREIGN KEY (`category_activity_id`) REFERENCES `category_activity` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table tripsters.activity : ~4 rows (environ)
INSERT INTO `activity` (`id`, `category_activity_id`, `name`, `description`, `cost`) VALUES
	(1, NULL, 'Croisière sur la Seine', NULL, NULL),
	(2, NULL, 'Visite guidée de la Tour Eiffel', NULL, NULL),
	(3, NULL, 'Visite du Musée du Louvre', NULL, NULL),
	(4, NULL, 'Dégustation de vins et fromages français', NULL, NULL);

-- Listage de la structure de table tripsters. category_activity
CREATE TABLE IF NOT EXISTS `category_activity` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table tripsters.category_activity : ~6 rows (environ)
INSERT INTO `category_activity` (`id`, `nom`) VALUES
	(1, 'Culture et Histoire'),
	(2, 'Aventure et Sports'),
	(3, 'Gastronomie et Vin'),
	(4, 'Nature et Plein Air'),
	(5, 'Bien-être et Relaxation'),
	(6, 'Art et Création'),
	(7, 'Famille et Enfants');

-- Listage de la structure de table tripsters. category_expense
CREATE TABLE IF NOT EXISTS `category_expense` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table tripsters.category_expense : ~0 rows (environ)

-- Listage de la structure de table tripsters. doctrine_migration_versions
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Listage des données de la table tripsters.doctrine_migration_versions : ~1 rows (environ)
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20240702122321', '2024-07-02 12:39:26', 201);

-- Listage de la structure de table tripsters. expense
CREATE TABLE IF NOT EXISTS `expense` (
  `id` int NOT NULL AUTO_INCREMENT,
  `trip_id` int DEFAULT NULL,
  `category_expense_id` int DEFAULT NULL,
  `amount` int NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2D3A8DA6A5BC2E0E` (`trip_id`),
  KEY `IDX_2D3A8DA6D58B8B05` (`category_expense_id`),
  CONSTRAINT `FK_2D3A8DA6A5BC2E0E` FOREIGN KEY (`trip_id`) REFERENCES `trip` (`id`),
  CONSTRAINT `FK_2D3A8DA6D58B8B05` FOREIGN KEY (`category_expense_id`) REFERENCES `category_expense` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table tripsters.expense : ~1 rows (environ)
INSERT INTO `expense` (`id`, `trip_id`, `category_expense_id`, `amount`, `description`, `name`, `date`) VALUES
	(1, 1, NULL, 20, 'nourriture', 'pizza', '2024-07-02');

-- Listage de la structure de table tripsters. messenger_messages
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table tripsters.messenger_messages : ~0 rows (environ)

-- Listage de la structure de table tripsters. trip
CREATE TABLE IF NOT EXISTS `trip` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `destination` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `budget` int DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table tripsters.trip : ~0 rows (environ)
INSERT INTO `trip` (`id`, `name`, `description`, `start_date`, `end_date`, `destination`, `budget`, `creation_date`) VALUES
	(1, 'Voyage au maroc', 'afefef', '2024-08-02', '2024-08-22', 'maroc', 3000, NULL);

-- Listage de la structure de table tripsters. trip_activity
CREATE TABLE IF NOT EXISTS `trip_activity` (
  `id` int NOT NULL AUTO_INCREMENT,
  `trip_id` int DEFAULT NULL,
  `activity_id` int DEFAULT NULL,
  `start_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `IDX_4253A4AA5BC2E0E` (`trip_id`),
  KEY `IDX_4253A4A81C06096` (`activity_id`),
  CONSTRAINT `FK_4253A4A81C06096` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`),
  CONSTRAINT `FK_4253A4AA5BC2E0E` FOREIGN KEY (`trip_id`) REFERENCES `trip` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table tripsters.trip_activity : ~6 rows (environ)
INSERT INTO `trip_activity` (`id`, `trip_id`, `activity_id`, `start_date`, `end_date`) VALUES
	(1, 1, 2, '2024-07-03 14:14:08', '2024-07-03 14:14:08'),
	(2, 1, 4, '2024-07-03 14:14:08', '2024-07-03 14:14:08'),
	(3, 1, 4, '2024-07-03 14:14:08', '2024-07-03 14:14:08'),
	(4, 1, 1, '2024-07-03 14:14:08', '2024-07-03 14:14:08'),
	(5, 1, 2, '2024-07-03 14:14:08', '2024-07-03 14:14:08'),
	(6, 1, 1, '2024-07-06 14:31:00', '2024-07-13 14:32:00');

-- Listage de la structure de table tripsters. trip_expense
CREATE TABLE IF NOT EXISTS `trip_expense` (
  `id` int NOT NULL AUTO_INCREMENT,
  `trip_id` int NOT NULL,
  `expense_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1BD6C80BA5BC2E0E` (`trip_id`),
  KEY `IDX_1BD6C80BF395DB7B` (`expense_id`),
  CONSTRAINT `FK_1BD6C80BA5BC2E0E` FOREIGN KEY (`trip_id`) REFERENCES `trip` (`id`),
  CONSTRAINT `FK_1BD6C80BF395DB7B` FOREIGN KEY (`expense_id`) REFERENCES `expense` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table tripsters.trip_expense : ~1 rows (environ)
INSERT INTO `trip_expense` (`id`, `trip_id`, `expense_id`) VALUES
	(1, 1, 1),
	(2, 1, 1);

-- Listage de la structure de table tripsters. trip_user
CREATE TABLE IF NOT EXISTS `trip_user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `trip_id` int DEFAULT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_A6AB4522A5BC2E0E` (`trip_id`),
  KEY `IDX_A6AB4522A76ED395` (`user_id`),
  CONSTRAINT `FK_A6AB4522A5BC2E0E` FOREIGN KEY (`trip_id`) REFERENCES `trip` (`id`),
  CONSTRAINT `FK_A6AB4522A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table tripsters.trip_user : ~0 rows (environ)

-- Listage de la structure de table tripsters. user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table tripsters.user : ~2 rows (environ)
INSERT INTO `user` (`id`, `email`, `roles`, `password`) VALUES
	(1, 'riyad@exemple.com', '[]', '$2y$13$33dImOWFNLuP6Jty8aP.h.8UAxNdQyZxE83Pgpi9B/17CEnOaWazm'),
	(2, 'riyad25@exemple.com', '[]', '$2y$13$KuOtlDopjXtOJwK1XDZEcOaecHx3ikTVdwc3LdOuO5WDW5NogsrXC');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;


