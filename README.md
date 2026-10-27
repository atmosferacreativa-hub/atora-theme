# Atora Meridian

Tema de WordPress oficial de **ATORA LMS**: portada, blog, cabecera y pie, y la identidad visual (color, letra y tamaño de lectura) de todas las pantallas del plugin. Es el tema que corre en demo.atora.studio.

**Reparto de papeles.** El plugin es dueño del marcado y de los datos de curso, programa, lección, panel y docente. El tema no sobrescribe esas plantillas: las viste con las variables `--atora-*` y con `assets/css/atora-lms.css`. Todo dato académico que muestra el tema (acceso, progreso, cursos del estudiante, lecciones) pasa por `inc/compatibility/class-atora-plugin-bridge.php`, que llama al plugin y nunca escribe sus datos.

## Requisitos

| | Mínimo | Probado en CI |
|---|---|---|
| WordPress | 6.0 | 6.4 y 6.8 |
| PHP | 7.4 | 7.4, 8.1, 8.2, 8.3 y 8.4 (sintaxis); 8.1 (WordPress) |
| ATORA LMS | 6.34.0 recomendado (mínimo útil 6.28.2) | rama `main`, contra la API de la app |

El tema funciona sin ATORA LMS, pero las plantillas de curso, programa y lección, y los enlaces de cuenta y login, dependen del plugin. Con ATORA LMS ≥ 6.26.72 los enlaces de "Cuenta" e "Iniciar sesión" llevan a la página de cuenta del sitio (`/cuenta/?redirect_to=…`) en lugar de `wp-login.php`. Con ATORA LMS ≥ 6.28.2 el porcentaje de avance de un curso sale de `atora_lms_get_progress()`, el mismo cálculo que la app; con versiones anteriores el tema usa las lecciones completadas guardadas en el usuario. Con ATORA LMS ≥ 6.33.2 el acceso y "Mis cursos" incluyen los cursos de los programas vigentes del estudiante, igual que la app.

## Identidad de la academia

**Apariencia → Personalizar → Identidad ATORA** (o Apariencia → Identidad ATORA):

- **Color principal**: Azul ATORA, Ámbar, Bosque, Vino, Grafito institucional o un color propio (si es muy claro, el tema lo oscurece lo justo para que los botones se lean).
- **Letra**: Editorial (Sora + Source Sans 3), Clara (Source Sans 3) o del sistema. Las fuentes van dentro del tema.
- **Tamaño de lectura en las lecciones**: 17, 18 o 20 px.
- **Botón de la cabecera** (texto y enlace; "-" lo oculta) y **texto del pie**.
- **Promoción de ATORA en el blog**: apagada por defecto; solo para los sitios propios de ATORA.

Se aplica a todo: portada, blog, cursos, lecciones, panel del estudiante, panel docente y SpeedGrader. Si en **ATORA LMS → Ajustes** la academia elige un tema distinto de "Claro", manda el del plugin.

El logo se sube en **Personalizar → Identidad del sitio**, y los menús en **Apariencia → Menús** (sin menú asignado, la cabecera muestra Cursos, Programas, Blog y, con sesión, Mi aprendizaje).

## Para docentes

En el editor de lecciones, el insertador trae **ATORA: lecciones**: Objetivos de aprendizaje, Idea clave, Actividad de práctica, Resumen y Glosario. Son bloques normales de WordPress; en la app llegan como texto.

## Instalación

1. Descarga el ZIP de la versión desde las etiquetas del repositorio (o genera uno con `git archive --prefix=atora-theme/ vX.Y.Z`).
2. En WordPress: **Apariencia → Temas → Añadir nuevo → Subir tema**. Si ya existe, elige reemplazar el actual.
3. Activa **Atora Meridian**. La carpeta del tema debe llamarse `atora-theme`.

## Generar el ZIP

```bash
./scripts/build-dist.sh v3.0.9   # desde una etiqueta (o un commit); sin argumento usa HEAD
```

Genera `dist/atora-meridian-<versión>.zip`. El script lee la versión de `style.css` y falla si hay cambios sin confirmar o si `style.css` y `ATORA_THEME_VERSION` no coinciden. Deja fuera lo listado en `.distignore`: `.git`, `.github`, `.gitignore`, `scripts`, `README.md`, `CHANGELOG.md`, `.distignore` y `dist`. El CI construye el ZIP en cada PR y verifica su contenido.

La carpeta raíz del ZIP es siempre **`atora-theme`**, la carpeta con la que el tema está instalado. WordPress guarda menús, widgets y ajustes del personalizador por carpeta de tema: con otro nombre se instalaría como un tema nuevo y el sitio perdería esa configuración.

## Actualizar un sitio

1. Genera el ZIP desde la etiqueta de la versión.
2. En WordPress: **Apariencia → Temas → Añadir nuevo → Subir tema** y elige el ZIP.
3. Cuando WordPress detecte que el tema ya existe, elige **Reemplazar el actual con el subido**.
4. Comprueba en **Apariencia → Temas** la versión nueva, y en **Menús** y **Personalizar** que la configuración siga asignada.

Si el tema no estaba instalado, actívalo después de subirlo; al usar la carpeta `atora-theme`, recupera los ajustes que el sitio tenía guardados para ella.

## WooCommerce

Opcional. El tema declara soporte (`add_theme_support( 'woocommerce' )`) e incluye `woocommerce.php` como envoltorio. Los enlaces de Tienda y Carrito del header, el footer y las barras laterales, y su configuración en JavaScript, **solo aparecen si WooCommerce está activo**. Lo mismo vale para Podcast (solo si existe el tipo de contenido `podcast`) y para Blog (solo si hay una página de entradas).

## Versiones

- La versión se declara en dos lugares que deben coincidir: la cabecera `Version:` de `style.css` y `ATORA_THEME_VERSION` en `functions.php`. El CI lo comprueba (`scripts/check-version.sh`).
- Cada versión publicada lleva su etiqueta `vX.Y.Z` y su entrada en `CHANGELOG.md` en el mismo PR que sube el número.
- El tema anterior, **Atora Theme 1.1.0**, se conserva en la rama `legacy-1.x` y en la etiqueta `v1.1.0-legacy`.

## CI

`.github/workflows/ci.yml`:

- sintaxis PHP (7.4 a 8.4), versión alineada, validación de enlaces `tel:` y WhatsApp (`scripts/validate-links.sh`) y contenido del ZIP;
- WordPress 6.4 y 6.8 sin el plugin: activa el tema y carga portada, página, entrada, búsqueda y 404 sin errores PHP;
- **ATORA LMS (`main`) + contrato de la app**: instala el plugin, siembra `wp atora seed-e2e` y exige que la API móvil (`/wp-json/atora-mobile/v1`) responda **exactamente igual** con un tema mínimo y con Meridian (`scripts/check-mobile-contract.py`); luego recorre las páginas de la academia como visitante, estudiante y docente (`scripts/check-academy-pages.sh`: sin errores PHP, un solo `<main>`, identidad del tema presente).
