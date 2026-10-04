#!/usr/bin/env bash
#
# Genera dist/atora-meridian-<versión>.zip desde una etiqueta o commit (git archive),
# nunca desde archivos sin confirmar.
#
# Uso:
#   ./scripts/build-dist.sh            # desde HEAD
#   ./scripts/build-dist.sh v3.0.9     # desde una etiqueta o commit
#
# La carpeta raíz del ZIP es `atora-theme`: es la carpeta con la que el tema está
# instalado. Con otro nombre WordPress lo instala como tema nuevo y el sitio pierde
# menús, widgets y ajustes del personalizador (theme_mods_atora-theme).

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

REF="${1:-HEAD}"
THEME_DIR="atora-theme"

if [[ -n "$(git status --porcelain --untracked-files=normal)" ]]; then
	echo "El árbol tiene cambios sin confirmar; el ZIP debe salir de un commit limpio." >&2
	exit 1
fi

if ! git rev-parse --verify --quiet "${REF}^{commit}" > /dev/null; then
	echo "No existe la referencia: ${REF}" >&2
	exit 1
fi

style_version="$(git show "${REF}:style.css" | sed -nE 's/^Version:[[:space:]]*([^[:space:]]+).*/\1/p' | head -n1)"
php_version="$(git show "${REF}:functions.php" | sed -nE "s/.*define\( *'ATORA_THEME_VERSION', *'([^']+)' *\);.*/\1/p" | head -n1)"

if [[ -z "$style_version" || "$style_version" != "$php_version" ]]; then
	echo "Versión desalineada en ${REF}: style.css='${style_version}' vs ATORA_THEME_VERSION='${php_version}'." >&2
	exit 1
fi

DIST_DIR="$ROOT_DIR/dist"
ZIP_PATH="$DIST_DIR/atora-meridian-${style_version}.zip"
STAGE="$(mktemp -d)"
trap 'rm -rf "$STAGE"' EXIT

mkdir -p "$STAGE/$THEME_DIR" "$DIST_DIR"
git archive "$REF" | tar -x -C "$STAGE/$THEME_DIR"

# Exclusiones de .distignore (del árbol actual: las etiquetas antiguas no lo traen).
while IFS= read -r entry || [[ -n "$entry" ]]; do
	entry="${entry%%#*}"
	entry="$(echo "$entry" | xargs)"
	[[ -z "$entry" ]] && continue
	rm -rf "$STAGE/$THEME_DIR/${entry#/}"
done < "$ROOT_DIR/.distignore"

rm -f "$ZIP_PATH"
( cd "$STAGE" && zip -rqX "$ZIP_PATH" "$THEME_DIR" )

echo "Listo: $ZIP_PATH (ref ${REF}, versión ${style_version})"
