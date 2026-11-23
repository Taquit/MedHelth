---
name: MedHealth
description: Swiss easy-read medication adherence system built for older adults and low-vision desktop legibility.
colors:
  bosque-800: "#143A2E"
  bosque-900: "#0E2B22"
  bosque-700: "#1D5040"
  bosque-100: "#E3EDE8"
  confirmado: "#1B6B46"
  confirmado-700: "#134E33"
  confirmado-50: "#E1F2E9"
  pendiente: "#B06A0C"
  pendiente-700: "#7B4905"
  pendiente-50: "#FFFBF0"
  error-600: "#B83227"
  error-700: "#91241B"
  error-50: "#FCECEB"
  papel: "#ECF1EE"
  hoja: "#FFFFFF"
  tinta: "#141917"
  tinta-2: "#444E4A"
  regla: "#D4CEC2"
  timetable-done: "#E2EAE5"
typography:
  display:
    fontFamily: "\"Atkinson Hyperlegible\", system-ui, -apple-system, sans-serif"
    fontSize: "2.75rem"
    fontWeight: 700
    lineHeight: 1.12
    letterSpacing: "-0.02em"
  timetable-hour:
    fontFamily: "\"Atkinson Hyperlegible\", system-ui, -apple-system, sans-serif"
    fontSize: "2.5rem"
    fontWeight: 700
    lineHeight: 1
    letterSpacing: "-0.02em"
    fontFeature: "\"tnum\""
  headline:
    fontFamily: "\"Atkinson Hyperlegible\", system-ui, -apple-system, sans-serif"
    fontSize: "2.25rem"
    fontWeight: 700
    lineHeight: 1.18
    letterSpacing: "-0.015em"
  title:
    fontFamily: "\"Atkinson Hyperlegible\", system-ui, -apple-system, sans-serif"
    fontSize: "1.625rem"
    fontWeight: 700
    lineHeight: 1.3
    letterSpacing: "0em"
  body-large:
    fontFamily: "\"Atkinson Hyperlegible\", system-ui, -apple-system, sans-serif"
    fontSize: "1.375rem"
    fontWeight: 400
    lineHeight: 1.5
    letterSpacing: "0em"
  body:
    fontFamily: "\"Atkinson Hyperlegible\", system-ui, -apple-system, sans-serif"
    fontSize: "1.125rem"
    fontWeight: 400
    lineHeight: 1.55
    letterSpacing: "0em"
  label:
    fontFamily: "\"Atkinson Hyperlegible\", system-ui, -apple-system, sans-serif"
    fontSize: "1rem"
    fontWeight: 700
    lineHeight: 1.35
    letterSpacing: "0.05em"
rounded:
  pill: "4px"
  control: "6px"
spacing:
  sp-1: "4px"
  sp-2: "8px"
  sp-3: "12px"
  sp-4: "16px"
  sp-5: "20px"
  sp-6: "24px"
  sp-8: "32px"
  sp-10: "40px"
  sp-12: "48px"
  sp-14: "56px"
