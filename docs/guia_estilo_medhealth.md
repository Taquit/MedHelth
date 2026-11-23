# MedHealth — Guía de estilo visual

Documento de entrega con el diseño visual del sistema MedHealth (adherencia a medicamentos para adultos mayores).

## 1. Concepto de diseño

**"Lectura Fácil Suiza"**: cada pantalla se comporta como un horario impreso institucional. Se usan líneas gruesas de tinta para separar filas, números tabulares grandes para las horas de cada toma y mucho espacio en blanco para proteger la lectura de personas con baja visión.

Principios:

- Una decisión clara por pantalla.
- Líneas estructurales de 2 px a 4 px en lugar de sombras.
- Una sola familia tipográfica, con tamaño mínimo de 18 px en el cuerpo.
- Botones y campos grandes (56–60 px de alto).
- Todo texto en español, con un icono SVG junto al texto. El color nunca es el único indicador de estado.
- Diseño pensado para escritorio (mínimo 1024 px).

## 2. Paleta de colores

### Primarios (verde bosque)

| Nombre | Token | Hex | Uso |
|---|---|---|---|
| Bosque profundo | `bosque-800` | `#143A2E` | Barra lateral, barra superior, encabezados de tabla, botón primario, enlaces |
| Bosque medianoche | `bosque-900` | `#0E2B22` | Hover del botón primario, texto en chips informativos |
| Bosque dosel | `bosque-700` | `#1D5040` | Tono intermedio de apoyo |
| Bruma de bosque | `bosque-100` | `#E3EDE8` | Chips y avisos informativos, insignias de iconos |

### Estado: confirmado (verde)

| Nombre | Token | Hex | Uso |
|---|---|---|---|
| Esmeralda | `confirmado` | `#1B6B46` | Botón "Se tomó", barra de progreso, bordes de confirmado |
| Esmeralda profundo | `confirmado-700` | `#134E33` | Hover y texto sobre fondos confirmados |
| Menta | `confirmado-50` | `#E1F2E9` | Fondo de chips y avisos de éxito |

### Estado: pendiente (ámbar)

| Nombre | Token | Hex | Uso |
|---|---|---|---|
| Ocre ámbar | `pendiente` | `#B06A0C` | **Contorno de foco**, borde de la toma pendiente |
| Ocre quemado | `pendiente-700` | `#7B4905` | Texto en chips y avisos de pendiente |
| Crema cálido | `pendiente-50` | `#FFFBF0` | Fondo de la siguiente toma y de avisos |

### Estado: error (rojo)

| Nombre | Token | Hex | Uso |
|---|---|---|---|
| Bermellón | `error-600` | `#B83227` | Acciones destructivas, botón "No se tomó", campos inválidos |
| Óxido profundo | `error-700` | `#91241B` | Hover y texto en chips y avisos de error |
| Rubor | `error-50` | `#FCECEB` | Fondo de chips, avisos y campos con error |

### Neutros

| Nombre | Token | Hex | Uso |
|---|---|---|---|
| Papel salvia | `papel` | `#ECF1EE` | Fondo general de lectura, ítem activo del menú |
| Hoja | `hoja` | `#FFFFFF` | Tarjetas, formularios, tablas, modales, campos |
| Tinta | `tinta` | `#141917` | Texto principal, líneas estructurales de 3–4 px |
| Tinta 2 | `tinta-2` | `#444E4A` | Texto secundario, bordes de campos, horas completadas |
| Regla | `regla` | `#D4CEC2` | Líneas secundarias, bordes de tarjetas, pista de progreso |
| Salvia asentado | `timetable-done` | `#E2EAE5` | Fila de toma ya confirmada |

### Reglas de color

- **Nunca solo color**: cada estado combina color, texto explícito ("Tomado hoy", "Toma pendiente", "Omitido hoy") e icono SVG.
- **Foco ocre**: todo elemento interactivo muestra al enfocarse un contorno de `3px solid #B06A0C` con 2–3 px de separación.

## 3. Tipografía

**Familia única:** Atkinson Hyperlegible (Braille Institute), con respaldo `system-ui, -apple-system, sans-serif`. Pesos permitidos: 400 y 700. No se usa ninguna otra fuente ni pesos finos.

