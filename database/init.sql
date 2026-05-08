-- ============================================
-- CRISAMEX — Base de Datos Completa v10
-- Control de Radiaciones e Ingeniería S.A. de C.V.
-- ============================================
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ── SITE CONFIG ─────────────────────────────
DROP TABLE IF EXISTS `site_config`;
CREATE TABLE `site_config` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `clave` VARCHAR(100) NOT NULL UNIQUE,
  `valor` TEXT,
  `descripcion` VARCHAR(255),
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `site_config` (`clave`,`valor`,`descripcion`) VALUES
('empresa_nombre','CRISAMEX','Nombre'),
('empresa_razon_social','Control de Radiaciones e Ingeniería, S.A. de C.V.','Razón social'),
('empresa_slogan','La Seguridad Radiológica en su empresa es nuestra misión','Slogan'),
('empresa_descripcion','Somos un equipo de expertos mexicanos certificados con 25 años de experiencia en ofrecer servicios integrales en Seguridad y Protección Radiológica, dando soporte en los usos pacíficos de la energía nuclear y radiaciones ionizantes a todo México y al extranjero.','Descripción'),
('empresa_anos_experiencia','25','Años de experiencia'),
('empresa_telefono','01 55 5650 8420','Teléfono'),
('empresa_email','contacto@crisamex.com','Email'),
('empresa_whatsapp','525556508420','WhatsApp'),
('empresa_direccion','Ciudad de México, México','Dirección'),
('empresa_horario','Lun–Vie: 9:00–18:00 | Sáb: 9:00–13:00','Horario'),
('empresa_facebook','https://www.facebook.com/crisamexx','Facebook'),
('empresa_linkedin','https://www.linkedin.com/company/crisamex','LinkedIn'),
('meta_title_home','CRISAMEX — Control de Radiaciones e Ingeniería S.A. de C.V.','Meta title'),
('meta_description_home','Expertos en Seguridad y Protección Radiológica con 25 años de experiencia en México. CNSNS, STPS, COFEPRIS, SCT, ISO 9001 certificados.','Meta description'),
('google_analytics','','Google Analytics ID');