components:
  button-primary:
    backgroundColor: "{colors.bosque-800}"
    textColor: "{colors.hoja}"
    rounded: "{rounded.control}"
    padding: "14px 26px"
    height: "58px"
  button-primary-hover:
    backgroundColor: "{colors.bosque-900}"
    textColor: "{colors.hoja}"
  button-secondary:
    backgroundColor: "{colors.hoja}"
    textColor: "{colors.tinta}"
    rounded: "{rounded.control}"
    padding: "14px 26px"
    height: "58px"
  button-secondary-hover:
    backgroundColor: "{colors.papel}"
    textColor: "{colors.bosque-900}"
  button-confirm:
    backgroundColor: "{colors.confirmado}"
    textColor: "{colors.hoja}"
    rounded: "{rounded.control}"
    padding: "14px 26px"
    height: "58px"
  button-confirm-hover:
    backgroundColor: "{colors.confirmado-700}"
    textColor: "{colors.hoja}"
  button-danger:
    backgroundColor: "{colors.error-600}"
    textColor: "{colors.hoja}"
    rounded: "{rounded.control}"
    padding: "14px 26px"
    height: "58px"
  button-danger-hover:
    backgroundColor: "{colors.error-700}"
    textColor: "{colors.hoja}"
  button-danger-outline:
    backgroundColor: "{colors.hoja}"
    textColor: "{colors.error-600}"
    rounded: "{rounded.control}"
    padding: "14px 26px"
    height: "58px"
  button-danger-outline-hover:
    backgroundColor: "{colors.error-50}"
    textColor: "{colors.error-700}"
  input-default:
    backgroundColor: "{colors.hoja}"
    textColor: "{colors.tinta}"
    rounded: "{rounded.control}"
    padding: "14px 18px"
    height: "60px"
  chip-confirmed:
    backgroundColor: "{colors.confirmado-50}"
    textColor: "{colors.confirmado-700}"
    typography: "{typography.label}"
    rounded: "{rounded.pill}"
    padding: "8px 14px"
  chip-pending:
    backgroundColor: "{colors.pendiente-50}"
    textColor: "{colors.pendiente-700}"
    typography: "{typography.label}"
    rounded: "{rounded.pill}"
    padding: "8px 14px"
  chip-error:
    backgroundColor: "{colors.error-50}"
    textColor: "{colors.error-700}"
    typography: "{typography.label}"
    rounded: "{rounded.pill}"
    padding: "8px 14px"
  chip-info:
    backgroundColor: "{colors.bosque-100}"
    textColor: "{colors.bosque-900}"
    typography: "{typography.label}"
    rounded: "{rounded.pill}"
    padding: "8px 14px"
  card-sheet:
    backgroundColor: "{colors.hoja}"
    textColor: "{colors.tinta}"
    rounded: "{rounded.control}"
    padding: "32px"
  card-form:
    backgroundColor: "{colors.hoja}"
    textColor: "{colors.tinta}"
    rounded: "{rounded.control}"
    padding: "40px 48px"
  sidebar-nav-item:
    backgroundColor: "{colors.bosque-800}"
    textColor: "{colors.papel}"
    rounded: "{rounded.control}"
    padding: "12px 16px"
    height: "58px"
  sidebar-nav-item-active:
    backgroundColor: "{colors.papel}"
    textColor: "{colors.bosque-800}"
    rounded: "{rounded.control}"
    padding: "12px 16px"
    height: "58px"
---

# Design System: MedHealth

## Overview

**Creative North Star: "Lectura Fácil Suiza"**

MedHealth applies the calm certainty of a Swiss easy-read printed timetable to daily medication management for older adults. Rather than floating cards, pastel gradients, or dense clinical-blue SaaS dashboards, every screen behaves like a well-printed institutional schedule: heavy ink horizontal rules divide tasks into unmistakable rows, monumental tabular numerals anchor each scheduled hour, and generous whitespace protects reading focus for patients with low vision.

The interface prioritizes one clear decision per screen. A deep forest-spruce navigation rail frames a cool sage-paper reading ground, while crisp white sheets hold forms, ledgers, and appointment-ready tables. Every interactive state is communicated through high-contrast color, heavy borders, iconography, and explicit Spanish copy together so no patient is ever forced to guess what a control does or whether a dose was recorded.

**Key Characteristics:**
- Print-grade Swiss timetable hierarchy using 2px–4px structural ink rules instead of drop shadows.
- Single-family `Atkinson Hyperlegible` typography with an 18px body floor and 40px tabular dose hours.
- Oversized, high-contrast click targets (56px–60px minimum height) built for older adults and low-vision desktop use.
- Fixed-column daily schedule rows that preserve spatial stability when a dose transitions from pending to confirmed.
- Spanish-only copy paired with clear inline SVG iconography so color is never the sole indicator of status.

## Colors

A grounded botanical-and-paper palette pairing deep forest spruce and ink black with semantic confirmation, pending-alert, and omission tones tuned for high contrast.

### Primary
- **Deep Forest Spruce** (`#143A2E`): Navigation rail background, public topbar, table headers, primary action buttons, and inline text links.
- **Midnight Spruce** (`#0E2B22`): Hover state for primary buttons, dark navigation items, and active link emphasis.
- **Canopy Spruce** (`#1D5040`): Supporting mid-tone spruce reserved for tonal hierarchy within primary surfaces.
- **Spruce Mist** (`#E3EDE8`): Informational chips, informational banners, and framed icon badges.

### Secondary
- **Emerald Spruce** (`#1B6B46`): Primary dose confirmation action ("Se tomó"), treatment progress fill, and confirmed status borders.
- **Deep Emerald** (`#134E33`): Hover state for dose confirmation buttons and high-contrast text inside confirmed chips, banners, and ledger counts.
- **Mint Wash** (`#E1F2E9`): Background surface for confirmed status chips, success banners, and completed medication containers.

