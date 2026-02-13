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
  `url_archivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` enum('imagen','pdf','rayos_x') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_archivo`),
  KEY `id_paciente` (`id_paciente`),
  KEY `id_cita` (`id_cita`),
  CONSTRAINT `archivos_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`),
  CONSTRAINT `archivos_ibfk_2` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`)
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
  `id_usuario` int DEFAULT NULL,
  `accion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tabla_afectada` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_registro` bigint DEFAULT NULL,
  `detalles` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,NULL,'update','citas',7,'{\"new_estado\": \"pendiente\", \"old_estado\": \"pendiente\"}','2026-02-11 00:28:22'),(2,NULL,'update','citas',9,'{\"new_estado\": \"pendiente\", \"old_estado\": \"pendiente\"}','2026-02-11 23:31:22'),(3,NULL,'update','citas',10,'{\"new_estado\": \"pendiente\", \"old_estado\": \"pendiente\"}','2026-02-11 23:42:21'),(4,NULL,'update','citas',8,'{\"new_estado\": \"pendiente\", \"old_estado\": \"pendiente\"}','2026-02-11 23:45:06'),(5,NULL,'update','citas',8,'{\"new_estado\": \"pendiente\", \"old_estado\": \"pendiente\"}','2026-02-11 23:45:23'),(6,NULL,'update','citas',10,'{\"new_estado\": \"pendiente\", \"old_estado\": \"pendiente\"}','2026-02-11 23:47:41');
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
  `id_cita` bigint DEFAULT NULL,
  `estado_envio` enum('enviado','fallido','pendiente') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_envio` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_notificacion`),
  KEY `id_cita` (`id_cita`),
  CONSTRAINT `bitacora_notificaciones_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`)
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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
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
  `precio_base` decimal(10,2) DEFAULT NULL,
  `categoria` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_servicio`),
  KEY `id_clinica` (`id_clinica`),
  CONSTRAINT `catalogo_servicios_ibfk_1` FOREIGN KEY (`id_clinica`) REFERENCES `clinicas` (`id_clinica`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogo_servicios`
--

LOCK TABLES `catalogo_servicios` WRITE;
/*!40000 ALTER TABLE `catalogo_servicios` DISABLE KEYS */;
INSERT INTO `catalogo_servicios` VALUES (1,1,'Brackets',4500.00,'Ortodoncia','2026-02-11 06:01:38','2026-02-11 06:29:26'),(2,1,'Endodoncia',4800.00,'Endodoncia','2026-02-11 06:30:08','2026-02-11 06:30:08'),(3,1,'Consulta general',400.00,'General','2026-02-12 05:27:56','2026-02-12 05:27:56');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogo_tipo_sangre`
--

LOCK TABLES `catalogo_tipo_sangre` WRITE;
/*!40000 ALTER TABLE `catalogo_tipo_sangre` DISABLE KEYS */;
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
  `id_clinica` int DEFAULT NULL,
  `id_paciente` int DEFAULT NULL,
  `id_doctor` int DEFAULT NULL,
  `id_servicio` int DEFAULT NULL,
  `fecha_hora_inicio` datetime DEFAULT NULL,
  `fecha_hora_fin` datetime DEFAULT NULL,
  `estado_cita` enum('pendiente','confirmada','cancelada','completada') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `motivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `citas`
--

LOCK TABLES `citas` WRITE;
/*!40000 ALTER TABLE `citas` DISABLE KEYS */;
INSERT INTO `citas` VALUES (3,1,4,1,NULL,'2026-02-10 17:52:00','2026-02-10 18:52:00','pendiente','Consulta General',0.00,'2026-02-11 05:51:00','2026-02-11 05:51:00'),(4,1,3,1,NULL,'2026-02-10 19:51:00','2026-02-10 20:51:00','pendiente','Consulta General',0.00,'2026-02-11 05:51:27','2026-02-11 05:51:27'),(5,1,5,1,NULL,'2026-02-21 17:58:00','2026-02-21 18:58:00','pendiente','Consulta General',0.00,'2026-02-11 05:58:45','2026-02-11 05:58:45'),(6,1,4,1,NULL,'2026-02-13 19:01:00','2026-02-13 20:01:00','pendiente','Ortodoncia',0.00,'2026-02-11 06:02:14','2026-02-11 06:02:14'),(7,1,6,1,NULL,'2026-02-16 17:00:00','2026-02-13 19:26:00','pendiente','Ortodoncia',0.00,'2026-02-11 06:27:18','2026-02-11 06:28:22'),(8,1,6,1,NULL,'2026-02-11 23:45:23','2026-02-13 19:26:00','pendiente','Ortodoncia',0.00,'2026-02-11 06:27:20','2026-02-12 05:45:23'),(9,1,5,1,3,'2026-02-11 23:31:22','2026-02-27 18:30:00','pendiente','Consulta general',400.00,'2026-02-12 05:28:29','2026-02-12 05:31:22'),(10,1,3,1,1,'2026-02-11 23:47:41','2026-02-20 18:40:00','pendiente','Brackets',4500.00,'2026-02-12 05:41:21','2026-02-12 05:47:41');
/*!40000 ALTER TABLE `citas` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trg_citas_update` BEFORE UPDATE ON `citas` FOR EACH ROW BEGIN
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
  UNIQUE KEY `rfc_clinica` (`rfc_clinica`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clinicas`
--

LOCK TABLES `clinicas` WRITE;
/*!40000 ALTER TABLE `clinicas` DISABLE KEYS */;
INSERT INTO `clinicas` VALUES (1,'Clínica Principal','XAXX010101000',0.00,'555-0000','Ciudad Base','Estado Base','00000','2026-02-10 17:54:24','2026-02-10 17:54:24');
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
  `tiempo_anticipacion` int NOT NULL,
  `unidad_tiempo` enum('dias','horas','minutos') COLLATE utf8mb4_unicode_ci NOT NULL,
  `plantilla_mensaje` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_regla`),
  KEY `id_clinica` (`id_clinica`),
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
  CONSTRAINT `doctores_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios_sistema` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctores`
--

LOCK TABLES `doctores` WRITE;
/*!40000 ALTER TABLE `doctores` DISABLE KEYS */;
INSERT INTO `doctores` VALUES (1,7,'CEDULA-12345','Lunes a Viernes 9:00 - 18:00','2026-02-10 23:47:48','2026-02-10 23:47:48');
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
  `id_servicio` int DEFAULT NULL,
  `id_paciente` int DEFAULT NULL,
  `fecha_evolucion` datetime DEFAULT NULL,
  `descripcion_avance` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subjetivo_soap` text COLLATE utf8mb4_unicode_ci,
  `objetivo_soap` text COLLATE utf8mb4_unicode_ci,
  `plan_tratamiento` text COLLATE utf8mb4_unicode_ci,
  `estado_paciente` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_evolucion`),
  KEY `id_servicio` (`id_servicio`),
  KEY `id_paciente` (`id_paciente`),
  CONSTRAINT `evolucion_tratamiento_ibfk_1` FOREIGN KEY (`id_servicio`) REFERENCES `catalogo_servicios` (`id_servicio`),
  CONSTRAINT `evolucion_tratamiento_ibfk_2` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`)
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
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horarios_bloqueados`
--

DROP TABLE IF EXISTS `horarios_bloqueados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `horarios_bloqueados` (
  `id_bloqueo` int NOT NULL AUTO_INCREMENT,
  `id_doctor` int DEFAULT NULL,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `motivo` enum('vacaciones','enfermedad','otro') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estatus_horario` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_bloqueo`),
  KEY `id_doctor` (`id_doctor`),
  CONSTRAINT `horarios_bloqueados_ibfk_1` FOREIGN KEY (`id_doctor`) REFERENCES `doctores` (`id_doctor`)
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
  `id_clinica` int DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `metodo` enum('efectivo','tarjeta','transferencia','otro') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_ingreso` datetime DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_ingreso`),
  KEY `id_cita` (`id_cita`),
  KEY `id_clinica` (`id_clinica`),
  CONSTRAINT `ingresos_caja_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`),
  CONSTRAINT `ingresos_caja_ibfk_2` FOREIGN KEY (`id_clinica`) REFERENCES `clinicas` (`id_clinica`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingresos_caja`
--

LOCK TABLES `ingresos_caja` WRITE;
/*!40000 ALTER TABLE `ingresos_caja` DISABLE KEYS */;
INSERT INTO `ingresos_caja` VALUES (1,9,1,400.00,'efectivo','Abono desde Dashboard','2026-02-11 23:31:22','2026-02-12 05:31:22','2026-02-12 05:31:22'),(2,8,1,500.00,'efectivo','Abono desde Dashboard','2026-02-11 23:45:23','2026-02-12 05:45:23','2026-02-12 05:45:23'),(3,10,1,500.00,'efectivo','Abono desde Dashboard','2026-02-11 23:47:41','2026-02-12 05:47:41','2026-02-12 05:47:41');
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
  `stock` int DEFAULT '0',
  `precio` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_item`),
  KEY `id_clinica` (`id_clinica`),
  CONSTRAINT `inventario_ibfk_1` FOREIGN KEY (`id_clinica`) REFERENCES `clinicas` (`id_clinica`)
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
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificaciones`
--

DROP TABLE IF EXISTS `notificaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificaciones` (
  `id_notificacion` bigint NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `tipo` enum('recordatorio','confirmacion','cancelacion','push') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mensaje` text COLLATE utf8mb4_unicode_ci,
  `device_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('pendiente','enviado','leido') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_notificacion`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios_sistema` (`id_usuario`)
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
  `id_paciente` int DEFAULT NULL,
  `id_cita` bigint DEFAULT NULL,
  `numero_diente` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cara_diente` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado_diente` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_odontograma`),
  KEY `id_paciente` (`id_paciente`),
  KEY `id_cita` (`id_cita`),
  CONSTRAINT `odontograma_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`),
  CONSTRAINT `odontograma_ibfk_2` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`)
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
  `id_paciente` int DEFAULT NULL,
  `peso_kg` decimal(4,2) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_paciente_peso`),
  KEY `id_paciente` (`id_paciente`),
  CONSTRAINT `paciente_peso_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`)
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
  `id_paciente` int DEFAULT NULL,
  `id_tipo_sangre` int DEFAULT NULL,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_paciente_tipo_sangre`),
  KEY `id_paciente` (`id_paciente`),
  KEY `id_tipo_sangre` (`id_tipo_sangre`),
  CONSTRAINT `paciente_tipo_sangre_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`),
  CONSTRAINT `paciente_tipo_sangre_ibfk_2` FOREIGN KEY (`id_tipo_sangre`) REFERENCES `catalogo_tipo_sangre` (`id_tipo_sangre`)
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
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellido_paterno` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellido_materno` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alergias_criticas` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `sexo` enum('M','F','O') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correo_electronico` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_sangre` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `ocupacion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enfermedades_cronicas` text COLLATE utf8mb4_unicode_ci,
  `alergias` text COLLATE utf8mb4_unicode_ci,
  `id_contacto_emergencia` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_paciente`),
  UNIQUE KEY `id_usuario` (`id_usuario`),
  KEY `id_contacto_emergencia` (`id_contacto_emergencia`),
  CONSTRAINT `pacientes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios_sistema` (`id_usuario`),
  CONSTRAINT `pacientes_ibfk_2` FOREIGN KEY (`id_contacto_emergencia`) REFERENCES `contacto_emergencia` (`id_contacto_emergencia`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pacientes`
