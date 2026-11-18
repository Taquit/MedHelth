# Controladores de Aplicación (`controllers/`)

Este directorio contiene los puntos de entrada HTTP que procesan formularios (`POST`) y acciones de estado (`GET`), coordinando la autenticación (`includes/auth.php`), utilidades (`includes/helpers.php`) y modelos (`models/`).

## Contratos de Endpoints

### 1. Autenticación, Recuperación y Registro de Usuarios

- **[login.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/login.php)**
  - **Método**: `POST` (`email`, `password`).
  - **Efectos secundarios**: Verifica credenciales con `Usuario::emailExiste()` y `Usuario::login()`. Si son válidas, inicializa `$_SESSION['id_usuario']`, `$_SESSION['correo']` y `$_SESSION['rol']` y redirige a `../views/dashboard.php`. Si fallan, muestra alerta JS y redirige a `../views/login.html`.

- **[logout.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/logout.php)**
  - **Método**: `GET` / `POST`.
  - **Efectos secundarios**: Destruye la sesión activa (`session_destroy()`) y redirige a `../views/login.html`.

- **[registrar_paciente.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/registrar_paciente.php)**
  - **Método**: `POST` (`nombres`, `paterno`, `materno`, `email`, `password`, `telefono` opcional).
  - **Efectos secundarios**: Crea el registro en `usuarios` (rol `'paciente'`) y en `pacientes`, inicia automáticamente la sesión del nuevo paciente y redirige a `../views/pregunta_cuidador.html`.

- **[solicitar_recuperacion.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/solicitar_recuperacion.php)**
  - **Método**: `POST` (`email`).
  - **Efectos secundarios**: Verifica si el correo existe con `Usuario::emailExiste()` y genera un token temporal en `recuperacion_password` mediante `RecuperacionPassword::crear(60)`. Redirige a `../views/login.html`.

- **[procesar_cambio_password.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/procesar_cambio_password.php)**
  - **Método**: `POST` (`current_password`, `new_password`). Requiere sesión activa (`requireLogin('../views/login.html')`).
  - **Efectos secundarios**: Verifica la contraseña actual con `Usuario::obtenerPasswordActual()` y `password_verify()`. Si coincide, actualiza el hash con `Usuario::actualizarPassword()` y redirige a `../views/perfil.php`.

---

### 2. Gestión de Cuidadores

- **[registrar_cuidador.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/registrar_cuidador.php)**
  - **Método**: `POST` (`nombre`, `paterno`, `materno`, `parentesco`, `telefono`, `email`, `password`). Requiere sesión activa (`requireLogin('../views/login.html')`).
  - **Efectos secundarios**: Ejecuta una transacción PDO que crea el usuario con rol `'cuidador'`, crea el perfil en `cuidadores` y lo vincula al paciente actual mediante `Cuidador::asignarPaciente()`. Redirige a `../views/perfil.php`.

- **[procesar_baja_cuidador.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/procesar_baja_cuidador.php)**
  - **Método**: `GET`. Requiere sesión activa (`requireLogin('../views/login.html')`).
  - **Efectos secundarios**: Obtiene el paciente actual e invoca `Cuidador::darBaja($id_paciente)` para cerrar la relación activa con fecha fin del día actual. Redirige a `../views/perfil.php`.

---

### 3. Gestión de Medicamentos

- **[guardar_medicamento.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/guardar_medicamento.php)**
  - **Método**: `POST` (`nombre`, `dosis`, `via`, `hora_inicio`, `frecuencia_cant`, `frecuencia_unidad`, `duracion_cant`, `duracion_unidad`, `indicaciones`). Requiere sesión activa (`requireLogin('../views/login.html')`).
  - **Efectos secundarios**: Crea una receta base con `Receta::crear()`, normaliza la frecuencia a horas (`convertirFrecuenciaAHoras()`) y la duración a días (`convertirDuracionADias()`), inserta el medicamento con `Medicamento::crear()` y programa su primera alerta en `notificaciones` mediante `Notificacion::crear()`. Redirige a `../views/medicamentos.php`.

- **[actualizar_medicamento.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/actualizar_medicamento.php)**
  - **Método**: `POST` (`id_medicamento`, `nombre`, `dosis`, `via`, `hora_inicio`, `frecuencia_cant`, `frecuencia_unidad`, `duracion_cant`, `duracion_unidad`, `indicaciones`). Requiere sesión activa (`requireLogin('../views/login.html')`).
  - **Efectos secundarios**: Convierte unidades y actualiza el registro validando que pertenezca al paciente en sesión (`Medicamento::actualizar($id_paciente)`). Redirige a `../views/medicamentos.php`.

- **[eliminar_medicamento.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/eliminar_medicamento.php)**
  - **Método**: `GET` (`id`). Requiere sesión activa (`requireLogin('../views/login.html')`).
  - **Efectos secundarios**: Elimina el medicamento si pertenece al paciente autenticado (`Medicamento::eliminar($id_paciente)`) y redirige a `../views/medicamentos.php`.

---

### 4. Seguimiento de Tomas, Notificaciones y Reportes PDF

- **[registrar_toma.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/registrar_toma.php)**
  - **Método**: `GET` (`id`, `status`) y opcionalmente `POST` (`motivo`). Requiere sesión activa (`requireLogin('../views/login.html')`).
  - **Efectos secundarios**: Verifica que el medicamento pertenezca al paciente (`Medicamento::perteneceAPaciente()`), inserta el registro en `historial_tomas` mediante `HistorialToma::registrar()`, resuelve la alerta activa vinculando `id_toma` mediante `Notificacion::resolverPorMedicamento()` y redirige a `../views/notificacion_tomada.html` (si `status === 'tomado'`) o a `../views/dashboard.php` (si `status === 'no_tomado'`).

- **[posponer_toma.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/posponer_toma.php)**
  - **Método**: `GET` (`id`). Requiere sesión activa (`requireLogin('../views/login.html')`).
  - **Efectos secundarios**: Verifica pertenencia del medicamento (`Medicamento::perteneceAPaciente()`), pospone 5 minutos la alerta en la tabla `notificaciones` (`Notificacion::posponerPorMedicamento($id_medicamento, 5)`) y redirige a `../views/notificacion_posponer.html`.

- **[generar_pdf.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/generar_pdf.php)**
  - **Método**: `GET` (`id_medicamento` opcional). Requiere sesión activa (`requireLogin('../views/login.html')`).
  - **Efectos secundarios**: Registra la bitácora del reporte en la tabla `reportes` mediante `Reporte::crear()` (soportando `id_medicamento = NULL` y `generado_por` según el rol en sesión), consulta el historial de tomas (`HistorialToma::leerPorPaciente()`) y fuerza la descarga del documento `Reporte_CuidatePlus.pdf`.