### Tertiary
- **Warm Amber Ochre** (`#B06A0C`): Universal keyboard focus outline (`3px solid`), active pending timetable row border, and alert borders.
- **Burnt Ochre** (`#7B4905`): High-contrast text inside pending chips, warning banners, and pending ledger counts.
- **Warm Cream Highlight** (`#FFFBF0`): Surface highlight for the next pending dose row and warning banners.
- **Vermilion Rust** (`#B83227`): Destructive primary actions, "No se tomó" outline buttons, invalid form borders, and inline error messages.
- **Deep Rust** (`#91241B`): Hover state for destructive actions and high-contrast text inside error chips and banners.
- **Blush Tint** (`#FCECEB`): Background surface for error chips, error banners, and invalid form inputs.

### Neutral
- **Sage Paper** (`#ECF1EE`): Primary reading canvas across public and authenticated shells, active sidebar navigation fill, and high-contrast light text on forest-spruce chrome.
- **Crisp Sheet** (`#FFFFFF`): Structured containers, forms, tables, modals, ledger cells, and editable input fields.
- **Print Ink** (`#141917`): Primary body and heading text, 3px–4px structural dividers, and high-emphasis container borders.
- **Graphite Ink** (`#444E4A`): Secondary metadata text, helper copy, default input borders, and completed timetable hours.
- **Warm Rule** (`#D4CEC2`): Secondary horizontal rules, table cell dividers, default card borders, and progress bar tracks.
- **Settled Sage** (`#E2EAE5`): Receded background tint for timetable rows already confirmed as taken today.

### Named Rules
**The Never-Color-Alone Rule.** Every semantic state—confirmed, pending, or omitted—must pair its color token with explicit Spanish text ("Tomado hoy", "Toma pendiente", "Omitido hoy") and an inline SVG icon.

**The Focus-Ochre Rule.** Keyboard focus across every interactive element uses a 3px solid Warm Amber Ochre (`#B06A0C`) outline with a 2px–3px offset so focus remains unmistakably visible on both light paper and dark spruce surfaces.

## Typography

**Display Font:** Atkinson Hyperlegible (with `system-ui, -apple-system, sans-serif` fallback)
**Body Font:** Atkinson Hyperlegible (with `system-ui, -apple-system, sans-serif` fallback)

**Character:** Purpose-built for low-vision readers by the Braille Institute, `Atkinson Hyperlegible` maximizes character distinction between lookalike glyphs (`1`, `I`, `l`; `0`, `O`) and pairs regular (400) and bold (700) weights cleanly without ornamental flourishes.

### Hierarchy
- **Display** (700, `2.75rem` / 44px, `1.12` line-height, `-0.02em` tracking; scales to `2rem` below 640px): Public landing hero headline (`index.html`).
- **Timetable Hour** (700, `2.5rem` / 40px, `1` line-height, `-0.02em` tracking, tabular numerals): Monumental scheduled dose time anchoring the left column of every daily timetable row.
- **Headline** (700, `2.25rem` / 36px, `1.18` line-height, `-0.015em` tracking): Authenticated page titles (`h1`), including the daily patient greeting.
- **Title** (700, `1.625rem` / 26px, `1.3` line-height): Section headings (`h2`), card headers, and brand wordmark.
- **Body Large** (400 / 700, `1.375rem` / 22px, `1.5` line-height): Lead subtitles, daily date headers, and primary explanatory copy.
- **Body** (400 / 700, `1.125rem` / 18px, `1.55` line-height, max `72ch` on long-form legal text): Default reading size for instructions, table cells, form labels, and helper copy.
- **Label** (700, `1rem` / 16px, `1.35` line-height, `0.05em` tracking when uppercase): Reserved strictly for status chips, uppercase table headers, and secondary metadata tags.

### Named Rules
**The 18px Floor Rule.** All primary reading content, form labels, and interactive controls remain at or above `1.125rem` (18px); `1rem` (16px) is permitted only for chips, table headers, and compact metadata labels.

**The Tabular-Time Rule.** Every dose hour, adherence count, and history timestamp uses `font-variant-numeric: tabular-nums` so vertical columns of hours and counts align with printed precision.

## Layout

MedHealth is architected for desktop home browsers (1024px minimum to 1440px+ reference width) using two shell topologies built on a 4px/8px spacing scale (`--sp-1: 4px` through `--sp-14: 56px`):

