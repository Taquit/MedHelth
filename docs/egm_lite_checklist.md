# Diagnóstico y Plan de Refactorización (EGM - Etapa 1 y Ajustes Etapa 2)

## 1. Diagnóstico Arquitectónico de la Carpeta Root (`/`)

Tras analizar la totalidad de los archivos en la raíz del proyecto (`care_sis`), así como los subdirectorios `config/`, `models/`, y `controllers/`, y tras incorporar los hallazgos de la **Auditoría Goldfish (Etapa 2)**, se consolidan los siguientes problemas y soluciones de diseño:

### A. Duplicación de Capa de Datos y Archivos Huérfanos (Código Muerto)
- **Doble conector de Base de Datos**: Conviven [conexion.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/conexion.php) (basado en `mysqli`) y [config/Database.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/config/Database.php) (basado en `PDO`). Toda la arquitectura activa utiliza `PDO` a través de `config/Database.php`.
- **Scripts legacy en raíz**: [insertar_medicamento.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/insertar_medicamento.php) y [registrar_usuario.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/registrar_usuario.php) utilizan `conexion.php` (`mysqli`) y han sido completamente reemplazados por [controllers/guardar_medicamento.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/guardar_medicamento.php) y [controllers/registrar_paciente.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/registrar_paciente.php).
- **Controlador roto / huérfano**: [controllers/guardar_cuidador_interno.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/guardar_cuidador_interno.php) invoca un método inexistente `$cuidador->registrar(...)` en la clase `Cuidador` (el flujo real usa [controllers/registrar_cuidador.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/controllers/registrar_cuidador.php)).
- **Maquetas HTML obsoletas**: [template.html](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/template.html) y [registro_cuidador.html](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/registro_cuidador.html) son prototipos estáticos previos a la migración a PHP (reemplazados por [alta_cuidador.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/alta_cuidador.php)).

### B. Duplicación Masiva en Vistas y Controladores (Violación de DRY)
- **Verificación de sesión y obtención de Paciente Activo**: Se repite la lógica de inicio de sesión, conexión a BD y consulta de `Paciente::leerPorUsuario()` en vistas y controladores.
- **Conversión de Unidades de Tiempo (Frecuencia y Duración)**: Duplicada entre `controllers/guardar_medicamento.php`, `controllers/actualizar_medicamento.php` y `editar_medicamento.php`.
- **Formateo de Fechas en Español**: El arreglo `$meses` y el formateo manual se repite en `dashboard.php`, `historial.php` y `perfil_cuidador.php`.
- **Componentes UI repetidos**: La barra de navegación (`<nav class="navbar">`) y el pie de página (`<footer class="footer">`) están copiados en cada vista principal.

### C. Enlaces Rotos, Bugs Funcionales y Brechas de Seguridad (Ajustado con Auditoría Goldfish)
- **Rutas hardcodeadas a carpeta inexistente `../ADS/`**: En `dashboard.php`, `notificacion_posponer.html`, `notificacion_tomada.html` y `pregunta_cuidador.html`.
- **Enlaces a `.html` inexistentes en lugar de `.php`**: En `agregar_medicamento.php`, `editar_medicamento.php`, `reporte.php` (`historial.html` y `perfil.html`), y `registro_paciente.html` (`terminos.html`).
- **Correo del cuidador ignorado en vista**: En `perfil_cuidador.php`, `$activo['correo']` ya viene del `JOIN` en `Cuidador::obtenerActivo()`, pero se mostraba `"registrado@cuidate.com"`.
- **Vulnerabilidad IDOR en Medicamentos y Tomas**: `Medicamento::leerUno()`, `Medicamento::actualizar()`, `Medicamento::eliminar()` y `controllers/registrar_toma.php` operaban sin validar que el `id_medicamento` pertenezca al `id_paciente` autenticado a través de la tabla `recetas`.
- **Flujo de registro inicial de cuidador (`registrar_paciente.php` -> `pregunta_cuidador.html` -> `registro_cuidador_inicial.html`)**: Al registrarse el paciente no se iniciaba su sesión automáticamente y `registro_cuidador_inicial.html` carecía de atributos `name`, método `POST` y campo `password`, descartando los datos ingresados.

---

## 2. Checklist Estructurado de Refactorización

### 2.1. Archivos a Eliminar, Crear o Reorganizar

- [x] **Eliminar archivos obsoletos / código muerto (verificados sin referencias activas)**:
  - [x] Eliminar `conexion.php`
  - [x] Eliminar `insertar_medicamento.php`
  - [x] Eliminar `registrar_usuario.php`
  - [x] Eliminar `controllers/guardar_cuidador_interno.php`
  - [x] Eliminar `template.html` y `registro_cuidador.html`