-- ── SERVICIOS ───────────────────────────────
DROP TABLE IF EXISTS `servicios`;
CREATE TABLE `servicios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `titulo` VARCHAR(150) NOT NULL,
  `descripcion_corta` TEXT,
  `descripcion_larga` LONGTEXT,
  `icono` VARCHAR(100) DEFAULT 'fas fa-radiation',
  `imagen` VARCHAR(255),
  `orden` INT DEFAULT 0,
  `activo` TINYINT(1) DEFAULT 1,
  `slug` VARCHAR(150) UNIQUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `servicios` (`titulo`,`descripcion_corta`,`descripcion_larga`,`icono`,`orden`,`slug`) VALUES
('Asesoría en Seguridad Radiológica',
 'Consultoría especializada para el cumplimiento normativo en protección radiológica ante CNSNS, STPS y COFEPRIS.',
 'Ofrecemos asesoría integral en seguridad radiológica para empresas e instituciones que utilizan fuentes de radiación ionizante. Nuestros expertos certificados guían a su organización en el cumplimiento de la normatividad vigente ante CNSNS, STPS y COFEPRIS. Incluye elaboración de programas de protección radiológica, revisión de instalaciones, evaluación de riesgos y capacitación del personal responsable.',
 'fas fa-shield-alt',1,'asesoria-seguridad-radiologica'),

('Trámites ante Autoridades',
 'Gestión ágil de licencias, permisos y autorizaciones ante CNSNS, STPS, COFEPRIS y SCT.',
 'Gestionamos todos los trámites necesarios ante las autoridades reguladoras en materia de protección radiológica. Incluye obtención y renovación de licencias de operación, registro de fuentes radiactivas, autorizaciones de importación y exportación, y todo el proceso requerido para operar legalmente en México con material radiactivo o instalaciones nucleares.',
 'fas fa-file-alt',2,'tramites-autoridades'),

('Instrumentación Nuclear',
 'Venta, renta, calibración y mantenimiento de equipos certificados de detección y medición de radiación.',
 'Proveemos equipos de detección y medición de radiación ionizante de las mejores marcas del mercado internacional. Ofrecemos servicios de calibración trazable, mantenimiento preventivo y correctivo, así como asesoría para la selección del instrumento adecuado según su aplicación específica. Contamos con laboratorio propio de calibración reconocido por las autoridades.',
 'fas fa-atom',3,'instrumentacion-nuclear'),

('Servicios en Sitio',
 'Auditorías radiológicas, mediciones en campo y evaluaciones de dosis en las instalaciones del cliente.',
 'Realizamos visitas técnicas a las instalaciones del cliente para llevar a cabo auditorías de seguridad radiológica, mediciones de campos de radiación, evaluación de áreas de trabajo, determinación de zonas controladas y supervisadas, y elaboración de reportes técnicos para presentar ante las autoridades reguladoras correspondientes.',
 'fas fa-tools',4,'servicios-en-sitio'),

('Transporte de Material Radiactivo',
 'Transporte seguro y certificado de fuentes radiactivas conforme a normativa SCT e IAEA.',
 'Contamos con autorización de la Secretaría de Comunicaciones y Transportes (SCT) para el transporte de material radiactivo en sus diferentes categorías. Nuestras unidades especializadas y personal capacitado garantizan el movimiento seguro de fuentes radiactivas cumpliendo con toda la normatividad nacional e internacional aplicable, incluyendo IAEA.',
 'fas fa-truck',5,'transporte-material-radiactivo'),

('Capacitación Radiológica',
 'Cursos y talleres especializados para personal técnico, supervisores radiológicos y trabajadores expuestos.',
 'Impartimos cursos de capacitación en protección radiológica dirigidos a trabajadores ocupacionalmente expuestos, supervisores radiológicos y personal de seguridad. Nuestros programas cumplen con los requisitos de las autoridades reguladoras y pueden realizarse en nuestras instalaciones o en las del cliente, con modalidades presencial y en línea.',
 'fas fa-graduation-cap',6,'capacitacion-seguridad-radiologica');

-- ── CERTIFICACIONES ─────────────────────────
DROP TABLE IF EXISTS `certificaciones`;
CREATE TABLE `certificaciones` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(200) NOT NULL,
  `organismo` VARCHAR(200),
  `descripcion` TEXT,
  `descripcion_corta` VARCHAR(255),
  `imagen` VARCHAR(255),
  `orden` INT DEFAULT 0,
  `activo` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `certificaciones` (`nombre`,`organismo`,`descripcion`,`descripcion_corta`,`orden`) VALUES
('ISO 9001:2008','Bureau Veritas / Gestión de Calidad',
 'Certificación en Sistema de Gestión de Calidad que avala la excelencia de nuestros procesos y servicios en seguridad radiológica.',
 'Sistema de Gestión de Calidad certificado internacionalmente',1),

('CNSNS','Comisión Nacional de Seguridad Nuclear y Salvaguardias',
 'Autorización ante la CNSNS para operar en materia de energía nuclear, manejo de fuentes radiactivas y servicios de protección radiológica en todo México.',
 'Autorización para operar en materia de energía nuclear',2),

('STPS','Secretaría del Trabajo y Previsión Social',
 'Autorización ante la STPS para prestar servicios de seguridad radiológica en el ámbito laboral, garantizando la protección de los trabajadores.',
 'Autorización para servicios de seguridad radiológica laboral',3),

('COFEPRIS','Comisión Federal para la Protección contra Riesgos Sanitarios',
 'Autorización ante COFEPRIS para el manejo de fuentes radiactivas en el ámbito sanitario, incluyendo hospitales, clínicas y centros de diagnóstico.',
 'Autorización para el ámbito sanitario y médico',4),

('SCT','Secretaría de Comunicaciones y Transportes',
 'Autorización para el transporte de material radiactivo conforme a la normativa de la SCT y los estándares internacionales del IAEA.',
 'Autorización para transporte de material radiactivo',5);

-- ── EQUIPO ──────────────────────────────────
DROP TABLE IF EXISTS `equipo`;
CREATE TABLE `equipo` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(100) NOT NULL,
  `puesto` VARCHAR(150),
  `descripcion` TEXT,
  `foto` VARCHAR(255),
  `linkedin` VARCHAR(255),
  `email` VARCHAR(150),
  `orden` INT DEFAULT 0,
  `activo` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `equipo` (`nombre`,`puesto`,`descripcion`,`orden`) VALUES
('Dr. Roberto Sánchez','Director General',
 'Físico nuclear con más de 25 años de experiencia en seguridad radiológica. Doctor en Física por la UNAM y certificado por organismos internacionales en protección radiológica.',1),
('Ing. María López','Directora Técnica',
 'Ingeniera nuclear certificada con especialidad en protección radiológica industrial y médica. Auditora líder ISO 9001 con amplia experiencia en gestión de calidad.',2),
('Lic. Carlos Mendoza','Supervisor Radiológico Senior',
 'Supervisor radiológico con 15 años de experiencia en plantas industriales, hospitales y centros de investigación. Especialista en trámites regulatorios.',3),
('Ing. Ana García','Especialista en Instrumentación',
 'Experta en calibración y mantenimiento de equipos de detección de radiación. Certificada por fabricantes internacionales líderes del sector.',4);

-- ── VALORES ─────────────────────────────────
DROP TABLE IF EXISTS `valores`;
CREATE TABLE `valores` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `titulo` VARCHAR(100) NOT NULL,
  `descripcion` TEXT,
  `icono` VARCHAR(100) DEFAULT 'fas fa-star',
  `orden` INT DEFAULT 0,
  `activo` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `valores` (`titulo`,`descripcion`,`icono`,`orden`) VALUES
('Seguridad','La seguridad radiológica es nuestra prioridad absoluta en cada servicio que prestamos a nuestros clientes.','fas fa-shield-alt',1),
('Calidad','Nos comprometemos con la más alta calidad en cada proceso, respaldados por nuestra certificación ISO 9001:2008.','fas fa-award',2),
('Experiencia','25 años de trayectoria continua avalan nuestro conocimiento y profesionalismo en el sector radiológico mexicano.','fas fa-history',3),
('Confiabilidad','Somos reconocidos por los organismos reguladores como una empresa confiable, responsable y comprometida.','fas fa-handshake',4),
('Innovación','Aplicamos las mejores prácticas y tecnologías actuales para ofrecer servicios de vanguardia a nuestros clientes.','fas fa-lightbulb',5),
('Compromiso','Nos comprometemos con el cumplimiento normativo y la operación completamente segura de su empresa.','fas fa-check-circle',6);

-- ── ESTADÍSTICAS ────────────────────────────
DROP TABLE IF EXISTS `estadisticas`;
CREATE TABLE `estadisticas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `numero` VARCHAR(20) NOT NULL,
  `etiqueta` VARCHAR(100) NOT NULL,
  `icono` VARCHAR(100) DEFAULT 'fas fa-chart-bar',
  `orden` INT DEFAULT 0,
  `activo` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `estadisticas` (`numero`,`etiqueta`,`icono`,`orden`) VALUES
