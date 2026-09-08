<?php
/**
 * Title: Blog con filtros
 * Slug: atora-theme/blog-index
 * Categories: atora-theme
 * Viewport Width: 1280
 * Keywords: blog, articulos, recursos, contenido
 */
?>
<!-- Hero -->
<!-- wp:group {"align":"full","layout":{"type":"constrained"},"style":{"spacing":{"padding":{"top":"72px","bottom":"48px"}}}} -->
<div class="wp-block-group alignfull" style="background: linear-gradient(180deg, var(--as-cream) 0%, var(--as-bone) 100%); padding: 72px 0 48px;"><!-- wp:paragraph {"className":"atora-theme-eyebrow"} -->
<p class="atora-theme-eyebrow">· Recursos abiertos</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"style":{"typography":{"fontFamily":"var(--as-font-serif)","fontSize":"56px","fontWeight":"700","letterSpacing":"-0.02em"}}} -->
<h1 style="font-family: var(--as-font-serif); font-size: 56px; font-weight: 700; letter-spacing: -0.02em;">Aprende antes de comprar</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"fontSize":"17px","lineHeight":"1.65","color":"var(--as-ink)"}} -->
<p style="font-size: 17px; line-height: 1.65;">Guías, ensayos y análisis sobre fotografía, comunicación visual y construcción de marca.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- Filtros y buscador -->
<!-- wp:group {"align":"full","layout":{"type":"constrained"},"style":{"spacing":{"padding":{"top":"24px","bottom":"48px"}}}} -->
<div class="wp-block-group alignfull" style="padding: 24px 0 48px;"><!-- wp:group {"style":{"display":"flex","justifyContent":"space-between","alignItems":"center","gap":"24px","flexWrap":"wrap"}} -->
<div class="wp-block-group" style="display: flex; justify-content: space-between; align-items: center; gap: 24px; flex-wrap: wrap;"><!-- Filtros pill -->
<!-- wp:group {"style":{"display":"flex","gap":"8px","flexWrap":"wrap"}} -->
<div class="wp-block-group" style="display: flex; gap: 8px; flex-wrap: wrap;"><!-- wp:paragraph {"style":{"typography":{"fontFamily":"var(--as-font-mono)","fontSize":"10px","fontWeight":"700","textTransform":"uppercase","letterSpacing":"0.08em"},"backgroundColor":"var(--as-ink)","color":"var(--as-cream)","padding":"8px 14px","borderRadius":"999px","margin":"0","cursor":"pointer"}} -->
<p style="font-family: var(--as-font-mono); font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; background: var(--as-ink); color: var(--as-cream); padding: 8px 14px; border-radius: 999px; margin: 0; cursor: pointer;">Todos</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontFamily":"var(--as-font-mono)","fontSize":"10px","fontWeight":"700","textTransform":"uppercase","letterSpacing":"0.08em"},"backgroundColor":"var(--as-surface-2)","color":"var(--as-muted)","padding":"8px 14px","borderRadius":"999px","margin":"0","cursor":"pointer","border":"1px solid var(--as-rule)"}} -->
<p style="font-family: var(--as-font-mono); font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; background: var(--as-surface-2); color: var(--as-muted); padding: 8px 14px; border-radius: 999px; margin: 0; cursor: pointer; border: 1px solid var(--as-rule);">Técnica</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontFamily":"var(--as-font-mono)","fontSize":"10px","fontWeight":"700","textTransform":"uppercase","letterSpacing":"0.08em"},"backgroundColor":"var(--as-surface-2)","color":"var(--as-muted)","padding":"8px 14px","borderRadius":"999px","margin":"0","cursor":"pointer","border":"1px solid var(--as-rule)"}} -->
<p style="font-family: var(--as-font-mono); font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; background: var(--as-surface-2); color: var(--as-muted); padding: 8px 14px; border-radius: 999px; margin: 0; cursor: pointer; border: 1px solid var(--as-rule);">Ensayo</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontFamily":"var(--as-font-mono)","fontSize":"10px","fontWeight":"700","textTransform":"uppercase","letterSpacing":"0.08em"},"backgroundColor":"var(--as-surface-2)","color":"var(--as-muted)","padding":"8px 14px","borderRadius":"999px","margin":"0","cursor":"pointer","border":"1px solid var(--as-rule)"}} -->
<p style="font-family: var(--as-font-mono); font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; background: var(--as-surface-2); color: var(--as-muted); padding: 8px 14px; border-radius: 999px; margin: 0; cursor: pointer; border: 1px solid var(--as-rule);">Oficio</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontFamily":"var(--as-font-mono)","fontSize":"10px","fontWeight":"700","textTransform":"uppercase","letterSpacing":"0.08em"},"backgroundColor":"var(--as-surface-2)","color":"var(--as-muted)","padding":"8px 14px","borderRadius":"999px","margin":"0","cursor":"pointer","border":"1px solid var(--as-rule)"}} -->
<p style="font-family: var(--as-font-mono); font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; background: var(--as-surface-2); color: var(--as-muted); padding: 8px 14px; border-radius: 999px; margin: 0; cursor: pointer; border: 1px solid var(--as-rule);">Recursos</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- Buscador -->
<!-- wp:search {"label":"Buscar artículos","placeholder":"Escribe aquí...","showLabel":false,"buttonText":"Buscar","buttonPosition":"button-inside","isButtonOutside":false} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- Grid de artículos: 3 columnas -->
<!-- wp:group {"align":"full","layout":{"type":"constrained"},"style":{"spacing":{"padding":{"top":"0","bottom":"72px"}}}} -->
<div class="wp-block-group alignfull" style="padding: 0 0 72px;"><!-- wp:query {"query":{"perPage":9,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"exclude","inherit":false},"displayLayout":{"type":"grid","columns":3},"layout":{"type":"default"}} -->
<div class="wp-block-query"><!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->

