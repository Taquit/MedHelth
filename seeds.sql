-- Datos iniciales de prueba para MedHealth (care_sis)
-- Credenciales de prueba:
--   Paciente: paciente@medhealth.com / password123
--   Cuidador: cuidador@medhealth.com / password123

USE seguimiento_medicamentos;

-- 1. Usuarios (contraseña encriptada en BCRYPT para "password123")
INSERT INTO usuarios (id_usuario, correo, password, rol, fecha_registro) VALUES
(1, 'paciente@medhealth.com', '$2y$12$6q6gPL0G7f9.kvbFKP38beE7AHEHyAd1IT7G8Zybp1GyC1m028q/q', 'paciente', NOW()),
(2, 'cuidador@medhealth.com', '$2y$12$6q6gPL0G7f9.kvbFKP38beE7AHEHyAd1IT7G8Zybp1GyC1m028q/q', 'cuidador', NOW())
ON DUPLICATE KEY UPDATE correo=VALUES(correo);

-- 2. Paciente
INSERT INTO pacientes (id_paciente, id_usuario, nombre, apellido_paterno, apellido_materno, telefono) VALUES
(1, 1, 'Maria', 'Gonzalez', 'Perez', '5512345678')
ON DUPLICATE KEY UPDATE nombre=VALUES(nombre);

-- 3. Cuidador
INSERT INTO cuidadores (id_cuidador, id_usuario, nombre, apellido_paterno, apellido_materno, parentesco, telefono) VALUES
(1, 2, 'Laura', 'Hernandez', 'Perez', 'Hija', '5587654321')
ON DUPLICATE KEY UPDATE nombre=VALUES(nombre);

-- 4. Vinculo Paciente - Cuidador activo
INSERT INTO paciente_cuidador (id_relacion, id_paciente, id_cuidador, fecha_inicio, fecha_fin) VALUES
(1, 1, 1, CURDATE(), NULL)
ON DUPLICATE KEY UPDATE fecha_inicio=VALUES(fecha_inicio);

-- 5. Receta médica
INSERT INTO recetas (id_receta, id_paciente, fecha_emision, observaciones) VALUES
(1, 1, CURDATE(), 'Tratamiento de control de presion y analgesia preventiva')
ON DUPLICATE KEY UPDATE observaciones=VALUES(observaciones);

-- 6. Medicamentos
INSERT INTO medicamentos (id_medicamento, id_receta, nombre, dosis, frecuencia_horas, duracion_dias, hora_inicio, via_administracion, indicaciones) VALUES
(1, 1, 'Losartan', '50 mg', 12, 30, '08:00:00', 'Oral', 'Tomar una tableta despues del desayuno con suficiente agua'),
(2, 1, 'Paracetamol', '500 mg', 8, 7, '14:00:00', 'Oral', 'Tomar solo en caso de dolor o molestia articular')
ON DUPLICATE KEY UPDATE nombre=VALUES(nombre);

-- 7. Historial de tomas
INSERT INTO historial_tomas (id_toma, id_medicamento, fecha_hora, estado, observaciones) VALUES
(1, 1, CONCAT(CURDATE(), ' 08:05:00'), 'tomado', 'Toma matutina registrada a tiempo')
ON DUPLICATE KEY UPDATE estado=VALUES(estado);

-- 8. Notificaciones
INSERT INTO notificaciones (id_notificacion, id_medicamento, id_toma, fecha_hora_programada, pospuesta_veces, estado_alerta, notificar_cuidador) VALUES
(1, 1, 1, CONCAT(CURDATE(), ' 08:00:00'), 0, 'atendida', 0),
(2, 2, NULL, CONCAT(CURDATE(), ' 14:00:00'), 0, 'pendiente', 0)
ON DUPLICATE KEY UPDATE estado_alerta=VALUES(estado_alerta);
