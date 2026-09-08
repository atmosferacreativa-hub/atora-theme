(function () {
	'use strict';

	// ── Menú móvil (original del tema) ──────────────────────────────────────
	var toggle = document.querySelector('.atora-theme-nav-toggle');
	var nav = document.getElementById('atora-theme-primary-nav');

	if (toggle && nav) {
		toggle.addEventListener('click', function () {
			var open = document.body.classList.toggle('atora-theme-nav-open');
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		});

		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape' && document.body.classList.contains('atora-theme-nav-open')) {
				document.body.classList.remove('atora-theme-nav-open');
				toggle.setAttribute('aria-expanded', 'false');
				toggle.focus();
			}
		});

		nav.addEventListener('click', function (event) {
			if (event.target && event.target.closest('a')) {
				document.body.classList.remove('atora-theme-nav-open');
				toggle.setAttribute('aria-expanded', 'false');
			}
		});
	}

	// ── Anti-duplicados ─────────────────────────────────────────────────────
	// Oculta cualquier card de post que se repita dentro de un mismo listado.
	// Cinturón de seguridad: el fix principal vive en home.php + functions.php,
	// pero esto cubre shortcodes, widgets y Query Loops que se generan en cliente.
	function atoraDedupe() {
		var scopes = document.querySelectorAll(
			'.atora-theme-post-grid, ' +
			'.wp-block-post-template, ' +
			'[data-atora-deduplicate]'
		);
		scopes.forEach(function (scope) {
			var seen = Object.create(null);
			var items = scope.querySelectorAll(
				'article[id], li.wp-block-post[id], [data-post-id]'
			);
			items.forEach(function (el) {
				var key = el.getAttribute('id') || el.getAttribute('data-post-id');
				if (!key) return;
				if (seen[key]) {
					el.setAttribute('aria-hidden', 'true');
					el.style.display = 'none';
				} else {
					seen[key] = true;
				}
			});
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', atoraDedupe);
	} else {
		atoraDedupe();
	}

	// Si alguna extensión inyecta cards después (lazy load, infinite scroll),
	// nos volvemos a ejecutar en el siguiente tick.
	if (typeof MutationObserver !== 'undefined') {
		var debounce;
		var mo = new MutationObserver(function () {
			clearTimeout(debounce);
			debounce = setTimeout(atoraDedupe, 80);
		});
		mo.observe(document.body, { childList: true, subtree: true });
	}
})();
