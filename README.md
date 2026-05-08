# CRISAMEX — Sitio Web Completo v6
Stack: PHP 8.2 + MySQL + Apache + Docker

## 🚀 SUBIR A RENDER.COM

### 1. Subir a GitHub
```bash
cd ~/Downloads/crisamex
git init && git add . && git commit -m "CRISAMEX v6"
# Crear repo en github.com, luego:
git remote add origin https://github.com/TU_USUARIO/crisamex-web.git
git branch -M main && git push -u origin main
```

### 2. Crear MySQL gratis en PlanetScale
1. Ve a planetscale.com → crear cuenta → New database: `crisamex-db`
2. Connect → PHP (PDO) → copia las variables de entorno
3. Branches → main → Console → pega el contenido de database/init.sql

### 3. Deploy en Render.com
1. render.com → New → Web Service → conecta tu repo GitHub
2. Environment: Docker | Dockerfile: ./docker/Dockerfile.render
3. Variables de entorno:
   - DB_HOST = tu-host.planetscale.com
   - DB_NAME = crisamex-db
   - DB_USER = tu-usuario
   - DB_PASS = tu-password
   - APP_URL = https://crisamex.onrender.com
   - APP_ENV = production
4. Create Web Service → espera 3-5 min

### 4. Inicializar DB
En el Shell de Render: `php database/setup.php`

### 5. Acceder
- Sitio: https://crisamex.onrender.com
- Admin: https://crisamex.onrender.com/admin
- Login: admin@crisamex.com / password

## 🏃 LOCAL
```bash
cd ~/Downloads/crisamex && mkdir -p public/uploads
docker-compose up -d --build
# http://localhost:8090
```