| Nivel | Tamaño | Peso | Interlineado | Tracking | Uso |
|---|---|---|---|---|---|
| Display | 2.75 rem (44 px) | 700 | 1.12 | -0.02em | Título principal de la página de inicio (baja a 2 rem bajo 640 px) |
| Hora de toma | 2.5 rem (40 px) | 700 | 1 | -0.02em | Hora de cada fila del horario (números tabulares) |
| Headline | 2.25 rem (36 px) | 700 | 1.18 | -0.015em | Títulos de página (h1), saludo diario |
| Title | 1.625 rem (26 px) | 700 | 1.3 | 0 | Secciones (h2), encabezados de tarjeta, nombre de marca |
| Body Large | 1.375 rem (22 px) | 400 / 700 | 1.5 | 0 | Subtítulos, fecha del día, texto explicativo principal |
| Body | 1.125 rem (18 px) | 400 / 700 | 1.55 | 0 | Instrucciones, celdas, etiquetas, ayudas (máx. 72 ch en textos largos) |
| Label | 1 rem (16 px) | 700 | 1.35 | 0.05em en mayúsculas | Solo chips, encabezados de tabla y etiquetas secundarias |

Reglas:

- **Piso de 18 px**: todo contenido de lectura, etiquetas de formulario y controles va a 18 px o más. Se permiten 16 px solo en chips, encabezados de tabla y metadatos.
- **Números tabulares**: horas, conteos y marcas de tiempo usan `font-variant-numeric: tabular-nums`.

## 4. Espaciado y forma

**Escala de espaciado:** 4, 8, 12, 16, 20, 24, 32, 40, 48 y 56 px (`--sp-1` a `--sp-14`).

**Radios:**

- Controles, campos, tarjetas e insignias: **6 px**.
- Chips y pistas de progreso: **4 px**. No se usan cápsulas completas (9999 px) ni círculos.

**Bordes:**

- 2 px: tarjetas, campos, chips y divisores de tabla.
- 3 px: botones, filas del horario, marcos de vista previa y modales.
- 4 px: encabezados de página, divisores de la barra lateral y bandas de sección.

**Elevación:** ninguna. No hay `box-shadow`, degradados ni vidrio esmerilado. La profundidad se logra con contraste de superficies (papel, hoja, bosque) y líneas sólidas. La única capa espacial es el modal de confirmación, con fondo `rgba(20, 25, 23, 0.62)` y borde de tinta de 3 px.

## 5. Componentes

### Botones

- Alto mínimo de 58 px (56 px dentro de filas del horario), relleno `14px 26px`, borde de 3 px, radio de 6 px.
- Texto de 20 px en negrita, con 10 px entre icono y etiqueta.
- Transición de 0.14 s. Deshabilitado: opacidad 0.55 y cursor `not-allowed`.

| Variante | Fondo | Texto | Hover |
|---|---|---|---|
| Primario | `#143A2E` | Blanco | `#0E2B22` |
| Confirmar ("Se tomó") | `#1B6B46` | Blanco | `#134E33` |
| Secundario | Blanco, borde tinta | `#141917` | Fondo `#ECF1EE` |
| Peligro | `#B83227` | Blanco | `#91241B` |
| Peligro contorno ("No se tomó") | Blanco, borde `#B83227` | `#B83227` | Fondo `#FCECEB`, texto `#91241B` |

### Campos de formulario

- Alto mínimo de 60 px (128 px en áreas de texto), relleno `14px 18px`, borde de 2 px `#444E4A`, fondo blanco, radio de 6 px, texto de 20 px.
- Etiqueta de 18 px en negrita sobre el campo, con 8 px de separación.
- Casillas de verificación de 28 × 28 px con `accent-color: #143A2E`.
- **Foco:** borde `#143A2E` más contorno `3px solid #B06A0C` con 2 px de separación.
- **Solo lectura:** fondo `#ECF1EE` y borde `#D4CEC2`.
- **Error:** borde `#B83227`, fondo `#FCECEB` y mensaje en negrita.

### Chips y avisos

