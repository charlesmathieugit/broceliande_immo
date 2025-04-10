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


-- Listage de la structure de la base pour broceliande_immo
CREATE DATABASE IF NOT EXISTS `broceliande_immo` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `broceliande_immo`;

-- Listage de la structure de table broceliande_immo. annonces
CREATE TABLE IF NOT EXISTS `annonces` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('vente','location') COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `charges` decimal(10,2) DEFAULT '0.00',
  `pieces` int NOT NULL,
  `surface` decimal(6,2) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_bien` enum('appartement','maison','terrain','commerce') COLLATE utf8mb4_unicode_ci NOT NULL,
  `dpe_rating` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ges_rating` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `features` json DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `idx_annonces_category` (`category`),
  KEY `idx_annonces_type_bien` (`type_bien`),
  KEY `idx_annonces_city` (`city`),
  KEY `idx_annonces_price` (`price`),
  CONSTRAINT `annonces_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table broceliande_immo.annonces : ~0 rows (environ)
DELETE FROM `annonces`;
INSERT INTO `annonces` (`id`, `user_id`, `title`, `category`, `price`, `charges`, `pieces`, `surface`, `description`, `address`, `postal_code`, `city`, `type_bien`, `dpe_rating`, `ges_rating`, `features`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 2, 'Magnifique maison avec jardin', 'vente', 320000.00, 0.00, 6, 150.00, 'Superbe maison familiale avec grand jardin arboré. Cuisine équipée, salon lumineux, 4 chambres, 2 salles de bain. Garage double.', '12 rue des Chênes', '35380', 'Paimpont', 'maison', 'B', 'A', '["Jardin", "Garage", "Cuisine équipée", "Double vitrage"]', 1, '2025-04-04 07:11:10', '2025-04-04 07:11:10'),
	(2, 2, 'Appartement T3 centre-ville', 'vente', 180000.00, 0.00, 3, 65.00, 'Bel appartement rénové en centre-ville. Séjour avec balcon, cuisine américaine, 2 chambres. Cave et parking.', '5 place du Marché', '35160', 'Montfort-sur-Meu', 'appartement', 'C', 'B', '["Balcon", "Parking", "Cave", "Ascenseur"]', 1, '2025-04-04 07:11:10', '2025-04-04 07:11:10'),
	(3, 2, 'Terrain constructible 800m²', 'vente', 85000.00, 0.00, 0, 800.00, 'Beau terrain plat et viabilisé. Proche commodités. Zone pavillonnaire calme.', 'Lotissement des Bruyères', '35750', 'Iffendic', 'terrain', NULL, NULL, '["Viabilisé", "Plat", "Vue dégagée"]', 1, '2025-04-04 07:11:10', '2025-04-04 07:11:10'),
	(4, 2, 'T2 meublé centre-ville', 'location', 550.00, 50.00, 2, 45.00, 'Charmant T2 meublé en centre-ville. Cuisine équipée, chambre avec placard, salle d\'eau rénovée.', '8 rue de la Mairie', '35380', 'Plélan-le-Grand', 'appartement', 'D', 'C', '["Meublé", "Cuisine équipée", "Interphone", "Local à vélos"]', 1, '2025-04-04 07:11:10', '2025-04-04 07:11:10'),
	(5, 2, 'Maison T4 avec jardin', 'location', 850.00, 30.00, 4, 95.00, 'Belle maison familiale avec jardin clos. Cuisine aménagée, salon-séjour, 3 chambres, garage.', '15 rue des Fontaines', '35750', 'Iffendic', 'maison', 'C', 'B', '["Jardin", "Garage", "Cuisine aménagée", "Buanderie"]', 1, '2025-04-04 07:11:10', '2025-04-04 07:11:10'),
	(6, 2, 'Studio étudiant', 'location', 380.00, 40.00, 1, 25.00, 'Studio rénové idéal étudiant. Kitchenette équipée, salle d\'eau moderne, placards.', '3 rue du Collège', '35160', 'Montfort-sur-Meu', 'appartement', 'E', 'D', '["Meublé", "Kitchenette", "Internet inclus"]', 1, '2025-04-04 07:11:10', '2025-04-04 07:11:10'),
	(7, 2, 'Magnifique maison avec jardin', 'vente', 320000.00, 0.00, 6, 150.00, 'Superbe maison familiale avec grand jardin arboré. Cuisine équipée, salon lumineux, 4 chambres, 2 salles de bain. Garage double.', '12 rue des Chênes', '35380', 'Paimpont', 'maison', 'B', 'A', '["Jardin", "Garage", "Cuisine équipée", "Double vitrage"]', 1, '2025-04-04 07:13:32', '2025-04-04 07:13:32'),
	(8, 2, 'Appartement T3 centre-ville', 'vente', 180000.00, 0.00, 3, 65.00, 'Bel appartement rénové en centre-ville. Séjour avec balcon, cuisine américaine, 2 chambres. Cave et parking.', '5 place du Marché', '35160', 'Montfort-sur-Meu', 'appartement', 'C', 'B', '["Balcon", "Parking", "Cave", "Ascenseur"]', 1, '2025-04-04 07:13:32', '2025-04-04 07:13:32'),
	(9, 2, 'Terrain constructible 800m²', 'vente', 85000.00, 0.00, 0, 800.00, 'Beau terrain plat et viabilisé. Proche commodités. Zone pavillonnaire calme.', 'Lotissement des Bruyères', '35750', 'Iffendic', 'terrain', NULL, NULL, '["Viabilisé", "Plat", "Vue dégagée"]', 1, '2025-04-04 07:13:32', '2025-04-04 07:13:32'),
	(10, 2, 'T2 meublé centre-ville', 'location', 550.00, 50.00, 2, 45.00, 'Charmant T2 meublé en centre-ville. Cuisine équipée, chambre avec placard, salle d\'eau rénovée.', '8 rue de la Mairie', '35380', 'Plélan-le-Grand', 'appartement', 'D', 'C', '["Meublé", "Cuisine équipée", "Interphone", "Local à vélos"]', 1, '2025-04-04 07:13:32', '2025-04-04 07:13:32'),
	(11, 2, 'Maison T4 avec jardin', 'location', 850.00, 30.00, 4, 95.00, 'Belle maison familiale avec jardin clos. Cuisine aménagée, salon-séjour, 3 chambres, garage.', '15 rue des Fontaines', '35750', 'Iffendic', 'maison', 'C', 'B', '["Jardin", "Garage", "Cuisine aménagée", "Buanderie"]', 1, '2025-04-04 07:13:32', '2025-04-04 07:13:32'),
	(12, 2, 'Studio étudiant', 'location', 380.00, 40.00, 1, 25.00, 'Studio rénové idéal étudiant. Kitchenette équipée, salle d\'eau moderne, placards.', '3 rue du Collège', '35160', 'Montfort-sur-Meu', 'appartement', 'E', 'D', '["Meublé", "Kitchenette", "Internet inclus"]', 1, '2025-04-04 07:13:32', '2025-04-04 07:13:32');

-- Listage de la structure de table broceliande_immo. contacts
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_contacts_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table broceliande_immo.contacts : ~0 rows (environ)
DELETE FROM `contacts`;

-- Listage de la structure de table broceliande_immo. favoris
CREATE TABLE IF NOT EXISTS `favoris` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `annonce_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_favori` (`user_id`,`annonce_id`),
  KEY `annonce_id` (`annonce_id`),
  CONSTRAINT `favoris_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `favoris_ibfk_2` FOREIGN KEY (`annonce_id`) REFERENCES `annonces` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table broceliande_immo.favoris : ~0 rows (environ)
