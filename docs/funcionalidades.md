# Catálogo de Funcionalidades de la Plataforma MedHealth (`care_sis`)

Este documento detalla todas las funcionalidades disponibles en la plataforma **MedHealth**, organizadas por módulo funcional, indicando los actores involucrados, las reglas de negocio y los archivos del sistema (`views/`, `controllers/`, `models/`, `includes/`) que las implementan.

---

## 1. Módulo de Autenticación, Registro y Seguridad

Gestiona el ciclo de vida de las cuentas de usuario, el control de sesiones y la protección de datos personales.

### 1.1. Registro de Paciente (Onboarding Inicial)
- **Descripción**: Permite a un usuario nuevo crear su cuenta principal con rol de `paciente`, almacenando sus datos personales (nombre, apellidos, teléfono) y sus credenciales de acceso con contraseña encriptada mediante `BCRYPT`.
- **Flujo funcional**:
  1. El usuario accede desde [index.html](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/index.html) a [views/registro_paciente.html](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/registro_paciente.html).
  2. Al enviar el formulario, [controllers/registrar_paciente.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/registrar_paciente.php) valida que el correo no esté duplicado (`Usuario::emailExiste()`).
  3. Se crea la cuenta en la tabla `usuarios` (`Usuario::registrar()`) y el perfil en `pacientes` (`Paciente::crear()`).
  4. El sistema inicia automáticamente la sesión del paciente y lo dirige al asistente de vinculación de cuidador ([views/pregunta_cuidador.html](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/pregunta_cuidador.html)), desde donde puede elegir registrar un cuidador inmediatamente ([views/registro_cuidador_inicial.html](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/registro_cuidador_inicial.html)) o pasar directo al panel principal ([views/dashboard.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/dashboard.php)).

### 1.2. Inicio y Cierre de Sesión
- **Descripción**: Autentica a usuarios registrados y protege todas las vistas internas mediante sesiones de PHP.
- **Componentes**:
  - **Login**: [views/login.html](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/login.html) envía credenciales a [controllers/login.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/login.php), el cual verifica el hash de la contraseña (`Usuario::login()`) e inicializa `$_SESSION['id_usuario']`, `$_SESSION['correo']` y `$_SESSION['rol']`.
  - **Protección de rutas**: [includes/auth.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/includes/auth.php) expone `requireLogin()`, bloqueando el acceso no autenticado a cualquier vista `.php` o controlador privado.
  - **Logout**: [controllers/logout.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/logout.php) destruye la sesión activa y redirige a la pantalla de inicio de sesión.

### 1.3. Cambio de Contraseña
- **Descripción**: Permite al usuario autenticado actualizar su contraseña desde su perfil validando primero su contraseña actual.
- **Componentes**: [views/cambiar_contrasena.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/cambiar_contrasena.php), [controllers/procesar_cambio_password.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/procesar_cambio_password.php) y `Usuario::actualizarPassword()` en [models/Usuario.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/models/Usuario.php).

### 1.4. Solicitud de Recuperación de Contraseña
- **Descripción**: Genera un token criptográfico de un solo uso con vigencia temporal (60 minutos por defecto) en la tabla `recuperacion_password` para usuarios registrados y muestra confirmación estándar anti-enumeración en pantalla. *(Estado actual: genera y almacena el token en BD mediante `RecuperacionPassword::crear(60)`; el envío de correo electrónico y la pantalla para consumir el token y restablecer la contraseña quedan pendientes para integración con servidor SMTP).*
- **Componentes**: [views/olvide_contrasena.html](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/olvide_contrasena.html), [controllers/solicitar_recuperacion.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/solicitar_recuperacion.php) y [models/RecuperacionPassword.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/models/RecuperacionPassword.php).

### 1.5. Consulta de Términos, Condiciones y Aviso de Privacidad
- **Descripción**: Presenta el descargo de responsabilidad médica, políticas de privacidad de datos y responsabilidades del usuario.
- **Componente**: [views/terminos.html](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/terminos.html).

---

## 2. Módulo de Gestión de Medicamentos y Recetas