- Radio de 4 px (6 px en avisos de ancho completo), borde de 2 px, texto en negrita de 16 px (18 px en avisos) en español, con icono SVG.

| Variante | Fondo | Borde | Texto |
|---|---|---|---|
| Confirmado | `#E1F2E9` | `#1B6B46` | `#134E33` |
| Pendiente | `#FFFBF0` | `#B06A0C` | `#7B4905` |
| Error | `#FCECEB` | `#B83227` | `#91241B` |
| Info | `#E3EDE8` | `#143A2E` | `#0E2B22` |

### Tarjetas y contenedores

- Fondo blanco (menta `#E1F2E9` para tarjetas de completado), radio de 6 px, sin sombra.
- Borde `2px solid #D4CEC2` en contenedores comunes, y `2px solid #141917` en formularios, catálogo de medicamentos, estados vacíos y tablas.
- Relleno de 32 px (40 × 48 px en formularios, 40 px en modales).

### Navegación

- **Barra lateral (paciente):** 272 px de ancho, fondo `#143A2E`, borde derecho de 4 px en tinta. Contiene la marca, 6 enlaces de 58 px de alto (texto de 20 px en negrita, iconos de 26 px) y al pie la identidad del paciente con el botón de salir. El ítem activo se invierte: fondo `#ECF1EE`, texto `#143A2E` y `aria-current="page"`.
- **Barra superior (pública):** 88 px de alto, fondo `#143A2E`, borde inferior de 4 px en tinta, marca a la izquierda y enlaces de 18 px en negrita a la derecha.

### Horario diario y libro de adherencia

- **Libro de adherencia:** tres celdas iguales ("Tomas de hoy", "Confirmadas", "Por confirmar") con conteos de 2 rem en negrita, tabulares, junto a etiquetas de 18 px.
- **Fila del horario:** cuadrícula de 3 columnas con borde de 3 px:
  - Hora de 148 px (40 px, tabular).
  - Detalle del medicamento, flexible: título de 24 px, dosis y vía, instrucciones y chip de estado.
  - Acciones de 440 px fijos.
  - La siguiente toma se resalta con fondo `#FFFBF0` y borde de 2 px `#B06A0C`. Las tomas confirmadas pasan a `#E2EAE5` y cambian los botones por el chip "Tomado hoy" sin mover las demás columnas.

## 6. Estructura de pantalla (layout)

- **Shell del paciente:** barra lateral fija de 272 px más área de trabajo de papel salvia con relleno `40px 48px 56px` y separación vertical de 28 px. El encabezado de página lleva una línea inferior de `4px solid #141917`.
- **Shell público:** barra superior de 88 px y contenedor centrado de máximo 1280 px (relleno `44px 40px 56px`). La portada se divide en `0.94fr / 1.06fr` con 44 px de separación, seguida de una banda de 3 columnas con línea superior de 4 px.
- **Puntos de quiebre:**
  - **≤ 1260 px:** barra lateral de 244 px, relleno `32px 28px`, horario en `124px / 1fr / 360px`, y rejillas de 2 columnas pasan a 1.
  - **≤ 1023 px:** barra lateral horizontal arriba, y filas, celdas, portada y formularios apilados en una columna.
  - **≤ 640 px:** barra pública de 64 px mínimo, Display de 2 rem y tarjetas con 24 px de relleno.

## 7. Qué hacer y qué evitar

**Hacer**

- Mantener el texto de lectura en 18 px o más.
- Dar a todo botón, enlace y campo un alto de 56–60 px o más.
- Acompañar cada color de estado con texto en español e icono SVG.
- Usar números tabulares en horas, conteos y marcas de tiempo.
- Mantener fijas las columnas de hora (148 px) y acciones (440 px) para que confirmar una toma no mueva el diseño.
- Conservar el contorno de foco de 3 px en `#B06A0C`.

**Evitar**

- Sombras, vidrio esmerilado o degradados decorativos.
- Botones tipo píldora o avatares circulares (radios solo de 4 px o 6 px).
- Una segunda familia tipográfica o pesos menores a 400.
- Varias acciones primarias compitiendo en la misma pantalla.
- Distinguir tomado, pendiente u omitido solo por color.
