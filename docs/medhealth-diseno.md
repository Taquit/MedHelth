# MedHealth · Guía de diseño para replicar la maqueta (web de escritorio)

Documento para que Claude Code implemente las vistas de `care_sis` (PHP + HTML + CSS) con el mismo estilo de la maqueta de Figma.

- Figma: https://www.figma.com/design/N58rdDbB0rVmbuPmMb1Ghx
- Nombre mostrado en la interfaz: **MedHealth** (el catálogo de funcionalidades lo llama "Cuidate+"; confirmar cuál queda).
- Plataforma: **web para computadora**, lienzo de referencia **1440 × 900 px**. Debe adaptarse a ventanas más angostas (mínimo 1024 px).
- Público principal: adultos mayores. Prioridad: letra grande, contraste alto, botones grandes, una acción principal por pantalla.

## 0. Estado de la maqueta (leer primero)

| Grupo | Estado en Figma | Qué hacer |
|---|---|---|
| Fundamentos (colores, texto, botones, campo) | Hecho | Usar tal cual esta guía |
| Acceso y registro (7 pantallas web) | Creado, **sin revisión visual** | Usar Figma como referencia; esta guía es la fuente |
| Paciente (15 pantallas) | **No creado en Figma** (límite de llamadas) | Implementar desde la sección 5, con el mismo estilo |
| Administrador y Farmacéutica (web) | Hecho, marcado "rol futuro" | Fuera del catálogo actual; no implementar aún |
| Versión móvil v1 | Archivada, reemplazada | Ignorar |

## 1. Tokens de color

Definir como variables CSS en `:root`.

```css
:root {
  --primario-600: #0077B6;   /* acciones principales, enlaces, logo */
  --primario-700: #005F92;   /* hover, ítem activo del menú, texto sobre fondo claro */
  --secundario-500: #00B4D8; /* decorativo (no usar como fondo de texto blanco) */
  --secundario-200: #90E0EF; /* chips informativos, barras secundarias */
  --secundario-50:  #EAF8FC; /* fondo de página, encabezados de tabla, tarjetas destacadas */
  --acento-500: #2A9D8F;     /* confirmaciones: "Se tomó", estado activo */
  --acento-700: #1B6F65;     /* texto sobre acento-50 */
  --acento-50:  #E3F4F2;
  --fondo: #FFFFFF;
  --texto: #0B2A3C;          /* texto principal */
  --texto-2: #4A6572;        /* texto secundario */
  --borde: #C9DDE6;
  --alerta-500: #E9A23B; --alerta-50: #FFF4E0; --alerta-700: #8A5A0A;
  --error-600: #C0392B;  --error-50: #FDECEA;
}
```

Reglas de uso:
- Paleta base entregada: primario `#0077B6`, secundarios `#00B4D8` y `#90E0EF`, acento `#2A9D8F`, fondo `#FFFFFF`. El resto (neutros, alerta, error, variantes 50/700) se añadió para completar la interfaz.
- Fondo de página: `--secundario-50`. Tarjetas, tablas y formularios: `--fondo` con borde `--borde`.
- Contraste: texto blanco sobre `--primario-600` ≈ 4.9:1 (cumple). Texto blanco sobre `--acento-500` ≈ 3.3:1, **solo válido para texto grande y en negritas** (≥ 24 px bold, como el botón "Se tomó"). Para texto pequeño sobre verde usar `--acento-700`.
- Nunca usar solo color para comunicar estado: siempre texto + ícono ("Tomado" con palomita, "No tomado" con X).

## 2. Tipografía

Fuente: **Atkinson Hyperlegible** (diseñada para baja visión). Solo existen Regular (400) y Bold (700).

```html
<link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible:wght@400;700&display=swap" rel="stylesheet">
```
```css
body { font-family: "Atkinson Hyperlegible", system-ui, sans-serif; color: var(--texto); }
```

