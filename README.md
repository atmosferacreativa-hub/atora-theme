# ATORA Theme

Tema oficial de WordPress para ATORA LMS. Proporciona la presentación visual, layouts, plantillas, patterns y presets del ecosistema sin duplicar la lógica académica o comercial del plugin.

## Requisitos

- WordPress 6.4 o superior.
- PHP 8.1 o superior.
- ATORA LMS 6.13.3 recomendado para las vistas académicas integradas.

El requisito mínimo de PHP se alinea con ATORA LMS. El tema puede mostrar contenido editorial sin el plugin, pero las vistas de cursos, programas, lecciones y progreso dependen de sus contratos públicos.

## Responsabilidades

**ATORA LMS controla:**

- cursos, programas, lecciones y matrículas;
- acceso, progreso, evaluaciones y certificados;
- usuarios, permisos, CRM, comercio e integraciones.

**ATORA Theme controla:**

- layouts y jerarquía visual;
- plantillas de curso, programa y lección;
- navegación, portada, posts y páginas;
- estilos, patterns y presets.

La integración comprueba funciones y clases del plugin antes de utilizarlas y debe mantener fallbacks seguros.

## Compatibilidad histórica

Los identificadores internos de presets `atora-them-*` se conservan deliberadamente. Aunque contienen el nombre anterior, pueden estar guardados en opciones y contenido de instalaciones existentes. Cambiarlos exige una migración versionada; no deben sustituirse mediante una búsqueda global.

También se mantienen alias de constantes, funciones y tamaños de imagen `ATORA_THEM_*`/`atora_them_*` para instalaciones y child themes anteriores.

Algunos nombres de archivo heredados, como `landing-them.php`, se conservan por compatibilidad y no afectan el nombre comercial **ATORA Theme**.

## Instalación para desarrollo

1. Clona el repositorio en `wp-content/themes/atora-theme`.
2. Instala y activa ATORA LMS.
3. Activa **ATORA Theme**.
4. Guarda los enlaces permanentes.
5. Prueba las vistas como visitante, estudiante, docente y administrador.

## Pruebas

GitHub Actions ejecuta lint de sintaxis con PHP 8.1, 8.2, 8.3 y 8.4, además de una instalación limpia y activación del tema en WordPress 6.4 y 6.8.

La automatización no sustituye las pruebas visuales, responsive, accesibilidad, WooCommerce ni la integración completa con ATORA LMS.

## Proyecto

- Sitio: https://atora.studio
- LMS: https://github.com/atmosferacreativa-hub/Atora-LMS-6
