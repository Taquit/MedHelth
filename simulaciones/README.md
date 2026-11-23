# Directorio de Simulaciones del Sistema MedHealth (`care_sis`)

Este directorio contiene conjuntos de datos simulados en formato JSON diseñados para pruebas unitarias, validacion de modelos de base de datos, desarrollo de interfaz sin backend activo y pruebas de integracion de flujos clinicos.

---

## 1. Estructura de Archivos

```
simulaciones/
├── usuarios.json                        # Entidad usuarios (credenciales y roles)
├── pacientes.json                       # Entidad pacientes (perfil demografico)
├── cuidadores.json                      # Entidad cuidadores (datos y parentesco)
├── paciente_cuidador.json               # Relacion N:M pacientes y cuidadores
├── recetas.json                         # Prescripciones medicas
├── medicamentos.json                    # Medicamentos asociados a recetas
├── historial_tomas.json                 # Registro de tomas (tomado / no_tomado)
├── notificaciones.json                  # Cola de alertas (pendiente, pospuesta, vencida)
├── reportes.json                        # Bitacora de reportes de adherencia
├── recuperacion_password.json           # Tokens temporales de recuperacion
├── escenario_completo_interactivo.json  # Escenario integral anidado de paciente
├── escenario_escalamiento_alertas.json  # Traza temporal de snooze y alerta a cuidador
├── escenario_panel_cuidador.json        # Vista de cuidador supervisando varios pacientes
├── mock_api_endpoints.json              # Payloads de peticion y respuesta de controladores
├── validar_datos.py                     # Script de validacion de integridad y sintaxis
└── README.md                            # Guia de uso y documentacion
```

---

## 2. Entidades Relacionales Individuales

Cada archivo representa una tabla del modelo relacional definido en [docs/update_models.md](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/docs/update_models.md):

| Archivo | Entidad | Llave Primaria | Llaves Foraneas | Descripcion |
| :--- | :--- | :--- | :--- | :--- |
| `usuarios.json` | `usuarios` | `id_usuario` | Ninguna | Cuentas de acceso (paciente y cuidador) con hashes bcrypt simulados. |
| `pacientes.json` | `pacientes` | `id_paciente` | `id_usuario` | Perfil del paciente con datos de contacto. |
| `cuidadores.json` | `cuidadores` | `id_cuidador` | `id_usuario` | Perfil de cuidadores familiares y profesionales. |
| `paciente_cuidador.json` | `paciente_cuidador` | `id_relacion` | `id_paciente`, `id_cuidador` | Vinculos activos e historicos entre pacientes y cuidadores. |
| `recetas.json` | `recetas` | `id_receta` | `id_paciente` | Recetas emitidas con observaciones clinicas. |
| `medicamentos.json` | `medicamentos` | `id_medicamento` | `id_receta` | Posologia (frecuencia en horas, duracion, hora de inicio, via). |
| `historial_tomas.json` | `historial_tomas` | `id_toma` | `id_medicamento` | Registro de cumplimiento u omision voluntaria con motivos. |
| `notificaciones.json` | `notificaciones` | `id_notificacion` | `id_medicamento`, `id_toma` | Estado de alarmas, contador de posposiciones y bandera de escalamiento. |
| `reportes.json` | `reportes` | `id_reporte` | `id_paciente`, `id_medicamento` | Registro de reportes generados por sistema, cuidador o paciente. |
| `recuperacion_password.json` | `recuperacion_password` | `id_recuperacion` | `id_usuario` | Tokens hash de 64 caracteres, marcas de tiempo y bandera de uso. |

---

## 3. Escenarios de Simulacion Avanzados

### 3.1. Escenario Completo Interactivo (`escenario_completo_interactivo.json`)
Contiene una estructura anidada que representa la experiencia completa de un paciente:
* Datos de perfil (`Maria Gonzalez Perez`).
* Cuidadores vinculados (hija y enfermero particular).
* Recetas medicas activas y desglose de medicamentos.
* Agenda diaria de tomas con estados en tiempo real (dosis tomada de las 08:00, dosis pendientes de las 13:00, 20:00 y 21:00).
* Indicadores clave de rendimiento (KPIs): 95% de adherencia global y racha de 14 dias consecutivos.

### 3.2. Escenario de Escalamiento de Alertas (`escenario_escalamiento_alertas.json`)
Describe paso a paso la transicion temporal del motor de notificaciones ante una omision:
1. **T=00m:** Disparo inicial de recordatorio (`pendiente`).
2. **T=+05m:** Paciente presiona posponer 10 minutos (`pospuesta`, `pospuesta_veces: 1`).
3. **T=+15m:** Segunda posposicion (`pospuesta_veces: 2`, limite alcanzado).
4. **T=+40m:** Agotamiento de la ventana de gracia (`vencida`, `notificar_cuidador: 1`).
5. **T=+42m:** Disparo de notificacion SMS/Push al telefono del cuidador registrado.
6. **T=+45m:** Contacto cuidador-paciente y registro formal de la omision por nauseas en `historial_tomas`.

### 3.3. Escenario de Panel de Cuidador (`escenario_panel_cuidador.json`)
Modela la interfaz del cuidador profesional supervisando multiples pacientes simultaneamente:
* **Paciente 1 (Maria Gonzalez):** Estado verde, 95% adherencia semanal, sin alertas pendientes.
* **Paciente 2 (Carlos Ruiz):** Estado rojo critico, 60% adherencia semanal, 1 alerta vencida urgente (Tramadol) con boton de accion inmediata de llamada telefonica.

### 3.4. Mock de Endpoints API (`mock_api_endpoints.json`)
Ejemplos de solicitudes (GET/POST) y respuestas JSON para pruebas de integracion en los controladores:
* `controllers/auth.php`: Login y solicitud de recuperacion de contraseña.
* `controllers/tomas.php`: Confirmacion de toma de medicamento.
* `controllers/alertas.php`: Posponer recordatorio por 10 minutos.
* `controllers/medicamentos.php`: Listado de medicamentos programados para el dia en curso.
* `controllers/cuidador.php`: Vinculacion de nuevo paciente mediante codigo.
* `controllers/reportes.php`: Calculo de reporte de adherencia semanal.

---

## 4. Validacion de Datos

Para verificar que todos los archivos JSON cumplan con las reglas de integridad referencial y formato:

```bash
python3 simulaciones/validar_datos.py
```

El script comprueba:
1. Validez de la sintaxis JSON en todos los archivos.
2. Ausencia de caracteres no permitidos o emojis.
3. Existencia y concordancia de llaves primarias y foraneas en todas las relaciones.