- [x] **Crear módulos auxiliares y componentes compartidos (`includes/`)**:
  - [x] Crear `includes/auth.php`:
    - `requireLogin(string $redirectUrl = 'login.html'): void` (recibe `'login.html'` desde raíz y `'../login.html'` desde `controllers/` para evitar errores 404).
    - `obtenerPacienteActual(PDO $db): ?array` (retorna el arreglo asociativo del paciente autenticado o `null`).
    - `obtenerIdPacienteActual(PDO $db): int` (retorna `id_paciente` o `0`).
  - [x] Crear `includes/helpers.php`:
    - `obtenerFechaActualEspanol(): string`
    - `formatearFechaEspanol(?string $fecha): string`
    - `convertirFrecuenciaAHoras(int $cantidad, string $unidad): int`
    - `convertirDuracionADias(int $cantidad, string $unidad): int`
    - `desglosarDuracionDias(int $dias): array` (retorna `['cantidad' => int, 'unidad' => string]`)
  - [x] Crear `includes/navbar.php` y `includes/footer.php`:
    - Parciales reutilizables con soporte para `$activePage` (`'inicio'`, `'medicamentos'`, `'historial'`, `'perfil'`).

### 2.2. Métodos e Interfaces a Refactorizar

- [x] **Modelos (`models/`)**:
  - [x] `models/Medicamento.php`:
    - Sanitizar propiedades de texto en `crear()` y `actualizar()`.
    - Añadir método `perteneceAPaciente(int $id_medicamento, int $id_paciente): bool` que verifique mediante `JOIN recetas r ON m.id_receta = r.id_receta WHERE m.id_medicamento = :id AND r.id_paciente = :id_paciente`.
    - Soportar validación opcional de `$id_paciente` en `leerUno(?int $id_paciente = null)`, `actualizar(?int $id_paciente = null)` y `eliminar(?int $id_paciente = null)` para prevenir IDOR sin romper compatibilidad de firmas.
  - [x] `models/Usuario.php` y `models/Reporte.php`:
    - Limpiar código y comentarios obsoletos. Mantener `controllers/generar_pdf.php` enfocado en `HistorialToma` sin forzar inserciones inválidas en `Reporte::crear()` que violen llaves foráneas.
- [x] **Controladores (`controllers/`)**:
  - [x] Estandarizar `include_once __DIR__ . '/...'` en todos los controladores, incluyendo `controllers/login.php` y `controllers/generar_pdf.php`.
  - [x] `controllers/registrar_paciente.php`: Iniciar sesión automáticamente tras crear el paciente (`$_SESSION['id_usuario']`, `$_SESSION['correo']`, `$_SESSION['rol']`) para que pueda registrar a su cuidador inmediatamente en el paso siguiente si elige "Sí".
  - [x] `controllers/guardar_medicamento.php` y `controllers/actualizar_medicamento.php`: Usar `requireLogin('../login.html')`, `obtenerIdPacienteActual($db)`, `convertirFrecuenciaAHoras()` y `convertirDuracionADias()`, además de verificar pertenencia del medicamento al paciente al actualizar.
  - [x] `controllers/eliminar_medicamento.php` y `controllers/registrar_toma.php`: Usar `requireLogin('../login.html')` y verificar `Medicamento::perteneceAPaciente()` antes de eliminar o registrar toma.
  - [x] `controllers/registrar_cuidador.php`, `controllers/procesar_baja_cuidador.php`, `controllers/procesar_cambio_password.php`, `controllers/posponer_toma.php`, `controllers/generar_pdf.php`: Simplificar mediante `requireLogin('../login.html')` y `obtenerPacienteActual($db)`.
- [x] **Vistas en Raíz (`/`)**:
  - [x] Vistas con layout completo (`dashboard.php`, `medicamentos.php`, `agregar_medicamento.php`, `editar_medicamento.php`, `historial.php`, `perfil.php`, `perfil_cuidador.php`, `alta_cuidador.php`, `baja_cuidador.php`): Usar `requireLogin('login.html')`, `includes/navbar.php` e `includes/footer.php`.
  - [x] Vistas de modal/superposición (`reporte.php`, `cambiar_contrasena.php`, `notificacion_no_tomada.php`): Usar `requireLogin('login.html')` conservando su estructura de modal específica (reparando el navbar de `reporte.php` y protegiendo `notificacion_no_tomada.php`).
  - [x] En `editar_medicamento.php`: Validar que `leerUno($id_paciente)` retorne `true`; si no existe o no pertenece al paciente, redirigir a `medicamentos.php`.
  - [x] En `perfil_cuidador.php`: Mostrar `$activo['correo']` real obtenido de la base de datos.
  - [x] En `pregunta_cuidador.html`, `registro_cuidador_inicial.html`, `registro_paciente.html`, `notificacion_posponer.html`, `notificacion_tomada.html`: Corregir enlaces rotos (`../ADS/...` y `terminos.html`) y conectar adecuadamente el formulario de `registro_cuidador_inicial.html` con `controllers/registrar_cuidador.php`.

### 2.3. Pruebas e Invariantes que Deben Conservarse

- [x] **Invariante 1 (Rutas Públicas)**: Todas las URLs activas de vistas y controladores se mantienen intactas.
- [x] **Invariante 2 (Esquema de Base de Datos)**: Ninguna tabla ni columna de `seguimiento_medicamentos` es alterada.
- [x] **Invariante 3 (Variables de Sesión)**: Se preservan `$_SESSION['id_usuario']`, `$_SESSION['correo']` y `$_SESSION['rol']`.
- [x] **Invariante 4 (Estilos y Recursos)**: `CSS/`, `Recursos/` y `libs/fpdf/` permanecen intactos.
- [x] **Invariante 5 (Validación de Sintaxis PHP)**: Todos los archivos `.php` deben aprobar `php -l` sin errores.
