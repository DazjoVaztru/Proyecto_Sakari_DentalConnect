-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: dental_connect_db
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `archivos`
--

DROP TABLE IF EXISTS `archivos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `archivos` (
  `id_archivo` bigint NOT NULL AUTO_INCREMENT,
  `id_paciente` int DEFAULT NULL,
  `id_cita` bigint DEFAULT NULL,
  `url_archivo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` enum('imagen','pdf','rayos_x') COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_archivo`),
  KEY `id_cita` (`id_cita`),
  KEY `idx_archivos_paciente` (`id_paciente`),
  CONSTRAINT `archivos_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`) ON DELETE SET NULL,
  CONSTRAINT `archivos_ibfk_2` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `archivos`
--

LOCK TABLES `archivos` WRITE;
/*!40000 ALTER TABLE `archivos` DISABLE KEYS */;
/*!40000 ALTER TABLE `archivos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `id_log` bigint NOT NULL AUTO_INCREMENT,
  `id_usuario` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tabla_afectada` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_registro` bigint DEFAULT NULL,
  `detalles` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`),
  KEY `idx_logs_usuario` (`id_usuario`),
  KEY `idx_logs_accion` (`accion`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,'root@localhost','update','citas',2,'{\"new_estado\": \"pendiente\", \"old_estado\": \"pendiente\"}','2026-02-10 13:28:55'),(2,'root@localhost','update','citas',2,'{\"new_estado\": \"pendiente\", \"old_estado\": \"pendiente\"}','2026-02-10 13:29:30'),(3,'root@localhost','update','citas',2,'{\"new_estado\": \"pendiente\", \"old_estado\": \"pendiente\"}','2026-02-10 13:29:37'),(4,'root@localhost','update','citas',3,'{\"new_estado\": \"pendiente\", \"old_estado\": \"pendiente\"}','2026-02-10 14:59:24');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bitacora_notificaciones`
--

DROP TABLE IF EXISTS `bitacora_notificaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bitacora_notificaciones` (
  `id_notificacion` bigint NOT NULL AUTO_INCREMENT,
  `id_cita` bigint NOT NULL,
  `estado_envio` enum('enviado','fallido','pendiente') COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_envio` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_notificacion`),
  KEY `idx_notificaciones_cita` (`id_cita`),
  CONSTRAINT `bitacora_notificaciones_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacora_notificaciones`
--

LOCK TABLES `bitacora_notificaciones` WRITE;
/*!40000 ALTER TABLE `bitacora_notificaciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `bitacora_notificaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogo_alergias`
--

DROP TABLE IF EXISTS `catalogo_alergias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalogo_alergias` (
  `id_alergia` int NOT NULL AUTO_INCREMENT,
  `nombre_alergeno` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_alergia`),
  UNIQUE KEY `nombre_alergeno` (`nombre_alergeno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogo_alergias`
--

LOCK TABLES `catalogo_alergias` WRITE;
/*!40000 ALTER TABLE `catalogo_alergias` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalogo_alergias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogo_enfermedades_cronicas`
--

DROP TABLE IF EXISTS `catalogo_enfermedades_cronicas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalogo_enfermedades_cronicas` (
  `id_enfermedad_cronica` int NOT NULL AUTO_INCREMENT,
  `nombre_enfermedad` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_enfermedad_cronica`),
  UNIQUE KEY `nombre_enfermedad` (`nombre_enfermedad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogo_enfermedades_cronicas`
--

LOCK TABLES `catalogo_enfermedades_cronicas` WRITE;
/*!40000 ALTER TABLE `catalogo_enfermedades_cronicas` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalogo_enfermedades_cronicas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogo_servicios`
--

DROP TABLE IF EXISTS `catalogo_servicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalogo_servicios` (
  `id_servicio` int NOT NULL AUTO_INCREMENT,
  `id_clinica` int NOT NULL,
  `nombre_servicio` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio_base` decimal(10,2) NOT NULL DEFAULT '0.00',
  `categoria` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_servicio`),
  KEY `idx_servicios_clinica` (`id_clinica`),
  CONSTRAINT `catalogo_servicios_ibfk_1` FOREIGN KEY (`id_clinica`) REFERENCES `clinicas` (`id_clinica`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogo_servicios`
--

LOCK TABLES `catalogo_servicios` WRITE;
/*!40000 ALTER TABLE `catalogo_servicios` DISABLE KEYS */;
INSERT INTO `catalogo_servicios` VALUES (1,1,'Consulta General',500.00,'Diagnóstico','2026-02-10 08:57:19','2026-02-10 08:57:19'),(2,1,'Limpieza Ultrasónica',800.00,'Preventiva','2026-02-10 08:57:19','2026-02-10 08:57:19'),(3,1,'Resina',600.00,'Restaurativa','2026-02-10 08:57:19','2026-02-10 08:57:19'),(4,1,'Extracción Simple',450.00,'Cirugía','2026-02-10 08:57:19','2026-02-10 08:57:19'),(5,1,'Ortodoncia (Brackets)',5000.00,'Ortodoncia','2026-02-10 17:12:47','2026-02-10 17:12:47'),(6,1,'Ortodoncia (Brackets)',5000.00,'Ortodoncia','2026-02-10 17:14:34','2026-02-10 17:14:34');
/*!40000 ALTER TABLE `catalogo_servicios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogo_tipo_sangre`
--

DROP TABLE IF EXISTS `catalogo_tipo_sangre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalogo_tipo_sangre` (
  `id_tipo_sangre` int NOT NULL AUTO_INCREMENT,
  `tipo` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tipo_sangre`),
  UNIQUE KEY `tipo` (`tipo`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogo_tipo_sangre`
--

LOCK TABLES `catalogo_tipo_sangre` WRITE;
/*!40000 ALTER TABLE `catalogo_tipo_sangre` DISABLE KEYS */;
INSERT INTO `catalogo_tipo_sangre` VALUES (1,'O+','2026-02-10 08:57:19','2026-02-10 08:57:19'),(2,'O-','2026-02-10 08:57:19','2026-02-10 08:57:19'),(3,'A+','2026-02-10 08:57:19','2026-02-10 08:57:19'),(4,'A-','2026-02-10 08:57:19','2026-02-10 08:57:19');
/*!40000 ALTER TABLE `catalogo_tipo_sangre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `citas`
--

DROP TABLE IF EXISTS `citas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `citas` (
  `id_cita` bigint NOT NULL AUTO_INCREMENT,
  `id_clinica` int NOT NULL,
  `id_paciente` int NOT NULL,
  `id_doctor` int NOT NULL,
  `id_servicio` int DEFAULT NULL,
  `fecha_hora_inicio` datetime NOT NULL,
  `fecha_hora_fin` datetime DEFAULT NULL,
  `estado_cita` enum('pendiente','confirmada','cancelada','completada') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `motivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cita`),
  KEY `id_servicio` (`id_servicio`),
  KEY `idx_citas_clinica_fecha` (`id_clinica`,`fecha_hora_inicio`),
  KEY `idx_citas_paciente` (`id_paciente`),
  KEY `idx_citas_doctor` (`id_doctor`),
  CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`id_clinica`) REFERENCES `clinicas` (`id_clinica`) ON DELETE CASCADE,
  CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`) ON DELETE CASCADE,
  CONSTRAINT `citas_ibfk_3` FOREIGN KEY (`id_doctor`) REFERENCES `doctores` (`id_doctor`) ON DELETE RESTRICT,
  CONSTRAINT `citas_ibfk_4` FOREIGN KEY (`id_servicio`) REFERENCES `catalogo_servicios` (`id_servicio`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `citas`
--

LOCK TABLES `citas` WRITE;
/*!40000 ALTER TABLE `citas` DISABLE KEYS */;
INSERT INTO `citas` VALUES (1,1,1,1,1,'2026-02-10 10:57:19',NULL,'pendiente',NULL,'2026-02-10 14:57:19','2026-02-10 08:57:19'),(2,1,2,1,6,'2026-02-10 13:29:37',NULL,'pendiente',NULL,'2026-02-10 17:14:34','2026-02-10 19:29:37'),(3,1,4,1,NULL,'2026-02-10 14:59:24','2026-02-12 09:29:00','pendiente','Limpieza Dental','2026-02-10 20:30:01','2026-02-10 20:59:24');
/*!40000 ALTER TABLE `citas` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 */ /*!50003 TRIGGER `trg_citas_update` BEFORE UPDATE ON `citas` FOR EACH ROW BEGIN

    INSERT INTO audit_logs (id_usuario, accion, tabla_afectada, id_registro, detalles)

    VALUES (CURRENT_USER(), 'update', 'citas', OLD.id_cita, JSON_OBJECT('old_estado', OLD.estado_cita, 'new_estado', NEW.estado_cita));

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `clinicas`
--

DROP TABLE IF EXISTS `clinicas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clinicas` (
  `id_clinica` int NOT NULL AUTO_INCREMENT,
  `nombre_comercial` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rfc_clinica` varchar(13) COLLATE utf8mb4_unicode_ci NOT NULL,
  `config_anticipo_pct` decimal(5,2) DEFAULT '0.00',
  `numero_telefono` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `localidad` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codigo_postal` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_clinica`),
  UNIQUE KEY `rfc_clinica` (`rfc_clinica`),
  KEY `idx_clinicas_rfc` (`rfc_clinica`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clinicas`
--

LOCK TABLES `clinicas` WRITE;
/*!40000 ALTER TABLE `clinicas` DISABLE KEYS */;
INSERT INTO `clinicas` VALUES (1,'Dental Connect Pro','DCP240206XYZ',0.00,'5551234567','Ciudad de México','CDMX',NULL,'2026-02-10 14:57:19','2026-02-10 14:57:19');
/*!40000 ALTER TABLE `clinicas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config_global`
--

DROP TABLE IF EXISTS `config_global`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `config_global` (
  `id_config` int NOT NULL AUTO_INCREMENT,
  `clave` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_config`),
  UNIQUE KEY `clave` (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config_global`
--

LOCK TABLES `config_global` WRITE;
/*!40000 ALTER TABLE `config_global` DISABLE KEYS */;
/*!40000 ALTER TABLE `config_global` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config_recordatorios`
--

DROP TABLE IF EXISTS `config_recordatorios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `config_recordatorios` (
  `id_regla` int NOT NULL AUTO_INCREMENT,
  `id_clinica` int NOT NULL,
  `tiempo_anticipacion` int NOT NULL DEFAULT '1',
  `unidad_tiempo` enum('dias','horas','minutos') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'horas',
  `plantilla_mensaje` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_regla`),
  KEY `idx_recordatorios_clinica` (`id_clinica`),
  CONSTRAINT `config_recordatorios_ibfk_1` FOREIGN KEY (`id_clinica`) REFERENCES `clinicas` (`id_clinica`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config_recordatorios`
--

LOCK TABLES `config_recordatorios` WRITE;
/*!40000 ALTER TABLE `config_recordatorios` DISABLE KEYS */;
/*!40000 ALTER TABLE `config_recordatorios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacto_emergencia`
--

DROP TABLE IF EXISTS `contacto_emergencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacto_emergencia` (
  `id_contacto_emergencia` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido_materno` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellido_paterno` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_telefono` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_contacto_emergencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacto_emergencia`
--

LOCK TABLES `contacto_emergencia` WRITE;
/*!40000 ALTER TABLE `contacto_emergencia` DISABLE KEYS */;
/*!40000 ALTER TABLE `contacto_emergencia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctores`
--

DROP TABLE IF EXISTS `doctores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctores` (
  `id_doctor` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `cedula_profesional` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `horario_default` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_doctor`),
  UNIQUE KEY `id_usuario` (`id_usuario`),
  UNIQUE KEY `cedula_profesional` (`cedula_profesional`),
  KEY `idx_doctores_usuario` (`id_usuario`),
  CONSTRAINT `doctores_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios_sistema` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctores`
--

LOCK TABLES `doctores` WRITE;
/*!40000 ALTER TABLE `doctores` DISABLE KEYS */;
INSERT INTO `doctores` VALUES (1,1,'12345678',NULL,'2026-02-10 14:57:19','2026-02-10 08:57:19');
/*!40000 ALTER TABLE `doctores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evolucion_tratamiento`
--

DROP TABLE IF EXISTS `evolucion_tratamiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `evolucion_tratamiento` (
  `id_evolucion` int NOT NULL AUTO_INCREMENT,
  `id_servicio` int NOT NULL,
  `id_paciente` int NOT NULL,
  `fecha_evolucion` datetime NOT NULL,
  `descripcion_avance` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subjetivo_soap` text COLLATE utf8mb4_unicode_ci,
  `objetivo_soap` text COLLATE utf8mb4_unicode_ci,
  `plan_tratamiento` text COLLATE utf8mb4_unicode_ci,
  `estado_paciente` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_evolucion`),
  KEY `id_servicio` (`id_servicio`),
  KEY `idx_evolucion_paciente_fecha` (`id_paciente`,`fecha_evolucion`),
  CONSTRAINT `evolucion_tratamiento_ibfk_1` FOREIGN KEY (`id_servicio`) REFERENCES `catalogo_servicios` (`id_servicio`) ON DELETE RESTRICT,
  CONSTRAINT `evolucion_tratamiento_ibfk_2` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evolucion_tratamiento`
--

LOCK TABLES `evolucion_tratamiento` WRITE;
/*!40000 ALTER TABLE `evolucion_tratamiento` DISABLE KEYS */;
/*!40000 ALTER TABLE `evolucion_tratamiento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horarios_bloqueados`
--

DROP TABLE IF EXISTS `horarios_bloqueados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `horarios_bloqueados` (
  `id_bloqueo` int NOT NULL AUTO_INCREMENT,
  `id_doctor` int NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `motivo` enum('vacaciones','enfermedad','otro') COLLATE utf8mb4_unicode_ci NOT NULL,
  `estatus_horario` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_bloqueo`),
  KEY `idx_bloqueos_doctor_fecha` (`id_doctor`,`fecha_inicio`),
  CONSTRAINT `horarios_bloqueados_ibfk_1` FOREIGN KEY (`id_doctor`) REFERENCES `doctores` (`id_doctor`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horarios_bloqueados`
--

LOCK TABLES `horarios_bloqueados` WRITE;
/*!40000 ALTER TABLE `horarios_bloqueados` DISABLE KEYS */;
/*!40000 ALTER TABLE `horarios_bloqueados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingresos_caja`
--

DROP TABLE IF EXISTS `ingresos_caja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ingresos_caja` (
  `id_ingreso` bigint NOT NULL AUTO_INCREMENT,
  `id_cita` bigint DEFAULT NULL,
  `id_clinica` int NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `metodo` enum('efectivo','tarjeta','transferencia','otro') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'efectivo',
  `descripcion` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_ingreso` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_ingreso`),
  KEY `id_cita` (`id_cita`),
  KEY `idx_ingresos_clinica_fecha` (`id_clinica`,`fecha_ingreso`),
  CONSTRAINT `ingresos_caja_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`) ON DELETE SET NULL,
  CONSTRAINT `ingresos_caja_ibfk_2` FOREIGN KEY (`id_clinica`) REFERENCES `clinicas` (`id_clinica`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingresos_caja`
--

LOCK TABLES `ingresos_caja` WRITE;
/*!40000 ALTER TABLE `ingresos_caja` DISABLE KEYS */;
INSERT INTO `ingresos_caja` VALUES (1,2,1,500.00,'efectivo','Anticipo Ortodoncia','2026-02-10 11:14:34','2026-02-10 17:14:34','2026-02-10 11:14:34'),(6,2,1,500.00,'efectivo','Abono desde Dashboard','2026-02-10 13:29:30','2026-02-10 19:29:30','2026-02-10 19:29:30'),(7,2,1,500.00,'efectivo','Abono desde Dashboard','2026-02-10 13:29:37','2026-02-10 19:29:37','2026-02-10 19:29:37');
/*!40000 ALTER TABLE `ingresos_caja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventario`
--

DROP TABLE IF EXISTS `inventario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventario` (
  `id_item` int NOT NULL AUTO_INCREMENT,
  `id_clinica` int NOT NULL,
  `nombre_item` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `precio` decimal(10,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_item`),
  KEY `idx_inventario_clinica` (`id_clinica`),
  CONSTRAINT `inventario_ibfk_1` FOREIGN KEY (`id_clinica`) REFERENCES `clinicas` (`id_clinica`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventario`
--

LOCK TABLES `inventario` WRITE;
/*!40000 ALTER TABLE `inventario` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificaciones`
--

DROP TABLE IF EXISTS `notificaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificaciones` (
  `id_notificacion` bigint NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `tipo` enum('recordatorio','confirmacion','cancelacion','push') COLLATE utf8mb4_unicode_ci NOT NULL,
  `mensaje` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('pendiente','enviado','leido') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_notificacion`),
  KEY `idx_notificaciones_usuario` (`id_usuario`),
  CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios_sistema` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificaciones`
--

LOCK TABLES `notificaciones` WRITE;
/*!40000 ALTER TABLE `notificaciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `notificaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `odontograma`
--

DROP TABLE IF EXISTS `odontograma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `odontograma` (
  `id_odontograma` int NOT NULL AUTO_INCREMENT,
  `id_paciente` int NOT NULL,
  `id_cita` bigint DEFAULT NULL,
  `numero_diente` enum('11','12','13','14','15','16','17','18','21','22','23','24','25','26','27','28','31','32','33','34','35','36','37','38','41','42','43','44','45','46','47','48') COLLATE utf8mb4_unicode_ci NOT NULL,
  `cara_diente` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado_diente` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_odontograma`),
  KEY `id_cita` (`id_cita`),
  KEY `idx_odontograma_paciente` (`id_paciente`),
  CONSTRAINT `odontograma_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`) ON DELETE CASCADE,
  CONSTRAINT `odontograma_ibfk_2` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `odontograma`
--

LOCK TABLES `odontograma` WRITE;
/*!40000 ALTER TABLE `odontograma` DISABLE KEYS */;
/*!40000 ALTER TABLE `odontograma` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paciente_peso`
--

DROP TABLE IF EXISTS `paciente_peso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `paciente_peso` (
  `id_paciente_peso` int NOT NULL AUTO_INCREMENT,
  `id_paciente` int NOT NULL,
  `peso_kg` decimal(4,2) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_paciente_peso`),
  KEY `idx_peso_paciente_fecha` (`id_paciente`,`fecha_registro`),
  CONSTRAINT `paciente_peso_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paciente_peso`
--

LOCK TABLES `paciente_peso` WRITE;
/*!40000 ALTER TABLE `paciente_peso` DISABLE KEYS */;
/*!40000 ALTER TABLE `paciente_peso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paciente_tipo_sangre`
--

DROP TABLE IF EXISTS `paciente_tipo_sangre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `paciente_tipo_sangre` (
  `id_paciente_tipo_sangre` int NOT NULL AUTO_INCREMENT,
  `id_paciente` int NOT NULL,
  `id_tipo_sangre` int NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_paciente_tipo_sangre`),
  KEY `id_paciente` (`id_paciente`),
  KEY `id_tipo_sangre` (`id_tipo_sangre`),
  CONSTRAINT `paciente_tipo_sangre_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`) ON DELETE CASCADE,
  CONSTRAINT `paciente_tipo_sangre_ibfk_2` FOREIGN KEY (`id_tipo_sangre`) REFERENCES `catalogo_tipo_sangre` (`id_tipo_sangre`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paciente_tipo_sangre`
--

LOCK TABLES `paciente_tipo_sangre` WRITE;
/*!40000 ALTER TABLE `paciente_tipo_sangre` DISABLE KEYS */;
/*!40000 ALTER TABLE `paciente_tipo_sangre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pacientes`
--

DROP TABLE IF EXISTS `pacientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pacientes` (
  `id_paciente` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido_materno` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellido_paterno` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alergias_criticas` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `calle` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_exterior` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_interior` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `colonia` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `municipio` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ocupacion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_contacto_emergencia` int DEFAULT NULL,
  `fecha_nacimiento` date NOT NULL,
  `sexo` enum('M','F','O') COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo_electronico` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_sangre` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `peso` decimal(4,2) DEFAULT NULL,
  `enfermedades_cronicas` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_paciente`),
  UNIQUE KEY `id_usuario` (`id_usuario`),
  UNIQUE KEY `correo_electronico` (`correo_electronico`),
  KEY `id_contacto_emergencia` (`id_contacto_emergencia`),
  KEY `idx_pacientes_nombre` (`apellido_paterno`,`nombre`),
  KEY `idx_pacientes_telefono` (`telefono`),
  CONSTRAINT `pacientes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios_sistema` (`id_usuario`) ON DELETE CASCADE,
  CONSTRAINT `pacientes_ibfk_2` FOREIGN KEY (`id_contacto_emergencia`) REFERENCES `contacto_emergencia` (`id_contacto_emergencia`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pacientes`
--

LOCK TABLES `pacientes` WRITE;
/*!40000 ALTER TABLE `pacientes` DISABLE KEYS */;
INSERT INTO `pacientes` VALUES (1,2,'María',NULL,'Gómez','5559876543',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1995-05-20','F','paciente@demo.com','O+',NULL,NULL,'2026-02-10 14:57:19','2026-02-10 08:57:19'),(2,1,'María','López','Gómez','555-987-6543',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1998-05-20','F','maria@demo.com','O+',NULL,NULL,'2026-02-10 17:14:34','2026-02-10 11:14:34'),(4,4,'Marco','hernandez','osorio','2381516645',NULL,NULL,NULL,NULL,NULL,NULL,'estudiante',NULL,'2002-01-23','M','mrcocrck2019@gmail.com','O+',90.00,'Ninguna','2026-02-10 20:17:15','2026-02-10 20:17:15');
/*!40000 ALTER TABLE `pacientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pacientes_alergias`
--

DROP TABLE IF EXISTS `pacientes_alergias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pacientes_alergias` (
  `id_registro` bigint NOT NULL AUTO_INCREMENT,
  `id_paciente` int NOT NULL,
  `id_alergia` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_registro`),
  UNIQUE KEY `id_paciente` (`id_paciente`,`id_alergia`),
  KEY `id_alergia` (`id_alergia`),
  CONSTRAINT `pacientes_alergias_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`) ON DELETE CASCADE,
  CONSTRAINT `pacientes_alergias_ibfk_2` FOREIGN KEY (`id_alergia`) REFERENCES `catalogo_alergias` (`id_alergia`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pacientes_alergias`
--

LOCK TABLES `pacientes_alergias` WRITE;
/*!40000 ALTER TABLE `pacientes_alergias` DISABLE KEYS */;
/*!40000 ALTER TABLE `pacientes_alergias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pacientes_enfermedades_cronicas`
--

DROP TABLE IF EXISTS `pacientes_enfermedades_cronicas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pacientes_enfermedades_cronicas` (
  `id_registro` bigint NOT NULL AUTO_INCREMENT,
  `id_paciente` int NOT NULL,
  `id_enfermedad_cronica` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_registro`),
  UNIQUE KEY `id_paciente` (`id_paciente`,`id_enfermedad_cronica`),
  KEY `id_enfermedad_cronica` (`id_enfermedad_cronica`),
  CONSTRAINT `pacientes_enfermedades_cronicas_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`) ON DELETE CASCADE,
  CONSTRAINT `pacientes_enfermedades_cronicas_ibfk_2` FOREIGN KEY (`id_enfermedad_cronica`) REFERENCES `catalogo_enfermedades_cronicas` (`id_enfermedad_cronica`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pacientes_enfermedades_cronicas`
--

LOCK TABLES `pacientes_enfermedades_cronicas` WRITE;
/*!40000 ALTER TABLE `pacientes_enfermedades_cronicas` DISABLE KEYS */;
/*!40000 ALTER TABLE `pacientes_enfermedades_cronicas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permisos`
--

DROP TABLE IF EXISTS `permisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permisos` (
  `id_permiso` int NOT NULL AUTO_INCREMENT,
  `rol` enum('admin','doctor','recepcionista','paciente') COLLATE utf8mb4_unicode_ci NOT NULL,
  `accion` enum('read','write','delete') COLLATE utf8mb4_unicode_ci NOT NULL,
  `recurso` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_permiso`),
  UNIQUE KEY `idx_permisos_rol_accion_recurso` (`rol`,`accion`,`recurso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permisos`
--

LOCK TABLES `permisos` WRITE;
/*!40000 ALTER TABLE `permisos` DISABLE KEYS */;
/*!40000 ALTER TABLE `permisos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id_review` int NOT NULL AUTO_INCREMENT,
  `id_cita` bigint NOT NULL,
  `id_paciente` int NOT NULL,
  `calificacion` int NOT NULL,
  `comentario` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_review`),
  KEY `id_paciente` (`id_paciente`),
  KEY `idx_reviews_cita` (`id_cita`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`) ON DELETE CASCADE,
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`) ON DELETE CASCADE,
  CONSTRAINT `reviews_chk_1` CHECK ((`calificacion` between 1 and 5))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seguimiento_clinico`
--

DROP TABLE IF EXISTS `seguimiento_clinico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seguimiento_clinico` (
  `id_seguimiento` int NOT NULL AUTO_INCREMENT,
  `id_cita` bigint NOT NULL,
  `postratamiento` enum('si','no') COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_servicio` int NOT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_seguimiento`),
  KEY `id_servicio` (`id_servicio`),
  KEY `idx_seguimiento_cita` (`id_cita`),
  CONSTRAINT `seguimiento_clinico_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`) ON DELETE CASCADE,
  CONSTRAINT `seguimiento_clinico_ibfk_2` FOREIGN KEY (`id_servicio`) REFERENCES `catalogo_servicios` (`id_servicio`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seguimiento_clinico`
--

LOCK TABLES `seguimiento_clinico` WRITE;
/*!40000 ALTER TABLE `seguimiento_clinico` DISABLE KEYS */;
/*!40000 ALTER TABLE `seguimiento_clinico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('uzazqqv0vN9oepaXLRof9Q1djUePDbic9qmR9dLh',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYVNPdmZkSUNJVWxqb0FaSVdlcldKZkgwRExROGg5a0R3UTRFdU9oRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9kZW50YWxjb25uZWN0LnRlc3QvcGFjaWVudGVzIjtzOjU6InJvdXRlIjtzOjE1OiJwYWNpZW50ZXMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1770735615);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tokens`
--

DROP TABLE IF EXISTS `tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tokens` (
  `id_token` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_token` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_expiracion` datetime NOT NULL,
  `estado` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_token`),
  KEY `idx_tokens_usuario` (`id_usuario`),
  CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios_sistema` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tokens`
--

LOCK TABLES `tokens` WRITE;
/*!40000 ALTER TABLE `tokens` DISABLE KEYS */;
INSERT INTO `tokens` VALUES (1,4,'PAC-FR1VVC','acceso_app','2026-02-10 14:17:15','2027-02-10 14:17:15','activo','2026-02-10 20:17:15','2026-02-10 20:17:15');
/*!40000 ALTER TABLE `tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios_sistema`
--

DROP TABLE IF EXISTS `usuarios_sistema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios_sistema` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `id_clinica` int NOT NULL,
  `nombre_completo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` enum('admin','doctor','recepcionista','paciente') COLLATE utf8mb4_unicode_ci NOT NULL,
  `especialidad` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `ultimo_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_usuarios_clinica` (`id_clinica`),
  KEY `idx_usuarios_email` (`email`),
  CONSTRAINT `usuarios_sistema_ibfk_1` FOREIGN KEY (`id_clinica`) REFERENCES `clinicas` (`id_clinica`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios_sistema`
--

LOCK TABLES `usuarios_sistema` WRITE;
/*!40000 ALTER TABLE `usuarios_sistema` DISABLE KEYS */;
INSERT INTO `usuarios_sistema` VALUES (1,1,'Dr. Juan Pérez','doctor',NULL,'admin@dentalconnect.com','$2y$12$lskTNPVCfeGFA5h6AyAG..1hP1hgyhJRqeRx.VmtQ2BVoztQK1wNO',1,NULL,'2026-02-10 14:57:19','2026-02-10 14:57:19'),(2,1,'Paciente Demo','paciente',NULL,'paciente@demo.com','$2y$12$7gdKqxIfQWighDUqzMFJ5e0jNhVR2lBuVD7047r1qiixy9hrvRtJ2',1,NULL,'2026-02-10 14:57:19','2026-02-10 08:57:19'),(4,1,'Marco osorio','paciente',NULL,'mrcocrck2019@gmail.com','$2y$12$rgfu.SctqPQBhO8Fq9F/S.jMJPnjjhHJy4NOYJ3/chkOlx6dAIwiS',1,NULL,'2026-02-10 20:17:15','2026-02-10 20:17:15');
/*!40000 ALTER TABLE `usuarios_sistema` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-10  9:00:34