Permite administrar el catálogo completo de fármacos prescritos al paciente, normalizando unidades de tiempo y garantizando que cada paciente solo pueda acceder o modificar sus propios medicamentos (protección contra IDOR).

### 2.1. Alta de Medicamentos y Creación Automática de Receta
- **Descripción**: Registra un medicamento con su nombre, dosis, vía de administración, hora de primera toma, frecuencia, duración e indicaciones específicas.
- **Reglas de negocio**:
  - Genera automáticamente un registro contenedor en la tabla `recetas` ([models/Receta.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/models/Receta.php)).
  - Convierte la frecuencia ingresada (minutos u horas) a horas enteras (`convertirFrecuenciaAHoras()` en [includes/helpers.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/includes/helpers.php)).
  - Convierte la duración del tratamiento (días, semanas o meses) a días enteros (`convertirDuracionADias()` en [includes/helpers.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/includes/helpers.php)).
  - Programa la primera notificación en estado `pendiente` en la tabla `notificaciones` ([models/Notificacion.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/models/Notificacion.php)).
- **Componentes**: [views/agregar_medicamento.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/agregar_medicamento.php) y [controllers/guardar_medicamento.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/guardar_medicamento.php).

### 2.2. Consulta del Catálogo de Medicamentos
- **Descripción**: Muestra en tarjetas todos los medicamentos asociados al paciente autenticado con su dosis, vía de administración, progreso, duración en días e indicaciones.
- **Componentes**: [views/medicamentos.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/medicamentos.php) y `Medicamento::leerPorPaciente()` en [models/Medicamento.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/models/Medicamento.php).

### 2.3. Edición de Medicamentos
- **Descripción**: Permite modificar cualquier atributo de un medicamento existente. Desglosa automáticamente los días almacenados en unidades legibles (días, semanas o meses) mediante `desglosarDuracionDias()`.
- **Seguridad**: Verifica que el medicamento pertenezca al paciente en sesión mediante `Medicamento::perteneceAPaciente()` antes de cargar el formulario o ejecutar el `UPDATE`.
- **Componentes**: [views/editar_medicamento.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/editar_medicamento.php) y [controllers/actualizar_medicamento.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/actualizar_medicamento.php).

### 2.4. Eliminación de Medicamentos
- **Descripción**: Elimina un medicamento previa confirmación del usuario y validación de pertenencia al paciente activo.
- **Componentes**: [controllers/eliminar_medicamento.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/eliminar_medicamento.php) y `Medicamento::eliminar()` en [models/Medicamento.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/models/Medicamento.php).

---

## 3. Módulo de Seguimiento Diario, Tomas y Notificaciones (Dashboard)

Centraliza el control diario de adherencia terapéutica y la respuesta ante alertas de medicación.

### 3.1. Panel del Día (Dashboard)
- **Descripción**: Muestra el saludo personalizado al paciente según la hora (`obtenerSaludoHora()`), la fecha actual formateada en español (`obtenerFechaActualEspanol()`) y la lista de todos los medicamentos registrados del paciente (`Medicamento::leerPorPaciente()`, sin filtrado automático por fecha de vencimiento) con tres acciones rápidas de confirmación cuando la toma del día está pendiente, o un chip de estado `"Tomado"` cuando ya se registró una toma en la fecha actual (`obtenerTomasHoyPorPaciente()`).
- **Componente**: [views/dashboard.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/dashboard.php).

### 3.2. Confirmación de Toma Realizada ("Se tomó")
- **Descripción**: Registra inmediatamente en `historial_tomas` que el medicamento fue ingerido (`estado = 'tomado'`), marca la alerta activa en `notificaciones` como `'atendida'` vinculando el `id_toma` (`Notificacion::resolverPorMedicamento()`), y presenta la pantalla de confirmación exitosa.
- **Componentes**: [controllers/registrar_toma.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/registrar_toma.php), [models/HistorialToma.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/models/HistorialToma.php), [models/Notificacion.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/models/Notificacion.php) y [views/notificacion_tomada.html](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/notificacion_tomada.html).

