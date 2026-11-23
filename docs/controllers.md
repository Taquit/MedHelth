# Controladores de Aplicación (`controllers/`)

Este directorio contiene los puntos de entrada HTTP que procesan formularios (`POST`) y acciones de estado (`GET`), coordinando la autenticación (`includes/auth.php`), utilidades (`includes/helpers.php`) y modelos (`models/`).

## Contratos de Endpoints

### 1. Autenticación, Recuperación y Registro de Usuarios

- **[login.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/login.php)**
  - **Método**: `POST` (`email`, `password`).
  - **Efectos secundarios**: Verifica credenciales con `Usuario::emailExiste()` y `Usuario::login()`. Si son válidas, inicializa `$_SESSION['id_usuario']`, `$_SESSION['correo']`, `$_SESSION['rol']` y `$_SESSION['nombre_usuario']` (si tiene perfil de paciente) y redirige a `../views/dashboard.php` (asume usuarios con perfil de paciente; un usuario con rol `'cuidador'` que ingrese operará sin registro en `pacientes`, con `$id_paciente = 0`). Si fallan, redirige a `../views/login.html?error=credenciales` o `?error=no_registrado`.

- **[logout.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/logout.php)**
  - **Método**: `GET` / `POST`.
  - **Efectos secundarios**: Destruye la sesión activa (`session_destroy()`) y redirige a `../views/login.html`.

- **[registrar_paciente.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/registrar_paciente.php)**
  - **Método**: `POST` (`nombres`, `paterno`, `materno`, `email`, `password`, `telefono` opcional).
  - **Efectos secundarios**: Si el correo existe, redirige a `../views/registro_paciente.html?error=correo_duplicado`. Crea el registro en `usuarios` (rol `'paciente'`) y en `pacientes` (sin transacción PDO explícita entre ambas tablas), inicia automáticamente la sesión (`id_usuario`, `correo`, `rol`, `nombre_usuario`) y redirige a `../views/pregunta_cuidador.html?nombre=...`. En errores de inserción o campos vacíos redirige a `../views/registro_paciente.html?error=registro`.

- **[solicitar_recuperacion.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/solicitar_recuperacion.php)**
  - **Método**: `POST` (`email`).
  - **Efectos secundarios**: Carga `includes/helpers.php` (zona horaria `America/Mexico_City`), verifica si el correo existe con `Usuario::emailExiste()`, genera un token temporal en `recuperacion_password` mediante `RecuperacionPassword::crear(60)` (capturando silenciosamente `PDOException` si la tabla no existe aún) y redirige siempre a `../views/login.html?recuperacion=enviada` (anti-enumeración). *Nota*: El envío real de correo SMTP y la vista/controlador para consumir el token y restablecer la contraseña aún no están implementados.

- **[procesar_cambio_password.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/procesar_cambio_password.php)**
  - **Método**: `POST` (`current_password`, `new_password`, `confirm_password` opcional). Requiere sesión activa (`requireLogin('../views/login.html')`).
  - **Efectos secundarios**: Valida coincidencia entre `new_password` y `confirm_password` (`?error=no_coincide`), verifica la contraseña actual con `Usuario::obtenerPasswordActual()` (`?error=actual_incorrecta`), actualiza el hash con `Usuario::actualizarPassword()` y redirige a `../views/perfil.php?exito=password`. Si faltan campos obligatorios o falla `actualizarPassword()`, redirige a `../views/cambiar_contrasena.php?error=error_actualizar`.

---

### 2. Gestión de Cuidadores

- **[registrar_cuidador.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/registrar_cuidador.php)**
  - **Método**: `POST` (`nombre`, `paterno`, `materno`, `parentesco`, `telefono`, `email`, `password`, `origen` opcional `'inicial'|'alta'`). Requiere sesión activa (`requireLogin('../views/login.html')`). En servidor valida explícitamente no vacíos `email`, `password` y `nombre`; el resto cuenta con `required` en HTML.
  - **Efectos secundarios**: Ejecuta una transacción PDO que crea el usuario con rol `'cuidador'`, crea el perfil en `cuidadores` y lo vincula al paciente actual mediante `Cuidador::asignarPaciente()`. Si falla o el correo existe, redirige a la vista de origen (`registro_cuidador_inicial.html` o `alta_cuidador.php`) con `?error=correo_duplicado` o `?error=registro`. En éxito redirige a `../views/dashboard.php` (si `origen=inicial`) o `../views/perfil_cuidador.php?exito=1` (si `origen=alta`).

