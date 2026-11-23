# Diagnóstico y Plan de Refactorización (EGM - Etapa 1 y Ajustes Etapa 2)

## Parte I. Refactorización Backend y Estructura (Completada)

- [x] **Eliminación de código muerto**: `conexion.php`, `insertar_medicamento.php`, `registrar_usuario.php`, `controllers/guardar_cuidador_interno.php`, `template.html`, `registro_cuidador.html`.
- [x] **Creación de `includes/`**: `auth.php`, `helpers.php`, `navbar.php`, `footer.php`.
- [x] **Seguridad anti-IDOR y Modelos**: `Medicamento::perteneceAPaciente()`, `RecuperacionPassword`, `Notificacion`, `Reporte`.
- [x] **Reorganización por carpetas**: Vistas movidas a `views/` y documentación centralizada en `docs/`.

---

## Parte II. Implementación Front-End MedHealth (`docs/medhealth-diseno.md`)

### 1. Diagnóstico de Brechas Visuales y Estructurales (Etapa 1 y Ajustes Goldfish Etapa 2)
- **Sistema de diseño ausente**: Los archivos actuales en `CSS/*.css` utilizan `Arial`, tamaños entre `13px` y `16px` (inferiores al mínimo obligatorio de `18px` para adultos mayores), paleta antigua (`#00257A`, `#EEE5E5`) y botones menores a `56px`/`64px`. Para evitar colisiones de especificidad CSS, `CSS/style.css` debe limpiarse dejando únicamente `@import url('../assets/css/medhealth.css');` y retirarse las hojas heredadas `CSS/<vista>.css` de las vistas migradas.
- **Navegación de paciente**: `includes/navbar.php` implementa una barra superior horizontal de 4 enlaces; `docs/medhealth-diseno.md` requiere una **barra lateral izquierda de 280px** (`--primario-600`) con logo **MedHealth**, 6 ítems de `64px` (`Hoy` [`hoy`], `Mis medicamentos` [`medicamentos`], `Historial` [`historial`], `Reporte PDF` [`reporte`], `Mi cuidador` [`cuidador`], `Mi perfil` [`perfil`]) y bloque inferior con nombre del usuario y cierre de sesión, eliminando la sub-barra duplicada `.profile-sidebar`.
- **Layout público (W1–W7) y vistas `.html` estáticas**: W1–W7, P2 y P3 son archivos `.html` estáticos (no ejecutan PHP), por lo que requieren barra superior estática `.public-topbar` de `96px` con SVG inline y manejo de parámetros/errores mediante `URLSearchParams` y `.textContent` sobre contenedores `aria-live="polite"`. En P2 y P3 debe eliminarse el dashboard falso heredado detrás del overlay.
- **Contraste WCAG y accesibilidad**:
  - No alterar `html { font-size }` respecto al valor del navegador (`16px` base), usando `rem` (`1.125rem` = `18px` mínimo en contenido; `1rem` = `16px` en chips).
  - Blanco sobre `--acento-500` (`#2A9D8F`) solo cumple contraste en texto grande en negritas (`>= 24px` / `1.5rem` bold `700`), por lo que `.btn-confirmar` debe mantener `font-size: 1.5rem; font-weight: 700` en todos los breakpoints, y chips/banners verdes deben usar `--acento-50` + `--acento-700`.
  - Sustituir todas las entidades Unicode (`&#9200;`, `&#10004;`, `&#10006;`, `&#10005;`) por SVG lineales (`medhealthIcon()` o SVG inline).