| Token | Tamaño / interlínea | Peso | Uso |
|---|---|---|---|
| `display` | 40 / 48 | 700 | Título principal de portada, números grandes |
| `h1` | 32 / 40 | 700 | Título de pantalla |
| `h2` | 26 / 34 | 700 | Título de sección |
| `cuerpo-grande` | 22 / 32 | 400 | Texto base de lectura, valores de campos |
| `cuerpo-grande-bold` | 22 / 32 | 700 | Nombres de medicamento, enlaces |
| `boton` | 24 / 32 | 700 | Botones grandes |
| `cuerpo` | 18 / 26 | 400 | Texto de apoyo (mínimo absoluto de lectura) |
| `cuerpo-bold` | 18 / 26 | 700 | Etiquetas de campo, encabezados de tabla |
| `etiqueta` | 16 / 22 | 700 | Chips, subetiquetas |

Regla: ningún texto de contenido por debajo de **18 px**; 16 px solo en chips y etiquetas.

## 3. Espaciado, bordes y sombras

- Escala de espaciado: 4, 8, 12, 16, 20, 24, 32, 40, 48, 56.
- Radios: botones 14, campos 14, tarjetas pequeñas 16–20, tarjetas de formulario/modal 24, chips 999.
- Bordes: 2 px `--borde` en tarjetas y campos; 3 px en botones secundarios y estados destacados.
- Sin sombras pesadas; la jerarquía se logra con borde, fondo y espacio. Modal: scrim `rgba(11,42,60,.5)`.
- Objetivo táctil/clic mínimo: **56 px** de alto en botones y campos (en Figma: botón 64 px, campo 64 px).

## 4. Componentes

**Botón grande** (`.btn`): padding 16 × 32, radio 14, texto `boton` (24 bold), centrado, ancho según contenido o 100 % en formularios. Variantes:
- `primario`: fondo `--primario-600`, texto blanco.
- `secundario`: fondo blanco, borde 3 px `--primario-600`, texto `--primario-600`.
- `confirmar`: fondo `--acento-500`, texto blanco (ej. "Se tomó").
- `peligro`: fondo `--error-600`, texto blanco (ej. "Eliminar", "Dar de baja").
- Foco visible: anillo 3 px `--secundario-500` con offset 2 px. Hover: `--primario-700` (primario).

**Campo de texto** (`.campo`): etiqueta arriba (`cuerpo-bold`, color `--texto`), debajo caja de 64 px, borde 2 px `--borde`, radio 14, padding horizontal 20, texto `cuerpo-grande`, placeholder `--texto-2`. Selector: igual con chevron-down a la derecha. Textarea: mismo estilo, mínimo 128 px. Siempre etiqueta visible (no solo placeholder). Error: borde `--error-600` y mensaje debajo con ícono.

**Chip de estado** (`.chip`): pill, padding 5 × 12, texto `etiqueta`. Mapeo:
- Tomado / Activo / Alta / Listo → fondo `--acento-50`, texto `--acento-700`
- No tomado / Baja / Error → fondo `--error-50`, texto `--error-600`
- Pospuesta / Pendiente / En preparación → fondo `--alerta-50`, texto `--alerta-700`
- Informativo / Nuevo → fondo `--secundario-200`, texto `--texto`

**Tarjeta** (`.tarjeta`): fondo blanco, borde 2 px `--borde`, radio 16–24, padding 20–48 según tamaño (formularios 48 × 40).

**Tabla** (`.tabla`): contenedor tarjeta con `overflow:hidden`. Encabezado: fondo `--secundario-50`, alto 52, texto `etiqueta` en mayúsculas color `--texto-2`. Filas: alto ≥ 76, borde inferior 1 px `--borde`, padding horizontal 24, texto `cuerpo`. Columnas con anchos fijos y la última flexible para acciones.

**Barra lateral (paciente)**: ancho 280, fondo `--primario-600`, padding 16 × 32. Logo arriba (círculo blanco con ícono pill + "MedHealth" 24 bold blanco). Ítems de 64 px de alto, radio 14, ícono 32 + texto 22 bold blanco; activo con fondo `--primario-700`. Abajo: nombre del usuario y "Cerrar sesión".

Ítems del menú del paciente: **Hoy** (home) · **Mis medicamentos** (pill) · **Historial** (clock) · **Reporte PDF** (file) · **Mi cuidador** (heart) · **Mi perfil** (user).

