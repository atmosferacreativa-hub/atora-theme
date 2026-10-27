# Changelog — Atora Meridian

## 3.1.0 (2026-10-27)

Endurecimiento y alineación con ATORA LMS 6.34 y la app 1.0.x. El tema pasa a ser **la capa de identidad** de la academia: el plugin es dueño del marcado y de los datos; el tema fija color, letra, ritmo de lectura y el marco (cabecera, pie, blog, portada).

### Más simple para docentes y creadores
- **Un solo lugar de ajustes: Apariencia → Personalizar → Identidad ATORA** (también en Apariencia → Identidad ATORA). Color principal (Azul ATORA, Ámbar, Bosque, Vino, Grafito o color propio), letra (Editorial, Clara o del sistema), tamaño de lectura de las lecciones (17, 18 o 20 px), botón de la cabecera y texto del pie. El color propio se oscurece solo lo necesario para que los botones pasen el contraste AA con texto blanco.
- Se retira el menú **ATORA THEME** (15 pantallas): constructores de cabecera y pie, áreas de widgets, esquemas de color, tipografía, rendimiento, título, avanzado, importar/exportar, centro de diseño, presets, asistente, plantillas y widgets avanzados. Sus ajustes se guardaban pero **ninguna página los leía** (salvo los presets, que agregaban un tercer color de acento en conflicto). Los valores guardados quedan en la base de datos y no se usan.
- Se retiran las cajas **"Configuración visual ATORA"** de cursos, lecciones, programas, páginas, entradas y docentes: elegían plantillas del tema que nunca se mostraban (el plugin decide las plantillas de curso, programa y lección).
- **Patrones de lección** (insertador → ATORA: lecciones): Objetivos de aprendizaje, Idea clave, Actividad de práctica, Resumen y Glosario. Solo bloques del núcleo; en la app llegan como texto limpio.
- Patrones de página reescritos para una academia (presentación de curso o programa, introducción de artículo, invitación de cierre).
- Los presets de plantillas del plugin conservan sus nombres en español (el tema ya no los renombra a "Meridian …").

### Congruencia con el plugin
- **Una sola paleta.** Antes convivían tres: la de Meridian en `style.css`, el tema "Claro" del plugin (índigo, redefinido en `body`, que pisaba al tema) y los tokens de presets (verde azulado). Ahora la identidad se imprime después del plugin y sobre `body.atora-meridian.atora-theme-light`; si la academia elige en el plugin otro tema (oscuro, vibrante…), manda el plugin. También se alinean los acentos locales del panel del estudiante, el panel docente y SpeedGrader.
- **Letra del tema en las páginas del plugin** (curso, lección, panel, docente): Sora y Source Sans 3 incluidas en el tema, sin Google Fonts.
- **Sin plantillas parciales congeladas.** El tema sobrescribía 28 parciales del plugin (secciones de curso, del resumen del curso, de docente, captura CRM y `inline-assets.php` de la lección) con copias antiguas: la página de curso mostraba secciones encogidas y el docente equivocado, y la lección perdía el arranque diferido del reproductor de 6.3x. Se eliminan; el plugin dibuja su marcado actual y el tema lo viste con `assets/css/atora-lms.css`.
- **Acceso por programa (6.33.2).** El puente usa `CLMS_Helper::user_can_access_course()`: un estudiante inscrito solo en un programa ya no figura "sin acceso" en el tema. "Mis cursos" suma los cursos de sus programas vigentes, como la app.
- Docentes de las tarjetas desde `_clms_course_teacher_ids` (como el plugin), no el autor del post. Se quita el conteo de estudiantes (`_clms_enrollment_count` no existe en el plugin) y "Acceso abierto" en cursos sin precio.
- Pie: **Verificar un certificado** (`/verificar/`) y **Eliminar mi cuenta** (`/eliminar-cuenta/`, la exigen Google Play y App Store) cuando el plugin los ofrece.
- La plantilla **Student Dashboard** muestra el panel del plugin (`[clms_dashboard]` o su bloque) si la página lo tiene, en lugar de reemplazarlo por un resumen propio.
- Las páginas con pantallas del plugin (panel, panel docente, SpeedGrader, catálogo, perfil, agenda…) usan todo el ancho: el panel docente se veía en una columna de 424 px.
- El tema ya no **borra bloques de ATORA LMS al guardar** páginas y entradas (quitaba en silencio el panel, el catálogo o el login). Del insertador de páginas solo se ocultan los bloques de estructura de curso.

### Lectura y accesibilidad
- Lección en modo concentración: sin franja superior ni botón comercial en la cabecera; texto de lectura a 70 caracteres por línea; progreso y evidencia de lectura como tarjetas; en móvil, primero el contenido y después el temario; versión de impresión limpia.
- Foco visible en enlaces y botones, `prefers-reduced-motion`, casillas y radios de tamaño normal (la regla global de `input` los estiraba al 100 %, también en los cuestionarios), contenido con animación de entrada visible aunque falle el JavaScript, un solo `<main>` en las lecciones y en el panel docente.
- Márgenes de 16 px en móvil (antes 10 px); cabecera, portada y archivos alineados al mismo ancho.

### Seguridad y limpieza
- Fuera el código que no se cargaba pero viajaba en el ZIP: `inc/lms/` (incluía una acción AJAX `atora_theme_enroll`, también para visitantes, que matriculaba sin pasar por el comercio si se volvía a cargar), `inc/widgets/`, plantillas de correo, `inc/accessibility.php`, `inc/editor-styles.php` y 19 hojas y scripts sin uso.
- Fuera la acción AJAX pública `atora_theme_submit_crm_lead` (sin límite de envíos; el plugin no la escucha).
- Fuera "Avanzado" del tema, que guardaba JavaScript arbitrario en una opción.
- El tema ya no define funciones con prefijo del plugin (`atora_lms_*`).
- Título de los comentarios escapado.

### Textos
- Sin textos internos de producto en el sitio público ("capa refinada de publicación y comercio…", "más premium", "constructor…"). Portada, archivos, blog, tienda y pie hablan de aprender en la academia.
- El blog deja de promocionar ATORA (atora.studio, "Solicitar una demo") en la academia de un cliente: es opcional en Identidad ATORA, para los sitios propios.
- Catálogo `languages/es.php` ampliado; extractos sin palabras pegadas ("estudio.Planifica").

### CI
- Nuevo trabajo **ATORA LMS (main) + contrato de la app**: instala el plugin, siembra `wp atora seed-e2e` y compara las respuestas de la API móvil (`/wp-json/atora-mobile/v1`: login, discovery, me, dashboard, cursos, programas, notas, certificados, hoy, agenda, mensajes, sincronización y el primer curso) con un tema mínimo y con Meridian: deben ser idénticas (`scripts/check-mobile-contract.py`). Recorre además las páginas de la academia como visitante, estudiante y docente (`scripts/check-academy-pages.sh`).

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
