-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.4.3 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para ecomarket_db
CREATE DATABASE IF NOT EXISTS `ecomarket_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `ecomarket_db`;

-- Volcando estructura para tabla ecomarket_db.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla ecomarket_db.categories: ~1 rows (aproximadamente)
REPLACE INTO `categories` (`id`, `nombre`) VALUES
	(1, 'Tecnologia');

-- Volcando estructura para tabla ecomarket_db.orderitems
CREATE TABLE IF NOT EXISTS `orderitems` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cantidad` int NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `OrderId` int DEFAULT NULL,
  `ProductId` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `OrderId` (`OrderId`),
  KEY `ProductId` (`ProductId`),
  CONSTRAINT `orderitems_ibfk_1` FOREIGN KEY (`OrderId`) REFERENCES `orders` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `orderitems_ibfk_2` FOREIGN KEY (`ProductId`) REFERENCES `products` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla ecomarket_db.orderitems: ~11 rows (aproximadamente)
REPLACE INTO `orderitems` (`id`, `cantidad`, `precio_unitario`, `createdAt`, `updatedAt`, `OrderId`, `ProductId`) VALUES
	(1, 2, 15000.00, '2026-02-24 17:51:40', '2026-02-24 17:51:40', 1, NULL),
	(2, 3, 15000.00, '2026-02-24 17:59:44', '2026-02-24 17:59:44', 3, NULL),
	(3, 3, 15000.00, '2026-02-24 18:02:53', '2026-02-24 18:02:53', 4, NULL),
	(4, 2, 15000.00, '2026-02-24 18:02:53', '2026-02-24 18:02:53', 4, 3),
	(5, 1, 15000.00, '2026-02-24 18:02:53', '2026-02-24 18:02:53', 4, 4),
	(6, 3, 15000.00, '2026-02-24 18:14:09', '2026-02-24 18:14:09', 5, NULL),
	(7, 2, 200.00, '2026-02-24 18:14:09', '2026-02-24 18:14:09', 5, 5),
	(8, 1, 80.00, '2026-02-24 18:14:09', '2026-02-24 18:14:09', 5, 6),
	(9, 3, 15000.00, '2026-03-03 16:24:01', '2026-03-03 16:24:01', 6, NULL),
	(10, 2, 200.00, '2026-03-03 16:24:01', '2026-03-03 16:24:01', 6, 5),
	(11, 1, 80.00, '2026-03-03 16:24:01', '2026-03-03 16:24:01', 6, 6),
	(12, 1, 3000.00, '2026-03-03 18:06:24', '2026-03-03 18:06:24', 9, 11),
	(13, 2, 200.00, '2026-03-03 18:06:24', '2026-03-03 18:06:24', 9, 5),
	(14, 1, 80.00, '2026-03-03 18:06:24', '2026-03-03 18:06:24', 9, 6);