### 3.3. Posposición de Alerta ("Posponer 5 minutos")
- **Descripción**: Reprograma la alerta activa del medicamento sumando 5 minutos a partir de la hora actual del servidor en `fecha_hora_programada`, incrementando el contador `pospuesta_veces` y actualizando el estado a `'pospuesta'` (`Notificacion::posponerPorMedicamento()`).
- **Componentes**: [controllers/posponer_toma.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/posponer_toma.php), [models/Notificacion.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/models/Notificacion.php) y [views/notificacion_posponer.html](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/notificacion_posponer.html).

### 3.4. Registro de Omisión con Motivo ("No se tomó")
- **Descripción**: Abre un formulario modal donde el paciente explica la razón por la que no ingirió la dosis. Registra el evento en `historial_tomas` con `estado = 'no_tomado'` y las observaciones ingresadas, y resuelve la notificación activa.
- **Componentes**: [views/notificacion_no_tomada.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/notificacion_no_tomada.php) y [controllers/registrar_toma.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/registrar_toma.php).

---

## 4. Módulo de Historial y Reportes de Adherencia (PDF)

Proporciona trazabilidad completa sobre el cumplimiento del tratamiento médico.

### 4.1. Consulta de Historial de Tomas
- **Descripción**: Presenta una tabla ordenada cronológicamente (de más reciente a más antigua) con todas las tomas y omisiones del paciente, indicando nombre del medicamento, fecha en español, hora, observaciones y distintivo visual de estado (`Tomado` / `No Tomado`).
- **Componentes**: [views/historial.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/historial.php) y `HistorialToma::leerPorPaciente()` en [models/HistorialToma.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/models/HistorialToma.php).

### 4.2. Generación y Descarga de Reporte en PDF
- **Descripción**: Genera un documento PDF descargable (`Reporte_MedHealth.pdf`) usando la librería `FPDF`, con cabecera MedHealth, vista previa antes de la descarga en `views/reporte.php`, nombre del paciente, fecha de emisión y tabla detallada de adherencia con código de colores por estado. Además, registra la auditoría del reporte generado en la tabla `reportes` (`Reporte::crear()`).
- **Componentes**: [views/reporte.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/reporte.php), [controllers/generar_pdf.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/generar_pdf.php), [models/Reporte.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/models/Reporte.php) y `libs/fpdf/fpdf.php`.

---

## 5. Módulo de Perfil y Administración de Cuidadores

Permite consultar los datos del paciente y gestionar el ciclo completo de asignación y baja de cuidadores responsables.

### 5.1. Visualización de Perfil del Paciente
- **Descripción**: Muestra los datos personales del paciente autenticado (nombre, apellido paterno, apellido materno y correo electrónico obtenido mediante `JOIN` con `usuarios`), junto con los accesos a cambio de contraseña y cierre de sesión.
- **Componente**: [views/perfil.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/perfil.php).

### 5.2. Alta y Asignación Transaccional de Cuidador
- **Descripción**: Permite al paciente registrar un nuevo cuidador con sus datos personales (nombre, apellidos, parentesco, teléfono) y crearle su propia cuenta de usuario (`rol = 'cuidador'`).
- **Reglas de negocio**:
  - Se ejecuta dentro de una transacción PDO (`beginTransaction()` / `commit()` / `rollBack()`).
  - Si el paciente ya tenía un cuidador activo previo, `Cuidador::asignarPaciente()` da de baja automáticamente el vínculo anterior (`fecha_fin = CURDATE()`) antes de insertar la nueva relación activa en `paciente_cuidador` con `fecha_inicio = CURDATE()`.
- **Componentes**: [views/alta_cuidador.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/alta_cuidador.php), [views/registro_cuidador_inicial.html](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/registro_cuidador_inicial.html), [controllers/registrar_cuidador.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/registrar_cuidador.php) y [models/Cuidador.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/models/Cuidador.php).

### 5.3. Consulta de Cuidador Activo e Historial de Cuidadores
- **Descripción**: Muestra los datos de contacto (incluyendo correo y teléfono) del cuidador actualmente activo (`fecha_fin IS NULL`) y una tabla inferior con el historial completo de cuidadores que han estado asignados al paciente, indicando fechas de inicio, fechas de fin y estado (`Alta` / `Baja`).
- **Componentes**: [views/perfil_cuidador.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/perfil_cuidador.php), `Cuidador::obtenerActivo()` y `Cuidador::obtenerHistorial()` en [models/Cuidador.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/models/Cuidador.php).