DELETE FROM `favoris`;

-- Listage de la structure de table broceliande_immo. images
CREATE TABLE IF NOT EXISTS `images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `annonce_id` int NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_primary` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `annonce_id` (`annonce_id`),
  CONSTRAINT `images_ibfk_1` FOREIGN KEY (`annonce_id`) REFERENCES `annonces` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table broceliande_immo.images : ~0 rows (environ)
DELETE FROM `images`;
INSERT INTO `images` (`id`, `annonce_id`, `file_path`, `is_primary`, `created_at`) VALUES
	(1, 1, '/uploads/annonces/maison1.jpg', 1, '2025-04-04 07:13:32'),
	(2, 1, '/uploads/annonces/maison2.jpg', 0, '2025-04-04 07:13:32'),
	(3, 2, '/uploads/annonces/appartement1.jpg', 1, '2025-04-04 07:13:32'),
	(4, 3, '/uploads/annonces/terrain1.jpg', 1, '2025-04-04 07:13:32'),
	(5, 4, '/uploads/annonces/t2-1.jpg', 1, '2025-04-04 07:13:32'),
	(6, 5, '/uploads/annonces/maison-location1.jpg', 1, '2025-04-04 07:13:32'),
	(7, 6, '/uploads/annonces/studio1.jpg', 1, '2025-04-04 07:13:32');

-- Listage de la structure de table broceliande_immo. notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('info','success','warning','error') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table broceliande_immo.notifications : ~0 rows (environ)
DELETE FROM `notifications`;

-- Listage de la structure de table broceliande_immo. rendez_vous
CREATE TABLE IF NOT EXISTS `rendez_vous` (
  `id` int NOT NULL AUTO_INCREMENT,
  `annonce_id` int NOT NULL,
  `user_id` int NOT NULL,
  `date_rendez_vous` datetime NOT NULL,
  `statut` enum('en_attente','confirme','annule','termine') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `type_visite` enum('presentiel','virtuel') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'presentiel',
  `commentaire` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `annonce_id` (`annonce_id`),
  KEY `user_id` (`user_id`),
  KEY `idx_rendez_vous_date` (`date_rendez_vous`),
  CONSTRAINT `rendez_vous_ibfk_1` FOREIGN KEY (`annonce_id`) REFERENCES `annonces` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rendez_vous_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table broceliande_immo.rendez_vous : ~0 rows (environ)
DELETE FROM `rendez_vous`;

-- Listage de la structure de table broceliande_immo. users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','agent','client') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'client',
  `firstname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table broceliande_immo.users : ~0 rows (environ)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `email`, `password`, `role`, `firstname`, `lastname`, `phone`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'admin@broceliande-immo.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Admin', 'System', NULL, 1, '2025-03-30 21:43:39', '2025-03-30 21:43:39'),
	(2, 'sophie.martin@broceliande-immo.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'agent', 'Sophie', 'Martin', '0299123456', 1, '2025-04-04 07:11:10', '2025-04-04 07:11:10');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
