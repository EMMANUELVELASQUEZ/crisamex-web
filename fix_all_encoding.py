#!/usr/bin/env python3
"""
CRISAMEX — Script de corrección total de encoding UTF-8
Corrige automáticamente TODOS los archivos PHP, SQL, CSS y JS
del proyecto para que los acentos, ñ y caracteres especiales
se vean correctamente en el sitio web.

USO:
    cd ~/Downloads/crisamex
    python3 fix_all_encoding.py

"""
import os
import re
import sys
from pathlib import Path

# ── Tabla de corrección de caracteres mal codificados ─────────────
# Estos son los patrones Ã³ = ó más comunes en Windows-1252/Latin-1
FIXES = {
    # Vocales con acento
    'Ã¡': 'á',  'Ã©': 'é',  'Ã­': 'í',  'Ã³': 'ó',  'Ãº': 'ú',
    'Ã ': 'à',  'Ã¨': 'è',  'Ã¬': 'ì',  'Ã²': 'ò',  'Ã¹': 'ù',
    # Mayúsculas con acento
    'Ã\x81': 'Á', 'Ã\x89': 'É', 'Ã\x8d': 'Í', 'Ã\x93': 'Ó', 'Ã\x9a': 'Ú',
    # Ñ
    'Ã±': 'ñ',  'Ã\x91': 'Ñ',
    # Ü Ü
    'Ã¼': 'ü',  'Ã\x9c': 'Ü',
    # Puntuación especial español
    'Â¿': '¿',  'Â¡': '¡',
    # Comillas tipográficas
    'â€œ': '"',  'â€\x9d': '"',  'â€˜': ''',  'â€™': ''',
    # Guión largo
    'â€"': '–',  'â€"': '—',
    # Puntos suspensivos
    'â€¦': '…',
    # Símbolo de grado
    'Â°': '°',
    # Marca registrada
    'Â®': '®',
    # Copyright
    'Â©': '©',
    # Bullet
    'â€¢': '•',
    # Tildes sueltas
    'Ã\xa0': 'à', 'Ã\xa1': 'á',
    # Patrones residuales
    'Ã': 'Á',
}

# Correcciones adicionales a nivel de bytes
BYTE_FIXES = [
    # Doble codificación UTF-8
    (b'\xc3\xa1', 'á'),  # á
    (b'\xc3\xa9', 'é'),  # é
    (b'\xc3\xad', 'í'),  # í
    (b'\xc3\xb3', 'ó'),  # ó
    (b'\xc3\xba', 'ú'),  # ú
    (b'\xc3\x81', 'Á'),  # Á
    (b'\xc3\x89', 'É'),  # É
    (b'\xc3\x8d', 'Í'),  # Í
    (b'\xc3\x93', 'Ó'),  # Ó
    (b'\xc3\x9a', 'Ú'),  # Ú
    (b'\xc3\xb1', 'ñ'),  # ñ
    (b'\xc3\x91', 'Ñ'),  # Ñ
    (b'\xc3\xbc', 'ü'),  # ü
    (b'\xc2\xbf', '¿'),  # ¿
    (b'\xc2\xa1', '¡'),  # ¡
    (b'\xc2\xb0', '°'),  # °
    (b'\xc3\x9c', 'Ü'),  # Ü
]

# ── Archivos que NO deben modificarse ─────────────────────────────
SKIP_FILES = {
    '.env', '.gitignore', 'composer.lock', 'package-lock.json',
    'fix_all_encoding.py', 'reset_passwords.php',
}

SKIP_DIRS = {
    '.git', 'vendor', 'node_modules', '.docker',
    '__pycache__', '.idea', '.vscode',
}

EXTENSIONS = {'.php', '.html', '.htm', '.css', '.js', '.sql', '.txt', '.xml', '.json'}

