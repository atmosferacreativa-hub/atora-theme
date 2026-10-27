(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		document.body.classList.add('is-ready');
		setupStickyHeader();
		setupMobileNavigation();
		setupSearchPanel();
		setupRevealObserver();
	});

	function setupStickyHeader() {
		var header = document.querySelector('[data-site-header]');
		if (!header) {
			return;
		}

		var sync = function () {
			header.classList.toggle('is-scrolled', window.scrollY > 12);
		};

		sync();
		window.addEventListener('scroll', sync, { passive: true });
	}

	function setupMobileNavigation() {
		var header = document.querySelector('[data-site-header]');
		var toggle = document.querySelector('[data-mobile-toggle]');
		var nav = document.querySelector('[data-navigation]');

		if (!header || !toggle || !nav) {
			return;
		}

		var close = function () {
			toggle.setAttribute('aria-expanded', 'false');
			header.classList.remove('menu-open');
			document.body.classList.remove('meridian-lock-scroll');
		};

		toggle.addEventListener('click', function () {
			var expanded = toggle.getAttribute('aria-expanded') === 'true';
			toggle.setAttribute('aria-expanded', expanded ? 'false' : 'true');
			header.classList.toggle('menu-open', !expanded);
			document.body.classList.toggle('meridian-lock-scroll', !expanded);
		});

		window.addEventListener('resize', function () {
			if (window.innerWidth > 960) close();
		});
		nav.addEventListener('click', function (event) {
			if (event.target.closest('a')) close();
		});
		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
				close();
				toggle.focus();
			}
		});

		document.addEventListener('click', function (event) {
			if (window.innerWidth > 960) {
				return;
			}

			if (!header.contains(event.target)) {
				close();
			}
		});
	}

	function setupSearchPanel() {
		var panel = document.querySelector('[data-search-panel]');
		var toggles = document.querySelectorAll('[data-search-toggle]');
		var closeButton = document.querySelector('[data-search-close]');

		if (!panel || !toggles.length) {
			return;
		}

		var opener = null;
		var setState = function (open) {
			var wasOpen = !panel.hidden;
			panel.hidden = !open;
			panel.classList.toggle('is-open', open);
			document.body.classList.toggle('meridian-search-open', open);

			toggles.forEach(function (toggle) {
				toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
			});

			if (!open && wasOpen && opener) opener.focus();
			if (open) {
				var menuToggle = document.querySelector('[data-mobile-toggle]');
				if (menuToggle && menuToggle.getAttribute('aria-expanded') === 'true') { menuToggle.click(); opener = menuToggle; }
				var input = panel.querySelector('input[type="search"]');
				if (input) {
					window.setTimeout(function () {
						input.focus();
					}, 60);
				}
			}
		};

		toggles.forEach(function (toggle) {
			toggle.addEventListener('click', function () {
				opener = toggle;
				var isOpen = panel.classList.contains('is-open');
				setState(!isOpen);
			});
		});

		if (closeButton) {
			closeButton.addEventListener('click', function () {
				setState(false);
			});
		}

		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape') {
				setState(false);
			}
		});
	}

	function setupRevealObserver() {
		var items = document.querySelectorAll('[data-reveal]');
		if (!items.length || typeof IntersectionObserver === 'undefined' ||
			(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches)) return;
		var show = function (item) {
			item.classList.remove('is-reveal-pending');
			item.classList.add('is-visible');
		};
		var observer;
		try {
			observer = new IntersectionObserver(function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) { show(entry.target); observer.unobserve(entry.target); }
				});
			}, { rootMargin: '0px', threshold: 0 });
			items.forEach(function (item) {
				// Never hide a section already in view while fonts or scripts load.
				if (item.getBoundingClientRect().top > window.innerHeight) item.classList.add('is-reveal-pending');
				observer.observe(item);
			});
			// Content remains usable even if the observer stops delivering callbacks.
			window.setTimeout(function () { items.forEach(show); observer.disconnect(); }, 5000);
		} catch (error) {
			items.forEach(show);
			if (observer) observer.disconnect();
		}
	}
})();