--

LOCK TABLES `pacientes` WRITE;
/*!40000 ALTER TABLE `pacientes` DISABLE KEYS */;
INSERT INTO `pacientes` VALUES (3,5,'marco antonio','osorio','hernandez','2381516645',NULL,'2002-01-23','M','mrcocrck2019@gmail.com','A+',98.00,'Estudiante','Ninguna',NULL,NULL,'2026-02-11 00:11:23','2026-02-11 00:11:23',1),(4,6,'AOSDNANDOLKSA','KJSDFJSNDOAS','OSDNFOISD','1234567890',NULL,'2000-02-16','M','OSDFOASND@oasdoaind.com','B+',98.00,'Nini','Ninguna',NULL,NULL,'2026-02-11 00:49:03','2026-02-11 00:49:03',1),(5,9,'maria','apsdnoas','jjncoasno','234918829293',NULL,'2002-01-24','M','oasdnoiasnd@gmail.com','O+',98.00,'desmayo123','ninguna',NULL,NULL,'2026-02-11 05:58:12','2026-02-11 05:58:12',1),(6,10,'jgckjgfugvi','ugtfkjnlb','JKYFDKBJLBLJ','4334776465674',NULL,'2002-01-17','M','JGDJVKVCI@gmail.com','O+',98.00,'estudiante','Ninguna',NULL,NULL,'2026-02-11 06:25:45','2026-02-11 06:25:45',1);
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
  `id_paciente` int DEFAULT NULL,
  `id_alergia` int DEFAULT NULL,
  PRIMARY KEY (`id_registro`),
  UNIQUE KEY `uk_pa` (`id_paciente`,`id_alergia`),
  KEY `id_alergia` (`id_alergia`),
  CONSTRAINT `pacientes_alergias_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`),
  CONSTRAINT `pacientes_alergias_ibfk_2` FOREIGN KEY (`id_alergia`) REFERENCES `catalogo_alergias` (`id_alergia`)
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
  `id_paciente` int DEFAULT NULL,
  `id_enfermedad_cronica` int DEFAULT NULL,
  PRIMARY KEY (`id_registro`),
  UNIQUE KEY `uk_pec` (`id_paciente`,`id_enfermedad_cronica`),
  KEY `id_enfermedad_cronica` (`id_enfermedad_cronica`),
  CONSTRAINT `pacientes_enfermedades_cronicas_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`),
  CONSTRAINT `pacientes_enfermedades_cronicas_ibfk_2` FOREIGN KEY (`id_enfermedad_cronica`) REFERENCES `catalogo_enfermedades_cronicas` (`id_enfermedad_cronica`)
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
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
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
  UNIQUE KEY `uk_permiso` (`rol`,`accion`,`recurso`)
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
  `id_cita` bigint DEFAULT NULL,
  `id_paciente` int DEFAULT NULL,
  `calificacion` int DEFAULT NULL,
  `comentario` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id_review`),
  KEY `id_cita` (`id_cita`),
  KEY `id_paciente` (`id_paciente`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`),
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`)
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
  `id_cita` bigint DEFAULT NULL,
  `postratamiento` enum('si','no') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_servicio` int DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id_seguimiento`),
  KEY `id_cita` (`id_cita`),
  KEY `id_servicio` (`id_servicio`),
  CONSTRAINT `seguimiento_clinico_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`),
  CONSTRAINT `seguimiento_clinico_ibfk_2` FOREIGN KEY (`id_servicio`) REFERENCES `catalogo_servicios` (`id_servicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
