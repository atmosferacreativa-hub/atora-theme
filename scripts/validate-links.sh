#!/usr/bin/env bash
set -euo pipefail

failures=0

if [[ ! -d patterns && ! -d templates && ! -d template-parts && ! -d page-templates ]]; then
  echo "No theme directories found to scan." >&2
  exit 1
fi

mapfile -t files < <(
  find . \
    -path './.git' -prune -o \
    -type f -name '*.php' -print
)

for file in "${files[@]}"; do
  while IFS= read -r line; do
    lineno="${line%%:*}"
    content="${line#*:}"

    url="$(printf '%s\n' "$content" | sed -E 's/.*href="([^"]+)".*/\1/')"
    if [[ -z "$url" ]]; then
      continue
    fi

    if [[ "$url" == tel:* ]]; then
      if [[ ! "$url" =~ ^tel:\+?[0-9]{7,15}$ ]]; then
        echo "Invalid tel: link: ${file}:${lineno}: ${url}" >&2
        failures=$((failures + 1))
      fi
      continue
    fi

    if [[ "$url" =~ ^https?://wa\.me/ ]]; then
      if [[ ! "$url" =~ ^https?://wa\.me/[0-9]{7,15}(/)?(\?.*)?$ ]]; then
        echo "Invalid wa.me link: ${file}:${lineno}: ${url}" >&2
        failures=$((failures + 1))
      fi
      continue
    fi
  done < <(grep -nE 'href="(tel:|https?://wa\.me/)[^"]*"' "$file" || true)
done

if (( failures > 0 )); then
  echo "Link validation failed: ${failures} invalid link(s)." >&2
  exit 1
fi

echo "Link validation OK."