**Contenido principal**: fondo `--secundario-50`, padding 40 × 32. Encabezado: título `h1` + subtítulo `cuerpo` `--texto-2` a la izquierda; a la derecha acción principal de la pantalla si aplica.

**Modal de confirmación**: scrim sobre la pantalla anterior; tarjeta centrada de 560 px, radio 24, padding 40, título `h1`, texto `cuerpo-grande`, dos botones (acción + cancelar). Cerrar con Esc y botón Cancelar.

**Layout público** (acceso/registro): fondo `--secundario-50`, barra superior de 96 px (logo a la izquierda, enlace "Términos y privacidad" a la derecha) y tarjeta centrada (560 px para login/recuperación, 900 px para registros, 960 px para términos).

**Íconos**: estilo lineal (stroke 2, extremos redondeados), viewBox 24. Usar Lucide o equivalentes: `pill`, `home`, `clock`, `file-text`, `heart`, `user`, `plus`, `alert-triangle`, `check`, `x`, `chevron-left`, `chevron-right`, `chevron-down`, `search`, `phone`, `lock`, `mail`. Tamaños: 24 en campos, 28–32 en menú, 48–64 dentro de círculos de color.

**Accesibilidad obligatoria**: `lang="es"`, etiquetas `<label for>`, `aria-live="polite"` para mensajes de confirmación, orden de tabulación lógico, no depender solo del color, tamaño de letra del navegador respetado (usar `rem`).

## 5. Pantallas y su vínculo con `care_sis`

Rutas y archivos según el catálogo de funcionalidades. "Figma" indica si existe en la maqueta.

### 5.1 Acceso y registro (layout público)

| # | Pantalla | Archivo | Figma | Contenido clave |
|---|---|---|---|---|
| W1 | Inicio | `index.html` | Sí | Título display "Tus medicamentos, siempre a tiempo.", texto de apoyo, botones "Iniciar sesión" (primario) y "Crear mi cuenta" (secundario). A la derecha tarjeta con 3 beneficios (recordatorios, historial y reporte PDF, cuidador de confianza), cada uno con ícono en círculo `--secundario-50`. |
| W2 | Registro de paciente | `views/registro_paciente.html` → `controllers/registrar_paciente.php` | Sí | Tarjeta 900 px. Campos en 2 columnas: Nombre(s), Apellido paterno, Apellido materno, Teléfono, Correo electrónico, Contraseña. Casilla "Acepto los términos y el aviso de privacidad". Botón "Crear mi cuenta" + enlace "Ya tengo cuenta". Error: correo duplicado (`Usuario::emailExiste()`). |
| W3 | Iniciar sesión | `views/login.html` → `controllers/login.php` | Sí | Tarjeta 560 px. Correo, Contraseña, enlace "¿Olvidaste tu contraseña?", botón "Entrar" (100 %), texto "¿Eres nuevo en MedHealth?" y botón secundario "Crear mi cuenta". |
| W4 | Recuperar contraseña | `views/olvide_contrasena.html` → `controllers/solicitar_recuperacion.php` | Sí | Enlace "‹ Volver a iniciar sesión", título, texto, campo Correo, aviso con ícono reloj "El enlace funciona durante 60 minutos y solo una vez.", botón "Enviar enlace". |
| W5 | ¿Tienes un cuidador? | `views/pregunta_cuidador.html` | Sí | Círculo verde con palomita, "¡Tu cuenta está lista, {nombre}!", dos tarjetas de 440 px: "Sí, registrar a mi cuidador" (botón primario → W6) y "Lo haré más tarde" (botón secundario → `views/dashboard.php`). |
| W6 | Registrar cuidador (inicial) | `views/registro_cuidador_inicial.html` → `controllers/registrar_cuidador.php` | Sí | Tarjeta 900 px. Nombre(s), Apellido paterno, Apellido materno, Parentesco (selector), Teléfono, Correo, Contraseña para su cuenta (nota: "Compártela con tu cuidador para que pueda entrar."). Botón "Guardar cuidador" + enlace "Omitir por ahora". |
| W7 | Términos y privacidad | `views/terminos.html` | Sí | Tarjeta 960 px con tres bloques `h2` en `--primario-700`: Aviso médico, Privacidad de tus datos, Tus responsabilidades. Botón secundario "Entendido". |