# ── Colores para terminal ──────────────────────────────────────────
GREEN  = '\033[92m'
RED    = '\033[91m'
YELLOW = '\033[93m'
BLUE   = '\033[94m'
RESET  = '\033[0m'
BOLD   = '\033[1m'

def fix_text_content(content: str) -> tuple[str, int]:
    """Aplica todas las correcciones de caracteres al contenido."""
    changes = 0
    for wrong, correct in FIXES.items():
        count = content.count(wrong)
        if count > 0:
            content = content.replace(wrong, correct)
            changes += count
    return content, changes

def fix_file(filepath: Path) -> tuple[bool, int, str]:
    """
    Corrige el encoding de un archivo.
    Retorna: (fue_modificado, num_cambios, mensaje)
    """
    filename = filepath.name

    if filename in SKIP_FILES:
        return False, 0, "omitido"

    ext = filepath.suffix.lower()
    if ext not in EXTENSIONS:
        return False, 0, "extensión no relevante"

    # Leer el archivo en modo binario para detectar el encoding
    try:
        raw = filepath.read_bytes()
    except Exception as e:
        return False, 0, f"error de lectura: {e}"

    # Intentar decodificar como UTF-8
    try:
        content = raw.decode('utf-8')
        encoding_original = 'utf-8'
    except UnicodeDecodeError:
        # Intentar como Latin-1 (Windows-1252)
        try:
            content = raw.decode('latin-1')
            encoding_original = 'latin-1'
        except Exception as e:
            return False, 0, f"no se pudo leer: {e}"

    # Aplicar correcciones de texto
    content_fixed, changes = fix_text_content(content)

    # Si el archivo era Latin-1 o hubo cambios, guardarlo como UTF-8
    if encoding_original != 'utf-8' or changes > 0:
        try:
            # Guardar respaldo del original
            backup = filepath.with_suffix(filepath.suffix + '.bak')
            backup.write_bytes(raw)

            # Guardar archivo corregido en UTF-8
            filepath.write_text(content_fixed, encoding='utf-8')
            return True, changes, f"{encoding_original}→UTF-8, {changes} correcciones"
        except Exception as e:
            return False, 0, f"error al guardar: {e}"

    return False, 0, "ya estaba en UTF-8 correcto"

def add_utf8_header_to_php(filepath: Path) -> bool:
    """
    Asegura que los archivos PHP clave tengan mb_internal_encoding al inicio.
    Solo para: index.php
    """
    if filepath.name != 'index.php':
        return False

    try:
        content = filepath.read_text(encoding='utf-8')
    except:
        return False

    utf8_header = "mb_internal_encoding('UTF-8');\nmb_http_output('UTF-8');\nini_set('default_charset', 'UTF-8');\n"

    # Verificar si ya tiene configuración UTF-8
    if "mb_internal_encoding" in content or "mb_http_output" in content:
        return False

    # Insertar después de <?php
    if content.startswith('<?php'):
        new_content = '<?php\n' + utf8_header + '\n' + content[5:].lstrip('\n')
        filepath.write_text(new_content, encoding='utf-8')
        return True

    return False