('+25','Años de Experiencia','fas fa-calendar-alt',1),
('+500','Clientes Atendidos','fas fa-users',2),
('+1000','Proyectos Completados','fas fa-project-diagram',3),
('5','Certificaciones Vigentes','fas fa-certificate',4);

-- ── MENSAJES CONTACTO ────────────────────────
DROP TABLE IF EXISTS `contacto_mensajes`;
CREATE TABLE `contacto_mensajes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(100) NOT NULL,
  `empresa` VARCHAR(150),
  `email` VARCHAR(150) NOT NULL,
  `telefono` VARCHAR(20),
  `servicio_interes` VARCHAR(150),
  `mensaje` TEXT NOT NULL,
  `ip` VARCHAR(45),
  `leido` TINYINT(1) DEFAULT 0,
  `respondido` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── ADMIN USUARIOS ──────────────────────────
DROP TABLE IF EXISTS `admin_usuarios`;
CREATE TABLE `admin_usuarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `rol` ENUM('superadmin','admin','editor') DEFAULT 'editor',
  `activo` TINYINT(1) DEFAULT 1,
  `ultimo_acceso` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Hash de: Crisamex2024!
INSERT INTO `admin_usuarios` (`nombre`,`email`,`password_hash`,`rol`) VALUES
('Administrador CRISAMEX','admin@crisamex.com',
 '$2y$10$FG2cHslN3puNUu61W/PSzewCl2Fyd2ta2f0fb88qcKWpOYFk2gudS','superadmin');

