# Capa de Modelos de Datos (`models/`)

Este directorio contiene las 9 clases de acceso a datos (DAO / Active Record ligero) alineadas con las 10 tablas de la arquitectura relacional definida en [update_models.md](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/docs/update_models.md) para la base de datos `seguimiento_medicamentos`. Todas las clases reciben una instancia `PDO` en su constructor `__construct($db)`.

---

## Modelos y Contratos de Interfaz

### 1. [Usuario.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/models/Usuario.php)
Representa la tabla `usuarios`.
- **Propiedades públicas**: `$id_usuario`, `$correo`, `$password`, `$rol`, `$fecha_registro`.
- **Métodos**:
  - `public function registrar()`: Sanitiza `$correo` y `$rol`, encripta `$password` con `PASSWORD_BCRYPT`, inserta el registro en `usuarios`, asigna `$this->id_usuario = $this->conn->lastInsertId()` y retorna `bool`.
  - `public function emailExiste()`: Consulta por `$this->correo`. Si existe, hidrata `$id_usuario`, `$password` (hash) y `$rol`, y retorna `true`; en caso contrario retorna `false`.
  - `public function login($password_ingresada)`: Verifica `$password_ingresada` contra `$this->password` usando `password_verify()`. Retorna `bool`.
  - `public function actualizarPassword($nuevo_password)`: Encripta `$nuevo_password` con `PASSWORD_BCRYPT` y actualiza la columna `password` para `$this->id_usuario`. Retorna `bool`.
  - `public function obtenerPasswordActual()`: Retorna el hash `string|null` almacenado en la base de datos para `$this->id_usuario`.

---

### 2. [RecuperacionPassword.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/models/RecuperacionPassword.php)
Representa la tabla `recuperacion_password`.
- **Propiedades públicas**: `$id_recuperacion`, `$id_usuario`, `$token`, `$fecha_solicitud`, `$fecha_expiracion`, `$usado`.
- **Métodos**:
  - `public function crear(int $minutosValidez = 60)`: Genera un token aleatorio seguro (`bin2hex(random_bytes(32))`) si no fue provisto, calcula `fecha_solicitud` y `fecha_expiracion` en horario PHP, inserta con `usado = 0` y asigna `$this->id_recuperacion`. Retorna `bool`.
  - `public function validarToken(string $token): bool`: Consulta si el token existe, tiene `usado = 0` y `fecha_expiracion >= :ahora` (`date('Y-m-d H:i:s')`). Si es válido, hidrata las propiedades de la instancia y retorna `true`.
  - `public function marcarComoUsado(): bool`: Actualiza `usado = 1` para `$this->id_recuperacion`. Retorna `bool`.

---

### 3. [Paciente.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/models/Paciente.php)
Representa la tabla `pacientes`.
- **Propiedades públicas**: `$id_paciente`, `$id_usuario`, `$nombre`, `$apellido_paterno`, `$apellido_materno`, `$telefono`.
- **Métodos**:
  - `public function crear()`: Sanitiza las propiedades de texto e inserta el perfil del paciente vinculado a `$this->id_usuario`. Retorna `bool`.
  - `public function leerPorUsuario()`: Realiza un `JOIN` entre `pacientes` y `usuarios` filtrando por `$this->id_usuario` (`LIMIT 0,1`). Retorna `PDOStatement`.

---

### 4. [Cuidador.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/models/Cuidador.php)
Gestiona las tablas `cuidadores` y `paciente_cuidador`.
- **Propiedades públicas**: `$id_cuidador`, `$id_usuario`, `$id_relacion`, `$nombre`, `$apellido_paterno`, `$apellido_materno`, `$parentesco`, `$telefono`.
- **Métodos**:
  - `public function crear()`: Sanitiza propiedades, inserta en `cuidadores`, asigna `$this->id_cuidador = $this->conn->lastInsertId()` y retorna `bool`.
  - `public function asignarPaciente($id_paciente)`: Invoca `$this->darBaja($id_paciente)` para cerrar asignaciones previas y registra una nueva fila en `paciente_cuidador` con `fecha_inicio = CURDATE()`. Retorna `bool`.
  - `public function darBaja($id_paciente)`: Actualiza `fecha_fin = CURDATE()` en `paciente_cuidador` para los registros activos (`fecha_fin IS NULL`) del paciente. Retorna `bool`.
  - `public function obtenerActivo($id_paciente)`: Ejecuta un `JOIN` entre `cuidadores`, `paciente_cuidador` y `usuarios` donde `pc.id_paciente = ? AND pc.fecha_fin IS NULL`, retornando `c.*`, `u.correo` y `pc.fecha_inicio`. Retorna `array|false` (`PDO::FETCH_ASSOC`).
  - `public function obtenerHistorial($id_paciente)`: Consulta los cuidadores asociados a `$id_paciente` proyectando `c.nombre`, `c.apellido_paterno`, `pc.fecha_inicio` y `pc.fecha_fin`, ordenados por `fecha_inicio DESC`. Retorna `PDOStatement`.

---

### 5. [Receta.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/models/Receta.php)
Representa la tabla `recetas`.
- **Propiedades públicas**: `$id_receta`, `$id_paciente`, `$fecha_emision`, `$observaciones`.
- **Métodos**:
  - `public function crear()`: Inserta una receta para `$this->id_paciente` con fecha actual `date('Y-m-d')` y observación por defecto `"Registro individual de medicamento"`. Retorna el ID insertado (`string`) en caso de éxito o `false` si falla.

