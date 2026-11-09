mysqldump: [Warning] Using a password on the command line interface can be insecure.
Warning: A partial dump from a server that has GTIDs will by default include the GTIDs of all transactions, even those that changed suppressed parts of the database. If you don't want to restore GTIDs, pass --set-gtid-purged=OFF. To make a complete dump, pass --all-databases --triggers --routines --events. 
Warning: A dump from a server that has GTIDs enabled will by default include the GTIDs of all transactions, even those that were executed during its extraction and might not be represented in the dumped data. This might result in an inconsistent data dump. 
In order to ensure a consistent backup of the database, pass --single-transaction or --lock-all-tables or --source-data. 
-- MySQL dump 10.13  Distrib 9.6.0, for macos26.2 (arm64)
--
-- Host: turntable.proxy.rlwy.net    Database: railway
-- ------------------------------------------------------
-- Server version	9.7.2

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
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ '';

--
-- Table structure for table `admin_usuarios`
--

DROP TABLE IF EXISTS `admin_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` enum('superadmin','admin','editor') COLLATE utf8mb4_unicode_ci DEFAULT 'editor',
  `activo` tinyint(1) DEFAULT '1',
  `ultimo_acceso` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_usuarios`
--

LOCK TABLES `admin_usuarios` WRITE;
/*!40000 ALTER TABLE `admin_usuarios` DISABLE KEYS */;
INSERT INTO `admin_usuarios` VALUES (1,'Administrador CRISAMEX','admin@crisamex.com','$2y$10$FG2cHslN3puNUu61W/PSzewCl2Fyd2ta2f0fb88qcKWpOYFk2gudS','superadmin',1,'2026-06-01 19:51:08','2026-05-10 22:30:03');
/*!40000 ALTER TABLE `admin_usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `certificaciones`
--

DROP TABLE IF EXISTS `certificaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `certificaciones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `organismo` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `descripcion_corta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `orden` int DEFAULT '0',
  `activo` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `certificaciones`
--

LOCK TABLES `certificaciones` WRITE;
/*!40000 ALTER TABLE `certificaciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `certificaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellidos` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `empresa` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rfc` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plan_id` int DEFAULT NULL,
  `licencia_inicio` date DEFAULT NULL,
  `licencia_fin` date DEFAULT NULL,
  `licencia_status` enum('activa','suspendida','vencida','trial') COLLATE utf8mb4_unicode_ci DEFAULT 'trial',
  `email_verificado` tinyint(1) DEFAULT '0',
  `activo` tinyint(1) DEFAULT '1',
  `ultimo_acceso` timestamp NULL DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notas_admin` text COLLATE utf8mb4_unicode_ci,
  `registrado_por` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `plan_id` (`plan_id`),
  KEY `idx_registrado_por` (`registrado_por`),
  CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `planes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_clientes_admin` FOREIGN KEY (`registrado_por`) REFERENCES `admin_usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (1,'Carlos','Mendoza Demo','Empresa Demo S.A. de C.V.',NULL,'demo@empresa.com','55 1234 5678','Director de Operaciones','$2y$10$FG2cHslN3puNUu61W/PSzewCl2Fyd2ta2f0fb88qcKWpOYFk2gudS',NULL,'2024-01-01','2027-12-31','activa',1,1,NULL,NULL,NULL,NULL,'2026-05-10 22:30:05'),(2,'emmanuel','velasquez','Emaster. s.a de c.v',NULL,'zS23023048@estudiantes.uv.mx','4722030716','ingeniero','$2y$10$Tl2yporUDHwMrqh.kYAFWOfHYEshPJBtBqjE2dY549FTxnwViM41m',NULL,NULL,NULL,'trial',1,1,'2026-05-28 17:55:47',NULL,NULL,NULL,'2026-05-10 23:04:47'),(3,'Emilio','Muñoz','UV',NULL,'isc.emilio95@gmail.com','22657606','Docente','$2y$10$qZkFv9yuZ8fTMs.uq4dGaudfOVLu7/l5YwfM6kFz6CG.p1amMHPFq',NULL,NULL,NULL,'trial',1,1,NULL,NULL,NULL,NULL,'2026-05-19 19:37:20'),(4,'emmanuel','velasquez','Emaster. s.a de c.v',NULL,'admin@crisamex.com','4722030716','ingeniero','$2y$10$opvVl1uS7ioH2nCV0Nuz9O175PdUxADHSPmOH1949sXtIprLE2aQq',NULL,NULL,NULL,'trial',1,1,NULL,NULL,NULL,NULL,'2026-05-28 13:36:42'),(5,'Emmanuel','Velasquez Geron','Emastercito. s.a de c.v',NULL,'velasquezemmanuel87@gmail.com','4720230716','Ingenerio y licenciado','$2y$10$s7jRAbTiDwyGuZa.X6dOfup/1hF95DvKZl/wRJlAMT.X6MAuj.YMS',NULL,NULL,NULL,'trial',1,1,NULL,NULL,NULL,NULL,'2026-06-01 19:49:57');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacto_mensajes`
--

DROP TABLE IF EXISTS `contacto_mensajes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacto_mensajes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `empresa` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `servicio_interes` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `servicio_id` int DEFAULT NULL,
  `mensaje` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `leido` tinyint(1) DEFAULT '0',
  `respondido` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_servicio_id` (`servicio_id`),
  CONSTRAINT `fk_contacto_servicio` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacto_mensajes`
