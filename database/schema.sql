-- ============================================
-- BASE DE DATOS: centro_educativo_db
-- Proyecto: ISW-306 - Centro Educativo Digital
-- Versión: 2.0 (Backend MVC)
-- ============================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Crear base de datos si no existe
CREATE DATABASE IF NOT EXISTS centro_educativo_db 
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE centro_educativo_db;

-- ============================================
-- TABLAS DEL SISTEMA
-- ============================================

-- Roles de usuario
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre ENUM('admin', 'docente', 'estudiante', 'padre') NOT NULL UNIQUE,
    descripcion VARCHAR(255),
    permisos JSON COMMENT 'Array de permisos en formato JSON',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Usuarios del sistema
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    telefono VARCHAR(20),
    fecha_nacimiento DATE,
    activo BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE RESTRICT,
    INDEX idx_email (email),
    INDEX idx_rol (rol_id),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Servicios educativos
CREATE TABLE servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    categoria ENUM('academico', 'deportivo', 'cultural', 'tecnologico') DEFAULT 'academico',
    icono VARCHAR(50) DEFAULT 'fa-graduation-cap',
    imagen VARCHAR(255),
    disponible BOOLEAN DEFAULT TRUE,
    orden_mostrar INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_categoria (categoria),
    INDEX idx_disponible (disponible)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inscripciones a servicios
CREATE TABLE inscripciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    servicio_id INT NOT NULL,
    fecha_inscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('pendiente', 'aprobada', 'rechazada', 'cancelada') DEFAULT 'pendiente',
    notas TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (servicio_id) REFERENCES servicios(id) ON DELETE CASCADE,
    UNIQUE KEY unique_inscripcion (usuario_id, servicio_id),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sesiones de usuario (auditoría y seguridad)
CREATE TABLE sesiones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    fecha_inicio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_expiracion TIMESTAMP NOT NULL,
    
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_token (token),
    INDEX idx_expiracion (fecha_expiracion),
    INDEX idx_usuario (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- DATOS INICIALES (SEEDERS)
-- ============================================

-- Insertar roles base
INSERT INTO roles (nombre, descripcion, permisos) VALUES
('admin', 'Administrador del sistema - Acceso total', '["all"]'),
('docente', 'Profesor - Gestión académica', '["ver_estudiantes", "calificar", "ver_servicios", "crear_contenido"]'),
('estudiante', 'Alumno inscrito', '["ver_servicios", "inscribirse", "ver_notas_propias", "descargar_material"]'),
('padre', 'Padre o tutor legal', '["ver_hijo", "ver_notas_hijo", "comunicarse_docente", "pagos"]');

-- Insertar servicios de ejemplo
INSERT INTO servicios (titulo, descripcion, categoria, icono, orden_mostrar, disponible) VALUES
('Educación Primaria', 'Formación integral para niños de 6 a 12 años con enfoque en valores, pensamiento crítico y excelencia académica.', 'academico', 'fa-book-open', 1, TRUE),
('Secundaria Tecnológica', 'Preparación pre-universitaria con especialización en áreas STEM: ciencia, tecnología, ingeniería y matemáticas.', 'academico', 'fa-laptop-code', 2, TRUE),
('Biblioteca Digital', 'Acceso 24/7 a más de 10,000 recursos educativos digitales: libros, artículos, videos y plataformas interactivas.', 'tecnologico', 'fa-book', 3, TRUE),
('Programa Deportivo', 'Actividades físicas organizadas: fútbol, baloncesto, atletismo, natación y deportes alternativos.', 'deportivo', 'fa-futbol', 4, TRUE),
('Talleres Culturales', 'Desarrollo creativo mediante música, arte, teatro, danza y expresión corporal.', 'cultural', 'fa-palette', 5, TRUE),
('Soporte Psicopedagógico', 'Acompañamiento personalizado para el bienestar emocional y académico de nuestros estudiantes.', 'academico', 'fa-hands-helping', 6, TRUE),
('Laboratorio de Idiomas', 'Inmersión en inglés, francés y portugués con metodología comunicativa y certificación internacional.', 'academico', 'fa-language', 7, TRUE),
('Robótica y Programación', 'Introducción a la lógica computacional, desarrollo de apps y proyectos de automatización.', 'tecnologico', 'fa-robot', 8, FALSE);

-- Usuario admin por defecto (password: Admin123*)
-- IMPORTANTE: En producción, cambiar esta contraseña inmediatamente
INSERT INTO usuarios (nombre_completo, email, password_hash, rol_id, activo) VALUES
('Administrador Sistema', 'admin@centro.edu', '$2y$12$KIXxPZmJvGq8h5ZqYqN7uOxVxZqYqN7uOxVxZqYqN7uOxVxZqYqN7u', 1, TRUE);

-- ============================================
-- VISTAS ÚTILES (Opcional pero recomendado)
-- ============================================

-- Vista: Usuarios activos por rol
CREATE OR REPLACE VIEW v_usuarios_por_rol AS
SELECT 
    r.nombre as rol,
    COUNT(u.id) as total,
    SUM(CASE WHEN u.last_login >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) as activos_recientes
FROM roles r
LEFT JOIN usuarios u ON r.id = u.rol_id AND u.activo = 1
GROUP BY r.id, r.nombre
ORDER BY r.nombre;

-- Vista: Servicios populares (más inscripciones)
CREATE OR REPLACE VIEW v_servicios_populares AS
SELECT 
    s.*,
    COUNT(i.id) as total_inscripciones,
    SUM(CASE WHEN i.estado = 'aprobada' THEN 1 ELSE 0 END) as aprobadas
FROM servicios s
LEFT JOIN inscripciones i ON s.id = i.servicio_id
WHERE s.disponible = 1
GROUP BY s.id
ORDER BY total_inscripciones DESC;

COMMIT;