-- Volcando estructura para tabla ecomarket_db.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `total` decimal(10,2) NOT NULL,
  `estado` varchar(255) DEFAULT 'pagado',
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `UserId` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `UserId` (`UserId`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla ecomarket_db.orders: ~5 rows (aproximadamente)
REPLACE INTO `orders` (`id`, `total`, `estado`, `createdAt`, `updatedAt`, `UserId`) VALUES
	(1, 30000.00, 'pagado', '2026-02-24 17:51:40', '2026-02-24 17:51:40', 1),
	(3, 45000.00, 'pagado', '2026-02-24 17:59:44', '2026-02-24 17:59:44', 1),
	(4, 90000.00, 'pagado', '2026-02-24 18:02:53', '2026-02-24 18:02:53', 1),
	(5, 45480.00, 'pagado', '2026-02-24 18:14:09', '2026-02-24 18:14:09', 2),
	(6, 45480.00, 'pagado', '2026-03-03 16:24:01', '2026-03-03 16:24:01', 2),
	(9, 3480.00, 'pagado', '2026-03-03 18:06:24', '2026-03-03 18:06:24', 4);

-- Volcando estructura para tabla ecomarket_db.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int DEFAULT '0',
  `descripcion` text,
  `imagen_url` varchar(255) DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `CategoryId` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `CategoryId` (`CategoryId`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`CategoryId`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla ecomarket_db.products: ~9 rows (aproximadamente)
REPLACE INTO `products` (`id`, `nombre`, `precio`, `stock`, `descripcion`, `imagen_url`, `createdAt`, `updatedAt`, `CategoryId`) VALUES
	(3, 'Laptop Gamer', 15000.00, 98, NULL, 'uploads\\imagen-1771954325056-627035694.jpg', '2026-02-24 17:32:05', '2026-02-24 18:02:53', 1),
	(4, 'sabritas otaku', 15000.00, 99, NULL, 'uploads\\imagen-1771954858637-47846651.jpg', '2026-02-24 17:40:58', '2026-02-24 18:02:53', 1),
	(5, 'mouse', 200.00, 4, NULL, 'uploads\\imagen-1771956691032-112272648.jpg', '2026-02-24 18:11:31', '2026-03-03 18:06:24', 1),
	(6, 'hdmi', 80.00, 97, NULL, 'uploads\\imagen-1771956735456-591085587.jpg', '2026-02-24 18:12:15', '2026-03-03 18:06:24', 1),
	(7, 'Celular gamer', 10000.00, 15, NULL, 'uploads\\imagen-1771956807011-159883456.jpg', '2026-02-24 18:13:27', '2026-02-24 18:13:27', 1),
	(8, 'Celular gamer', 10000.00, 15, NULL, 'uploads\\imagen-1772555027264-632114926.jpg', '2026-03-03 16:23:47', '2026-03-03 16:23:47', 1),
	(9, 'Celular gama baja', 1000.00, 100, NULL, 'uploads\\imagen-1772560072388-107445001.jpg', '2026-03-03 17:47:52', '2026-03-03 17:47:52', 1),
	(10, 'Celular gama baja', 1000.00, 100, NULL, 'uploads\\imagen-1772560121382-604256774.jpg', '2026-03-03 17:48:41', '2026-03-03 17:48:41', 1),
	(11, 'Celular gama baja', 3000.00, 99, NULL, 'uploads\\imagen-1772560450985-940179997.jpg', '2026-03-03 17:54:10', '2026-03-03 18:06:24', 1);

-- Volcando estructura para tabla ecomarket_db.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('user','admin') DEFAULT 'user',
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla ecomarket_db.users: ~4 rows (aproximadamente)
REPLACE INTO `users` (`id`, `nombre`, `email`, `password`, `rol`, `createdAt`, `updatedAt`) VALUES
	(1, 'Marco Antonio Osorio Hernandez', 'mrcocrck2019@gmail.com', '$2b$10$P6khpBmZOZLwoIH59qZ0d.pR6HR8u84zScnSZEKkkkrfBFAj0fvyO', 'user', '2026-02-17 17:07:11', '2026-02-17 17:07:11'),
	(2, 'Cliente Final', 'cliente@ecomarket.com', '$2b$10$Dx4BS9gc38KaCOKA9byOKOPiCB/t8.RtWO8vKjZheqVH9qD4TyJ32', 'admin', '2026-02-24 18:07:49', '2026-02-24 18:07:49'),
	(3, 'Mario Osorio Hernandez', 'desmayo@example.com', '$2b$10$FJbcxaRQ9t./KBMwJT7wu.ilLW4eHOHBrXwmwTpQQGMMNBpVVgqgO', 'user', '2026-03-03 16:51:47', '2026-03-03 16:51:47'),
	(4, 'Maria Osorio Hernandez', 'maria@gmail.com', '$2b$10$Um/H3.M1U310TVm9cYHLC.FDH0CSvo2Kg8TioJtWENLNRB1KffCyq', 'admin', '2026-03-03 17:52:17', '2026-03-03 17:52:17');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