- **Authenticated Patient Shell:** A fixed `272px` Deep Forest Spruce left sidebar separated from the Sage Paper workspace by a `4px` Print Ink vertical border. The main column uses `40px 48px 56px` padding and a `28px` vertical flex gap, opening with a bottom-ruled page header (`4px solid #141917`).
- **Public Shell:** An `88px`-tall Deep Forest Spruce topbar with a `4px` ink bottom rule above a centered `1280px` maximum-width workspace (`44px 40px 56px` padding). The hero splits `0.94fr / 1.06fr` with a `44px` gap, followed by a 3-column instructional band separated by a `4px` top rule.
- **Daily Timetable Grid:** Full-width horizontal rows structured as a strict 3-column grid (`148px` hour column, flexible `minmax(0, 1fr)` medication details, `440px` fixed action column) with `28px` column gaps and `26px 20px` row padding.
- **Responsive Breakpoints:**
  - `<= 1260px`: Sidebar narrows to `244px`, main padding tightens to `32px 28px`, timetable grid adjusts to `124px / 1fr / 360px`, and 2-column medication/report grids collapse to 1 column.
  - `<= 1023px`: Sidebar stacks to a horizontal wrapped bar at the top; timetable rows, ledger cells, hero, and 2-column forms stack to single columns.
  - `<= 640px`: Public topbar compresses to `64px` minimum height, display headline reduces to `2rem`, and card padding tightens to `24px`.

## Elevation & Depth

MedHealth is strictly flat. No component uses `box-shadow` or blurred drop-shadow elevation. Depth and visual hierarchy are conveyed exclusively through surface contrast (Sage Paper canvas vs. Crisp Sheet containers vs. Deep Forest Spruce chrome), structural ink rules (`2px`–`4px`), and tonal tinting for state changes (Warm Cream Highlight for due doses, Settled Sage for completed rows). Only confirmation dialogs introduce spatial layering, using a dark translucent scrim (`rgba(20, 25, 23, 0.62)`) behind a `3px` ink-bordered Crisp Sheet modal.

### Named Rules
**The Zero-Shadow Rule.** Never add `box-shadow` to cards, buttons, inputs, or navigation surfaces. Separate layers with surface color shifts and 2px–4px solid borders instead.

## Shapes

Form language is crisp, institutional, and print-inspired. Corners stay tightly controlled between `4px` and `6px` across the entire interface:

- **Controls, Inputs, Cards, Avatars, and Icon Badges:** Crisp rounded rectangle (`6px` radius).
- **Status Chips and Progress Tracks:** Tight rectangular tag (`4px` radius)—never full pill (`9999px`) capsules.
- **Structural Borders:** `2px` default borders on cards, inputs, chips, and table dividers; `3px` borders on buttons, timetable rows, preview frames, and modals; `4px` rules on page headers, sidebar dividers, and public section bands.

## Components

Every component is built to feel deliberate, sturdy, and immediately legible at arm's length, with generous hit areas and zero decorative ambiguity.

### Buttons
- **Shape:** Crisp rounded rectangle (`6px` radius) with `3px` solid border, `58px` minimum height (`56px` inside timetable rows), `14px 26px` padding, `1.25rem` (20px) bold type, and `10px` icon-to-label gap.
- **Primary:** Deep Forest Spruce (`#143A2E`) fill with Crisp Sheet (`#FFFFFF`) text; shifts to Midnight Spruce (`#0E2B22`) on hover.
- **Confirm Dose:** Emerald Spruce (`#1B6B46`) fill with Crisp Sheet (`#FFFFFF`) text; shifts to Deep Emerald (`#134E33`) on hover. Used exclusively for "Se tomó".
- **Secondary:** Crisp Sheet (`#FFFFFF`) fill with Print Ink (`#141917`) text and border; shifts to Sage Paper (`#ECF1EE`) with Midnight Spruce border on hover.
- **Danger / Danger Outline:** Vermilion Rust (`#B83227`) solid fill for destructive confirmations, or Crisp Sheet fill with Vermilion Rust border/text ("No se tomó") that tints to Blush Tint (`#FCECEB`) on hover.
- **Hover / Focus / Disabled:** Fast `0.14s ease` color transitions; `3px` Warm Amber Ochre focus ring; `0.55` opacity with `not-allowed` cursor when disabled.

