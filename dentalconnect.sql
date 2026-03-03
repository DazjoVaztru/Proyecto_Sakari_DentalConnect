-- --------------------------------------------------------
-- Host:                         localhost
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.archivos: ~0 rows (aproximadamente)

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.audit_logs: ~0 rows (aproximadamente)

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
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.cache: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.catalogo_servicios: ~4 rows (aproximadamente)
INSERT INTO `catalogo_servicios` (`id_servicio`, `id_clinica`, `nombre_servicio`, `precio_base`, `categoria`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Consulta general', 400.00, 'General', '2026-02-28 05:18:33', '2026-02-28 05:18:33'),
	(2, 1, 'Limpieza de Dientes', 80.00, 'Limpieza', '2026-02-28 05:18:56', '2026-02-28 05:18:56'),
	(3, 1, 'Brackets', 4000.00, 'Estética', '2026-02-28 05:19:18', '2026-02-28 05:19:18'),
	(4, 4, 'Consulta General', 200.00, 'General', '2026-02-28 07:03:11', '2026-02-28 07:03:11');

-- Volcando estructura para tabla dental_connect_db.catalogo_tipo_sangre
CREATE TABLE IF NOT EXISTS `catalogo_tipo_sangre` (
  `id_tipo_sangre` int NOT NULL AUTO_INCREMENT,
  `tipo` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tipo_sangre`),
  UNIQUE KEY `tipo` (`tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.catalogo_tipo_sangre: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.citas
CREATE TABLE IF NOT EXISTS `citas` (
  `id_cita` bigint NOT NULL AUTO_INCREMENT,
  `id_clinica` int DEFAULT NULL,
  `id_paciente` int DEFAULT NULL,
  `id_doctor` int DEFAULT NULL,
  `id_servicio` int DEFAULT NULL,
  `fecha_hora_inicio` datetime DEFAULT NULL,
  `fecha_hora_fin` datetime DEFAULT NULL,
  `estado_cita` enum('pendiente','confirmada','cancelada','completada') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `motivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `costo_estimado` decimal(10,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cita`),
  KEY `id_clinica` (`id_clinica`),
  KEY `id_paciente` (`id_paciente`),
  KEY `id_doctor` (`id_doctor`),
  KEY `id_servicio` (`id_servicio`),
  CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`id_clinica`) REFERENCES `clinicas` (`id_clinica`),
  CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`),
  CONSTRAINT `citas_ibfk_3` FOREIGN KEY (`id_doctor`) REFERENCES `doctores` (`id_doctor`),
  CONSTRAINT `citas_ibfk_4` FOREIGN KEY (`id_servicio`) REFERENCES `catalogo_servicios` (`id_servicio`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.citas: ~2 rows (aproximadamente)
INSERT INTO `citas` (`id_cita`, `id_clinica`, `id_paciente`, `id_doctor`, `id_servicio`, `fecha_hora_inicio`, `fecha_hora_fin`, `estado_cita`, `motivo`, `costo_estimado`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 1, 3, '2026-03-01 13:50:00', '2026-03-01 14:50:00', 'pendiente', 'Brackets', 4000.00, '2026-02-28 06:50:35', '2026-02-28 06:50:35'),
	(2, 4, 2, 2, 4, '2026-03-01 12:00:00', '2026-03-01 13:00:00', 'pendiente', 'Consulta General', 200.00, '2026-02-28 07:03:45', '2026-02-28 07:03:45');

-- Volcando estructura para tabla dental_connect_db.clinicas
CREATE TABLE IF NOT EXISTS `clinicas` (
  `id_clinica` int NOT NULL AUTO_INCREMENT,
  `nombre_comercial` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rfc_clinica` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `config_anticipo_pct` decimal(5,2) DEFAULT '0.00',
  `numero_telefono` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `localidad` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codigo_postal` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_clinica`),
  UNIQUE KEY `rfc_clinica` (`rfc_clinica`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.clinicas: ~2 rows (aproximadamente)
INSERT INTO `clinicas` (`id_clinica`, `nombre_comercial`, `rfc_clinica`, `config_anticipo_pct`, `numero_telefono`, `localidad`, `estado`, `codigo_postal`, `created_at`, `updated_at`) VALUES
	(1, 'smile dental', 'OOHM020123N93', 0.00, '2361231289', NULL, NULL, NULL, '2026-02-27 18:19:14', '2026-02-27 18:19:14'),
	(4, 'clinica dental mexico', 'TRUE230103R23', 0.00, '2381523344', 'TEHUACAN', 'Puebla', '75765', '2026-02-28 05:03:14', '2026-02-28 05:03:14');

-- Volcando estructura para tabla dental_connect_db.config_global
CREATE TABLE IF NOT EXISTS `config_global` (
  `id_config` int NOT NULL AUTO_INCREMENT,
  `clave` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_config`),
  UNIQUE KEY `clave` (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.config_global: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.config_recordatorios
CREATE TABLE IF NOT EXISTS `config_recordatorios` (
  `id_regla` int NOT NULL AUTO_INCREMENT,
  `id_clinica` int NOT NULL,
  `tiempo_anticipacion` int NOT NULL,
  `unidad_tiempo` enum('dias','horas','minutos') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `plantilla_mensaje` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_regla`),
  KEY `id_clinica` (`id_clinica`),
  CONSTRAINT `config_recordatorios_ibfk_1` FOREIGN KEY (`id_clinica`) REFERENCES `clinicas` (`id_clinica`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.config_recordatorios: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.contacto_emergencia
CREATE TABLE IF NOT EXISTS `contacto_emergencia` (
  `id_contacto_emergencia` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido_materno` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellido_paterno` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_telefono` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_contacto_emergencia`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.contacto_emergencia: ~2 rows (aproximadamente)
INSERT INTO `contacto_emergencia` (`id_contacto_emergencia`, `nombre`, `apellido_materno`, `apellido_paterno`, `numero_telefono`, `created_at`, `updated_at`) VALUES
	(4, 'Emilia', 'Gonzalez', 'Hernandez', '2381919256', '2026-02-28 06:50:12', '2026-02-28 06:50:12'),
	(5, 'Lara', 'Dead', 'Croft', '2381567891', '2026-02-28 06:59:03', '2026-02-28 06:59:03');

-- Volcando estructura para tabla dental_connect_db.doctores
CREATE TABLE IF NOT EXISTS `doctores` (
  `id_doctor` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `cedula_profesional` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `horario_default` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_doctor`),
  UNIQUE KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `doctores_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios_sistema` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.doctores: ~2 rows (aproximadamente)
INSERT INTO `doctores` (`id_doctor`, `id_usuario`, `cedula_profesional`, `horario_default`, `created_at`, `updated_at`) VALUES
	(1, 1, NULL, NULL, '2026-02-27 18:19:15', '2026-02-27 18:19:15'),
	(2, 2, NULL, NULL, '2026-02-28 05:03:15', '2026-02-28 05:03:15');

-- Volcando estructura para tabla dental_connect_db.evolucion_tratamiento
CREATE TABLE IF NOT EXISTS `evolucion_tratamiento` (
  `id_evolucion` int NOT NULL AUTO_INCREMENT,
  `id_servicio` int DEFAULT NULL,
  `id_paciente` int DEFAULT NULL,
  `fecha_evolucion` datetime DEFAULT NULL,
  `descripcion_avance` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subjetivo_soap` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `objetivo_soap` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `plan_tratamiento` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `estado_paciente` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_evolucion`),
  KEY `id_servicio` (`id_servicio`),
  KEY `id_paciente` (`id_paciente`),
  CONSTRAINT `evolucion_tratamiento_ibfk_1` FOREIGN KEY (`id_servicio`) REFERENCES `catalogo_servicios` (`id_servicio`),
  CONSTRAINT `evolucion_tratamiento_ibfk_2` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.evolucion_tratamiento: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.failed_jobs: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.horarios_bloqueados
CREATE TABLE IF NOT EXISTS `horarios_bloqueados` (
  `id_bloqueo` int NOT NULL AUTO_INCREMENT,
  `id_doctor` int DEFAULT NULL,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `motivo` enum('vacaciones','enfermedad','otro') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estatus_horario` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_bloqueo`),
  KEY `id_doctor` (`id_doctor`),
  CONSTRAINT `horarios_bloqueados_ibfk_1` FOREIGN KEY (`id_doctor`) REFERENCES `doctores` (`id_doctor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.horarios_bloqueados: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.ingresos_caja
CREATE TABLE IF NOT EXISTS `ingresos_caja` (
  `id_ingreso` bigint NOT NULL AUTO_INCREMENT,
  `id_cita` bigint DEFAULT NULL,
  `id_clinica` int DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `metodo` enum('efectivo','tarjeta','transferencia','otro') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_ingreso` datetime DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_ingreso`),
  KEY `id_cita` (`id_cita`),
  KEY `id_clinica` (`id_clinica`),
  CONSTRAINT `ingresos_caja_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`),
  CONSTRAINT `ingresos_caja_ibfk_2` FOREIGN KEY (`id_clinica`) REFERENCES `clinicas` (`id_clinica`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.ingresos_caja: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.inventario
CREATE TABLE IF NOT EXISTS `inventario` (
  `id_item` int NOT NULL AUTO_INCREMENT,
  `id_clinica` int NOT NULL,
  `nombre_item` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` int DEFAULT '0',
  `precio` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_item`),
  KEY `id_clinica` (`id_clinica`),
  CONSTRAINT `inventario_ibfk_1` FOREIGN KEY (`id_clinica`) REFERENCES `clinicas` (`id_clinica`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.inventario: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.jobs: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.job_batches: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.migrations: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.notificaciones
CREATE TABLE IF NOT EXISTS `notificaciones` (
  `id_notificacion` bigint NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `tipo` enum('recordatorio','confirmacion','cancelacion','push') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mensaje` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `device_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('pendiente','enviado','leido') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_notificacion`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios_sistema` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.notificaciones: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.odontograma
CREATE TABLE IF NOT EXISTS `odontograma` (
  `id_odontograma` int NOT NULL AUTO_INCREMENT,
  `id_paciente` int DEFAULT NULL,
  `id_cita` bigint DEFAULT NULL,
  `numero_diente` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cara_diente` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado_diente` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observaciones` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_odontograma`),
  KEY `id_paciente` (`id_paciente`),
  KEY `id_cita` (`id_cita`),
  CONSTRAINT `odontograma_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`),
  CONSTRAINT `odontograma_ibfk_2` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.odontograma: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.pacientes
CREATE TABLE IF NOT EXISTS `pacientes` (
  `id_paciente` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellido_paterno` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellido_materno` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alergias_criticas` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `sexo` enum('M','F','O') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correo_electronico` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_sangre` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `ocupacion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enfermedades_cronicas` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `alergias` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `id_contacto_emergencia` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_paciente`),
  UNIQUE KEY `id_usuario` (`id_usuario`),
  KEY `id_contacto_emergencia` (`id_contacto_emergencia`),
  CONSTRAINT `pacientes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios_sistema` (`id_usuario`),
  CONSTRAINT `pacientes_ibfk_2` FOREIGN KEY (`id_contacto_emergencia`) REFERENCES `contacto_emergencia` (`id_contacto_emergencia`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.pacientes: ~2 rows (aproximadamente)
INSERT INTO `pacientes` (`id_paciente`, `id_usuario`, `nombre`, `apellido_paterno`, `apellido_materno`, `telefono`, `alergias_criticas`, `fecha_nacimiento`, `sexo`, `correo_electronico`, `tipo_sangre`, `peso`, `ocupacion`, `enfermedades_cronicas`, `alergias`, `id_contacto_emergencia`, `created_at`, `updated_at`, `is_active`) VALUES
	(1, 3, 'Marco', 'osorio', 'hernandez', '23815645678', NULL, '2002-01-23', 'M', 'mrcocrck2014@gmail.com', NULL, 95.00, 'estudiante', 'Ninguna', 'Ninguna', 4, '2026-02-28 06:50:13', '2026-02-28 06:50:13', 1),
	(2, 4, 'Maria', 'Perez', 'Leon', '2381234567', NULL, '2006-10-05', 'F', 'leoRE0@gmail.com', 'AB+', 90.00, 'Estudiante', 'Ninguna', 'Lidocaina', 5, '2026-02-28 06:59:03', '2026-02-28 06:59:03', 1);

-- Volcando estructura para tabla dental_connect_db.pacientes_alergias
CREATE TABLE IF NOT EXISTS `pacientes_alergias` (
  `id_registro` bigint NOT NULL AUTO_INCREMENT,
  `id_paciente` int DEFAULT NULL,
  `id_alergia` int DEFAULT NULL,
  PRIMARY KEY (`id_registro`),
  UNIQUE KEY `uk_pa` (`id_paciente`,`id_alergia`),
  KEY `id_alergia` (`id_alergia`),
  CONSTRAINT `pacientes_alergias_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`),
  CONSTRAINT `pacientes_alergias_ibfk_2` FOREIGN KEY (`id_alergia`) REFERENCES `catalogo_alergias` (`id_alergia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.pacientes_alergias: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.pacientes_enfermedades_cronicas
CREATE TABLE IF NOT EXISTS `pacientes_enfermedades_cronicas` (
  `id_registro` bigint NOT NULL AUTO_INCREMENT,
  `id_paciente` int DEFAULT NULL,
  `id_enfermedad_cronica` int DEFAULT NULL,
  PRIMARY KEY (`id_registro`),
  UNIQUE KEY `uk_pec` (`id_paciente`,`id_enfermedad_cronica`),
  KEY `id_enfermedad_cronica` (`id_enfermedad_cronica`),
  CONSTRAINT `pacientes_enfermedades_cronicas_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`),
  CONSTRAINT `pacientes_enfermedades_cronicas_ibfk_2` FOREIGN KEY (`id_enfermedad_cronica`) REFERENCES `catalogo_enfermedades_cronicas` (`id_enfermedad_cronica`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.pacientes_enfermedades_cronicas: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.paciente_peso
CREATE TABLE IF NOT EXISTS `paciente_peso` (
  `id_paciente_peso` int NOT NULL AUTO_INCREMENT,
  `id_paciente` int DEFAULT NULL,
  `peso_kg` decimal(4,2) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_paciente_peso`),
  KEY `id_paciente` (`id_paciente`),
  CONSTRAINT `paciente_peso_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.paciente_peso: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.paciente_tipo_sangre
CREATE TABLE IF NOT EXISTS `paciente_tipo_sangre` (
  `id_paciente_tipo_sangre` int NOT NULL AUTO_INCREMENT,
  `id_paciente` int DEFAULT NULL,
  `id_tipo_sangre` int DEFAULT NULL,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_paciente_tipo_sangre`),
  KEY `id_paciente` (`id_paciente`),
  KEY `id_tipo_sangre` (`id_tipo_sangre`),
  CONSTRAINT `paciente_tipo_sangre_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`),
  CONSTRAINT `paciente_tipo_sangre_ibfk_2` FOREIGN KEY (`id_tipo_sangre`) REFERENCES `catalogo_tipo_sangre` (`id_tipo_sangre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.paciente_tipo_sangre: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.password_reset_tokens: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.permisos
CREATE TABLE IF NOT EXISTS `permisos` (
  `id_permiso` int NOT NULL AUTO_INCREMENT,
  `rol` enum('admin','doctor','recepcionista','paciente') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `accion` enum('read','write','delete') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `recurso` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_permiso`),
  UNIQUE KEY `uk_permiso` (`rol`,`accion`,`recurso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.permisos: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.personal_access_tokens: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.publicidad
CREATE TABLE IF NOT EXISTS `publicidad` (
  `id_publicidad` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `titulo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `imagen_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_publicidad`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `publicidad_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios_sistema` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.publicidad: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.reviews
CREATE TABLE IF NOT EXISTS `reviews` (
  `id_review` int NOT NULL AUTO_INCREMENT,
  `id_cita` bigint DEFAULT NULL,
  `id_paciente` int DEFAULT NULL,
  `calificacion` int DEFAULT NULL,
  `comentario` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id_review`),
  KEY `id_cita` (`id_cita`),
  KEY `id_paciente` (`id_paciente`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`),
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.reviews: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.seguimiento_clinico
CREATE TABLE IF NOT EXISTS `seguimiento_clinico` (
  `id_seguimiento` int NOT NULL AUTO_INCREMENT,
  `id_cita` bigint DEFAULT NULL,
  `postratamiento` enum('si','no') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_servicio` int DEFAULT NULL,
  `observaciones` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id_seguimiento`),
  KEY `id_cita` (`id_cita`),
  KEY `id_servicio` (`id_servicio`),
  CONSTRAINT `seguimiento_clinico_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`),
  CONSTRAINT `seguimiento_clinico_ibfk_2` FOREIGN KEY (`id_servicio`) REFERENCES `catalogo_servicios` (`id_servicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.seguimiento_clinico: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.sessions: ~2 rows (aproximadamente)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('CM7yA7yavgN59PgwdZNvPLh4KOaDuYnM0eX4vGe1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ2dBV05GeXpBT2NSbEQxNHBkRjdKUVRRSUVzMGRsWUF6NmIyT1FtZyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9kZW50YWxjb25uZWN0LnRlc3QvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO319', 1772256847),
	('vyzklda6CuLvbDgeIWyNQ1GEaQtg6cgku3XtYHE0', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMlRQdXNmSXNIMWNWUkNWYnR5QzdsTVFid09LMk1URDYza2NIZjFHYiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9kZW50YWxjb25uZWN0LnRlc3QvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO319', 1772262260);

-- Volcando estructura para tabla dental_connect_db.tokens
CREATE TABLE IF NOT EXISTS `tokens` (
  `id_token` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_expiracion` datetime DEFAULT NULL,
  `estado` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_token`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios_sistema` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.tokens: ~2 rows (aproximadamente)
INSERT INTO `tokens` (`id_token`, `id_usuario`, `token`, `tipo_token`, `fecha_creacion`, `fecha_expiracion`, `estado`) VALUES
	(1, 3, 'PAC-DHWQC8', 'acceso_app', '2026-02-28 00:50:13', '2027-02-28 00:50:13', 'activo'),
	(2, 4, 'PAC-YJ1WQY', 'acceso_app', '2026-02-28 00:59:03', '2027-02-28 00:59:03', 'activo');

-- Volcando estructura para tabla dental_connect_db.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.users: ~0 rows (aproximadamente)

-- Volcando estructura para tabla dental_connect_db.usuarios_sistema
CREATE TABLE IF NOT EXISTS `usuarios_sistema` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `id_clinica` int DEFAULT NULL,
  `nombre_completo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` enum('administrador','doctor','recepcionista','paciente') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `reset_password_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_password_expires` datetime DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `email_2` (`email`),
  UNIQUE KEY `email_3` (`email`),
  UNIQUE KEY `email_4` (`email`),
  UNIQUE KEY `email_5` (`email`),
  UNIQUE KEY `email_6` (`email`),
  UNIQUE KEY `email_7` (`email`),
  UNIQUE KEY `email_8` (`email`),
  UNIQUE KEY `email_9` (`email`),
  KEY `id_clinica` (`id_clinica`),
  CONSTRAINT `usuarios_sistema_ibfk_1` FOREIGN KEY (`id_clinica`) REFERENCES `clinicas` (`id_clinica`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla dental_connect_db.usuarios_sistema: ~6 rows (aproximadamente)
INSERT INTO `usuarios_sistema` (`id_usuario`, `id_clinica`, `nombre_completo`, `rol`, `is_active`, `email`, `password`, `created_at`, `updated_at`, `reset_password_token`, `reset_password_expires`) VALUES
	(1, 1, 'Marco osorio Henrnandez', 'doctor', 1, 'mrcocrck2019@gmail.com', '$2y$12$3FLfeWl2TMhtP3DRY5Lh8uqgWOcnHC5N2lS0Wsc8K0yhA0J/S4KE6', '2026-02-27 18:19:15', '2026-02-27 18:35:34', NULL, NULL),
	(2, 4, 'Danna Yamilet Hernandez Marin', 'doctor', 1, 'maria@gmail.com', '$2y$12$IR6Omv1TCbTjfHwfQDL1Gug.pjQsp6ejUiVFW7KsSqnZeQPjD2AXK', '2026-02-27 23:03:15', '2026-02-27 23:03:15', NULL, NULL),
	(3, 1, 'Marco osorio hernandez', 'paciente', 1, 'mrcocrck2014@gmail.com', '$2y$12$LCdRntc7wmtdB1eANki40.02NqaZFP7bSiOFlkpalAqKjfy3tICXa', '2026-02-28 00:50:13', '2026-02-28 00:50:13', NULL, NULL),
	(4, 4, 'Maria Perez Leon', 'paciente', 1, 'leoRE0@gmail.com', '$2y$12$ZgMkzoSI8WOZDX.5tbW6me4ZQ7fyTc7ixPF3xifRmJHyquf.fQhqm', '2026-02-28 00:59:03', '2026-02-28 00:59:03', NULL, NULL),
	(5, 1, 'Test Paciente', 'paciente', 1, 'test@paciente.com', '$2y$12$Fe7RFnmGW1TT06lx.P4hXOGNnRenar1ytiA6VTxEmV8BA0ZLUc6dy', '2026-02-28 01:16:01', '2026-02-28 01:16:01', NULL, NULL),
	(6, 1, 'Test Paciente', 'paciente', 1, 'test2@paciente.com', '$2y$12$O13bNOc.f3sgdx6dt4pr5OuEkAjMsnhbbL1XTjqaPu3KV9qgZnvyu', '2026-02-28 01:16:01', '2026-02-28 01:16:01', NULL, NULL);

-- Volcando estructura para disparador dental_connect_db.trg_citas_update
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE DEFINER=`root`@`localhost` TRIGGER `trg_citas_update` BEFORE UPDATE ON `citas` FOR EACH ROW BEGIN



    INSERT INTO audit_logs (



        id_usuario,



        accion,



        tabla_afectada,



        id_registro,



        detalles



    )



    VALUES (



        NULL,



        'update',



        'citas',



        OLD.id_cita,



        JSON_OBJECT(



            'old_estado', OLD.estado_cita,



            'new_estado', NEW.estado_cita



        )



    );



END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
