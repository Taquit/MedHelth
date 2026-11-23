# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

- **Primary user:** Older adults managing daily prescribed medication schedules on a desktop computer, often with low-vision or cognitive-load considerations.
- **Supporting actor:** A trusted family member or caregiver whose contact information is linked by the patient for support and accountability.

## Product Purpose

MedHealth helps patients organize daily medication treatments, confirm or postpone scheduled doses, record reasons for missed doses, and generate clear adherence history and PDF reports for medical appointments. Success means the patient can complete daily medication tracking independently, reliably, and without confusion.

## Positioning

A patient-led medication adherence web application built specifically for older adults and low-vision accessibility, combining single-action daily dose confirmation, caregiver contact linkage, and appointment-ready PDF adherence reports in Spanish.

## Operating Context

- **Environment:** Desktop web browser (1024 px minimum to 1440 px+ reference width) used at home by patients, plus printed or downloaded PDF reports brought to medical consultations.
- **Core workflows:**
  1. Patient account registration, login, password management, and optional initial caregiver linking.
  2. Medication catalog management (add, edit, delete) with automatic recipe grouping and normalized frequency/duration scheduling.
  3. Daily adherence dashboard to mark a dose as taken ("Se tomó"), snooze an alert by 5 minutes ("Posponer 5 minutos"), or record a missed dose with an explanation ("No se tomó").
  4. Chronological adherence history review and downloadable PDF report generation (`Reporte_MedHealth.pdf`).
  5. Caregiver lifecycle management (assign active caregiver, view contact details, deactivate/replace while preserving assignment history).

## Capabilities and Constraints

- **Technical stack (existing):** PHP, HTML, CSS, MySQL/PDO (`seguimiento_medicamentos` schema), and FPDF (`libs/fpdf/`) for PDF export.
- **Language:** Spanish-only UI (`lang="es"`), including localized dates and clinical/adherence terminology.
- **Security & data boundaries:** Session-based authentication (`includes/auth.php`), BCRYPT password hashing, and per-patient ownership checks (IDOR protection) on all medication and history operations.
- **Medical boundary:** Non-diagnostic tracking tool; must preserve the medical disclaimer and privacy terms (`views/terminos.html`).
- **Explicitly out of current scope:** Active caregiver dashboard/escalation portal, Administrator role, Pharmacy role, and mobile-first native/responsive layouts below 1024 px.
- **Known incomplete backend integration:** Password recovery (`views/olvide_contrasena.html`, `controllers/solicitar_recuperacion.php`) generates and stores tokens in `recuperacion_password`, while SMTP email delivery and the token-consumption reset screen remain pending.

## Brand Commitments

- **Official product name:** MedHealth (repository `care_sis`).
- **Tone and voice:** Clear, respectful, reassuring Spanish copy tailored for older adults.
- **Existing assets:** Brand logos in `Recursos/` (`logo.png`, `logoTemplate.png`, `logoUno.png`).

## Evidence on Hand

- Functional specification and module traceability in `docs/funcionalidades.md`.
- Database schema and seed data in `schema.sql` and `seeds.sql`.
- Simulation datasets and validation scripts in `simulaciones/` (for testing and scenario modeling).
- No live clinical trials, external customer testimonials, or usage benchmarks exist; future work must not fabricate them.

## Product Principles

1. **One primary action per screen:** Minimize cognitive load by keeping each view focused on a single clear task for the patient.
2. **Low-vision legibility first:** Prioritize large, readable text, generous spacing, and unambiguous interactive targets over information density.
3. **Never rely on color alone:** Pair every status, confirmation, or warning with explicit Spanish text and clear iconography.
4. **Preserve patient ownership and history:** Keep medication records, adherence logs, and caregiver assignment history accurate and strictly scoped to the authenticated patient.
5. **Respect medical boundaries:** Support daily adherence and doctor-patient communication without offering medical advice or diagnosis.

## Accessibility & Inclusion

- Designed primarily for older adults and users with low vision.
- Must maintain high contrast, large readable typography (18 px minimum for body content, 16 px only for secondary tags/chips), large click targets (56 px+ height), and clear non-color-only state indicators.
