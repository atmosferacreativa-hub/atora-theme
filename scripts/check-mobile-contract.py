#!/usr/bin/env python3
"""
Contrato de la app móvil: el tema no debe cambiar la API que usa atora-mobile.

Uso:
  check-mobile-contract.py snapshot <base_url> <login> <password> <salida.json>
  check-mobile-contract.py compare  <a.json> <b.json>

`snapshot` inicia sesión en /wp-json/atora-mobile/v1 como lo hace la app,
recorre las rutas de lectura que usa y guarda las respuestas normalizadas
(sin fechas, tokens, firmas ni cursores, que cambian en cada llamada).
`compare` falla si dos instantáneas difieren: el CI toma una con un tema
mínimo y otra con Atora Meridian activo.

Solo usa la biblioteca estándar de Python.
"""

import json
import re
import sys
import urllib.error
import urllib.request

API = "/wp-json/atora-mobile/v1"

# Rutas de lectura del estudiante que la app usa al abrir y navegar.
STUDENT_ROUTES = [
    "/discovery",
    "/me",
    "/dashboard",
    "/courses",
    "/programs",
    "/grades",
    "/certificates",
    "/today",
    "/agenda",
    "/messages/threads",
    "/messages/unread-count",
    "/sync/changes",
]

VOLATILE_KEYS = re.compile(
    r"(token|expires|_at$|^at$|date|time|cursor|sig|nonce|url$|_url$|href|server_now|generated|version|etag|hash|last_access|updated)",
    re.I,
)


def request(method, url, body=None, token=None):
    data = json.dumps(body).encode() if body is not None else None
    req = urllib.request.Request(url, data=data, method=method)
    req.add_header("Accept", "application/json")
    if data is not None:
        req.add_header("Content-Type", "application/json")
    if token:
        req.add_header("Authorization", "Bearer " + token)
    try:
        with urllib.request.urlopen(req, timeout=60) as res:
            raw = res.read().decode("utf-8", "replace")
            return res.status, res.headers.get("Content-Type", ""), raw
    except urllib.error.HTTPError as err:
        return err.code, err.headers.get("Content-Type", ""), err.read().decode("utf-8", "replace")


def normalize(value, key=""):
    if isinstance(value, dict):
        return {k: ("<volatile>" if VOLATILE_KEYS.search(k) else normalize(v, k)) for k, v in sorted(value.items())}
    if isinstance(value, list):
        return [normalize(v, key) for v in value]
    return value


def snapshot(base, login, password, out):
    base = base.rstrip("/")
    result = {}

    status, ctype, raw = request(
        "POST", base + API + "/auth/login", {"login": login, "password": password, "device_name": "CI tema"}
    )
    if status != 200:
        sys.exit("No se pudo iniciar sesión en la API móvil: HTTP %s %s" % (status, raw[:300]))
    body = json.loads(raw)
    token = (body.get("session") or {}).get("access_token") or body.get("access_token")
    if not token:
        sys.exit("La respuesta de /auth/login no trae access_token.")
    result["/auth/login"] = {"status": status, "keys": sorted(body.keys())}

    for route in STUDENT_ROUTES:
        status, ctype, raw = request("GET", base + API + route, token=token)
        entry = {"status": status, "json": "application/json" in ctype}
        # Cualquier salida antes del JSON (espacios, avisos PHP, HTML del tema) rompe la app.
        stripped = raw.lstrip()
        entry["clean_body"] = raw == stripped and (stripped[:1] in ("{", "["))
        try:
            entry["body"] = normalize(json.loads(raw))
        except ValueError:
            entry["body"] = "<no-json>"
        result[route] = entry

    # Primer curso y su primera lección, como al tocar un curso en la app.
    courses = (json.loads(request("GET", base + API + "/courses", token=token)[2]) or {})
    items = courses.get("items") or courses.get("courses") or (courses if isinstance(courses, list) else [])
    if items:
        course_id = items[0].get("id")
        for route in ("/courses/%s" % course_id, "/courses/%s/grades" % course_id):
            status, ctype, raw = request("GET", base + API + route, token=token)
            try:
                result[route.replace(str(course_id), "{id}")] = {"status": status, "body": normalize(json.loads(raw))}
            except ValueError:
                result[route.replace(str(course_id), "{id}")] = {"status": status, "body": "<no-json>"}

    with open(out, "w", encoding="utf-8") as fh:
        json.dump(result, fh, ensure_ascii=False, indent=1, sort_keys=True)
    print("Instantánea: %d rutas en %s" % (len(result), out))


def compare(a_path, b_path):
    with open(a_path, encoding="utf-8") as fh:
        a = json.load(fh)
    with open(b_path, encoding="utf-8") as fh:
        b = json.load(fh)

    failures = 0
    for route in sorted(set(a) | set(b)):
        if a.get(route) != b.get(route):
            failures += 1
            print("DIFIERE %s" % route)
            print("  sin el tema: %s" % json.dumps(a.get(route), ensure_ascii=False)[:600])
            print("  con el tema: %s" % json.dumps(b.get(route), ensure_ascii=False)[:600])
        elif route != "/auth/login" and not a[route].get("clean_body", True):
            failures += 1
            print("SALIDA SUCIA %s (texto antes del JSON)" % route)
        else:
            print("OK %s (HTTP %s)" % (route, a[route].get("status")))

    sys.exit(1 if failures else 0)


if __name__ == "__main__":
    if len(sys.argv) == 6 and sys.argv[1] == "snapshot":
        snapshot(*sys.argv[2:6])
    elif len(sys.argv) == 4 and sys.argv[1] == "compare":
        compare(sys.argv[2], sys.argv[3])
    else:
        sys.exit(__doc__)
