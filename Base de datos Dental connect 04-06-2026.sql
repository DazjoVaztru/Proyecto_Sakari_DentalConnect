-- --------------------------------------------------------
-- Host:                         interchange.proxy.rlwy.net
-- Versión del servidor:         9.6.0 - MySQL Community Server - GPL
-- SO del servidor:              Linux
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


-- Volcando estructura de base de datos para dental_connect_db
CREATE DATABASE IF NOT EXISTS `dental_connect_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `dental_connect_db`;

-- Volcando estructura para tabla dental_connect_db.archivos
CREATE TABLE IF NOT EXISTS `archivos` (
  `id_archivo` bigint NOT NULL AUTO_INCREMENT,
  `id_paciente` int DEFAULT NULL,
  `id_cita` bigint DEFAULT NULL,
  `url_archivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` enum('imagen','pdf','rayos_x') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_archivo`),
  KEY `id_paciente` (`id_paciente`),
  KEY `id_cita` (`id_cita`),
  CONSTRAINT `archivos_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`),
  CONSTRAINT `archivos_ibfk_2` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.archivos: ~3 rows (aproximadamente)
REPLACE INTO `archivos` (`id_archivo`, `id_paciente`, `id_cita`, `url_archivo`, `tipo`, `descripcion`) VALUES
	(1, 8, NULL, 'evoluciones/1772642272_176698-izuku_midoriya-mi_heroe_la_justicia-mi_hroe_de_la_academia-todo_podria-arte_animado-3840x2160.jpg', 'imagen', 'Evolucion_5'),
	(2, 9, NULL, 'pacientes/fotos/paciente_9_1772644401.jpg', 'imagen', 'Evolucion_6'),
	(3, 9, NULL, 'evoluciones/1772644384_anime-pc-1920-x-1200-background-ke3asf6e85mhu2vz.jpg', 'imagen', 'Evolucion_7');

-- Volcando estructura para tabla dental_connect_db.audit_logs
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id_log` bigint NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `accion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tabla_afectada` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_registro` bigint DEFAULT NULL,
  `detalles` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.audit_logs: ~7 rows (aproximadamente)
REPLACE INTO `audit_logs` (`id_log`, `id_usuario`, `accion`, `tabla_afectada`, `id_registro`, `detalles`, `created_at`) VALUES
	(1, NULL, 'update', 'citas', 4, '{"new_estado": "pendiente", "old_estado": "pendiente"}', '2026-03-01 18:27:32'),
	(2, NULL, 'update', 'citas', 4, '{"new_estado": "pendiente", "old_estado": "pendiente"}', '2026-03-01 18:27:32'),
	(3, NULL, 'update', 'citas', 4, '{"new_estado": "completada", "old_estado": "pendiente"}', '2026-03-01 18:27:49'),
	(4, NULL, 'update', 'citas', 3, '{"new_estado": "pendiente", "old_estado": "pendiente"}', '2026-03-01 18:29:09'),
	(5, NULL, 'update', 'citas', 3, '{"new_estado": "pendiente", "old_estado": "pendiente"}', '2026-03-01 18:29:09'),
	(6, NULL, 'update', 'citas', 3, '{"new_estado": "pendiente", "old_estado": "pendiente"}', '2026-03-01 18:29:22'),
	(7, NULL, 'update', 'citas', 1, '{"new_estado": "completada", "old_estado": "pendiente"}', '2026-03-04 16:43:00');

-- Volcando estructura para tabla dental_connect_db.bitacora_notificaciones
CREATE TABLE IF NOT EXISTS `bitacora_notificaciones` (
  `id_notificacion` bigint NOT NULL AUTO_INCREMENT,
  `id_cita` bigint DEFAULT NULL,
  `estado_envio` enum('enviado','fallido','pendiente') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_envio` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_notificacion`),
  KEY `id_cita` (`id_cita`),
  CONSTRAINT `bitacora_notificaciones_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.bitacora_notificaciones: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.cache: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.cache_locks: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.catalogo_alergias
CREATE TABLE IF NOT EXISTS `catalogo_alergias` (
  `id_alergia` int NOT NULL AUTO_INCREMENT,
  `nombre_alergeno` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_alergia`),
  UNIQUE KEY `nombre_alergeno` (`nombre_alergeno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.catalogo_alergias: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.catalogo_enfermedades_cronicas
CREATE TABLE IF NOT EXISTS `catalogo_enfermedades_cronicas` (
  `id_enfermedad_cronica` int NOT NULL AUTO_INCREMENT,
  `nombre_enfermedad` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_enfermedad_cronica`),
  UNIQUE KEY `nombre_enfermedad` (`nombre_enfermedad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.catalogo_enfermedades_cronicas: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.catalogo_servicios
CREATE TABLE IF NOT EXISTS `catalogo_servicios` (
  `id_servicio` int NOT NULL AUTO_INCREMENT,
  `id_clinica` int NOT NULL,
  `nombre_servicio` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio_base` decimal(10,2) DEFAULT NULL,
  `categoria` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_servicio`),
  KEY `id_clinica` (`id_clinica`),
  CONSTRAINT `catalogo_servicios_ibfk_1` FOREIGN KEY (`id_clinica`) REFERENCES `clinicas` (`id_clinica`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.catalogo_servicios: ~6 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
