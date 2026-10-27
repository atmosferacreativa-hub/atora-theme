#!/usr/bin/env bash
# Recorre las páginas de la academia (visitante, estudiante y docente) con
# Atora Meridian activo y ATORA LMS. Falla si alguna página:
#  - no responde lo esperado o muestra un error de PHP;
#  - anida dos <main> (las plantillas del plugin ya traen el suyo);
#  - no lleva la identidad del tema (variables --atora-* y capa atora-lms.css);
#  - deja avisos de PHP del tema en debug.log.
# Uso: check-academy-pages.sh <ruta-wordpress> <url-base>
set -u
WP="$1"
BASE="${2%/}"
failures=0

cookie_for() {
	SHOT_USER="$1" wp eval-file "$(dirname "$0")/auth-cookie.php" --path="$WP" 2>/dev/null
}

check() {
	local who="$1" path="$2" expected="$3" cookie="$4" standalone="${5:-}"
	local out=/tmp/academy-page.html code mains
	code=$(curl -s -L -o "$out" -w '%{http_code}' ${cookie:+-H "Cookie: $cookie"} "$BASE$path")
	mains=$(grep -o '<main[ >]' "$out" | wc -l)
	if [[ "$code" != "$expected" ]]; then
		echo "FAIL [$who] $path: HTTP $code (esperado $expected)"; failures=$((failures + 1)); return
	fi
	if grep -qiE 'Fatal error|Parse error|critical error|Warning</b>:' "$out"; then
		echo "FAIL [$who] $path: error de PHP en la página"; failures=$((failures + 1)); return
	fi
	if [[ -n "$standalone" ]]; then
		echo "OK   [$who] $path: HTTP $code (página propia del plugin)"; return
	fi
	if [[ "$expected" == 200 && "$mains" -ne 1 ]]; then
		echo "FAIL [$who] $path: $mains elementos <main> (debe haber uno)"; failures=$((failures + 1)); return
	fi
	if [[ "$expected" == 200 ]] && ! grep -q 'id="atora-identity"' "$out"; then
		echo "FAIL [$who] $path: falta la identidad del tema"; failures=$((failures + 1)); return
	fi
	echo "OK   [$who] $path: HTTP $code"
}

STUDENT=$(cookie_for estudiante_e2e)
TEACHER=$(cookie_for docente_e2e)
if [[ -z "$STUDENT" || -z "$TEACHER" ]]; then
	echo "FAIL no se pudo crear la sesión de prueba (wp eval-file)"; exit 1
fi

for path in / /cursos/ /programas/ /cursos/curso-e2e/ /programas/programa-e2e/ "/?s=curso"; do
	check visitante "$path" 200 ""
done
# Las páginas públicas del plugin también deben cargar la identidad de la academia.
check visitante /verificar/ 200 ""
check visitante /eliminar-cuenta/ 200 ""
check visitante /no-existe/ 404 ""

for path in /panel-estudiante/ /cursos/curso-e2e/ /leccion/leccion-e2e-videos/ /leccion/leccion-e2e-quiz/ /leccion/leccion-e2e-tarea/; do
	check estudiante "$path" 200 "$STUDENT"
done

for path in /panel-docente/ /cursos/curso-e2e/; do
	check docente "$path" 200 "$TEACHER"
done

if [[ -f "$WP/wp-content/debug.log" ]] && grep -E 'PHP (Fatal|Parse|Warning|Notice|Deprecated)' "$WP/wp-content/debug.log" | grep -q 'themes/atora-theme'; then
	echo "FAIL avisos de PHP del tema en debug.log:"
	grep -E 'PHP (Fatal|Parse|Warning|Notice|Deprecated)' "$WP/wp-content/debug.log" | grep 'themes/atora-theme' | head -20
	failures=$((failures + 1))
fi

exit $failures
