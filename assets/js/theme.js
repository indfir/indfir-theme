/**
 * Indfir theme behaviour: mobile nav, search bar, back-to-top.
 */
(function () {
	'use strict';

	function toggle(button, target, openClass) {
		if (!button || !target) {
			return;
		}

		button.addEventListener('click', function () {
			var isOpen = target.classList.toggle(openClass);
			button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

			if (isOpen) {
				var field = target.querySelector('input[type="search"]');
				if (field) {
					field.focus();
				}
			}
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		toggle(
			document.querySelector('.if-nav-toggle'),
			document.getElementById('if-primary-nav'),
			'is-open'
		);

		toggle(
			document.querySelector('.if-search-toggle'),
			document.getElementById('if-searchbar'),
			'is-open'
		);

		// Close the mobile nav / search with Escape.
		document.addEventListener('keydown', function (event) {
			if ('Escape' !== event.key) {
				return;
			}

			['if-primary-nav', 'if-searchbar'].forEach(function (id) {
				var el = document.getElementById(id);
				if (el && el.classList.contains('is-open')) {
					el.classList.remove('is-open');
				}
			});

			document.querySelectorAll('[aria-expanded="true"]').forEach(function (btn) {
				btn.setAttribute('aria-expanded', 'false');
			});
		});

		// Dark mode toggle.
		var themeToggle = document.getElementById('if-theme-toggle');
		if (themeToggle) {
			themeToggle.addEventListener('click', function () {
				var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
				if (isDark) {
					document.documentElement.removeAttribute('data-theme');
					document.documentElement.classList.remove('dark-mode');
					try { localStorage.setItem('indfir_theme', 'light'); } catch (e) {}
				} else {
					document.documentElement.setAttribute('data-theme', 'dark');
					document.documentElement.classList.add('dark-mode');
					try { localStorage.setItem('indfir_theme', 'dark'); } catch (e) {}
				}
			});
		}

		// Sticky navbar with glassmorphism blur & shadow.
		var header = document.querySelector('.if-header');
		if (header) {
			var checkSticky = function () {
				header.classList.toggle('is-stuck', window.scrollY > 15);
			};
			window.addEventListener('scroll', checkSticky, { passive: true });
			checkSticky();

			// Publish the navbar height so the share rail and the "now reading"
			// bar can sit clear of it whatever the logo size is.
			var syncHeaderHeight = function () {
				document.documentElement.style.setProperty(
					'--if-header-h',
					header.offsetHeight + 'px'
				);
			};
			window.addEventListener('resize', syncHeaderHeight, { passive: true });
			window.addEventListener('load', syncHeaderHeight);
			syncHeaderHeight();
		}

		// "Sedang dibaca" bar: shown once the article title has scrolled away.
		var nowReading = document.getElementById('if-nowreading');
		var entryTitle = document.querySelector('.if-entry__title');
		if (nowReading && entryTitle) {
			var updateNowReading = function () {
				nowReading.classList.toggle(
					'is-visible',
					entryTitle.getBoundingClientRect().bottom < 0
				);
			};
			window.addEventListener('scroll', updateNowReading, { passive: true });
			updateNowReading();
		}

		// Copy-link buttons in the share rail / row.
		document.querySelectorAll('.if-share__copy').forEach(function (button) {
			button.addEventListener('click', function () {
				var url = button.dataset.url || window.location.href;

				var done = function () {
					button.classList.add('is-copied');
					window.setTimeout(function () {
						button.classList.remove('is-copied');
					}, 1600);
				};

				if (navigator.clipboard && navigator.clipboard.writeText) {
					navigator.clipboard.writeText(url).then(done, function () {});
					return;
				}

				// http:// contexts and older browsers have no async clipboard.
				var field = document.createElement('textarea');
				field.value = url;
				field.setAttribute('readonly', '');
				field.style.position = 'fixed';
				field.style.opacity = '0';
				document.body.appendChild(field);
				field.select();
				try {
					document.execCommand('copy');
					done();
				} catch (e) {}
				document.body.removeChild(field);
			});
		});

		// Reading progress bar on single articles.
		var progressBar = document.getElementById('if-read-progress');
		if (progressBar) {
			var updateProgress = function () {
				var article = document.querySelector('article');
				if (!article) {
					return;
				}
				var rect = article.getBoundingClientRect();
				var windowHeight = window.innerHeight || document.documentElement.clientHeight;
				var total = rect.height - windowHeight;
				if (total <= 0) {
					progressBar.style.width = '100%';
					return;
				}
				var current = -rect.top;
				var pct = Math.min(100, Math.max(0, (current / total) * 100));
				progressBar.style.width = pct.toFixed(1) + '%';
			};

			window.addEventListener('scroll', updateProgress, { passive: true });
			window.addEventListener('resize', updateProgress, { passive: true });
			updateProgress();
		}

		// Back to top.
		var top = document.querySelector('.if-top');
		if (top) {
			var onScroll = function () {
				top.classList.toggle('is-visible', window.scrollY > 600);
			};

			window.addEventListener('scroll', onScroll, { passive: true });
			onScroll();

			top.addEventListener('click', function () {
				window.scrollTo({ top: 0, behavior: 'smooth' });
			});
		}

		// Submenus open on tap on touch devices without losing the parent link.
		if (window.matchMedia('(hover: none)').matches) {
			document.querySelectorAll('.if-nav li > ul').forEach(function (submenu) {
				var parentLink = submenu.parentNode.querySelector(':scope > a');
				if (!parentLink) {
					return;
				}

				parentLink.addEventListener('click', function (event) {
					if (!submenu.dataset.opened) {
						event.preventDefault();
						submenu.dataset.opened = '1';
						submenu.style.opacity = '1';
						submenu.style.visibility = 'visible';
						submenu.style.transform = 'none';
					}
				});
			});
		}
	});
})();