- **Controladores y modelos**:
  - Eliminar `alert()` bloqueantes y `die()` en `login.php`, `registrar_paciente.php`, `registrar_cuidador.php`, `solicitar_recuperacion.php`, `procesar_baja_cuidador.php` y `procesar_cambio_password.php`, reemplazándolos por redirecciones con query params de error/éxito.
  - Configurar `date_default_timezone_set('America/Mexico_City')` para coherencia de saludos y posposición de 5 minutos.
  - Preservar nombres de campos POST existentes (`nombres`, `paterno`, `materno`, `telefono`, `email`, `password` en paciente; `nombre`, `paterno`, `materno`, `parentesco`, `telefono`, `email`, `password` + hidden `origen` en cuidador; `id_medicamento`, `nombre`, `dosis`, `via`, `hora_inicio`, `frecuencia_cant`, `frecuencia_unidad`, `duracion_cant`, `duracion_unidad`, `indicaciones` en medicamentos).

### 2. Checklist Estructurado de Implementación Front-End

#### 2.1. Hojas de Estilo y Activos Compartidos
- [x] Crear `assets/css/medhealth.css` con tokens `:root`, `Atkinson Hyperlegible` en `rem` (sin modificar `html { font-size }`), botones `.btn` (`64px`, texto `1.5rem` bold, variantes `.btn-primario`, `.btn-secundario`, `.btn-confirmar`, `.btn-peligro`, `.btn-peligro-contorno`), campos `.campo` (`64px`, textarea `128px`, errores `--error-600`), chips `.chip`, tarjetas `.tarjeta` (`560`, `900`, `960`, `440`), tablas `.tabla`, barra lateral `.sidebar` (`280px`), `.public-topbar` (`96px`), modales `.modal-scrim` y responsividad (`1440px` a `1024px` y móvil).
- [x] Limpiar `CSS/style.css` dejando únicamente `@import url('../assets/css/medhealth.css');` y retirar los `<link>` a hojas heredadas en todas las vistas.

#### 2.2. Parciales y Utilidades en `includes/`
- [x] Crear `includes/icons.php` con `medhealthIcon(string $name, int $size = 24, string $class = ''): string` cubriendo los 17 iconos lineales de `medhealth-diseno.md` (`pill`, `home`, `clock`, `file-text`, `heart`, `user`, `plus`, `alert-triangle`, `check`, `x`, `chevron-left`, `chevron-right`, `chevron-down`, `search`, `phone`, `lock`, `mail`, más `log-out`).
- [x] Actualizar `includes/auth.php`: incluir `icons.php`, `helpers.php`, y añadir `obtenerNombreUsuarioActual(?PDO $db = null): string` (abre `Database` si `$db === null` y no existe en `$_SESSION['nombre_usuario']`).
- [x] Actualizar `includes/helpers.php`: fijar `date_default_timezone_set('America/Mexico_City')`, añadir `obtenerSaludoHora(): string` y `obtenerIniciales(string $nombreCompleto): string`.
- [x] Actualizar `includes/navbar.php` y `includes/footer.php` (y crear `includes/layout_paciente.php` como alias compatible) para renderizar la barra lateral MedHealth de `280px` con `$activePage` (`hoy`, `medicamentos`, `historial`, `reporte`, `cuidador`, `perfil`) y el cierre de layout con script para cerrar modales con tecla `Escape`.

#### 2.3. Modelos y Controladores
- [x] `models/Cuidador.php`: Incluir `pc.fecha_inicio` en `obtenerActivo($id_paciente)`.
- [x] `models/HistorialToma.php`:
  - `obtenerTomasHoyPorPaciente(int $id_paciente): array` (`array<int, string>` mapeando `id_medicamento => estado_hoy` para `DATE(h.fecha_hora) = date('Y-m-d')`).
  - `contarDiasTomadosPorPaciente(int $id_paciente): array` (`array<int, int>` con `COUNT(DISTINCT DATE(h.fecha_hora))` donde `estado = 'tomado'`).
