#!/usr/bin/env bash
# Verifica que style.css (cabecera "Version:") y ATORA_THEME_VERSION en
# functions.php declaren la misma versión.
set -euo pipefail

style_version="$(sed -nE 's/^Version:[[:space:]]*([^[:space:]]+).*/\1/p' style.css | head -n1)"
php_version="$(sed -nE "s/.*define\( *'ATORA_THEME_VERSION', *'([^']+)' *\);.*/\1/p" functions.php | head -n1)"

if [[ -z "$style_version" || -z "$php_version" ]]; then
  echo "No se pudo leer la versión (style.css='${style_version}', functions.php='${php_version}')." >&2
  exit 1
fi

if [[ "$style_version" != "$php_version" ]]; then
  echo "Versión desalineada: style.css=${style_version} vs ATORA_THEME_VERSION=${php_version}" >&2
  exit 1
fi

echo "Versión OK: ${style_version}"