### 5.2 Paciente (layout con barra lateral) — **pendiente en Figma**

Todas requieren `requireLogin()` (`includes/auth.php`). Usar la barra lateral de la sección 4 con el ítem activo correspondiente.

**P1 · Hoy** — `views/dashboard.php` (ítem activo: Hoy)
- Encabezado: "Buenos días, {nombre}" (`h1`) y fecha en español con `obtenerFechaActualEspanol()` (`cuerpo-grande`, `--texto-2`).
- Lista vertical de tarjetas, una por medicamento activo del día. Cada tarjeta: círculo con ícono `pill` (64 px, `--secundario-50`), nombre (`cuerpo-grande-bold`), dosis · vía (`cuerpo`), hora de la próxima toma con ícono reloj, y a la derecha **tres botones**:
  - **Se tomó** (`confirmar`) → `controllers/registrar_toma.php` (estado `tomado`) → P2.
  - **Posponer 5 min** (`secundario`) → `controllers/posponer_toma.php` → P3.
  - **No se tomó** (`peligro` en versión contorno o `secundario` con texto `--error-600`) → abre P4.
- Estado vacío: "Hoy no tienes medicinas pendientes" con botón "Agregar medicamento".
- Tarjeta con toma ya registrada: fondo `--acento-50`, borde `--acento-500`, chip "Tomado" y sin botones.

**P2 · Toma confirmada** — `views/notificacion_tomada.html`
- Tarjeta centrada: círculo verde 116 px con palomita blanca dentro de halo `--acento-50`, "¡Muy bien, {nombre}!", "Registraste {medicamento} a las {hora}.", botón primario "Volver a mi panel".

**P3 · Alerta pospuesta** — `views/notificacion_posponer.html`
- Tarjeta centrada con ícono reloj, "Te lo recordaremos en 5 minutos", nueva hora, botón primario "Volver a mi panel".

**P4 · No se tomó (modal)** — `views/notificacion_no_tomada.php` → `controllers/registrar_toma.php` (estado `no_tomado`)
- Modal sobre el panel: título "¿Por qué no la tomaste?", textarea "Motivo (opcional)", botones "Guardar" (primario) y "Cancelar" (secundario). Muestra el nombre del medicamento.

**P5 · Mis medicamentos** — `views/medicamentos.php` (ítem activo: Mis medicamentos)
- Encabezado con botón primario "+ Agregar medicamento" (`views/agregar_medicamento.php`).
- Cuadrícula de 2 columnas de tarjetas. Cada tarjeta: ícono pill, nombre (`cuerpo-grande-bold`), "{dosis} · {vía}", barra de progreso (alto 14, pista `--borde`, avance `--acento-500`) con texto "Día X de N", frecuencia, indicaciones (`cuerpo`), y botones "Editar" (secundario) y "Eliminar" (peligro, con confirmación P8).

**P6 · Agregar medicamento** — `views/agregar_medicamento.php` → `controllers/guardar_medicamento.php`
- Tarjeta de formulario en 2 columnas: Nombre del medicamento, Dosis, Vía de administración (selector), Hora de la primera toma, Frecuencia (número + selector Minutos/Horas), Duración (número + selector Días/Semanas/Meses), Indicaciones (textarea, ancho completo). Botones "Guardar medicamento" (primario) y "Cancelar" (secundario).
- Reglas visibles: frecuencia y duración se convierten a horas/días enteros en el servidor (`convertirFrecuenciaAHoras()`, `convertirDuracionADias()`).

**P7 · Editar medicamento** — `views/editar_medicamento.php` → `controllers/actualizar_medicamento.php`
- Igual a P6 con valores precargados; la duración se desglosa con `desglosarDuracionDias()`. Título "Editar {nombre}". Botón "Guardar cambios".

**P8 · Eliminar medicamento (modal)** — `controllers/eliminar_medicamento.php`
- Modal de confirmación: "¿Eliminar {nombre}?", texto "Esta acción no se puede deshacer.", botones "Eliminar" (peligro) y "Cancelar".