INSERT INTO `sessions` VALUES ('cD85FpEy1tjLWUU1fDIYdchsryqSRmix6aXfVCpC',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMkQ2cFdFY0ZNbFlDaTJTUjdkVjVYM3psUlR5QkxmM1VWWklTZzJCQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9kZW50YWxjb25uZWN0LnRlc3QvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjM6InVybCI7YToxOntzOjg6ImludGVuZGVkIjtzOjM1OiJodHRwOi8vZGVudGFsY29ubmVjdC50ZXN0L2Rhc2hib2FyZCI7fX0=',1770871775),('J4GqSE8ioav6Va9QLjZkuXZupdPIZkQ1LGB4RS36',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoic0FObUJ2d2hKb3dna3llSndwNnNoYzhHaHdzaVhpakxYR1Y5MENaeSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9kZW50YWxjb25uZWN0LnRlc3QvcmVzZXQtdGVtcCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNToiaHR0cDovL2RlbnRhbGNvbm5lY3QudGVzdCI7fX0=',1770859605);
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
  `id_usuario` int DEFAULT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_expiracion` datetime DEFAULT NULL,
  `estado` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_token`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios_sistema` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tokens`
--

LOCK TABLES `tokens` WRITE;
/*!40000 ALTER TABLE `tokens` DISABLE KEYS */;
INSERT INTO `tokens` VALUES (1,5,'PAC-8PNJIK','acceso_app','2026-02-10 18:11:23','2027-02-10 18:11:23','activo'),(2,6,'PAC-UVT5DQ','acceso_app','2026-02-10 18:49:03','2027-02-10 18:49:03','activo'),(3,9,'PAC-JEYXLO','acceso_app','2026-02-10 23:58:12','2027-02-10 23:58:12','activo'),(4,10,'PAC-Q6CVXD','acceso_app','2026-02-11 00:25:45','2027-02-11 00:25:45','activo');
/*!40000 ALTER TABLE `tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
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
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `ultimo_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email` (`email`),
  KEY `id_clinica` (`id_clinica`),
  CONSTRAINT `usuarios_sistema_ibfk_1` FOREIGN KEY (`id_clinica`) REFERENCES `clinicas` (`id_clinica`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios_sistema`
--

LOCK TABLES `usuarios_sistema` WRITE;
/*!40000 ALTER TABLE `usuarios_sistema` DISABLE KEYS */;
INSERT INTO `usuarios_sistema` VALUES (5,1,'marco antonio osorio','paciente',NULL,'mrcocrck2019@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',1,NULL,'2026-02-11 00:11:23','2026-02-12 01:06:39'),(6,1,'AOSDNANDOLKSA KJSDFJSNDOAS','paciente',NULL,'OSDFOASND@oasdoaind.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',1,NULL,'2026-02-11 00:49:03','2026-02-12 01:06:39'),(7,1,'Dr. Juan Pérez','doctor',NULL,'doctor_default@test.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',1,NULL,'2026-02-10 23:47:48','2026-02-10 23:47:48'),(9,1,'maria apsdnoas','paciente',NULL,'oasdnoiasnd@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',1,NULL,'2026-02-11 05:58:12','2026-02-12 01:06:39'),(10,1,'jgckjgfugvi ugtfkjnlb','paciente',NULL,'JGDJVKVCI@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',1,NULL,'2026-02-11 06:25:45','2026-02-12 01:06:39');
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

-- Dump completed on 2026-02-11 22:50:52