- **[procesar_baja_cuidador.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/procesar_baja_cuidador.php)**
  - **Método**: `GET`. Requiere sesión activa (`requireLogin('../views/login.html')`).
  - **Efectos secundarios**: Obtiene el paciente actual e invoca `Cuidador::darBaja($id_paciente)` para cerrar la relación activa con fecha fin del día actual. Redirige a `../views/perfil_cuidador.php?exito=baja` (o `?error=baja`).

---

### 3. Gestión de Medicamentos

- **[guardar_medicamento.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/guardar_medicamento.php)**
  - **Método**: `POST` (`nombre`, `dosis`, `via`, `hora_inicio`, `frecuencia_cant`, `frecuencia_unidad`, `duracion_cant`, `duracion_unidad`, `indicaciones`). Requiere sesión activa (`requireLogin('../views/login.html')`).
  - **Efectos secundarios**: Crea una receta base con `Receta::crear()`, normaliza la frecuencia a horas (`convertirFrecuenciaAHoras()`) y la duración a días (`convertirDuracionADias()`), inserta el medicamento con `Medicamento::crear()` y programa la alerta inicial del día (`date('Y-m-d') . ' ' . $medicamento->hora_inicio`) en `notificaciones` mediante `Notificacion::crear()` (capturando `PDOException` si la tabla no existe). Redirige a `../views/medicamentos.php`; ante fallo de paciente o inserción aborta con `die()` / `echo`.

- **[actualizar_medicamento.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/actualizar_medicamento.php)**
  - **Método**: `POST` (`id_medicamento`, `nombre`, `dosis`, `via`, `hora_inicio`, `frecuencia_cant`, `frecuencia_unidad`, `duracion_cant`, `duracion_unidad`, `indicaciones`). Requiere sesión activa (`requireLogin('../views/login.html')`).
  - **Efectos secundarios**: Convierte unidades y actualiza el registro validando pertenencia (`Medicamento::actualizar($id_paciente)`). Redirige a `../views/medicamentos.php`, o imprime error plano si falla la actualización.

- **[eliminar_medicamento.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/eliminar_medicamento.php)**
  - **Método**: `GET` (`id`). Requiere sesión activa (`requireLogin('../views/login.html')`).
  - **Efectos secundarios**: Elimina el medicamento si pertenece al paciente autenticado (`Medicamento::eliminar($id_paciente)`) y redirige a `../views/medicamentos.php`, o imprime error plano si falla la eliminación.

---

### 4. Seguimiento de Tomas, Notificaciones y Reportes PDF

- **[registrar_toma.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/registrar_toma.php)**
  - **Método**: `GET` (`id`, `status` `'tomado'|'no_tomado'`) y opcionalmente `POST` (`motivo` opcional). Requiere sesión activa (`requireLogin('../views/login.html')`).
  - **Efectos secundarios**: Verifica pertenencia del medicamento con `Medicamento::leerUno($id_paciente)`, inserta el registro en `historial_tomas` mediante `HistorialToma::registrar()` (usando `'Registro rápido desde panel'` como observación por defecto cuando `status === 'tomado'` vía GET), resuelve la alerta activa vinculando `id_toma` mediante `Notificacion::resolverPorMedicamento()` (silenciando `PDOException` si la tabla no existe) y redirige a `../views/notificacion_tomada.html?nombre=...&medicamento=...&hora=...` (si `status === 'tomado'`) o a `../views/dashboard.php` (si `status === 'no_tomado'`).

- **[posponer_toma.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/posponer_toma.php)**
  - **Método**: `GET` (`id`). Requiere sesión activa (`requireLogin('../views/login.html')`).
  - **Efectos secundarios**: Verifica pertenencia del medicamento (`Medicamento::perteneceAPaciente()`); si falta `id` o no pertenece al paciente, redirige a `../views/dashboard.php`. Si es válido, pospone 5 minutos a partir de la hora actual en `notificaciones` (`Notificacion::posponerPorMedicamento($id_medicamento, 5)`) y redirige a `../views/notificacion_posponer.html?hora=...`.

- **[generar_pdf.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/generar_pdf.php)**
  - **Método**: `GET` (`id_medicamento` opcional validado con `Medicamento::perteneceAPaciente()`). Requiere sesión activa (`requireLogin('../views/login.html')`).
  - **Efectos secundarios**: Registra la bitácora del reporte en `reportes` mediante `Reporte::crear()` (asociando `id_medicamento` solo a efectos de bitácora si pertenece al paciente, y silenciando `PDOException` si la BD aún no permite `NULL`); el documento PDF siempre incluye todo el historial del paciente (`HistorialToma::leerPorPaciente()`), construido con `FPDF` (usando `utf8_decode()`) y descargado como `Reporte_MedHealth.pdf`.
