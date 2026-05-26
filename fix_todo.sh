#!/bin/bash
# ═══════════════════════════════════════════════════════════════
# CRISAMEX — Fix completo de acentos en UN solo comando
# Ejecutar desde: ~/Downloads/crisamex
# ═══════════════════════════════════════════════════════════════
set -e
GREEN='\033[92m'; YELLOW='\033[93m'; RED='\033[91m'; RESET='\033[0m'; BOLD='\033[1m'

echo ""
echo -e "${BOLD}═══════════════════════════════════════════════════════${RESET}"
echo -e "${BOLD}   CRISAMEX — Fix Encoding UTF-8 Completo${RESET}"
echo -e "${BOLD}═══════════════════════════════════════════════════════${RESET}"

# ── PASO 1: Corregir archivos PHP ─────────────────────────────
echo -e "\n${YELLOW}[1/4] Corrigiendo archivos PHP...${RESET}"
python3 fix_home.py
echo -e "${GREEN}✅ Archivos PHP corregidos${RESET}"

# ── PASO 2: Reemplazar database.php ──────────────────────────
echo -e "\n${YELLOW}[2/4] Actualizando database.php...${RESET}"
cat > crisamex/src/config/database.php << 'PHPEOF'
<?php
class Database {
    private static ?PDO $instance = null;
    public static function getInstance(): PDO {
        if (self::$instance === null) self::$instance = self::connect();
        return self::$instance;
    }
    private static function connect(): PDO {
        $host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'localhost';
        $db   = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?? 'crisamex_db';
        $user = $_ENV['DB_USER'] ?? getenv('DB_USER') ?? 'crisamex_user';
        $pass = $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?? '';
        $port = $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?? '3306';
        $dsn  = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
        $opts = [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, $user, $pass, $opts);
            $pdo->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("SET character_set_client=utf8mb4");
            $pdo->exec("SET character_set_connection=utf8mb4");
            $pdo->exec("SET character_set_results=utf8mb4");
            return $pdo;
        } catch (PDOException $e) {
            error_log('[DB] ' . $e->getMessage());
            http_response_code(503);
            die('Error de base de datos.');
        }
    }
    public static function query(string $sql, array $params=[]): PDOStatement {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    public static function fetchAll(string $sql, array $params=[]): array {
        return self::query($sql,$params)->fetchAll();
    }
    public static function fetchOne(string $sql, array $params=[]): array|false {
        return self::query($sql,$params)->fetch();
    }
    public static function lastInsertId(): string {
        return self::getInstance()->lastInsertId();
    }
    public static function getConfig(string $key): ?string {
        $r = self::fetchOne("SELECT valor FROM site_config WHERE clave=? LIMIT 1",[$key]);
        return $r ? $r['valor'] : null;
    }
}
PHPEOF
echo -e "${GREEN}✅ database.php actualizado${RESET}"

# ── PASO 3: Fix MySQL Railway ─────────────────────────────────
echo -e "\n${YELLOW}[3/4] Arreglando charset en MySQL Railway...${RESET}"
docker run --rm -i mysql:8.0 mysql \
  --default-character-set=utf8mb4 \
  -h turntable.proxy.rlwy.net \
  -P 28798 \
  -u root \
  -pCPDIsLcmUjPbNRfetZJHurNdXrRovjXu \
  railway << 'SQLEOF'
SET NAMES utf8mb4;
ALTER DATABASE railway CHARACTER SET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE servicios             CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE certificaciones       CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE equipo                CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE valores               CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE estadisticas          CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE site_config           CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE contacto_mensajes     CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE admin_usuarios        CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE planes                CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE clientes              CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE portal_mensajes       CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE portal_documentos     CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE portal_notificaciones CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
DELETE FROM servicios;
INSERT INTO servicios (titulo,descripcion_corta,descripcion_larga,icono,orden,slug,activo) VALUES
('Calibración de Equipos','Garantizamos que sus instrumentos de medición proporcionen datos exactos y confiables para su seguridad radiológica.','Nos especializamos principalmente en detectores Geiger-Müller (GM). Realizamos la calibración en laboratorios propios utilizando fuentes de referencia para ajustar cada instrumento con total precisión. Calibración anual para equipos portátiles y manuales.','fas fa-tachometer-alt',1,'calibracion-equipos',1),
('Prueba de Fuga (Frotis)','Procedimientos preventivos para confirmar la integridad de sus fuentes radiactivas y descartar cualquier tipo de contaminación.','Utilizamos un cotonete para frotar la superficie de la fuente. Analizamos este frotis en nuestro equipo monocanal buscando rastros de Americio. Si los niveles son óptimos, emitimos un Certificado de prueba de fuga.','fas fa-microscope',2,'prueba-de-fuga',1),
('Mantenimiento a Contenedores de Fuentes','Servicios de conservación para los sistemas de blindaje que protegen al personal de la radiación directa.','Trabajamos sobre contenedores de plomo recubiertos con acero. Realizamos mantenimientos para evitar el deterioro estructural y asegurar que el contenedor funcione como barrera impenetrable.','fas fa-shield-alt',3,'mantenimiento-contenedores',1),
('Verificación de Portales y Equipos de Rayos X','Nos aseguramos de que sus sistemas de detección y diagnóstico mantengan su precisión operativa.','Trabajamos con detectores de gran tamaño para monitoreo de personal, vehículos y carga. Cubrimos sistemas en aeropuertos, aduanas, radiografía industrial y equipos médicos dentales bajo permisos de la Secretaría de Energía y CNS.','fas fa-radiation',4,'verificacion-portales-rayos-x',1),
('Levantamiento de Niveles','Estudios técnicos de campo para mapear la presencia de radiación en áreas específicas de su instalación.','Utilizamos detectores portátiles Geiger-Müller. Nuestros técnicos recorren sus instalaciones midiendo la radiación ambiental para confirmar que el blindaje de plomo y acero sea totalmente efectivo.','fas fa-chart-area',5,'levantamiento-de-niveles',1),
('Capacitación y Trámites ante CNSNS','Gestión administrativa y formación técnica ante las autoridades reguladoras. Autorización de la Secretaría de Energía.','Realizamos todos los trámites ante la Comisión Nacional de Seguridad Nuclear y Salvaguardias (CNSNS). Impartimos capacitación técnica para que su personal opere materiales y equipos de manera segura.','fas fa-file-alt',6,'capacitacion-tramites-cnsns',1);
SELECT titulo FROM servicios;
SQLEOF
echo -e "${GREEN}✅ MySQL Railway corregido${RESET}"

# ── PASO 4: Push a GitHub / Railway ──────────────────────────
echo -e "\n${YELLOW}[4/4] Subiendo cambios a Railway...${RESET}"
git add .
git commit -m "Fix: UTF-8 encoding completo - acentos y ñ corregidos en todo el sitio"
git push origin main
echo -e "${GREEN}✅ Push exitoso${RESET}"

echo ""
echo -e "${BOLD}═══════════════════════════════════════════════════════${RESET}"
echo -e "${GREEN}${BOLD}   ✅ LISTO — Railway redesplegará en 3-5 minutos${RESET}"
echo -e "${BOLD}   Visita: https://crisamex-web-production.up.railway.app${RESET}"
echo -e "${BOLD}═══════════════════════════════════════════════════════${RESET}"
echo ""
