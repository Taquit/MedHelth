# Utilidades y Componentes Compartidos (`includes/`)

Este directorio agrupa los módulos auxiliares de autenticación, funciones puras de formateo/conversión, catálogo de iconos SVG lineales y parciales del layout de paciente MedHealth.

## Archivos y Contratos de Interfaz

### 1. [auth.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/includes/auth.php)
Gestiona el estado de la sesión de PHP, la protección de rutas autenticadas y la obtención del perfil del paciente asociado al usuario en sesión. Al ser incluido (`include_once`), inicia automáticamente la sesión en el ámbito global si aún no está activa (`session_start()`) e incluye `config/Database.php`, `models/Paciente.php`, `includes/helpers.php` e `includes/icons.php`.

- **`requireLogin(string $redirectUrl = 'login.html'): void`**
  - **Entradas**: `$redirectUrl` (ruta relativa hacia la página de inicio de sesión; por defecto `'login.html'` desde `views/` o `'../views/login.html'` desde `controllers/`).
  - **Salidas**: `void`.
  - **Efectos secundarios**: Inicia la sesión si no está activa (`session_start()`). Si `$_SESSION['id_usuario']` no está definido, envía cabecera HTTP `Location: $redirectUrl` y termina la ejecución con `exit`.

- **`obtenerPacienteActual(PDO $db): ?array`**
  - **Entradas**: `$db` (conexión `PDO` activa).
  - **Salidas**: `array` asociativo con las columnas de `pacientes` y `usuarios.correo`, o `null` si no hay sesión activa o no existe registro de paciente.
  - **Efectos secundarios**: Ejecuta una consulta `SELECT` mediante `Paciente::leerPorUsuario()`.

- **`obtenerIdPacienteActual(PDO $db): int`**
  - **Entradas**: `$db` (conexión `PDO` activa).
  - **Salidas**: `int` correspondiente a `id_paciente`, o `0` si no se encuentra.
  - **Efectos secundarios**: Invoca `obtenerPacienteActual($db)`.

- **`obtenerNombreUsuarioActual(?PDO $db = null): string`**
  - **Entradas**: `$db` (conexión `PDO` opcional).
  - **Salidas**: `string` con el nombre y apellido paterno del paciente en texto plano (decodificando entidades HTML si existieran), o el correo en sesión / `'Usuario'`.
  - **Efectos secundarios**: Retorna de inmediato si `$_SESSION['nombre_usuario']` no está vacío. Si está vacío, abre una conexión con `Database` únicamente cuando `$db === null && isset($_SESSION['id_usuario'])`, consulta `obtenerPacienteActual($db)` y almacena el resultado en `$_SESSION['nombre_usuario']`.

---

### 2. [helpers.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/includes/helpers.php)
Configura `date_default_timezone_set('America/Mexico_City')` al cargarse y provee funciones puras de dominio para fechas en español, saludos, iniciales y conversión de unidades de tratamiento.

- **`obtenerMesesEspanol(): array`**
  - **Salidas**: `array<int, string>` con los 12 meses del año en español (`"Enero"` a `"Diciembre"`).

- **`obtenerFechaActualEspanol(): string`**
  - **Salidas**: `string` con la fecha actual del sistema en formato `"dd de Mes del YYYY"`.

- **`formatearFechaEspanol(?string $fecha): string`**
  - **Entradas**: `$fecha` (cadena compatible con `strtotime` o `null`).
  - **Salidas**: `string` en formato `"dd de Mes YYYY"` o `'-'` si es vacía o inválida.

- **`obtenerSaludoHora(): string`**
  - **Salidas**: `'Buenos días'` (`< 12:00`), `'Buenas tardes'` (`12:00–18:59`) o `'Buenas noches'` (`>= 19:00`) según la hora local del servidor (`America/Mexico_City`).

- **`obtenerIniciales(string $nombreCompleto): string`**
  - **Entradas**: `$nombreCompleto`.
  - **Salidas**: Hasta dos iniciales en mayúsculas (o `'C'` si está vacío).

- **`convertirFrecuenciaAHoras(int $cantidad, string $unidad): int`**
  - **Entradas**: `$cantidad`, `$unidad` (`'horas'` o `'minutos'`).
  - **Salidas**: `int` en horas (`ceil($cantidad / 60)` si es `'minutos'`).

- **`convertirDuracionADias(int $cantidad, string $unidad): int`**
  - **Entradas**: `$cantidad`, `$unidad` (`'dias'`, `'semanas'` o `'meses'`).
  - **Salidas**: `int` en días (`semanas * 7`, `meses * 30`, o `dias`).

- **`desglosarDuracionDias(int $dias): array`**
  - **Entradas**: `$dias`.
  - **Salidas**: `array{cantidad: int, unidad: string}`; retorna `'meses'` solo cuando `$dias >= 30 && $dias % 30 === 0`, `'semanas'` solo cuando `$dias >= 7 && $dias % 7 === 0`, o `'dias'` en otro caso.

---

### 3. [icons.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/includes/icons.php)
Catálogo centralizado de iconos SVG lineales (estilo Lucide, `stroke-width="2"`, `viewBox="0 0 24 24"`, `aria-hidden="true"`).

- **`medhealthIcon(string $name, int $size = 24, string $class = ''): string`**
  - **Entradas**: `$name` (`'pill'`, `'home'`, `'clock'`, `'file-text'`, `'heart'`, `'user'`, `'plus'`, `'alert-triangle'`, `'check'`, `'x'`, `'chevron-left'`, `'chevron-right'`, `'chevron-down'`, `'search'`, `'phone'`, `'lock'`, `'mail'`, `'log-out'`), `$size` en px, `$class` CSS opcional.
  - **Salidas**: `string` con la etiqueta `<svg>...</svg>` (si `$name` no está en el catálogo, retorna un `<svg></svg>` vacío).

---

### 4. [navbar.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/includes/navbar.php) y [layout_paciente.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/includes/layout_paciente.php)
Renderizan el contenedor `.app-shell`, la barra lateral izquierda MedHealth de `280px` (`.sidebar`) y abren `<main class="app-main">`. `layout_paciente.php` actúa como alias que incluye `navbar.php`.
- **Entradas (contexto de vista)**: Variable opcional `$activePage` (`'hoy'`, `'medicamentos'`, `'historial'`, `'reporte'`, `'cuidador'`, `'perfil'`).
- **Salidas**: HTML de la barra lateral con los 6 ítems (`Hoy`, `Mis medicamentos`, `Historial`, `Reporte PDF`, `Mi cuidador`, `Mi perfil`), el nombre de usuario obtenido con `obtenerNombreUsuarioActual($db ?? null)` y enlace de cierre de sesión (`../controllers/logout.php`).

---

### 5. [footer.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/includes/footer.php)
Cierra `</main></div>` (sin cerrar `</body></html>`, los cuales cierran en cada vista) e incluye el listener global de teclado para cerrar el primer elemento `[data-modal-cancel]` visible al presionar `Escape`.
