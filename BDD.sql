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
  `created_by_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_AC74095A365B22FD` (`category_activity_id`),
  KEY `IDX_AC74095AB03A8386` (`created_by_id`),
  CONSTRAINT `FK_AC74095A365B22FD` FOREIGN KEY (`category_activity_id`) REFERENCES `category_activity` (`id`),
  CONSTRAINT `FK_AC74095AB03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table tripsters.activity : ~4 rows (environ)
INSERT INTO `activity` (`id`, `category_activity_id`, `name`, `description`, `cost`, `created_by_id`) VALUES
	(29, 8, 'Exploration des ruines romaines de Volubilis', '', 50, 7),
	(30, 9, 'Visite des cascades d\'Ouzoud', '', 20, 7),
	(31, 10, 'Séjour balnéaire à Essaouira ou Agadir', '', 10, 7),
	(32, 11, 'Cours de cuisine marocaine ', '', 30, 7);

-- Listage de la structure de table tripsters. category_activity
CREATE TABLE IF NOT EXISTS `category_activity` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `background_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table tripsters.category_activity : ~4 rows (environ)
INSERT INTO `category_activity` (`id`, `nom`, `background_color`) VALUES
	(8, 'Culture et Patrimoine', '#ff595e'),
	(9, 'Nature et Aventure', '#8ac926'),
	(10, 'Bien-être et Détente', '#ffca3a'),
	(11, 'Gastronomie et Vin', '#6a4c93');

-- Listage de la structure de table tripsters. category_expense
CREATE TABLE IF NOT EXISTS `category_expense` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `background_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table tripsters.category_expense : ~5 rows (environ)
INSERT INTO `category_expense` (`id`, `name`, `icon`, `background_color`) VALUES
	(1, 'Transport', 'fa-solid fa-car-side', '#E56997'),
	(2, 'Hébergement', 'fa-solid fa-bed', '#BD97CB'),
	(3, ' Nourriture', 'fa-solid fa-utensils', '#FBC740'),
	(4, 'Activités', 'fa-regular fa-futbol', '#66D2D6'),
	(5, 'Divers', 'fa-solid fa-ticket', '#F5631A');

-- Listage de la structure de table tripsters. comment
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `author_id` int NOT NULL,
  `activity_id` int DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9474526CF675F31B` (`author_id`),
  KEY `IDX_9474526C81C06096` (`activity_id`),
  CONSTRAINT `FK_9474526C81C06096` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`),
  CONSTRAINT `FK_9474526CF675F31B` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table tripsters.comment : ~1 rows (environ)
INSERT INTO `comment` (`id`, `author_id`, `activity_id`, `content`, `created_at`) VALUES
	(1, 7, 29, 'bonjour', '2024-07-22 13:16:05');

-- Listage de la structure de table tripsters. doctrine_migration_versions
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Listage des données de la table tripsters.doctrine_migration_versions : ~11 rows (environ)
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20240702122321', '2024-07-02 12:39:26', 201),
	('DoctrineMigrations\\Version20240709084110', '2024-07-09 08:41:17', 297),
	('DoctrineMigrations\\Version20240711125436', '2024-07-15 14:17:32', 111),
	('DoctrineMigrations\\Version20240715141842', '2024-07-15 14:18:47', 33),
	('DoctrineMigrations\\Version20240715151639', '2024-07-15 15:16:49', 56),
	('DoctrineMigrations\\Version20240715152841', '2024-07-15 15:28:58', 187),
	('DoctrineMigrations\\Version20240718120620', '2024-07-18 12:06:28', 40),
	('DoctrineMigrations\\Version20240722131411', '2024-07-22 13:14:19', 387),
	('DoctrineMigrations\\Version20240722134838', '2024-07-22 13:48:45', 40),
	('DoctrineMigrations\\Version20240722135039', '2024-07-22 13:50:45', 36),
	('DoctrineMigrations\\Version20240727184525', '2024-07-27 18:45:34', 126);

-- Listage de la structure de table tripsters. expense
CREATE TABLE IF NOT EXISTS `expense` (
  `id` int NOT NULL AUTO_INCREMENT,
  `trip_id` int DEFAULT NULL,
  `category_expense_id` int DEFAULT NULL,
  `amount` int NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2D3A8DA6A5BC2E0E` (`trip_id`),
  KEY `IDX_2D3A8DA6D58B8B05` (`category_expense_id`),
  CONSTRAINT `FK_2D3A8DA6A5BC2E0E` FOREIGN KEY (`trip_id`) REFERENCES `trip` (`id`),
  CONSTRAINT `FK_2D3A8DA6D58B8B05` FOREIGN KEY (`category_expense_id`) REFERENCES `category_expense` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table tripsters.expense : ~1 rows (environ)
INSERT INTO `expense` (`id`, `trip_id`, `category_expense_id`, `amount`, `description`, `name`, `date`) VALUES
	(7, 2, 1, 50, 'jjj', 'j', '2024-07-25');

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
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `destination` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `budget` int DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `image_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_size` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table tripsters.trip : ~2 rows (environ)
INSERT INTO `trip` (`id`, `name`, `description`, `start_date`, `end_date`, `destination`, `budget`, `creation_date`, `image_name`, `image_size`) VALUES
	(2, 'voyage de noce', 'voyage au maroc', '2024-08-01 00:47:00', '2024-08-15 00:48:00', 'maroc', 3000, NULL, 'maroc.jpg', NULL),
	(3, 'japon', 'voyage au japon', '2024-09-02 03:41:00', '2024-09-20 03:41:00', 'japon', 3000, NULL, '66a8451653413215996523.jpg', 10726);

-- Listage de la structure de table tripsters. trip_activity
CREATE TABLE IF NOT EXISTS `trip_activity` (
  `id` int NOT NULL AUTO_INCREMENT,
  `trip_id` int DEFAULT NULL,
  `activity_id` int DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4253A4AA5BC2E0E` (`trip_id`),
  KEY `IDX_4253A4A81C06096` (`activity_id`),
  CONSTRAINT `FK_4253A4A81C06096` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`),
  CONSTRAINT `FK_4253A4AA5BC2E0E` FOREIGN KEY (`trip_id`) REFERENCES `trip` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table tripsters.trip_activity : ~4 rows (environ)
INSERT INTO `trip_activity` (`id`, `trip_id`, `activity_id`, `start_date`, `end_date`) VALUES
	(31, 2, 29, '2024-08-01 09:00:00', '2024-08-01 10:00:00'),
	(32, 2, 30, '2024-08-02 09:00:00', '2024-08-02 10:00:00'),
	(33, 2, 31, '2024-08-03 09:00:00', '2024-08-03 10:00:00'),
	(34, 2, 32, '2024-08-04 09:00:00', '2024-08-04 10:00:00');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table tripsters.trip_user : ~2 rows (environ)
INSERT INTO `trip_user` (`id`, `trip_id`, `user_id`) VALUES
	(1, 2, 7),
	(2, 3, 7);

-- Listage de la structure de table tripsters. user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_size` int DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table tripsters.user : ~1 rows (environ)
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `image_name`, `image_size`, `updated_at`) VALUES
	(7, 'riyad@exemple.fr', '[]', '$2y$13$VyJEyReOMCtOror4BZ1qF.bCxxAddhW1OAMdMgS8RWTAurX.0p8qq', '6695a6a73f0b8228226301.JPG', 91834, '2024-07-15 22:45:59');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