### Chips & Banners
- **Style:** `4px` radius (`6px` for full-width banners), `2px` solid semantic border, bold `1rem` (`1.125rem` for banners) Spanish text paired with an inline SVG icon.
- **Variants:** Confirmed (`#E1F2E9` fill, `#1B6B46` border, `#134E33` text), Pending (`#FFFBF0` fill, `#B06A0C` border, `#7B4905` text), Error (`#FCECEB` fill, `#B83227` border, `#91241B` text), Info (`#E3EDE8` fill, `#143A2E` border, `#0E2B22` text).

### Cards / Containers
- **Corner Style:** Crisp rounded rectangle (`6px` radius).
- **Background:** Crisp Sheet (`#FFFFFF`), shifting to Mint Wash (`#E1F2E9`) for confirmed completion cards.
- **Shadow Strategy:** Strictly flat per The Zero-Shadow Rule.
- **Border:** `2px solid #D4CEC2` for standard containers; `2px solid #141917` for high-emphasis form sheets, medication catalog cards, empty states, and tables.
- **Internal Padding:** `32px` standard (`40px 48px` on form sheets, `40px` on confirmation modals).

### Inputs / Fields
- **Style:** `60px` minimum height (`128px` for textareas), `14px 18px` padding, `2px solid #444E4A` border, Crisp Sheet background, `6px` radius, `1.25rem` (20px) regular text, stacked beneath a `1.125rem` bold label with an `8px` gap. Checkboxes are oversized at `28px × 28px` with `accent-color: #143A2E`.
- **Focus:** Border shifts to Deep Forest Spruce (`#143A2E`) alongside a `3px solid #B06A0C` focus outline (`2px` offset).
- **Readonly / Error:** Readonly inputs use Sage Paper (`#ECF1EE`) fill and Warm Rule (`#D4CEC2`) border. Invalid inputs shift to Vermilion Rust (`#B83227`) border and Blush Tint (`#FCECEB`) fill, paired with a bold inline error message.

### Navigation
- **Authenticated Sidebar:** Fixed `272px` Deep Forest Spruce (`#143A2E`) rail with `4px` Print Ink right border. Contains brand header, 6 vertical `58px`-tall links (`1.25rem` bold, `26px` icons), and a bottom patient identity/logout footer. Active items invert to Sage Paper (`#ECF1EE`) fill with Deep Forest Spruce text (`aria-current="page"`).
- **Public Topbar:** `88px`-tall Deep Forest Spruce bar with `4px` Print Ink bottom border, brand badge + wordmark on the left, and `1.125rem` bold navigation links on the right.

### Swiss Daily Timetable Row & Adherence Ledger
- **Adherence Ledger:** Three equal Crisp Sheet cells (`Tomas de hoy`, `Confirmadas`, `Por confirmar`) displaying `2rem` bold tabular counts aligned on the baseline beside `1.125rem` labels.
- **Timetable Row:** Full-width `3px` ink-ruled row with `148px` monumental tabular hour (`2.5rem`), center medication details (`1.5rem` title, dosage/route, instructions, status chip), and a fixed `440px` right action column. The next due dose highlights in Warm Cream (`#FFFBF0`) with a `2px` Warm Amber Ochre border; confirmed rows recede to Settled Sage (`#E2EAE5`) and replace action buttons with a `Tomado hoy` chip without shifting adjacent columns.

## Do's and Don'ts

### Do:
- **Do** keep all body copy at `1.125rem` (18px) or larger and reserve `1rem` (16px) strictly for chips, table headers, and secondary metadata tags.
- **Do** size every clickable button, navigation link, and form input to at least `56px`–`60px` in height with generous padding.
- **Do** pair every status color with explicit Spanish text and an inline SVG icon (The Never-Color-Alone Rule).
- **Do** use `font-variant-numeric: tabular-nums` on every scheduled hour, ledger count, and history timestamp.
- **Do** maintain fixed-width hour (`148px`) and action (`440px`) columns on desktop timetable rows so confirming a dose never causes layout shift.
- **Do** preserve the `3px solid #B06A0C` keyboard focus outline on all interactive elements.

### Don't:
- **Don't** add `box-shadow`, glassmorphism, or decorative gradients to any surface; use surface shifts and 2px–4px solid borders instead.
- **Don't** use full-pill (`9999px`) buttons or circular avatar/icon halos; keep radii strictly at `4px` or `6px`.
- **Don't** introduce a second font family or thin font weights below `400`; `Atkinson Hyperlegible` in `400` and `700` is the sole typeface.
- **Don't** crowd multiple competing primary actions onto a single screen; keep each view anchored to one clear patient task.
- **Don't** rely on color alone to distinguish taken, pending, or missed doses.