### 5.4. Baja de Cuidador Activo
- **Descripción**: Permite finalizar la relación con el cuidador actual previa confirmación modal, estableciendo `fecha_fin = CURDATE()` en la tabla `paciente_cuidador` y conservando el registro en el historial.
- **Componentes**: [views/baja_cuidador.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/baja_cuidador.php), [controllers/procesar_baja_cuidador.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/procesar_baja_cuidador.php) y `Cuidador::darBaja()` en [models/Cuidador.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/models/Cuidador.php).

---

## 6. Resumen de Trazabilidad Funcional

| Módulo | Funcionalidad | Vista Principal | Controlador | Modelos Involucrados |
| :--- | :--- | :--- | :--- | :--- |
| **Autenticación** | Registro de paciente | `views/registro_paciente.html` | `controllers/registrar_paciente.php` | `Usuario`, `Paciente` |
| **Autenticación** | Asistente cuidador inicial | `views/pregunta_cuidador.html`, `views/registro_cuidador_inicial.html` | `controllers/registrar_cuidador.php` | `Usuario`, `Cuidador` |
| **Autenticación** | Inicio / Cierre de sesión | `views/login.html` | `controllers/login.php`, `logout.php` | `Usuario`, `Paciente` |
| **Autenticación** | Cambio de contraseña | `views/cambiar_contrasena.php` | `controllers/procesar_cambio_password.php` | `Usuario` |
| **Autenticación** | Recuperación de contraseña | `views/olvide_contrasena.html` | `controllers/solicitar_recuperacion.php` | `Usuario`, `RecuperacionPassword` |
| **Autenticación** | Términos y privacidad | `views/terminos.html` | - | - |
| **Medicamentos** | Alta de medicamento | `views/agregar_medicamento.php` | `controllers/guardar_medicamento.php` | `Receta`, `Medicamento`, `Notificacion` |
| **Medicamentos** | Listado de medicamentos | `views/medicamentos.php` | - | `Medicamento`, `HistorialToma` |
| **Medicamentos** | Edición de medicamento | `views/editar_medicamento.php` | `controllers/actualizar_medicamento.php` | `Medicamento` |
| **Medicamentos** | Eliminación de medicamento | `views/medicamentos.php` | `controllers/eliminar_medicamento.php` | `Medicamento` |
| **Seguimiento** | Panel del día (Dashboard) | `views/dashboard.php` | - | `Paciente`, `Medicamento`, `HistorialToma` |
| **Seguimiento** | Confirmar toma realizada | `views/dashboard.php`, `views/notificacion_tomada.html` | `controllers/registrar_toma.php` | `Medicamento`, `HistorialToma`, `Notificacion` |
| **Seguimiento** | Posponer alerta 5 min | `views/dashboard.php`, `views/notificacion_posponer.html` | `controllers/posponer_toma.php` | `Medicamento`, `Notificacion` |
| **Seguimiento** | Registrar toma omitida | `views/notificacion_no_tomada.php` | `controllers/registrar_toma.php` | `Medicamento`, `HistorialToma`, `Notificacion` |
| **Historial y PDF** | Historial de tomas | `views/historial.php` | - | `HistorialToma` |
| **Historial y PDF** | Descarga de reporte PDF | `views/reporte.php` | `controllers/generar_pdf.php` | `Paciente`, `Medicamento`, `HistorialToma`, `Reporte` |
| **Perfil** | Perfil del paciente | `views/perfil.php` | - | `Paciente` |
| **Cuidadores** | Alta y asignación | `views/alta_cuidador.php` | `controllers/registrar_cuidador.php` | `Usuario`, `Cuidador` |
| **Cuidadores** | Perfil e historial | `views/perfil_cuidador.php` | - | `Cuidador` |
| **Cuidadores** | Baja de cuidador | `views/baja_cuidador.php` | `controllers/procesar_baja_cuidador.php` | `Cuidador` |