- [x] `controllers/login.php`: Reemplazar `die()` y `alert()` por redirección a `../views/login.html?error=credenciales` o `?error=no_registrado`; guardar `$_SESSION['nombre_usuario']` consultando `obtenerPacienteActual($db)`.
- [x] `controllers/registrar_paciente.php`: Redirigir correo duplicado a `../views/registro_paciente.html?error=correo_duplicado`, errores de BD a `?error=registro`, guardar `$_SESSION['nombre_usuario']`, y redirigir a `../views/pregunta_cuidador.html?nombre=` . `urlencode($nombrePlano)`.
- [x] `controllers/registrar_cuidador.php`: Leer `$_POST['origen'] ?? 'alta'` (`inicial` -> `views/registro_cuidador_inicial.html` en error / `views/dashboard.php` en éxito; `alta` -> `views/alta_cuidador.php` en error / `views/perfil_cuidador.php?exito=1` en éxito), eliminando `alert()` y `window.history.back()`.
- [x] `controllers/solicitar_recuperacion.php`: Eliminar `alert()` y redirigir a `../views/login.html?recuperacion=enviada`.
- [x] `controllers/procesar_baja_cuidador.php`: Eliminar `alert()` y redirigir a `../views/perfil_cuidador.php?exito=baja` (o `?error=baja`).
- [x] `controllers/registrar_toma.php`: Validar `status` (`'tomado'` o `'no_tomado'`), permitir motivo opcional (`trim($_POST['motivo'] ?? '') === '' ? null : trim($_POST['motivo'])`), obtener nombre real vía `Medicamento::leerUno($id_paciente)`, y en `'tomado'` redirigir a `../views/notificacion_tomada.html?nombre=...&medicamento=...&hora=...`.
- [x] `controllers/posponer_toma.php`: Redirigir a `../views/notificacion_posponer.html?hora=` . `urlencode(date('H:i', time() + 300))`.
- [x] `controllers/procesar_cambio_password.php`: Validar `confirm_password`, eliminar `alert()`, redirigir errores a `../views/cambiar_contrasena.php?error=no_coincide` / `actual_incorrecta` / `error_actualizar`, y éxito a `../views/perfil.php?exito=password`.
- [x] `controllers/generar_pdf.php`: Actualizar encabezado a `'MedHealth Reporte de Adherencia'` y descarga como `'Reporte_MedHealth.pdf'`.

#### 2.4. Pantallas Públicas (W1–W7, HTML estático con SVG inline y `URLSearchParams`)
- [x] W1 `index.html`: Título display `"Tus medicamentos, siempre a tiempo."`, botones `"Iniciar sesión"` y `"Crear mi cuenta"`, tarjeta de 3 beneficios.
- [x] W2 `views/registro_paciente.html`: Tarjeta `900px` en 2 columnas con `nombres`, `paterno`, `materno`, `telefono`, `email`, `password`, checkbox `acepto_terminos`, error inline con `URLSearchParams`.
- [x] W3 `views/login.html`: Tarjeta `560px`, avisos inline (`error`, `recuperacion=enviada`), enlace recuperación y botón secundario crear cuenta.
- [x] W4 `views/olvide_contrasena.html`: Tarjeta `560px`, enlace volver, aviso 60 minutos con icono `clock`.
- [x] W5 `views/pregunta_cuidador.html`: Círculo verde con `check`, `"¡Tu cuenta está lista, {nombre}!"` con fallback limpio, dos tarjetas de `440px`.
- [x] W6 `views/registro_cuidador_inicial.html`: Tarjeta `900px` en 2 columnas, `<select name="parentesco">`, hidden `origen=inicial`, nota contraseña, enlace omitir, errores inline.
- [x] W7 `views/terminos.html`: Tarjeta `960px` con 3 bloques `h2` (`--primario-700`) bajo marca MedHealth y botón `"Entendido"` con fallback a `../index.html`.

