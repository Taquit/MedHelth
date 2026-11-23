#!/usr/bin/env python3
import json
import os
import sys

BASE_DIR = os.path.dirname(os.path.abspath(__file__))

FILES = {
    "usuarios": "usuarios.json",
    "pacientes": "pacientes.json",
    "cuidadores": "cuidadores.json",
    "paciente_cuidador": "paciente_cuidador.json",
    "recetas": "recetas.json",
    "medicamentos": "medicamentos.json",
    "historial_tomas": "historial_tomas.json",
    "notificaciones": "notificaciones.json",
    "reportes": "reportes.json",
    "recuperacion_password": "recuperacion_password.json",
    "escenario_completo": "escenario_completo_interactivo.json",
    "escenario_escalamiento": "escenario_escalamiento_alertas.json",
    "escenario_cuidador": "escenario_panel_cuidador.json",
    "mock_api": "mock_api_endpoints.json"
}

def load_json(filename):
    path = os.path.join(BASE_DIR, filename)
    with open(path, "r", encoding="utf-8") as f:
        return json.load(f)

def check_emojis(data, filename):
    text = json.dumps(data)
    for ch in text:
        code = ord(ch)
        if (0x1F600 <= code <= 0x1F64F) or (0x1F300 <= code <= 0x1F5FF) or \
           (0x1F680 <= code <= 0x1F6FF) or (0x2600 <= code <= 0x26FF) or \
           (0x2700 <= code <= 0x27BF) or (0x1F900 <= code <= 0x1F9FF):
            print(f"Error: Emoji detectado en {filename}: {ch} (U+{code:X})")
            return False
    return True

def main():
    print("Iniciando validacion de datos de simulacion...")
    datasets = {}
    has_errors = False

    for key, fname in FILES.items():
        try:
            data = load_json(fname)
            datasets[key] = data
            if not check_emojis(data, fname):
                has_errors = True
            print(f"OK [Sintaxis JSON]: {fname}")
        except Exception as e:
            print(f"ERROR al cargar {fname}: {e}")
            has_errors = True

    if has_errors:
        sys.exit(1)

    print("\nValidando integridad referencial...")

    # Sets of Primary Keys
    usuarios_ids = {u["id_usuario"] for u in datasets["usuarios"]}
    pacientes_ids = {p["id_paciente"] for p in datasets["pacientes"]}
    cuidadores_ids = {c["id_cuidador"] for c in datasets["cuidadores"]}
    recetas_ids = {r["id_receta"] for r in datasets["recetas"]}
    medicamentos_ids = {m["id_medicamento"] for m in datasets["medicamentos"]}
    tomas_ids = {t["id_toma"] for t in datasets["historial_tomas"]}

    # Check pacientes -> usuarios
    for p in datasets["pacientes"]:
        if p["id_usuario"] not in usuarios_ids:
            print(f"Error FK: Paciente {p['id_paciente']} referencia usuario inexistente {p['id_usuario']}")
            has_errors = True

    # Check cuidadores -> usuarios
    for c in datasets["cuidadores"]:
        if c["id_usuario"] not in usuarios_ids:
            print(f"Error FK: Cuidador {c['id_cuidador']} referencia usuario inexistente {c['id_usuario']}")
            has_errors = True

    # Check paciente_cuidador
    for pc in datasets["paciente_cuidador"]:
        if pc["id_paciente"] not in pacientes_ids:
            print(f"Error FK: paciente_cuidador {pc['id_relacion']} paciente {pc['id_paciente']} no existe")
            has_errors = True
        if pc["id_cuidador"] not in cuidadores_ids:
            print(f"Error FK: paciente_cuidador {pc['id_relacion']} cuidador {pc['id_cuidador']} no existe")
            has_errors = True

    # Check recetas -> pacientes
    for r in datasets["recetas"]:
        if r["id_paciente"] not in pacientes_ids:
            print(f"Error FK: Receta {r['id_receta']} paciente {r['id_paciente']} no existe")
            has_errors = True

    # Check medicamentos -> recetas
    for m in datasets["medicamentos"]:
        if m["id_receta"] not in recetas_ids:
            print(f"Error FK: Medicamento {m['id_medicamento']} receta {m['id_receta']} no existe")
            has_errors = True

    # Check historial_tomas -> medicamentos
    for t in datasets["historial_tomas"]:
        if t["id_medicamento"] not in medicamentos_ids:
            print(f"Error FK: Toma {t['id_toma']} medicamento {t['id_medicamento']} no existe")
            has_errors = True

    # Check notificaciones -> medicamentos & tomas
    for n in datasets["notificaciones"]:
        if n["id_medicamento"] not in medicamentos_ids:
            print(f"Error FK: Notificacion {n['id_notificacion']} medicamento {n['id_medicamento']} no existe")
            has_errors = True
        if n["id_toma"] is not None and n["id_toma"] not in tomas_ids:
            print(f"Error FK: Notificacion {n['id_notificacion']} toma {n['id_toma']} no existe")
            has_errors = True

    # Check reportes -> pacientes & medicamentos
    for rep in datasets["reportes"]:
        if rep["id_paciente"] not in pacientes_ids:
            print(f"Error FK: Reporte {rep['id_reporte']} paciente {rep['id_paciente']} no existe")
            has_errors = True
        if rep["id_medicamento"] is not None and rep["id_medicamento"] not in medicamentos_ids:
            print(f"Error FK: Reporte {rep['id_reporte']} medicamento {rep['id_medicamento']} no existe")
            has_errors = True

    # Check recuperacion_password -> usuarios
    for rp in datasets["recuperacion_password"]:
        if rp["id_usuario"] not in usuarios_ids:
            print(f"Error FK: Recuperacion {rp['id_recuperacion']} usuario {rp['id_usuario']} no existe")
            has_errors = True

    if not has_errors:
        print("\nIntegridad referencial validada exitosamente: 100% consistencia y 0 referencias rotas.")
        print("Validacion de reglas: 0 emojis detectados.")
        return 0
    else:
        print("\nSe encontraron errores en los datos.")
        return 1

if __name__ == "__main__":
    sys.exit(main())
