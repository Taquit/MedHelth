# Capa de Vistas (`views/` e `index.html`)

Todas las pantallas de interfaz de usuario se encuentran organizadas dentro del directorio `views/`, dejando únicamente [index.html](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/index.html) en la raíz del proyecto como punto de entrada principal del servidor web.

---

## 1. Punto de Entrada en Raíz (`/`)
- **[index.html](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/index.html)**: Pantalla de bienvenida que redirige a [views/registro_paciente.html](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/registro_paciente.html) o [views/login.html](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/login.html).

---

## 2. Vistas Públicas (`views/*.html`)
- **[views/login.html](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/login.html)**: Formulario de inicio de sesión (`POST` a `../controllers/login.php`).
- **[views/registro_paciente.html](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/registro_paciente.html)**: Formulario de registro de nuevo paciente (`POST` a `../controllers/registrar_paciente.php`).
- **[views/pregunta_cuidador.html](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/pregunta_cuidador.html)**: Paso posterior al alta de paciente para elegir si desea registrar un cuidador (`views/registro_cuidador_inicial.html`) o ir directo al panel (`views/dashboard.php`).
- **[views/registro_cuidador_inicial.html](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/registro_cuidador_inicial.html)**: Formulario de alta inicial de cuidador (`POST` a `../controllers/registrar_cuidador.php`).
- **[views/olvide_contrasena.html](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/olvide_contrasena.html)**: Pantalla de recuperación de contraseña.
- **[views/terminos.html](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/terminos.html)**: Términos, condiciones y aviso de privacidad.
- **[views/notificacion_tomada.html](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/notificacion_tomada.html)**: Confirmación visual de medicamento ingerido con retorno a `views/dashboard.php`.
- **[views/notificacion_posponer.html](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/notificacion_posponer.html)**: Confirmación visual de alerta pospuesta 5 minutos con retorno a `views/dashboard.php`.

---

## 3. Vistas Protegidas (`views/*.php`)
Todas las vistas PHP invocan `requireLogin('login.html')` desde [includes/auth.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/includes/auth.php) y cargan hojas de estilo desde `../CSS/`.

- **[views/dashboard.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/dashboard.php)**: Panel principal con los medicamentos del paciente para el día actual y acciones hacia `../controllers/registrar_toma.php`, `../controllers/posponer_toma.php` y `notificacion_no_tomada.php`.
- **[views/notificacion_no_tomada.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/notificacion_no_tomada.php)**: Modal para registrar el motivo por el cual no se ingirió un medicamento (`POST` a `../controllers/registrar_toma.php`).
- **[views/medicamentos.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/medicamentos.php)**: Listado de medicamentos activos del paciente con enlaces a `agregar_medicamento.php`, `editar_medicamento.php` y `../controllers/eliminar_medicamento.php`.
- **[views/agregar_medicamento.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/agregar_medicamento.php)**: Formulario modal para crear un medicamento (`POST` a `../controllers/guardar_medicamento.php`).
- **[views/editar_medicamento.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/editar_medicamento.php)**: Formulario modal precargado para actualizar un medicamento perteneciente al paciente (`POST` a `../controllers/actualizar_medicamento.php`).
- **[views/historial.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/historial.php)**: Tabla de historial de tomas del paciente y acceso a `reporte.php`.
- **[views/reporte.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/reporte.php)**: Modal de generación y descarga del reporte de adherencia en PDF (`../controllers/generar_pdf.php`).
- **[views/perfil.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/perfil.php)**: Vista de datos personales del paciente, enlace a `cambiar_contrasena.php`, pestaña `perfil_cuidador.php` y cierre de sesión (`../controllers/logout.php`).
- **[views/cambiar_contrasena.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/cambiar_contrasena.php)**: Modal para cambiar la contraseña actual (`POST` a `../controllers/procesar_cambio_password.php`).
- **[views/perfil_cuidador.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/perfil_cuidador.php)**: Datos del cuidador activo (`Cuidador::obtenerActivo()`) e historial de cuidadores (`Cuidador::obtenerHistorial()`), con enlaces a `alta_cuidador.php` y `baja_cuidador.php`.
- **[views/alta_cuidador.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/alta_cuidador.php)**: Formulario para dar de alta y asignar un nuevo cuidador (`POST` a `../controllers/registrar_cuidador.php`).
- **[views/baja_cuidador.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/views/baja_cuidador.php)**: Confirmación para dar de baja al cuidador actual (`../controllers/procesar_baja_cuidador.php`).
