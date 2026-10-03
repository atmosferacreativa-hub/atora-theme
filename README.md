# Atora Meridian

Tema de WordPress oficial de **ATORA LMS**: portada institucional, blog, plantillas de curso, programa, lección y mentor, y la capa visual de las páginas del plugin. Es el tema que corre en demo.atora.studio.

## Requisitos

| | Mínimo | Probado en CI |
|---|---|---|
| WordPress | 6.0 | 6.4 y 6.8 |
| PHP | 7.4 | 7.4, 8.1, 8.2, 8.3 y 8.4 (sintaxis); 8.1 (WordPress) |
| ATORA LMS | 6.26.75 recomendado | — |

El tema funciona sin ATORA LMS, pero las plantillas de curso, programa y lección, y los enlaces de cuenta y login, dependen del plugin. Con ATORA LMS ≥ 6.26.72 los enlaces de "Cuenta" e "Iniciar sesión" llevan a la página de cuenta del sitio (`/cuenta/?redirect_to=…`) en lugar de `wp-login.php`.

## Instalación

1. Descarga el ZIP de la versión desde las etiquetas del repositorio (o genera uno con `git archive --prefix=atora-theme/ vX.Y.Z`).
2. En WordPress: **Apariencia → Temas → Añadir nuevo → Subir tema**. Si ya existe, elige reemplazar el actual.
3. Activa **Atora Meridian**. La carpeta del tema debe llamarse `atora-theme`.

## WooCommerce

Opcional. El tema declara soporte (`add_theme_support( 'woocommerce' )`) e incluye `woocommerce.php` como envoltorio. Los enlaces de Tienda y Carrito del header, el footer y las barras laterales, y su configuración en JavaScript, **solo aparecen si WooCommerce está activo**. Lo mismo vale para Podcast (solo si existe el tipo de contenido `podcast`) y para Blog (solo si hay una página de entradas).

## Versiones

- La versión se declara en dos lugares que deben coincidir: la cabecera `Version:` de `style.css` y `ATORA_THEME_VERSION` en `functions.php`. El CI lo comprueba (`scripts/check-version.sh`).
- Cada versión publicada lleva su etiqueta `vX.Y.Z` y su entrada en `CHANGELOG.md` en el mismo PR que sube el número.
- El tema anterior, **Atora Theme 1.1.0**, se conserva en la rama `legacy-1.x` y en la etiqueta `v1.1.0-legacy`.

## CI

`.github/workflows/ci.yml`: sintaxis PHP (7.4 a 8.4), versión alineada, validación de enlaces `tel:` y WhatsApp (`scripts/validate-links.sh`), y una prueba en WordPress que activa el tema y carga portada, página, entrada, búsqueda y 404 sin errores PHP.
