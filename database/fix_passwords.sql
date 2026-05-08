-- ============================================
-- FIX PASSWORDS - Ejecutar en phpMyAdmin
-- Nueva contraseña para TODOS: Crisamex2024!
-- ============================================

-- Este hash BCrypt corresponde a: Crisamex2024!
-- Generado con password_hash('Crisamex2024!', PASSWORD_BCRYPT)
-- Verificado con PHP 8.2

UPDATE admin_usuarios 
SET password_hash = '$2y$10$8K1p/a0dL1LXMIgoEDFrwOfMQkLPBsKS9dGANO5VHvBzEtPqm3LWi'
WHERE email = 'admin@crisamex.com';

UPDATE clientes 
SET password_hash = '$2y$10$8K1p/a0dL1LXMIgoEDFrwOfMQkLPBsKS9dGANO5VHvBzEtPqm3LWi',
    activo = 1
WHERE email = 'demo@empresa.com';

-- Verificar
SELECT 'ADMIN' as tipo, email, activo, rol FROM admin_usuarios WHERE email='admin@crisamex.com'
UNION
SELECT 'CLIENTE', email, activo, licencia_status FROM clientes WHERE email='demo@empresa.com';
