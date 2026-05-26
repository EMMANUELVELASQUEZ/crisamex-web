# Tabla completa de reemplazos exactos encontrados en la página
fixes = {
    # Servicios - errores vistos en el HTML
    'CalibraciÃ³n de Equipos':              'Calibración de Equipos',
    'CalibraciÃ³n':                         'Calibración',
    'mediciÃ³n':                            'medición',
    'radiolÃ³gica':                         'radiológica',
    'radiolÃ³gico':                         'radiológico',
    'RadiolÃ³gica':                         'Radiológica',
    'RadiolÃ³gico':                         'Radiológico',
    'contaminaciÃ³n':                       'contaminación',
    'conservaciÃ³n':                        'conservación',
    'radiaciÃ³n':                           'radiación',
    'VerificaciÃ³n':                        'Verificación',
    'detecciÃ³n':                           'detección',
    'diagnÃ³stico':                         'diagnóstico',
    'precisiÃ³n':                           'precisión',
    'tÃ©cnicos':                            'técnicos',
    'tÃ©cnica':                             'técnica',
    'tÃ©cnico':                             'técnico',
    'TÃ©cnica':                             'Técnica',
    'radiaciÃ³n':                           'radiación',
    'Ã¡reas':                               'áreas',
    'especÃ­ficas':                         'específicas',
    'instalaciÃ³n':                         'instalación',
    'CapacitaciÃ³n':                        'Capacitación',
    'capacitaciÃ³n':                        'capacitación',
    'TrÃ¡mites':                            'Trámites',
    'trÃ¡mites':                            'trámites',
    'GestiÃ³n':                             'Gestión',
    'gestiÃ³n':                             'gestión',
    'formaciÃ³n':                           'formación',
    'FormaciÃ³n':                           'Formación',
    'AutorizaciÃ³n':                        'Autorización',
    'autorizaciÃ³n':                        'autorización',
    'SecretarÃ­a':                          'Secretaría',
    'EnergÃ­a':                             'Energía',
    'energÃ­a':                             'energía',
    # Más patrones comunes
    'Ã©':  'é', 'Ã³':  'ó', 'Ã¡':  'á',
    'Ã­':  'í', 'Ãº':  'ú', 'Ã±':  'ñ',
    'Ã':   'Á', 'Ã\x89': 'É', 'Ã\x93': 'Ó',
    'Â¿':  '¿', 'Â¡':  '¡',
}

import os, pathlib

project = pathlib.Path.home() / 'Downloads' / 'crisamex' / 'crisamex'
exts = {'.php', '.html', '.js'}
total = 0

for root, dirs, files in os.walk(project):
    dirs[:] = [d for d in dirs if d not in {'.git','vendor','node_modules'}]
    for fname in files:
        if not any(fname.endswith(e) for e in exts):
            continue
        fpath = pathlib.Path(root) / fname
        try:
            txt = fpath.read_text(encoding='utf-8', errors='replace')
        except:
            continue
        new = txt
        count = 0
        for wrong, right in fixes.items():
            if wrong in new:
                c = new.count(wrong)
                new = new.replace(wrong, right)
                count += c
        if count:
            fpath.write_text(new, encoding='utf-8')
            rel = fpath.relative_to(project)
            print(f'✅ {rel}  ({count} correcciones)')
            total += count

print(f'\n🎉 Total: {total} caracteres corregidos')