#### 2.5. Pantallas del Paciente (P1–P15)
- [x] P1 `views/dashboard.php` (`$activePage = 'hoy'`): Saludo, fecha en español, tarjetas con `hora_inicio` e icono `clock`, estado tomado (`--acento-50`, borde `--acento-500`, chip `"Tomado"` sin botones), 3 botones en pendientes (`Se tomó`, `Posponer 5 min`, `No se tomó`), estado vacío a `agregar_medicamento.php`.
- [x] P2 `views/notificacion_tomada.html`: Tarjeta centrada sobre `--secundario-50` con `.public-topbar`, halo `--acento-50`, círculo `116px` `--acento-500` con `check`, datos por `URLSearchParams` (`aria-live="polite"`), botón `"Volver a mi panel"`.
- [x] P3 `views/notificacion_posponer.html`: Tarjeta centrada sobre `--secundario-50` con `.public-topbar`, icono `clock` en tonos `--alerta-50` / `--alerta-700`, nueva hora (`URLSearchParams`), botón `"Volver a mi panel"`.
- [x] P4 `views/notificacion_no_tomada.php` (`$activePage = 'hoy'`): Modal `.modal-scrim` + `.modal-tarjeta` sobre el panel con nombre del medicamento y textarea `"Motivo (opcional)"` sin `required`.
- [x] P5 `views/medicamentos.php` y P8 (`$activePage = 'medicamentos'`): Grid 2 columnas, barra progreso `"Día X de N"` acotada (`min` y división segura), botones Editar y Eliminar con modal P8 accesible (`Escape` / Cancelar).
- [x] P6 `views/agregar_medicamento.php` (`$activePage = 'medicamentos'`): Formulario 2 columnas, `<select name="via">`, textarea `indicaciones`, `<label for>` en todos los campos.
- [x] P7 `views/editar_medicamento.php` (`$activePage = 'medicamentos'`): Precargado con `desglosarDuracionDias()`, `<select name="via">` preservando cualquier valor legado como `<option selected>`.
- [x] P9 `views/historial.php` (`$activePage = 'historial'`): Tabla `.tabla` con chips icono + texto y estado vacío.
- [x] P10 `views/reporte.php` (`$activePage = 'reporte'`): Dos columnas (explicación + descarga PDF a la izquierda; hoja blanca de vista previa real con datos del paciente e historial a la derecha).
- [x] P11 `views/perfil.php` (`$activePage = 'perfil'`): Campos con `<label for>` readonly, banner `aria-live="polite"` para `?exito=password`, botones `"Cambiar contraseña"` y `"Cerrar sesión"`.
- [x] P12 `views/cambiar_contrasena.php` (`$activePage = 'perfil'`): Dentro del layout lateral, tarjeta `560px` con `current_password`, `new_password`, `confirm_password`, errores inline.
- [x] P13 `views/perfil_cuidador.php` (`$activePage = 'cuidador'`): Avatar de iniciales, `"Desde {fecha}"`, banners de éxito/baja, estado vacío cuando no hay cuidador activo, y tabla historial debajo.
- [x] P14 `views/alta_cuidador.php` (`$activePage = 'cuidador'`): Formulario 2 columnas, hidden `origen=alta`, aviso `--alerta-50`, error inline.
- [x] P15 `views/baja_cuidador.php` (`$activePage = 'cuidador'`): Modal sobre la vista de cuidador con nombre real del cuidador activo (`"¿Dar de baja a {nombre}?"`) y botones confirmar/cancelar.

### 3. Invariantes a Conservar
- [x] **Invariante 1**: Ninguna modificación al esquema de base de datos ni lectura/escritura de `.env`.
- [x] **Invariante 2**: Cero emojis en todo el código y documentación (solo iconos SVG lineales).
- [x] **Invariante 3**: Todos los archivos `.php` aprueban `php -l` sin errores de sintaxis.
- [x] **Invariante 4**: Todos los criterios visuales y de accesibilidad de `docs/medhealth-diseno.md` Sección 7 (`Atkinson Hyperlegible`, texto >= `18px` / `16px` chips, controles >= `56px`, contraste WCAG `--acento-500`, `lang="es"`, `<label for>`, `aria-live="polite"`).