def process_directory(root_path: Path):
    """Procesa todos los archivos en el directorio recursivamente."""

    print(f"\n{BOLD}{'═'*65}{RESET}")
    print(f"{BOLD}  CRISAMEX — Corrector de Encoding UTF-8{RESET}")
    print(f"{BOLD}{'═'*65}{RESET}")
    print(f"  Directorio: {root_path}")
    print(f"{'─'*65}\n")

    total_files    = 0
    fixed_files    = 0
    total_changes  = 0
    skipped        = 0
    errors         = []
    fixed_list     = []

    # Recorrer todos los archivos
    for dirpath, dirnames, filenames in os.walk(root_path):
        # Excluir directorios que no deben procesarse
        dirnames[:] = [d for d in dirnames if d not in SKIP_DIRS]

        for filename in sorted(filenames):
            filepath = Path(dirpath) / filename

            # Calcular ruta relativa para mostrar
            try:
                rel_path = filepath.relative_to(root_path)
            except ValueError:
                rel_path = filepath

            ext = filepath.suffix.lower()
            if ext not in EXTENSIONS:
                continue

            total_files += 1

            # Corregir el archivo
            was_fixed, changes, msg = fix_file(filepath)

            if was_fixed:
                fixed_files   += 1
                total_changes += changes
                fixed_list.append((str(rel_path), changes, msg))
                print(f"  {GREEN}✓{RESET}  {rel_path}")
                print(f"      {BLUE}→ {msg}{RESET}")

                # Agregar header UTF-8 a index.php si aplica
                if filename == 'index.php':
                    if add_utf8_header_to_php(filepath):
                        print(f"      {YELLOW}→ Header UTF-8 agregado a index.php{RESET}")
            else:
                if "error" in msg:
                    errors.append((str(rel_path), msg))
                    print(f"  {RED}✗{RESET}  {rel_path}")
                    print(f"      {RED}→ {msg}{RESET}")
                else:
                    skipped += 1
                    # Solo mostrar archivos PHP que ya están bien
                    if ext == '.php':
                        print(f"  {YELLOW}·{RESET}  {rel_path}  {YELLOW}(ok){RESET}")

    # ── RESUMEN FINAL ─────────────────────────────────────────────
    print(f"\n{'═'*65}")
    print(f"{BOLD}  RESUMEN{RESET}")
    print(f"{'─'*65}")
    print(f"  Archivos analizados:    {total_files}")
    print(f"  {GREEN}Archivos corregidos:    {fixed_files}{RESET}")
    print(f"  {BLUE}Caracteres corregidos:  {total_changes}{RESET}")
    print(f"  Ya correctos:           {skipped}")

    if errors:
        print(f"\n  {RED}Errores ({len(errors)}):{RESET}")
        for path, msg in errors:
            print(f"    {RED}✗ {path}: {msg}{RESET}")

    if fixed_files > 0:
        print(f"\n{BOLD}{'─'*65}{RESET}")
        print(f"{GREEN}{BOLD}  ✅ Archivos corregidos:{RESET}")
        for path, changes, msg in fixed_list:
            print(f"    → {path}  ({changes} correcciones)")
        print(f"\n  {YELLOW}⚠  Se crearon respaldos .bak de cada archivo modificado.{RESET}")
        print(f"  {YELLOW}   Elimínalos con: find . -name '*.bak' -delete{RESET}")
    else:
        print(f"\n  {GREEN}✅ Todos los archivos ya están en UTF-8 correcto.{RESET}")

    print(f"{'═'*65}\n")

    return fixed_files, total_changes

def main():
    # Detectar directorio del proyecto
    if len(sys.argv) > 1:
        root = Path(sys.argv[1])
    else:
        # Auto-detectar: buscar carpeta crisamex
        candidates = [
            Path.home() / 'Downloads' / 'crisamex',
            Path.cwd(),
            Path.cwd().parent,
        ]
        root = None
        for candidate in candidates:
            if candidate.exists() and candidate.is_dir():
                root = candidate
                break

    if not root or not root.exists():
        print(f"{RED}Error: No se encontró el directorio del proyecto.{RESET}")
        print(f"Uso: python3 fix_all_encoding.py /ruta/al/proyecto")
        sys.exit(1)

    fixed, changes = process_directory(root)

    if fixed > 0:
        print(f"\n{BOLD}PRÓXIMOS PASOS:{RESET}")
        print(f"  1. cd {root}")
        print(f"  2. git add .")
        print(f"  3. git commit -m 'Fix: UTF-8 encoding - acentos y ñ corregidos'")
        print(f"  4. git push origin main")
        print(f"\n  Railway redesplegará automáticamente en 3-5 minutos.")
        print(f"  También ejecuta el SQL de charset en Railway MySQL.\n")

if __name__ == '__main__':
    main()