-- ── PLANES ──────────────────────────────────
DROP TABLE IF EXISTS `planes`;
CREATE TABLE `planes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) UNIQUE,
  `descripcion` TEXT,
  `precio_mensual` DECIMAL(10,2) DEFAULT 0,
  `precio_anual` DECIMAL(10,2) DEFAULT 0,
  `color` VARCHAR(20) DEFAULT '#C8151B',
  `icono` VARCHAR(80) DEFAULT 'fas fa-star',
  `features` LONGTEXT,
  `limite_usuarios` INT DEFAULT 1,
  `limite_reportes` INT DEFAULT 10,
  `soporte` VARCHAR(80) DEFAULT 'Email',
  `destacado` TINYINT(1) DEFAULT 0,
  `activo` TINYINT(1) DEFAULT 1,
  `orden` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `planes` (`nombre`,`slug`,`descripcion`,`precio_mensual`,`precio_anual`,`color`,`icono`,`features`,`limite_usuarios`,`limite_reportes`,`soporte`,`destacado`,`orden`) VALUES
('Básico','basico','Ideal para empresas pequeñas que requieren cumplimiento normativo básico.',
 2500.00,25000.00,'#555555','fas fa-radiation-alt',
 '["Asesoría radiológica básica","Trámites ante CNSNS","1 auditoría anual","Acceso al portal cliente","Reportes mensuales","Soporte por email"]',
 2,10,'Email',0,1),

('Profesional','profesional','La solución más popular para medianas empresas con múltiples fuentes.',
 6500.00,65000.00,'#C8151B','fas fa-shield-alt',
 '["Todo lo del plan Básico","Trámites ante STPS y COFEPRIS","3 auditorías anuales","Capacitación del personal","Instrumentación nuclear","Soporte prioritario 48h","Reportes ilimitados","Dashboard de cumplimiento"]',
 5,100,'Prioritario 48h',1,2),

('Enterprise','enterprise','Cobertura total para grandes corporativos y plantas industriales.',
 15000.00,150000.00,'#d4a017','fas fa-crown',
 '["Todo lo del plan Profesional","Gestor de cuenta dedicado","Auditorías ilimitadas","Transporte de material radiactivo","Servicio en sitio","Soporte 24/7","Usuarios ilimitados","Reportes personalizados"]',
 999,999,'24/7 Dedicado',0,3);

-- ── CLIENTES ────────────────────────────────
DROP TABLE IF EXISTS `clientes`;
CREATE TABLE `clientes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(100) NOT NULL,
  `apellidos` VARCHAR(100),
  `empresa` VARCHAR(150) NOT NULL,
  `rfc` VARCHAR(20),
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `telefono` VARCHAR(20),
  `cargo` VARCHAR(100),
  `password_hash` VARCHAR(255) NOT NULL,
  `plan_id` INT DEFAULT NULL,
  `licencia_inicio` DATE DEFAULT NULL,
  `licencia_fin` DATE DEFAULT NULL,
  `licencia_status` ENUM('activa','suspendida','vencida','trial') DEFAULT 'trial',
  `email_verificado` TINYINT(1) DEFAULT 0,
  `activo` TINYINT(1) DEFAULT 1,
  `ultimo_acceso` TIMESTAMP NULL,
  `avatar` VARCHAR(255) DEFAULT NULL,
  `notas_admin` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`plan_id`) REFERENCES `planes`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `clientes` (`nombre`,`apellidos`,`empresa`,`email`,`telefono`,`cargo`,`password_hash`,`plan_id`,`licencia_inicio`,`licencia_fin`,`licencia_status`,`email_verificado`,`activo`) VALUES