**P9 · Historial** — `views/historial.php` (ítem activo: Historial)
- Tabla: MEDICAMENTO · FECHA (español) · HORA · OBSERVACIONES · ESTADO (chip Tomado/No tomado). Orden de más reciente a más antigua. Estado vacío con texto "Aún no hay tomas registradas".

**P10 · Reporte PDF** — `views/reporte.php` → `controllers/generar_pdf.php`
- Dos columnas: izquierda explicación ("Descarga tu avance en un documento PDF") y botón primario "Descargar reporte PDF"; derecha vista previa de una hoja blanca con cabecera, nombre del paciente, fecha de emisión y tabla de adherencia. Archivo `Reporte_CuidatePlus.pdf` (cambiar nombre si el producto se renombra).

**P11 · Mi perfil** — `views/perfil.php` (ítem activo: Mi perfil)
- Tarjeta con Nombre, Apellido paterno, Apellido materno, Correo (solo lectura). Botones "Cambiar contraseña" (secundario) y "Cerrar sesión" (`controllers/logout.php`).

**P12 · Cambiar contraseña** — `views/cambiar_contrasena.php` → `controllers/procesar_cambio_password.php`
- Tarjeta 560 px: Contraseña actual, Contraseña nueva, Confirmar contraseña nueva; botón "Guardar contraseña" y "Cancelar".

**P13 · Mi cuidador** — `views/perfil_cuidador.php` (ítem activo: Mi cuidador)
- Tarjeta del cuidador activo (`fecha_fin IS NULL`): avatar con iniciales, nombre, parentesco, teléfono, correo, "Desde {fecha}". Botones "Registrar nuevo cuidador" (primario → P14) y "Dar de baja" (peligro → P15).
- Debajo, tabla "Historial de cuidadores": CUIDADOR · INICIO · FIN · ESTADO (chip Alta/Baja).
- Sin cuidador activo: estado vacío con botón "Registrar cuidador".

**P14 · Registrar cuidador (dentro de la app)** — `views/alta_cuidador.php` → `controllers/registrar_cuidador.php`
- Mismo formulario que W6 dentro de la barra lateral. Aviso `--alerta-50`: "Si ya tienes un cuidador activo, se dará de baja automáticamente al registrar uno nuevo." (transacción PDO en `Cuidador::asignarPaciente()`).

**P15 · Baja de cuidador (modal)** — `views/baja_cuidador.php` → `controllers/procesar_baja_cuidador.php`
- Modal: "¿Dar de baja a {nombre}?", texto "Se conservará en tu historial de cuidadores.", botones "Dar de baja" (peligro) y "Cancelar".

### 5.3 Roles futuros (ya en Figma, no implementar todavía)

- **Administrador (web)**: panel con 4 indicadores, gráfica de adherencia semanal, alertas; gestión de usuarios con filtros y tabla.
- **Farmacéutica (web)**: tabla de pedidos de resurtido con pestañas por estado y detalle del pedido con seguimiento.
- Estilo: barra lateral `--primario-600` de 260 px, tablas y chips iguales a esta guía, texto de 16–18 px.

## 6. Orden de implementación sugerido

1. Crear `assets/css/medhealth.css` con tokens (sección 1–3) y componentes (sección 4); cargar en todas las vistas.
2. Plantillas compartidas en `includes/`: `layout_publico` (cabecera + tarjeta centrada) y `layout_paciente` (barra lateral + contenido + modal).
3. Acceso y registro (W1–W7), conservando los controladores existentes.
4. Panel del día (P1–P4) con los tres botones y sus controladores.
5. Medicamentos (P5–P8), Historial y Reporte (P9–P10).
6. Perfil, contraseña y cuidadores (P11–P15).

## 7. Criterios de aceptación visual

- Fuente Atkinson Hyperlegible en toda la interfaz; ningún texto de contenido menor a 18 px.
- Botones y campos de al menos 56 px de alto; una acción principal visible por pantalla.
- Colores exclusivamente de los tokens; estados siempre con texto + ícono.
- Pantalla de 1440 × 900 sin desplazamiento horizontal; contenido se reacomoda a 1024 px.
- Formularios con etiquetas visibles y mensajes de error junto al campo.
