#!/usr/bin/env python3
"""
Traducciones completas y coherentes (CI).

Para cada `languages/*.po` comprueba, contra `languages/atora-learning.pot`:
- que estén todas las cadenas y ninguna quede vacía;
- que cada traducción conserve los marcadores (%s, %d, %1$s…);
- que exista el `.mo` y no sea más viejo que su `.po`.

Solo biblioteca estándar. Uso: check-translations.py [carpeta-languages]
"""

import os
import re
import sys

PLACEHOLDER = re.compile(r"%(?:\d+\$)?[sdf]")


def unquote(line):
    value = line[line.index('"') + 1 : line.rindex('"')]
    return bytes(value, "utf-8").decode("unicode_escape").encode("latin-1").decode("utf-8")


def parse(path):
    """Devuelve {(ctx, msgid): {"plural": str|None, "strs": [..]}} (sin la cabecera)."""
    entries = {}
    cur = None
    field = None

    def flush():
        if cur and cur.get("msgid"):
            entries[(cur.get("msgctxt"), cur["msgid"])] = {"plural": cur.get("msgid_plural"), "strs": cur.get("strs", {})}

    with open(path, encoding="utf-8") as fh:
        for raw in fh:
            line = raw.strip()
            if not line or line.startswith("#"):
                continue
            if line.startswith("msgctxt "):
                flush()
                cur, field = {"msgctxt": unquote(line)}, "msgctxt"
            elif line.startswith("msgid "):
                if cur is None or "msgid" in cur:
                    flush()
                    cur = {}
                cur["msgid"], field = unquote(line), "msgid"
            elif line.startswith("msgid_plural "):
                cur["msgid_plural"], field = unquote(line), "msgid_plural"
            elif line.startswith("msgstr["):
                idx = int(line[7 : line.index("]")])
                cur.setdefault("strs", {})[idx] = unquote(line)
                field = ("strs", idx)
            elif line.startswith("msgstr "):
                cur.setdefault("strs", {})[0] = unquote(line)
                field = ("strs", 0)
            elif line.startswith('"'):
                if isinstance(field, tuple):
                    cur["strs"][field[1]] += unquote(line)
                else:
                    cur[field] = cur.get(field, "") + unquote(line)
    flush()
    return entries


def main():
    folder = sys.argv[1] if len(sys.argv) > 1 else os.path.join(os.path.dirname(__file__), "..", "languages")
    pot = parse(os.path.join(folder, "atora-learning.pot"))
    failures = 0
    pos = sorted(f for f in os.listdir(folder) if f.endswith(".po"))
    if not pos:
        print("No hay archivos .po")
        return 1

    for name in pos:
        path = os.path.join(folder, name)
        po = parse(path)
        problems = []
        for key in pot:
            entry = po.get(key)
            if not entry or not entry["strs"] or any(not s for s in entry["strs"].values()):
                problems.append("falta: %r" % key[1])
                continue
            wanted = sorted(PLACEHOLDER.findall(key[1]))
            for idx, text in entry["strs"].items():
                got = sorted(PLACEHOLDER.findall(text))
                # Formas plurales: pueden omitir el número (p. ej. "un comentario").
                if got != wanted and not (pot[key]["plural"] and set(got) <= set(wanted)):
                    problems.append("marcadores distintos en %r: %s" % (key[1], text))
        mo = path[:-3] + ".mo"
        if not os.path.exists(mo):
            problems.append("falta el .mo (wp i18n make-mo languages)")
        elif os.path.getmtime(mo) + 1 < os.path.getmtime(path) and not os.environ.get("CI"):
            problems.append("el .mo es más viejo que el .po (wp i18n make-mo languages)")
        if problems:
            failures += 1
            print("FAIL %s" % name)
            for p in problems[:15]:
                print("  - " + p)
        else:
            print("OK   %s (%d cadenas)" % (name, len(pot)))

    return 1 if failures else 0


if __name__ == "__main__":
    sys.exit(main())