('Carlos','Mendoza Demo','Empresa Demo S.A. de C.V.','demo@empresa.com','55 1234 5678','Director de Operaciones',
 '$2y$10$FG2cHslN3puNUu61W/PSzewCl2Fyd2ta2f0fb88qcKWpOYFk2gudS',
 2,'2024-01-01','2027-12-31','activa',1,1);

-- ── PORTAL MENSAJES ─────────────────────────
DROP TABLE IF EXISTS `portal_mensajes`;
CREATE TABLE `portal_mensajes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `cliente_id` INT NOT NULL,
  `asunto` VARCHAR(200) NOT NULL,
  `mensaje` LONGTEXT NOT NULL,
  `de` ENUM('cliente','admin') DEFAULT 'cliente',
  `leido_admin` TINYINT(1) DEFAULT 0,
  `leido_cliente` TINYINT(1) DEFAULT 0,
  `adjunto` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`cliente_id`) REFERENCES `clientes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `portal_mensajes` (`cliente_id`,`asunto`,`mensaje`,`de`,`leido_admin`,`leido_cliente`) VALUES
(1,'Bienvenido al Portal CRISAMEX',
 'Hola Carlos, bienvenido al portal de clientes CRISAMEX. Estamos a sus órdenes para cualquier consulta sobre su plan Profesional. Su licencia está activa hasta diciembre 2027. No dude en contactarnos.',
 'admin',1,1),
(1,'¿Cuándo es mi próxima auditoría?',
 'Buenos días, quisiera saber cuándo está programada mi próxima auditoría anual y qué documentos debo preparar.',
 'cliente',0,1);

-- ── PORTAL DOCUMENTOS ───────────────────────
DROP TABLE IF EXISTS `portal_documentos`;
CREATE TABLE `portal_documentos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `cliente_id` INT NOT NULL,
  `titulo` VARCHAR(200) NOT NULL,
  `descripcion` TEXT,
  `archivo` VARCHAR(255),
  `tipo` ENUM('reporte','certificado','factura','contrato','otro') DEFAULT 'reporte',
  `subido_por` ENUM('admin','cliente') DEFAULT 'admin',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`cliente_id`) REFERENCES `clientes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `portal_documentos` (`cliente_id`,`titulo`,`descripcion`,`tipo`,`subido_por`) VALUES
(1,'Reporte de Auditoría Q1 2024','Reporte de auditoría radiológica del primer trimestre 2024. Resultados: APROBADO.','reporte','admin'),
(1,'Certificado ISO 9001 2024','Certificado vigente del Sistema de Gestión de Calidad ISO 9001.','certificado','admin'),
(1,'Programa de Protección Radiológica','Programa de protección radiológica actualizado y aprobado por CNSNS.','contrato','admin');

-- ── PORTAL NOTIFICACIONES ───────────────────
DROP TABLE IF EXISTS `portal_notificaciones`;
CREATE TABLE `portal_notificaciones` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `cliente_id` INT NOT NULL,
  `titulo` VARCHAR(200) NOT NULL,
  `mensaje` TEXT,
  `tipo` ENUM('info','success','warning','danger') DEFAULT 'info',
  `leida` TINYINT(1) DEFAULT 0,
  `url` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`cliente_id`) REFERENCES `clientes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `portal_notificaciones` (`cliente_id`,`titulo`,`mensaje`,`tipo`,`leida`) VALUES
(1,'¡Bienvenido a CRISAMEX Portal!','Tu cuenta ha sido activada con el plan Profesional. Ya tienes acceso completo a todos los servicios.','success',1),
(1,'Nueva auditoría programada','Tu próxima auditoría radiológica está programada para el 15 de junio 2024.','info',0),
(1,'Nuevo documento disponible','Se ha subido tu Reporte de Auditoría Q1 2024. Ingresa a Documentos para descargarlo.','success',0),
(1,'Licencia activa hasta Dic 2027','Tu licencia está vigente. Recuerda renovarla antes del vencimiento.','info',1);

SET FOREIGN_KEY_CHECKS = 1;
