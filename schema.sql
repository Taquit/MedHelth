-- Base de datos para MedHealth (care_sis)
-- Sistema de seguimiento y control de medicamentos

CREATE DATABASE IF NOT EXISTS seguimiento_medicamentos
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE seguimiento_medicamentos;

-- 1. Tabla de usuarios del sistema
CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    correo VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('paciente', 'cuidador') NOT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Tabla de pacientes
CREATE TABLE IF NOT EXISTS pacientes (
    id_paciente INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT UNSIGNED NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    apellido_paterno VARCHAR(100) NOT NULL,
    apellido_materno VARCHAR(100) NULL,
    telefono VARCHAR(20) NULL,
    CONSTRAINT fk_pacientes_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tabla de cuidadores
CREATE TABLE IF NOT EXISTS cuidadores (
    id_cuidador INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT UNSIGNED NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    apellido_paterno VARCHAR(100) NOT NULL,
    apellido_materno VARCHAR(100) NULL,
    parentesco VARCHAR(50) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    CONSTRAINT fk_cuidadores_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Relacion N:M entre pacientes y cuidadores
CREATE TABLE IF NOT EXISTS paciente_cuidador (
    id_relacion INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_paciente INT UNSIGNED NOT NULL,
    id_cuidador INT UNSIGNED NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NULL,
    CONSTRAINT fk_pc_paciente
        FOREIGN KEY (id_paciente) REFERENCES pacientes(id_paciente)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_pc_cuidador
        FOREIGN KEY (id_cuidador) REFERENCES cuidadores(id_cuidador)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Tabla de recetas medicas
CREATE TABLE IF NOT EXISTS recetas (
    id_receta INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_paciente INT UNSIGNED NOT NULL,
    fecha_emision DATE NOT NULL,
    observaciones TEXT NULL,
    CONSTRAINT fk_recetas_paciente
        FOREIGN KEY (id_paciente) REFERENCES pacientes(id_paciente)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Tabla de medicamentos asociados a recetas
CREATE TABLE IF NOT EXISTS medicamentos (
    id_medicamento INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_receta INT UNSIGNED NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    dosis VARCHAR(100) NOT NULL,
    frecuencia_horas INT NOT NULL,
    duracion_dias INT NOT NULL,
    hora_inicio TIME NOT NULL,
    via_administracion VARCHAR(50) NOT NULL,
    indicaciones TEXT NULL,
    CONSTRAINT fk_medicamentos_receta
        FOREIGN KEY (id_receta) REFERENCES recetas(id_receta)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Historial de tomas y omisiones
CREATE TABLE IF NOT EXISTS historial_tomas (
    id_toma INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_medicamento INT UNSIGNED NOT NULL,
    fecha_hora DATETIME NOT NULL,
    estado ENUM('tomado', 'no_tomado') NOT NULL,
    observaciones TEXT NULL,
    CONSTRAINT fk_historial_medicamento
        FOREIGN KEY (id_medicamento) REFERENCES medicamentos(id_medicamento)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Bitacora de reportes de adherencia
CREATE TABLE IF NOT EXISTS reportes (
    id_reporte INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_paciente INT UNSIGNED NOT NULL,
    id_medicamento INT UNSIGNED NULL,
    fecha DATETIME NOT NULL,
    generado_por ENUM('sistema', 'cuidador', 'paciente') NOT NULL,
    CONSTRAINT fk_reportes_paciente
        FOREIGN KEY (id_paciente) REFERENCES pacientes(id_paciente)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_reportes_medicamento
        FOREIGN KEY (id_medicamento) REFERENCES medicamentos(id_medicamento)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Recuperacion de contraseñas por token temporal
CREATE TABLE IF NOT EXISTS recuperacion_password (
    id_recuperacion INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT UNSIGNED NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    fecha_solicitud DATETIME NOT NULL,
    fecha_expiracion DATETIME NOT NULL,
    usado TINYINT(1) DEFAULT 0,
    CONSTRAINT fk_recuperacion_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Cola de notificaciones y recordatorios
CREATE TABLE IF NOT EXISTS notificaciones (
    id_notificacion INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_medicamento INT UNSIGNED NOT NULL,
    id_toma INT UNSIGNED NULL,
    fecha_hora_programada DATETIME NOT NULL,
    pospuesta_veces INT DEFAULT 0,
    estado_alerta ENUM('pendiente', 'pospuesta', 'atendida', 'vencida') NOT NULL,
    notificar_cuidador TINYINT(1) DEFAULT 0,
    CONSTRAINT fk_notificaciones_medicamento
        FOREIGN KEY (id_medicamento) REFERENCES medicamentos(id_medicamento)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_notificaciones_toma
        FOREIGN KEY (id_toma) REFERENCES historial_tomas(id_toma)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
