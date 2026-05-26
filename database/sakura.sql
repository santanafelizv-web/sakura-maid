-- ============================================================
-- SAKURA MAID SERVICES — Base de Datos Completa
-- MySQL 8.0 | Ejecutar en Railway o MySQL Workbench
-- ============================================================

CREATE DATABASE IF NOT EXISTS sakura_maid_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sakura_maid_db;

-- ─────────────────────────────────────────────
-- TABLAS
-- ─────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS usuario (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre        VARCHAR(80)  NOT NULL,
  apellido      VARCHAR(80)  NOT NULL,
  email         VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  telefono      VARCHAR(20),
  rol           ENUM('cliente','maid','admin') NOT NULL DEFAULT 'cliente',
  avatar_seed   VARCHAR(50) DEFAULT NULL,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE usuario ADD COLUMN avatar_seed VARCHAR(50) DEFAULT NULL;

CREATE TABLE IF NOT EXISTS perfil_maid (
  id                    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario_id            INT UNSIGNED NOT NULL UNIQUE,
  descripcion           TEXT,
  tarifa_hora           DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  disponibilidad        ENUM('disponible','ocupado','inactivo') DEFAULT 'disponible',
  calificacion_promedio FLOAT DEFAULT 0,
  total_servicios       INT DEFAULT 0,
  activo                TINYINT(1) DEFAULT 1,
  FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS servicio (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  cliente_id   INT UNSIGNED NOT NULL,
  maid_id      INT UNSIGNED NOT NULL,
  descripcion  TEXT,
  fecha        DATE NOT NULL,
  hora_inicio  TIME NOT NULL,
  hora_fin     TIME NOT NULL,
  direccion    VARCHAR(255) NOT NULL,
  estado       ENUM('pendiente','confirmado','en_progreso','completado','cancelado') NOT NULL DEFAULT 'pendiente',
  precio_total DECIMAL(10,2) DEFAULT 0.00,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (cliente_id) REFERENCES usuario(id),
  FOREIGN KEY (maid_id)    REFERENCES perfil_maid(id)
);

CREATE TABLE IF NOT EXISTS factura (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  servicio_id   INT UNSIGNED NOT NULL UNIQUE,
  numero        VARCHAR(20) NOT NULL UNIQUE,
  subtotal      DECIMAL(10,2) NOT NULL,
  impuesto      DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  total         DECIMAL(10,2) NOT NULL,
  estado_pago   ENUM('pendiente','pagado','reembolsado') DEFAULT 'pendiente',
  fecha_emision TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (servicio_id) REFERENCES servicio(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS resena (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  servicio_id  INT UNSIGNED NOT NULL UNIQUE,
  autor_id     INT UNSIGNED NOT NULL,
  calificacion TINYINT NOT NULL CHECK (calificacion BETWEEN 1 AND 5),
  comentario   TEXT,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (servicio_id) REFERENCES servicio(id) ON DELETE CASCADE,
  FOREIGN KEY (autor_id)    REFERENCES usuario(id)
);

CREATE TABLE IF NOT EXISTS notificacion (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT UNSIGNED NOT NULL,
  titulo     VARCHAR(150) NOT NULL,
  mensaje    TEXT NOT NULL,
  tipo       ENUM('servicio','pago','sistema') DEFAULT 'sistema',
  leida      TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE
);

-- ─────────────────────────────────────────────
-- STORED PROCEDURES
-- ─────────────────────────────────────────────

DELIMITER $$

-- SP: Generar factura automática al completar servicio
DROP PROCEDURE IF EXISTS sp_generar_factura$$
CREATE PROCEDURE sp_generar_factura(IN p_servicio_id INT)
BEGIN
  DECLARE v_precio DECIMAL(10,2);
  DECLARE v_subtotal DECIMAL(10,2);
  DECLARE v_itbis DECIMAL(10,2);
  DECLARE v_total DECIMAL(10,2);
  DECLARE v_numero VARCHAR(20);
  DECLARE v_existe INT;

  SELECT COUNT(*) INTO v_existe FROM factura WHERE servicio_id = p_servicio_id;
  IF v_existe = 0 THEN
    SELECT precio_total INTO v_precio FROM servicio WHERE id = p_servicio_id;
    SET v_subtotal = v_precio;
    SET v_itbis    = ROUND(v_precio * 0.18, 2);
    SET v_total    = v_subtotal + v_itbis;
    SET v_numero   = CONCAT('FAC-', YEAR(NOW()), '-', LPAD(p_servicio_id, 5, '0'));
    INSERT INTO factura (servicio_id, numero, subtotal, impuesto, total, estado_pago)
    VALUES (p_servicio_id, v_numero, v_subtotal, v_itbis, v_total, 'pendiente');
  END IF;
END$$

-- SP: Actualizar calificación promedio de una maid
DROP PROCEDURE IF EXISTS sp_actualizar_calificacion$$
CREATE PROCEDURE sp_actualizar_calificacion(IN p_maid_id INT)
BEGIN
  DECLARE v_promedio FLOAT;
  DECLARE v_total INT;
  SELECT AVG(r.calificacion), COUNT(r.id)
    INTO v_promedio, v_total
  FROM resena r
  JOIN servicio s ON r.servicio_id = s.id
  WHERE s.maid_id = p_maid_id;
  UPDATE perfil_maid SET calificacion_promedio = COALESCE(v_promedio,0), total_servicios = COALESCE(v_total,0)
  WHERE id = p_maid_id;
END$$

-- SP: Reporte de ingresos por mes
DROP PROCEDURE IF EXISTS sp_reporte_ingresos$$
CREATE PROCEDURE sp_reporte_ingresos(IN p_anio INT)
BEGIN
  SELECT
    MONTH(f.fecha_emision)  AS mes,
    MONTHNAME(f.fecha_emision) AS nombre_mes,
    COUNT(f.id)             AS total_facturas,
    SUM(f.total)            AS ingresos_total,
    SUM(f.impuesto)         AS itbis_total
  FROM factura f
  WHERE YEAR(f.fecha_emision) = p_anio AND f.estado_pago = 'pagado'
  GROUP BY MONTH(f.fecha_emision), MONTHNAME(f.fecha_emision)
  ORDER BY MONTH(f.fecha_emision);
END$$

-- SP: Top 5 maids más solicitadas
DROP PROCEDURE IF EXISTS sp_top_maids$$
CREATE PROCEDURE sp_top_maids()
BEGIN
  SELECT u.nombre, u.apellido, pm.calificacion_promedio,
    COUNT(s.id) AS servicios, SUM(s.precio_total) AS ingresos
  FROM servicio s
  JOIN perfil_maid pm ON s.maid_id = pm.id
  JOIN usuario u ON pm.usuario_id = u.id
  WHERE s.estado = 'completado'
  GROUP BY pm.id, u.nombre, u.apellido, pm.calificacion_promedio
  ORDER BY servicios DESC LIMIT 5;
END$$

DELIMITER ;

-- ─────────────────────────────────────────────
-- DATOS DE PRUEBA
-- ─────────────────────────────────────────────

INSERT INTO usuario (nombre, apellido, email, password_hash, telefono, rol) VALUES
('Admin',    'Sakura',   'admin@sakura.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '8090000001', 'admin'),
('Maria',    'Lopez',    'maria@sakura.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '8091111111', 'cliente'),
('Juan',     'Perez',    'juan@sakura.com',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '8092222222', 'cliente'),
('Ana',      'Gomez',    'ana@sakura.com',      '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '8093333333', 'maid'),
('Sofia',    'Ramirez',  'sofia@sakura.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '8094444444', 'maid'),
('Camila',   'Torres',   'camila@sakura.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '8095555555', 'maid');
-- Password de todos: "password"

INSERT INTO perfil_maid (usuario_id, descripcion, tarifa_hora, disponibilidad, calificacion_promedio, total_servicios) VALUES
(4, 'Especialista en limpieza de hogares y oficinas con 3 años de experiencia. Puntual y detallista.', 350.00, 'disponible', 4.8, 0),
(5, 'Limpieza profunda, organización y cuidado de espacios comerciales y residenciales.', 400.00, 'disponible', 4.5, 0),
(6, 'Servicio de limpieza rápido y eficiente. Especialidad en post-construcción.', 320.00, 'ocupado',    4.2, 0);

INSERT INTO servicio (cliente_id, maid_id, descripcion, fecha, hora_inicio, hora_fin, direccion, estado, precio_total) VALUES
(2, 1, 'Limpieza general del apartamento', '2026-04-10', '09:00:00', '12:00:00', 'Av. 27 de Febrero #45, SDO', 'completado', 1050.00),
(2, 1, 'Limpieza de cocina y baños',       '2026-04-20', '08:00:00', '11:00:00', 'Av. 27 de Febrero #45, SDO', 'completado', 1050.00),
(3, 2, 'Limpieza de oficina',              '2026-05-01', '07:00:00', '12:00:00', 'Piantini, SDO',               'completado', 2000.00),
(2, 3, 'Organización del hogar',           '2026-05-05', '10:00:00', '13:00:00', 'Los Prados, SDO',             'completado', 960.00),
(3, 1, 'Limpieza profunda',                '2026-05-10', '09:00:00', '14:00:00', 'Bella Vista, SDO',            'pendiente',  1750.00),
(2, 2, 'Limpieza de sala y cuartos',       '2026-05-15', '08:00:00', '11:00:00', 'Naco, SDO',                   'confirmado', 1200.00);

-- Generar facturas para servicios completados
CALL sp_generar_factura(1);
CALL sp_generar_factura(2);
CALL sp_generar_factura(3);
CALL sp_generar_factura(4);

UPDATE factura SET estado_pago = 'pagado' WHERE servicio_id IN (1,2,3);

INSERT INTO resena (servicio_id, autor_id, calificacion, comentario) VALUES
(1, 2, 5, 'Excelente trabajo, muy puntual y detallista.'),
(2, 2, 5, 'La dejó impecable, muy recomendada.'),
(3, 3, 4, 'Buen trabajo, llegó a tiempo.');

CALL sp_actualizar_calificacion(1);
CALL sp_actualizar_calificacion(2);

INSERT INTO notificacion (usuario_id, titulo, mensaje, tipo) VALUES
(2, 'Servicio confirmado', 'Tu servicio del 15 de mayo fue confirmado por Ana.',    'servicio'),
(4, 'Nuevo trabajo',       'María Lopez contrató tus servicios para el 15 de mayo.','servicio');