---

### 6. [Medicamento.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/models/Medicamento.php)
Representa la tabla `medicamentos`.
- **Propiedades públicas**: `$id_medicamento`, `$id_receta`, `$nombre`, `$dosis`, `$frecuencia_horas`, `$duracion_dias`, `$hora_inicio`, `$via_administracion`, `$indicaciones`.
- **Métodos**:
  - `public function perteneceAPaciente(int $id_medicamento, int $id_paciente): bool`: Verifica mediante `JOIN recetas` si el medicamento pertenece al paciente indicado.
  - `public function crear()`: Sanitiza campos de texto, inserta un nuevo medicamento asociado a `$this->id_receta`, asigna `$this->id_medicamento = $this->conn->lastInsertId()` y retorna `bool`.
  - `public function leerPorPaciente($id_paciente)`: Obtiene todos los medicamentos de un paciente uniendo con `recetas`. Retorna `PDOStatement`.
  - `public function leerUno(?int $id_paciente = null)`: Si `$id_paciente` no es nulo, verifica pertenencia con `perteneceAPaciente()`. Hidrata las propiedades de la instancia desde la BD y retorna `bool`.
  - `public function actualizar(?int $id_paciente = null)`: Si `$id_paciente` no es nulo, verifica pertenencia. Sanitiza campos y ejecuta `UPDATE` por `$this->id_medicamento`. Retorna `bool`.
  - `public function eliminar(?int $id_paciente = null)`: Si `$id_paciente` no es nulo, verifica pertenencia. Ejecuta `DELETE` por `$this->id_medicamento`. Retorna `bool`.

---

### 7. [HistorialToma.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/models/HistorialToma.php)
Representa la tabla `historial_tomas`.
- **Propiedades públicas**: `$id_toma`, `$id_medicamento`, `$fecha_hora`, `$estado`, `$observaciones`.
- **Métodos**:
  - `public function registrar()`: Sanitiza `$this->observaciones`, inserta una toma con `$this->fecha_hora = date('Y-m-d H:i:s')`, asigna `$this->id_toma = $this->conn->lastInsertId()` y retorna `bool`.
  - `public function leerPorPaciente($id_paciente)`: Consulta el historial de tomas haciendo `JOIN` con `medicamentos` y `recetas` para un `$id_paciente`, ordenado por `fecha_hora DESC`. Retorna `PDOStatement`.
  - `public function obtenerTomasHoyPorPaciente(int $id_paciente): array`: Consulta las tomas registradas en la fecha actual (`DATE(h.fecha_hora) = date('Y-m-d')`) ordenadas cronológicamente (`ASC`) y retorna `array<int, string>` mapeando `id_medicamento => estado` (conservando el último estado registrado hoy por cada medicamento).
  - `public function contarDiasTomadosPorPaciente(int $id_paciente): array`: Cuenta los días distintos con estado `'tomado'` por medicamento y retorna `array<int, int>` mapeando `id_medicamento => dias_tomados`.

---

### 8. [Notificacion.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/models/Notificacion.php)
Representa la tabla `notificaciones`.
- **Propiedades públicas**: `$id_notificacion`, `$id_medicamento`, `$id_toma`, `$fecha_hora_programada`, `$pospuesta_veces`, `$estado_alerta`, `$notificar_cuidador`.
- **Métodos**:
  - `public function crear(): bool`: Inserta una alerta programada (soportando `id_toma = NULL`), asigna `$this->id_notificacion = $this->conn->lastInsertId()` y retorna `bool`.
  - `public function posponerPorMedicamento(int $id_medicamento, int $minutos = 5): bool`: Busca la alerta activa (`'pendiente'` o `'pospuesta'`) más reciente del medicamento. Si existe, actualiza `fecha_hora_programada` a `$minutos` desde la hora actual del servidor (`time() + ($minutos * 60)`), incrementa `pospuesta_veces` y cambia `estado_alerta` a `'pospuesta'` (sin mutar propiedades en la instancia en memoria). Si no existe alerta previa, crea una nueva pospuesta mediante `crear()`.
  - `public function resolverPorMedicamento(int $id_medicamento, int $id_toma): bool`: Vincula la notificación activa del medicamento con el registro `$id_toma` de `historial_tomas` y actualiza `estado_alerta = 'atendida'`. Retorna `false` si no existe ninguna alerta `'pendiente'` o `'pospuesta'`.
  - `public function leerPendientesPorPaciente(int $id_paciente)`: Obtiene todas las notificaciones en estado `'pendiente'` o `'pospuesta'` de un paciente ordenadas por `fecha_hora_programada ASC`. Retorna `PDOStatement`.

---

### 9. [Reporte.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/models/Reporte.php)
Representa la tabla `reportes`.
- **Propiedades públicas**: `$id_reporte`, `$id_paciente`, `$id_medicamento`, `$fecha`, `$generado_por`.
- **Métodos**:
  - `public function crear()`: Inserta un registro de reporte soportando `id_medicamento = NULL` (`PDO::PARAM_NULL` para reportes generales de todo el tratamiento); si `generado_por` no pertenece al `ENUM('sistema','cuidador','paciente')`, asigna `'sistema'` por defecto. Asigna `$this->id_reporte = $this->conn->lastInsertId()` y retorna `bool`.
  - `public function leerPorPaciente(int $id_paciente)`: Consulta los reportes generados para un paciente con `LEFT JOIN medicamentos`. Retorna `PDOStatement`.
