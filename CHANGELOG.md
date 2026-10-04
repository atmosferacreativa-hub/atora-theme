# Changelog — Atora Meridian

## 3.0.10 (2026-10-04)

- **Fix — progreso de cursos en 0 %**: el tema tenía dos caminos y ninguno leía el progreso real. Ahora hay uno solo, `atora_get_course_progress()`: filtro `atora_theme_user_course_progress` → `atora_lms_get_progress()` del plugin (6.28.2+, mismo cálculo que la app, recibe el ID del post del curso) → respaldo con `_clms_completed_lessons`. `Atora_Theme_Plugin_Bridge::get_course_progress()` lo usa; ya no se lee `_clms_progress_{curso}`, que el plugin no escribe.
- **Fix — envolturas del puente nunca definidas**: `class-atora-plugin-bridge.php` se protegía con `class_exists()`, pero PHP declara la clase al compilar el archivo, así que la condición siempre era verdadera y el archivo salía antes de definir `atora_theme_get_plugin_course_progress()`, `atora_theme_is_lms_active()`, `atora_theme_plugin_user_has_access()` y demás. Las plantillas (vía `Atora_Template_Context`) daban siempre 0 %. La guarda pasa a una constante; las envolturas ahora existen.
- **CI**: `scripts/check-progress.php` comprueba en WordPress que el ayudante, el puente y las plantillas dan el mismo porcentaje, con y sin el plugin (falla con 3.0.9).
- README: versión recomendada del plugin, 6.28.2.

## 3.0.9 (2026-10-03)

- **Repo**: Meridian pasa a ser el tema oficial del repositorio. README, este changelog y `Theme URI` apuntando a `atmosferacreativa-hub/atora-theme`.
- **Fix**: `patterns/home-institucional.php` llamaba a `atora_theme_asset_url()`, que no existía (error fatal al activar el tema en WordPress 6.4 y al abrir el patrón en el editor en 6.8). Se define la función y el patrón usa `assets/images/atora-theme-logo.jpg` (la imagen que pedía, `logo-atora-theme.jpg`, era del tema 1.1.0 y no está en Meridian).
- **CI**: sintaxis PHP 7.4–8.4, comprobación de que `style.css` y `ATORA_THEME_VERSION` coinciden, validación de enlaces `tel:`/WhatsApp (con los arreglos de `fix/ci-yaml-step-name-20260924`) y prueba en WordPress 6.4 y 6.8 que activa el tema y carga páginas sin errores PHP.

## 3.0.8 (2026-10-03)

- **Fix**: `atoraTheme.cartUrl` y `shopUrl` solo se exponen a JavaScript si WooCommerce está activo (antes apuntaban a `/cart/` y `/tienda/` en sitios sin tienda; ningún script los usa).

## 3.0.7 (2026-10-03)

- **Header**: sin la franja "…listo para WooCommerce"; Carrito y Tienda solo con WooCommerce activo.
- **Footer**: "© AÑO ATORA." sin el nombre del tema; columna "Recursos" (Tienda/Carrito solo con WooCommerce, Podcast solo si existe el tipo de contenido, Blog solo si hay página de blog); enlaces legales solo a páginas existentes (página de privacidad de WordPress, `terminos-y-condiciones`); navegación de respaldo solo con páginas existentes; "Panel del estudiante" al panel real de ATORA LMS.
- **Login**: "Cuenta" y los botones de curso usan `CLMS_Frontend_URLs` → `/cuenta/?redirect_to=…` (con `wp-login.php` como respaldo); con sesión, "Cuenta" lleva al panel.
- **Fix**: el enlace Blog usaba `get_permalink(0)` (la página actual) cuando no había página de entradas.
- `style.css` y `ATORA_THEME_VERSION` alineadas (antes 3.0.4 y 3.0.6).

## 3.0.6

- Importación de la versión desplegada en demo.atora.studio, tomada de ATORA-Lab (`3f79660`, carpeta `atora-theme/`). Sus 25 archivos CSS/JS/JSON públicos eran idénticos a los del demo. La cabecera de `style.css` decía 3.0.4.
