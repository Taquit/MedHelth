# Utilidades y Componentes Compartidos (`includes/`)

Este directorio agrupa los módulos auxiliares de autenticación, funciones puras de formateo/conversión de unidades y parciales reutilizables de interfaz de usuario.

## Archivos y Contratos de Interfaz

### 1. [auth.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/includes/auth.php)
Gestiona el estado de la sesión de PHP, la protección de rutas autenticadas y la obtención del perfil del paciente asociado al usuario en sesión. Al ser incluido (`include_once`), inicia automáticamente la sesión en el ámbito global si aún no está activa (`session_start()`) e incluye `config/Database.php` y `models/Paciente.php`.

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

---

### 2. [helpers.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/includes/helpers.php)
Funciones puras de dominio para formateo de fechas en español y conversión de unidades de tratamiento médico.

- **`obtenerMesesEspanol(): array`**
  - **Entradas**: Ninguna.
  - **Salidas**: `array<int, string>` con los 12 meses del año en español (`"Enero"` a `"Diciembre"`).
  - **Efectos secundarios**: Ninguno.

- **`obtenerFechaActualEspanol(): string`**
  - **Entradas**: Ninguna.
  - **Salidas**: `string` con la fecha actual del sistema en formato `"dd de Mes del YYYY"`.
  - **Efectos secundarios**: Ninguno.

- **`formatearFechaEspanol(?string $fecha): string`**
  - **Entradas**: `$fecha` (cadena de fecha/hora compatible con `strtotime` o `null`).
  - **Salidas**: `string` en formato `"dd de Mes YYYY"` o `'-'` si la entrada es vacía o inválida.
  - **Efectos secundarios**: Ninguno.

- **`convertirFrecuenciaAHoras(int $cantidad, string $unidad): int`**
  - **Entradas**: `$cantidad` (valor numérico), `$unidad` (`'horas'` o `'minutos'`).
  - **Salidas**: `int` representando las horas equivalentes (si es `'minutos'`, redondea hacia arriba con `ceil($cantidad / 60)`).
  - **Efectos secundarios**: Ninguno.

- **`convertirDuracionADias(int $cantidad, string $unidad): int`**
  - **Entradas**: `$cantidad` (valor numérico), `$unidad` (`'dias'`, `'semanas'` o `'meses'`).
  - **Salidas**: `int` representando el total de días (`semanas * 7`, `meses * 30`, o `dias`).
  - **Efectos secundarios**: Ninguno.

- **`desglosarDuracionDias(int $dias): array`**
  - **Entradas**: `$dias` (total de días entero).
  - **Salidas**: `array{cantidad: int, unidad: string}` donde `unidad` es `'meses'` (múltiplos de 30), `'semanas'` (múltiplos de 7) o `'dias'`.
  - **Efectos secundarios**: Ninguno.

---

### 3. [navbar.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/includes/navbar.php)
Componente parcial de la barra de navegación superior.
- **Entradas (contexto de vista)**: Variable opcional `$activePage` (`'inicio'`, `'medicamentos'`, `'historial'`, `'perfil'`).
- **Salidas**: Renderiza el bloque `<nav class="navbar">` con enlaces a `dashboard.php`, `medicamentos.php`, `historial.php` y `perfil.php`.

### 4. [footer.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/includes/footer.php)
Componente parcial del pie de página.
- **Salidas**: Renderiza el bloque `<footer class="footer">`.