<!-- Card de artículo -->
<!-- wp:group {"className":"atora-theme-post-card","style":{"backgroundColor":"var(--as-surface)","borderColor":"var(--as-rule)","borderWidth":"1px","borderRadius":"var(--as-radius-lg)","boxShadow":"var(--as-shadow-sm)","padding":"0","overflow":"hidden"}} -->
<div class="wp-block-group atora-theme-post-card" style="background: var(--as-surface); border: 1px solid var(--as-rule); border-radius: var(--as-radius-lg); box-shadow: var(--as-shadow-sm); overflow: hidden;">

<!-- Image 4:3 -->
<!-- wp:post-featured-image {"isLink":false,"aspectRatio":"4/3","scale":"cover"} /-->

<!-- Contenido -->
<!-- wp:group {"style":{"spacing":{"padding":{"top":"24px","right":"20px","bottom":"20px","left":"20px"}}}} -->
<div class="wp-block-group" style="padding: 24px 20px 20px;">

<!-- Chip categoría -->
<!-- wp:post-terms {"term":"category","style":{"typography":{"fontFamily":"var(--as-font-mono)","fontSize":"10px","fontWeight":"500","textTransform":"uppercase","letterSpacing":"0.08em"},"backgroundColor":"var(--as-blue-soft)","color":"var(--as-blue)","padding":"4px 8px","borderRadius":"4px","display":"inline-block"}} /-->

<!-- Título -->
<!-- wp:post-title {"isLink":true,"style":{"typography":{"fontFamily":"var(--as-font-serif)","fontSize":"20px","fontWeight":"700"},"marginTop":"12px"}} /-->

<!-- Fecha y autor -->
<!-- wp:group {"style":{"display":"flex","gap":"8px","marginTop":"12px","fontSize":"12px","color":"var(--as-muted)"}} -->
<div class="wp-block-group" style="display: flex; gap: 8px; margin-top: 12px; font-size: 12px; color: var(--as-muted);"><!-- wp:post-date /--><!-- wp:paragraph {"style":{"margin":"0"}} --><p style="margin: 0;">·</p><!-- /wp:paragraph --><!-- wp:post-author {"showAvatar":false,"textAlign":"left","style":{"typography":{"fontSize":"12px"}}} /--></div>
<!-- /wp:group -->

<!-- Excerpt -->
<!-- wp:post-excerpt {"moreText":"Leer más →","style":{"typography":{"fontSize":"14px","lineHeight":"1.6"},"marginTop":"12px"}} /-->

</div>
<!-- /wp:group -->

</div>
<!-- /wp:group -->

<!-- /wp:post-template -->

<!-- Paginación -->
<!-- wp:query-pagination {"style":{"spacing":{"margin":{"top":"48px"}}}} -->
<div class="wp-block-query-pagination" style="margin-top: 48px;"><!-- wp:query-pagination-previous /--><!-- wp:query-pagination-numbers /--><!-- wp:query-pagination-next /--></div>
<!-- /wp:query-pagination -->

</div>
<!-- /wp:query --></div>
<!-- /wp:group -->