--

LOCK TABLES `contacto_mensajes` WRITE;
/*!40000 ALTER TABLE `contacto_mensajes` DISABLE KEYS */;
INSERT INTO `contacto_mensajes` VALUES (1,'leonardo vidal','chapolcito S.A DE C.V','chapol@gmail.com','2122658534','',NULL,'Hola Necesito informacion','201.138.16.49',1,0,'2026-05-26 05:08:20'),(2,'Marcos','marquitos.S.A DE C.V','marquitso123@gmail.com','1234567891','',NULL,'Hola tengo unda duda que quiero que me ayuden a resolver','201.162.232.195',1,0,'2026-06-01 19:45:40');
/*!40000 ALTER TABLE `contacto_mensajes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipo`
--

DROP TABLE IF EXISTS `equipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `puesto` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `orden` int DEFAULT '0',
  `activo` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipo`
--

LOCK TABLES `equipo` WRITE;
/*!40000 ALTER TABLE `equipo` DISABLE KEYS */;
INSERT INTO `equipo` VALUES (1,'Dr. Roberto Sánchez','Director General','Físico nuclear con más de 25 años de experiencia en seguridad radiológica. Doctor en Física por la UNAM y certificado por organismos internacionales en protección radiológica.',NULL,NULL,NULL,1,1,'2026-05-10 22:29:59'),(2,'Ing. María López','Directora Técnica','Ingeniera nuclear certificada con especialidad en protección radiológica industrial y médica. Auditora líder ISO 9001 con amplia experiencia en gestión de calidad.',NULL,NULL,NULL,2,1,'2026-05-10 22:29:59'),(3,'Lic. Carlos Mendoza','Supervisor Radiológico Senior','Supervisor radiológico con 15 años de experiencia en plantas industriales, hospitales y centros de investigación. Especialista en trámites regulatorios.',NULL,NULL,NULL,3,1,'2026-05-10 22:29:59'),(4,'Ing. Ana García','Especialista en Instrumentación','Experta en calibración y mantenimiento de equipos de detección de radiación. Certificada por fabricantes internacionales líderes del sector.',NULL,NULL,NULL,4,1,'2026-05-10 22:29:59');
/*!40000 ALTER TABLE `equipo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estadisticas`
--

DROP TABLE IF EXISTS `estadisticas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estadisticas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `etiqueta` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icono` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'fas fa-chart-bar',
  `orden` int DEFAULT '0',
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estadisticas`
--

LOCK TABLES `estadisticas` WRITE;
/*!40000 ALTER TABLE `estadisticas` DISABLE KEYS */;
INSERT INTO `estadisticas` VALUES (1,'+25','Años de Experiencia','fas fa-calendar-alt',1,1),(2,'+500','Clientes Atendidos','fas fa-users',2,1),(3,'+1000','Proyectos Completados','fas fa-project-diagram',3,1),(4,'5','Certificaciones Vigentes','fas fa-certificate',4,1);
/*!40000 ALTER TABLE `estadisticas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `planes`
--

DROP TABLE IF EXISTS `planes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `planes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `precio_mensual` decimal(10,2) DEFAULT '0.00',
  `precio_anual` decimal(10,2) DEFAULT '0.00',
  `color` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '#C8151B',
  `icono` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT 'fas fa-star',
  `features` longtext COLLATE utf8mb4_unicode_ci,
  `limite_usuarios` int DEFAULT '1',
  `limite_reportes` int DEFAULT '10',
  `soporte` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT 'Email',
  `destacado` tinyint(1) DEFAULT '0',
  `activo` tinyint(1) DEFAULT '1',
  `orden` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `planes`
--

LOCK TABLES `planes` WRITE;
/*!40000 ALTER TABLE `planes` DISABLE KEYS */;
INSERT INTO `planes` VALUES (1,'CalibraciÃ³n de Equipos','calibracion-equipos','CalibraciÃ³n anual de detectores Geiger-MÃ¼ller en laboratorio propio con fuentes de referencia certificadas.',3500.00,3500.00,'#555555','fas fa-tachometer-alt','[\"CalibraciÃ³n en laboratorio propio\",\"Detectores Geiger-MÃ¼ller (GM)\",\"Fuentes de referencia certificadas\",\"CalibraciÃ³n anual\",\"Certificado de calibraciÃ³n\",\"Reporte tÃ©cnico detallado\"]',1,10,'Email',0,1,1,'2026-05-12 05:08:39'),(2,'Prueba de Fuga (Frotis)','prueba-fuga','VerificaciÃ³n de integridad de fuentes radiactivas con anÃ¡lisis monocanal y certificado de no contaminaciÃ³n.',2500.00,2500.00,'#C8151B','fas fa-microscope','[\"Procedimiento con cotonete certificado\",\"AnÃ¡lisis en equipo monocanal\",\"DetecciÃ³n de Americio y otros isÃ³topos\",\"Certificado de prueba de fuga\",\"Dictamen tÃ©cnico\",\"Cumplimiento CNSNS\"]',1,10,'Email',1,1,2,'2026-05-12 05:08:39'),(3,'Mantenimiento a Contenedores','mantenimiento-contenedores','ConservaciÃ³n de sistemas de blindaje de plomo y acero que protegen al personal de radiaciÃ³n directa.',15000.00,15000.00,'#2563eb','fas fa-shield-alt','[\"Contenedores de plomo y acero\",\"PrevenciÃ³n de oxidaciÃ³n y corrosiÃ³n\",\"RevisiÃ³n de integridad estructural\",\"GarantÃ­a de blindaje efectivo\",\"Reporte de condiciones\",\"Mantenimiento preventivo y correctivo\"]',1,10,'Email',0,1,3,'2026-05-12 05:08:39'),(4,'VerificaciÃ³n de Portales y Rayos X','verificacion-portales','VerificaciÃ³n de portales de detecciÃ³n, sistemas de seguridad, industriales y equipos mÃ©dico-dentales.',12000.00,12000.00,'#7c3aed','fas fa-radiation','[\"Portales de detecciÃ³n fija\",\"Sistemas de banda y tÃºnel (aeropuertos/aduanas)\",\"RadiografÃ­a industrial y END\",\"Equipos mÃ©dicos y dentales\",\"Permiso SecretarÃ­a de EnergÃ­a\",\"Permiso CNS\"]',1,10,'Prioritario',0,1,4,'2026-05-12 05:08:39'),(5,'Levantamiento de Niveles','levantamiento-niveles','Mapeo tÃ©cnico de radiaciÃ³n en sus instalaciones con detectores portÃ¡tiles Geiger-MÃ¼ller.',0.00,0.00,'#d97706','fas fa-chart-area','[\"Detectores portÃ¡tiles GM propios\",\"MediciÃ³n en toda la instalaciÃ³n\",\"VerificaciÃ³n de blindajes\",\"DetecciÃ³n de fugas no reportadas\",\"Reporte de exposiciÃ³n laboral\",\"Cumplimiento lÃ­mites CNSNS\"]',1,10,'Email',0,1,5,'2026-05-12 05:08:39'),(6,'CapacitaciÃ³n y TrÃ¡mites CNSNS','capacitacion-tramites','TrÃ¡mites ante la CNSNS y capacitaciÃ³n tÃ©cnica para su personal. AutorizaciÃ³n SecretarÃ­a de EnergÃ­a.',13000.00,13000.00,'#16a34a','fas fa-file-alt','[\"TrÃ¡mites ante CNSNS\",\"Permisos de movimiento de fuentes\",\"CapacitaciÃ³n tÃ©cnica al personal\",\"AutorizaciÃ³n SecretarÃ­a de EnergÃ­a\",\"GestiÃ³n de licencias\",\"AsesorÃ­a legal radiolÃ³gica\"]',1,10,'Email',0,1,6,'2026-05-12 05:08:39');
/*!40000 ALTER TABLE `planes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `portal_documentos`
--

DROP TABLE IF EXISTS `portal_documentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `portal_documentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int NOT NULL,
  `titulo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `archivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` enum('reporte','certificado','factura','contrato','otro') COLLATE utf8mb4_unicode_ci DEFAULT 'reporte',
  `servicio_id` int DEFAULT NULL,
  `subido_por` enum('admin','cliente') COLLATE utf8mb4_unicode_ci DEFAULT 'admin',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `idx_doc_servicio` (`servicio_id`),
  CONSTRAINT `fk_pdocs_servicio` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `portal_documentos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `portal_documentos`
--

LOCK TABLES `portal_documentos` WRITE;
/*!40000 ALTER TABLE `portal_documentos` DISABLE KEYS */;
INSERT INTO `portal_documentos` VALUES (1,1,'Reporte de Auditoría Q1 2024','Reporte de auditoría radiológica del primer trimestre 2024. Resultados: APROBADO.',NULL,'reporte',NULL,'admin','2026-05-10 22:30:08'),(2,1,'Certificado ISO 9001 2024','Certificado vigente del Sistema de Gestión de Calidad ISO 9001.',NULL,'certificado',NULL,'admin','2026-05-10 22:30:08'),(3,1,'Programa de Protección Radiológica','Programa de protección radiológica actualizado y aprobado por CNSNS.',NULL,'contrato',NULL,'admin','2026-05-10 22:30:08'),(4,5,'mota','1234r55','doc_5_1780343602.docx','contrato',NULL,'admin','2026-06-01 19:53:24');
/*!40000 ALTER TABLE `portal_documentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `portal_mensajes`
--

DROP TABLE IF EXISTS `portal_mensajes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `portal_mensajes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int NOT NULL,
  `asunto` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mensaje` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `de` enum('cliente','admin') COLLATE utf8mb4_unicode_ci DEFAULT 'cliente',
  `admin_id` int DEFAULT NULL,
  `leido_admin` tinyint(1) DEFAULT '0',
  `leido_cliente` tinyint(1) DEFAULT '0',
  `adjunto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `idx_pm_admin` (`admin_id`),
  CONSTRAINT `fk_pmensajes_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin_usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `portal_mensajes_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `portal_mensajes`
--

LOCK TABLES `portal_mensajes` WRITE;
/*!40000 ALTER TABLE `portal_mensajes` DISABLE KEYS */;
INSERT INTO `portal_mensajes` VALUES (1,1,'Bienvenido al Portal CRISAMEX','Hola Carlos, bienvenido al portal de clientes CRISAMEX. Estamos a sus órdenes para cualquier consulta sobre su plan Profesional. Su licencia está activa hasta diciembre 2027. No dude en contactarnos.','admin',NULL,1,1,NULL,'2026-05-10 22:30:07'),(2,1,'¿Cuándo es mi próxima auditoría?','Buenos días, quisiera saber cuándo está programada mi próxima auditoría anual y qué documentos debo preparar.','cliente',NULL,1,1,NULL,'2026-05-10 22:30:07'),(3,1,'Mensaje de CRISAMEX','excelente pronto estaremos en contacto','admin',NULL,0,0,NULL,'2026-05-10 23:07:41'),(4,1,'Mensaje de CRISAMEX','excelente pronto estaremos en contacto','admin',NULL,0,0,NULL,'2026-05-10 23:07:43'),(5,1,'Mensaje de CRISAMEX','excelente pronto estaremos en contacto','admin',NULL,0,0,NULL,'2026-05-10 23:07:44'),(6,3,'Consulta del cliente','Hola','cliente',NULL,1,0,NULL,'2026-05-19 19:38:02'),(7,3,'Consulta del cliente','Hola','cliente',NULL,1,0,NULL,'2026-05-19 19:38:05'),(8,3,'Mensaje de CRISAMEX','hola que tal ing emilio','admin',NULL,0,0,NULL,'2026-05-19 19:38:55'),(9,3,'Mensaje de CRISAMEX','hola que tal ing emilio','admin',NULL,0,0,NULL,'2026-05-19 19:39:00'),(10,3,'Mensaje de CRISAMEX','hola que tal ing emilio','admin',NULL,0,0,NULL,'2026-05-19 19:39:08'),(11,2,'Consulta del cliente','hola hola','cliente',NULL,1,0,NULL,'2026-05-26 05:09:09'),(12,2,'Consulta del cliente','hola hola','cliente',NULL,1,0,NULL,'2026-05-26 05:09:13'),(13,2,'Mensaje de CRISAMEX','Hola emmanuel Velasquez, tiene alguna duda?','admin',NULL,0,1,NULL,'2026-05-26 05:11:41'),(14,2,'Consulta del cliente','😍','cliente',NULL,1,0,NULL,'2026-05-26 05:12:19'),(15,2,'Consulta del cliente','Excelente Trabajo Emmanuel','cliente',NULL,1,0,NULL,'2026-05-28 06:30:20'),(16,5,'Consulta del cliente','Hola admin','cliente',NULL,1,0,NULL,'2026-06-01 19:50:36'),(17,5,'Consulta del cliente','Hola admin','cliente',NULL,1,0,NULL,'2026-06-01 19:50:38'),(18,5,'Mensaje de CRISAMEX','hola','admin',NULL,0,1,NULL,'2026-06-01 19:52:09');
/*!40000 ALTER TABLE `portal_mensajes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `portal_notificaciones`
--

DROP TABLE IF EXISTS `portal_notificaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `portal_notificaciones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int NOT NULL,
  `titulo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mensaje` text COLLATE utf8mb4_unicode_ci,
  `tipo` enum('info','success','warning','danger') COLLATE utf8mb4_unicode_ci DEFAULT 'info',
  `servicio_id` int DEFAULT NULL,
  `leida` tinyint(1) DEFAULT '0',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `idx_pn_servicio` (`servicio_id`),
  CONSTRAINT `fk_pnotif_servicio` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `portal_notificaciones_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `portal_notificaciones`
--

LOCK TABLES `portal_notificaciones` WRITE;
/*!40000 ALTER TABLE `portal_notificaciones` DISABLE KEYS */;
INSERT INTO `portal_notificaciones` VALUES (1,1,'¡Bienvenido a CRISAMEX Portal!','Tu cuenta ha sido activada con el plan Profesional. Ya tienes acceso completo a todos los servicios.','success',NULL,1,NULL,'2026-05-10 22:30:09'),(2,1,'Nueva auditoría programada','Tu próxima auditoría radiológica está programada para el 15 de junio 2024.','info',NULL,0,NULL,'2026-05-10 22:30:09'),(3,1,'Nuevo documento disponible','Se ha subido tu Reporte de Auditoría Q1 2024. Ingresa a Documentos para descargarlo.','success',NULL,0,NULL,'2026-05-10 22:30:09'),(4,1,'Licencia activa hasta Dic 2027','Tu licencia está vigente. Recuerda renovarla antes del vencimiento.','info',NULL,1,NULL,'2026-05-10 22:30:09'),(5,2,'¡Bienvenido a CRISAMEX!','Tu cuenta ha sido creada. Un asesor se pondrá en contacto contigo en breve.','success',NULL,0,NULL,'2026-05-10 23:04:47'),(6,1,'Nuevo mensaje de CRISAMEX','Has recibido un nuevo mensaje del equipo CRISAMEX. Ingresa al portal para verlo.','info',NULL,0,NULL,'2026-05-10 23:07:42'),(7,1,'Nuevo mensaje de CRISAMEX','Has recibido un nuevo mensaje del equipo CRISAMEX. Ingresa al portal para verlo.','info',NULL,0,NULL,'2026-05-10 23:07:43'),(8,1,'Nuevo mensaje de CRISAMEX','Has recibido un nuevo mensaje del equipo CRISAMEX. Ingresa al portal para verlo.','info',NULL,0,NULL,'2026-05-10 23:07:44'),(9,3,'¡Bienvenido a CRISAMEX!','Tu cuenta ha sido creada. Un asesor se pondrá en contacto contigo en breve.','success',NULL,0,NULL,'2026-05-19 19:37:20'),(10,3,'Nuevo mensaje de CRISAMEX','Has recibido un nuevo mensaje del equipo CRISAMEX. Ingresa al portal para verlo.','info',NULL,0,NULL,'2026-05-19 19:38:56'),(11,3,'Nuevo mensaje de CRISAMEX','Has recibido un nuevo mensaje del equipo CRISAMEX. Ingresa al portal para verlo.','info',NULL,0,NULL,'2026-05-19 19:39:00'),(12,3,'Nuevo mensaje de CRISAMEX','Has recibido un nuevo mensaje del equipo CRISAMEX. Ingresa al portal para verlo.','info',NULL,0,NULL,'2026-05-19 19:39:09'),(13,2,'Nuevo mensaje de CRISAMEX','Has recibido un nuevo mensaje del equipo CRISAMEX. Ingresa al portal para verlo.','info',NULL,0,NULL,'2026-05-26 05:11:41'),(14,4,'¡Bienvenido a CRISAMEX!','Tu cuenta ha sido creada. Un asesor se pondrá en contacto contigo en breve.','success',NULL,0,NULL,'2026-05-28 13:36:42'),(15,5,'¡Bienvenido a CRISAMEX!','Tu cuenta ha sido creada. Un asesor se pondrá en contacto contigo en breve.','success',NULL,0,NULL,'2026-06-01 19:49:57'),(16,5,'Nuevo mensaje de CRISAMEX','Has recibido un nuevo mensaje del equipo CRISAMEX. Ingresa al portal para verlo.','info',NULL,0,NULL,'2026-06-01 19:52:09'),(17,5,'Nuevo documento disponible','CRISAMEX ha subido un nuevo documento para ti: mota','success',NULL,0,NULL,'2026-06-01 19:53:24');
/*!40000 ALTER TABLE `portal_notificaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicios`
--

DROP TABLE IF EXISTS `servicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `servicios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion_corta` text COLLATE utf8mb4_unicode_ci,
  `descripcion_larga` longtext COLLATE utf8mb4_unicode_ci,
  `icono` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'fas fa-radiation',
  `imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `orden` int DEFAULT '0',
  `activo` tinyint(1) DEFAULT '1',
  `slug` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicios`
--

LOCK TABLES `servicios` WRITE;
/*!40000 ALTER TABLE `servicios` DISABLE KEYS */;
INSERT INTO `servicios` VALUES (13,'Calibración de Equipos','Garantizamos que sus instrumentos de medición proporcionen datos exactos y confiables para su seguridad radiológica.','Nos especializamos principalmente en detectores Geiger-Müller (GM). Realizamos la calibración en laboratorios propios utilizando fuentes de referencia para ajustar cada instrumento con total precisión. Calibración anual para equipos portátiles y manuales.','fas fa-tachometer-alt',NULL,1,1,'calibracion-equipos','2026-05-26 21:59:11','2026-05-26 21:59:11'),(14,'Prueba de Fuga (Frotis)','Procedimientos preventivos para confirmar la integridad de sus fuentes radiactivas y descartar cualquier tipo de contaminación.','Utilizamos un cotonete para frotar la superficie de la fuente. Analizamos este frotis en nuestro equipo monocanal buscando rastros de Americio. Si los niveles son óptimos, emitimos un Certificado de prueba de fuga.','fas fa-microscope',NULL,2,1,'prueba-de-fuga','2026-05-26 21:59:11','2026-05-26 21:59:11'),(15,'Mantenimiento a Contenedores de Fuentes','Servicios de conservación para los sistemas de blindaje que protegen al personal de la radiación directa.','Trabajamos sobre contenedores de plomo recubiertos con acero. Realizamos mantenimientos para evitar el deterioro estructural y asegurar que el contenedor funcione como barrera impenetrable.','fas fa-shield-alt',NULL,3,1,'mantenimiento-contenedores','2026-05-26 21:59:11','2026-05-26 21:59:11'),(16,'Verificación de Portales y Equipos de Rayos X','Nos aseguramos de que sus sistemas de detección y diagnóstico mantengan su precisión operativa.','Trabajamos con detectores de gran tamaño para monitoreo de personal, vehículos y carga. Cubrimos sistemas en aeropuertos, aduanas, radiografía industrial y equipos médicos dentales bajo permisos de la Secretaría de Energía y CNS.','fas fa-radiation',NULL,4,1,'verificacion-portales-rayos-x','2026-05-26 21:59:11','2026-05-26 21:59:11'),(17,'Levantamiento de Niveles','Estudios técnicos de campo para mapear la presencia de radiación en áreas específicas de su instalación.','Utilizamos detectores portátiles Geiger-Müller. Nuestros técnicos recorren sus instalaciones midiendo la radiación ambiental para confirmar que el blindaje de plomo y acero sea totalmente efectivo.','fas fa-chart-area',NULL,5,1,'levantamiento-de-niveles','2026-05-26 21:59:11','2026-05-26 21:59:11'),(18,'Capacitación y Trámites ante CNSNS','Gestión administrativa y formación técnica ante las autoridades reguladoras. Autorización de la Secretaría de Energía.','Realizamos todos los trámites ante la Comisión Nacional de Seguridad Nuclear y Salvaguardias (CNSNS). Impartimos capacitación técnica para que su personal opere materiales y equipos de manera segura.','fas fa-file-alt',NULL,6,1,'capacitacion-tramites-cnsns','2026-05-26 21:59:11','2026-05-26 21:59:11');
/*!40000 ALTER TABLE `servicios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_config`
--

DROP TABLE IF EXISTS `site_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `site_config` (
  `id` int NOT NULL AUTO_INCREMENT,
  `clave` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` text COLLATE utf8mb4_unicode_ci,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clave` (`clave`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_config`
--

LOCK TABLES `site_config` WRITE;
/*!40000 ALTER TABLE `site_config` DISABLE KEYS */;
INSERT INTO `site_config` VALUES (1,'empresa_nombre','CRISAMEX','Nombre','2026-05-10 22:29:55'),(2,'empresa_razon_social','Control de Radiaciones e Ingeniería, S.A. de C.V.','Razón social','2026-05-10 22:29:55'),(3,'empresa_slogan','La Seguridad Radiológica en su empresa es nuestra misión','Slogan','2026-05-10 22:29:55'),(4,'empresa_descripcion','Somos un equipo de expertos mexicanos certificados con 25 años de experiencia en ofrecer servicios integrales en Seguridad y Protección Radiológica, dando soporte en los usos pacíficos de la energía nuclear y radiaciones ionizantes a todo México y al extranjero.','Descripción','2026-05-10 22:29:55'),(5,'empresa_anos_experiencia','25','Años de experiencia','2026-05-10 22:29:55'),(6,'empresa_telefono','01 55 5650 8420','Teléfono','2026-05-10 22:29:55'),(7,'empresa_email','contacto@crisamex.com','Email','2026-05-10 22:29:55'),(8,'empresa_whatsapp','525556508420','WhatsApp','2026-05-10 22:29:55'),(9,'empresa_direccion','Ciudad de México, México','Dirección','2026-05-10 22:29:55'),(10,'empresa_horario','Lun–Vie: 9:00–18:00 | Sáb: 9:00–13:00','Horario','2026-05-10 22:29:55'),(11,'empresa_facebook','https://www.facebook.com/crisamexx','Facebook','2026-05-10 22:29:55'),(12,'empresa_linkedin','https://www.linkedin.com/company/crisamex','LinkedIn','2026-05-10 22:29:55'),(13,'meta_title_home','CRISAMEX — Control de Radiaciones e Ingeniería S.A. de C.V.','Meta title','2026-05-10 22:29:55'),(14,'meta_description_home','Expertos en Seguridad y Protección Radiológica con 25 años de experiencia en México. CNSNS, STPS, COFEPRIS, SCT, ISO 9001 certificados.','Meta description','2026-05-10 22:29:55'),(15,'google_analytics','','Google Analytics ID','2026-05-10 22:29:55');
/*!40000 ALTER TABLE `site_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `valores`
--

DROP TABLE IF EXISTS `valores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `valores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `icono` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'fas fa-star',
  `orden` int DEFAULT '0',
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `valores`
--

LOCK TABLES `valores` WRITE;
/*!40000 ALTER TABLE `valores` DISABLE KEYS */;
INSERT INTO `valores` VALUES (1,'Seguridad','La seguridad radiológica es nuestra prioridad absoluta en cada servicio que prestamos a nuestros clientes.','fas fa-shield-alt',1,1),(2,'Calidad','Nos comprometemos con la más alta calidad en cada proceso, respaldados por nuestra certificación ISO 9001:2008.','fas fa-award',2,1),(3,'Experiencia','25 años de trayectoria continua avalan nuestro conocimiento y profesionalismo en el sector radiológico mexicano.','fas fa-history',3,1),(4,'Confiabilidad','Somos reconocidos por los organismos reguladores como una empresa confiable, responsable y comprometida.','fas fa-handshake',4,1),(5,'Innovación','Aplicamos las mejores prácticas y tecnologías actuales para ofrecer servicios de vanguardia a nuestros clientes.','fas fa-lightbulb',5,1),(6,'Compromiso','Nos comprometemos con el cumplimiento normativo y la operación completamente segura de su empresa.','fas fa-check-circle',6,1);
/*!40000 ALTER TABLE `valores` ENABLE KEYS */;
UNLOCK TABLES;
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-09 11:41:04
