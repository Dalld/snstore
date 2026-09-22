/**
 * SN Store front-end interactions — vanilla JS, no dependencies.
 */
(function () {
	'use strict';

	function $(sel, ctx) { return (ctx || document).querySelector(sel); }
	function $all(sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); }

	/* ---------------- Search overlay ---------------- */
	function initSearch() {
		var btn = $('#sn-search-toggle');
		var panel = $('#sn-search-panel');
		if (!btn || !panel) return;

		function close() {
			panel.hidden = true;
			btn.setAttribute('aria-expanded', 'false');
		}
		function open() {
			panel.hidden = false;
			btn.setAttribute('aria-expanded', 'true');
			var input = panel.querySelector('input[type="search"]');
			if (input) input.focus();
		}
		btn.addEventListener('click', function () {
			if (panel.hidden) open(); else close();
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape') close();
		});
	}

	/* ---------------- Mobile drawer ---------------- */
	function initDrawer() {
		var burger = $('#sn-burger');
		var drawer = $('#sn-drawer');
		var overlay = $('#sn-drawer-overlay');
		var closer = $('#sn-drawer-close');
		if (!burger || !drawer || !overlay) return;

		function open() {
			drawer.hidden = false;
			drawer.setAttribute('aria-hidden', 'false');
			requestAnimationFrame(function () {
				drawer.classList.add('is-open');
				overlay.hidden = false;
				requestAnimationFrame(function () { overlay.classList.add('is-open'); });
			});
			burger.setAttribute('aria-expanded', 'true');
			document.body.style.overflow = 'hidden';
		}
		function close() {
			drawer.classList.remove('is-open');
			overlay.classList.remove('is-open');
			drawer.setAttribute('aria-hidden', 'true');
			burger.setAttribute('aria-expanded', 'false');
			document.body.style.overflow = '';
			setTimeout(function () {
				drawer.hidden = true;
				overlay.hidden = true;
			}, 300);
		}
		burger.addEventListener('click', open);
		if (closer) closer.addEventListener('click', close);
		overlay.addEventListener('click', close);
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && drawer.classList.contains('is-open')) close();
		});

		// Submenu accordions.
		$all('.sn-drawer-list .menu-item-has-children > a').forEach(function (link) {
			var li = link.parentNode;
			var sub = li.querySelector(':scope > ul');
			if (!sub) return;
			var btn = document.createElement('button');
			btn.className = 'sn-subtoggle';
			btn.type = 'button';
			btn.setAttribute('aria-expanded', 'false');
			btn.setAttribute('aria-label', link.textContent.trim() + ' submenu');
			btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 8l5 5 5-5"/></svg>';
			li.appendChild(btn);
			btn.addEventListener('click', function () {
				var expanded = btn.getAttribute('aria-expanded') === 'true';
				btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
				sub.classList.toggle('sn-sub-open', !expanded);
			});
		});
	}

	/* ---------------- Hero carousel ---------------- */
	function initHero() {
		var hero = $('#sn-hero');
		if (!hero) return;
		var slides = $all('.sn-hero-slide', hero);
		var dots = $all('.sn-hero-dot', hero);
		if (slides.length < 2) return;

		var index = 0;
		var timer = null;

		function show(i) {
			index = (i + slides.length) % slides.length;
			slides.forEach(function (s, k) { s.classList.toggle('is-active', k === index); });
			dots.forEach(function (d, k) { d.classList.toggle('is-active', k === index); });
		}
		function play() {
			stop();
			timer = setInterval(function () { show(index + 1); }, 6500);
		}
		function stop() {
			if (timer) { clearInterval(timer); timer = null; }
		}
		dots.forEach(function (d) {
			d.addEventListener('click', function () {
				show(parseInt(d.getAttribute('data-dot'), 10));
				play();
			});
		});
		$all('.sn-hero-arrow', hero).forEach(function (arrow) {
			arrow.addEventListener('click', function () {
				show(index + parseInt(arrow.getAttribute('data-hero'), 10));
				play();
			});
		});
		hero.addEventListener('mouseenter', stop);
		hero.addEventListener('mouseleave', play);

		// Touch swipe.
		var startX = null;
		hero.addEventListener('pointerdown', function (e) { startX = e.clientX; });
		hero.addEventListener('pointerup', function (e) {
			if (startX === null) return;
			var dx = e.clientX - startX;
			if (Math.abs(dx) > 42) { show(index + (dx < 0 ? 1 : -1)); play(); }
			startX = null;
		});
		show(0);
		play();
	}

	/* ---------------- Quantity steppers ---------------- */
	function initQty() {
		document.addEventListener('click', function (e) {
			var minus = e.target.closest('.sn-qty-minus');
			var plus = e.target.closest('.sn-qty-plus');
			if (!minus && !plus) return;
			var wrap = (minus || plus).closest('[data-qty]');
			if (!wrap) return;
			var input = wrap.querySelector('input.qty');
			if (!input) return;
			var val = parseInt(input.value, 10) || 1;
			var min = parseFloat(input.getAttribute('min')) || 1;
			var max = parseFloat(input.getAttribute('max'));
			var next = minus ? Math.max(min, val - 1) : val + 1;
			if (!isNaN(max) && max > 0) next = Math.min(max, next);
			input.value = next;
			input.dispatchEvent(new Event('change', { bubbles: true }));
		});
	}

	/* ---------------- PDP gallery (thumbs strip + arrows + swipe) ---------------- */
	function galleryThumbs() {
		return $all('.sn-pdp-thumb');
	}
	function galleryActiveIndex() {
		var thumbs = galleryThumbs();
		for (var i = 0; i < thumbs.length; i++) {
			if (thumbs[i].classList.contains('is-active')) return i;
		}
		return 0;
	}
	function galleryShowIndex(i) {
		var thumbs = galleryThumbs();
		if (!thumbs.length) return;
		thumbs[((i % thumbs.length) + thumbs.length) % thumbs.length].click();
	}
	function galleryCenterThumb(thumb) {
		var strip = thumb.parentElement;
		if (!strip) return;
		var target = thumb.offsetLeft - strip.clientWidth / 2 + thumb.clientWidth / 2;
		strip.scrollTo({ left: Math.max(0, target), behavior: 'smooth' });
	}
	function initGallery() {
		var main = $('#sn-pdp-main-img');
		if (!main) return;
		// Thumb clicks swap the main image (delegated: variation swaps included).
		document.addEventListener('click', function (e) {
			var thumb = e.target.closest('.sn-pdp-thumb');
			if (!thumb) return;
			var src = thumb.getAttribute('data-src');
			if (!src) return;
			main.src = src;
			$all('.sn-pdp-thumb').forEach(function (t) { t.classList.remove('is-active'); });
			thumb.classList.add('is-active');
			galleryCenterThumb(thumb);
		});
		// Prev/next arrows over the main image.
		$all('.sn-pdp-arrow').forEach(function (btn) {
			btn.addEventListener('click', function () {
				galleryShowIndex(galleryActiveIndex() + parseInt(btn.getAttribute('data-dir'), 10));
			});
		});
		// Swipe left/right on the main image (mobile).
		var media = main.closest('.sn-pdp-media');
		if (media) {
			var sx = 0, sy = 0, tracking = false;
			media.addEventListener('touchstart', function (e) {
				sx = e.touches[0].clientX; sy = e.touches[0].clientY; tracking = true;
			}, { passive: true });
			media.addEventListener('touchend', function (e) {
				if (!tracking) return;
				tracking = false;
				var dx = e.changedTouches[0].clientX - sx;
				var dy = e.changedTouches[0].clientY - sy;
				if (Math.abs(dx) > 40 && Math.abs(dx) > Math.abs(dy)) {
					galleryShowIndex(galleryActiveIndex() + (dx < 0 ? 1 : -1));
				}
			}, { passive: true });
		}
	}

	/* ---------------- Horizontal scroll rows ---------------- */
	function initScrollRows() {
		document.addEventListener('click', function (e) {
			var btn = e.target.closest('.sn-scroll-btn');
			if (!btn) return;
			var wrap = btn.closest('.sn-grid-wrap');
			var row = wrap && wrap.querySelector('.sn-grid--scroll');
			if (!row) return;
			var step = row.clientWidth * 0.8 * parseInt(btn.getAttribute('data-scroll'), 10);
			row.scrollBy({ left: step, behavior: 'smooth' });
		});
	}

	/* ---------------- Ordering select ---------------- */
	function initOrderby() {
		var select = $('.sn-orderby');
		if (!select) return;
		select.addEventListener('change', function () {
			if (select.form) select.form.submit();
		});
	}

	/* ---------------- Variable product pill picker ---------------- */
	function snVarValueText(select) {
		if (!select.value) return '';
		var opt = select.querySelector('option[value="' + select.value + '"]');
		return opt ? opt.textContent.trim() : select.value;
	}
	function snSlug(s) {
		return String(s).toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
	}
	function snSyncVarGroup(select) {
		var group = select.closest('.sn-var-group');
		if (!group) return;
		var val = select.value;
		group.querySelectorAll('.sn-var-btn').forEach(function (b) {
			b.classList.toggle('is-active', b.getAttribute('data-value') === val);
		});
		var out = group.querySelector('.sn-var-value');
		if (out) out.textContent = snVarValueText(select);
	}
	function initVariationPills() {
		// Safety net: align each pill's data-value with the option value the
		// hidden select actually carries. Taxonomy attributes use term slugs,
		// custom attributes use the raw option text — match both.
		$all('.sn-var-group').forEach(function (group) {
			var select = group.querySelector('select');
			if (!select) return;
			var opts = Array.prototype.slice.call(select.options).filter(function (o) { return o.value; });
			group.querySelectorAll('.sn-var-btn').forEach(function (btn) {
				var bv = btn.getAttribute('data-value');
				if (!bv) return;
				var match = opts.find(function (o) {
					return o.value === bv || snSlug(o.value) === snSlug(bv);
				});
				if (match && match.value !== bv) btn.setAttribute('data-value', match.value);
			});
		});
		// Native change events: pill clicks and our own dispatched events.
		document.addEventListener('change', function (e) {
			var select = e.target.closest ? e.target.closest('.sn-var-group select') : null;
			if (!select) return;
			snSyncVarGroup(select);
		});
		// jQuery-triggered change events — Woo's "Clear" reset uses
		// .trigger('change'), which does NOT emit a native event our
		// document listener could hear, so listen on the jQuery bus too.
		if (window.jQuery) {
			window.jQuery(document.body).on('change', '.sn-var-group select', function () {
				snSyncVarGroup(this);
			});
		}
		// Pills drive the hidden native select that Woo's variation engine listens to.
		document.addEventListener('click', function (e) {
			var btn = e.target.closest('.sn-var-btn');
			if (!btn) return;
			var group = btn.closest('.sn-var-group');
			var select = group ? group.querySelector('select') : null;
			if (!select) return;
			var wasActive = btn.classList.contains('is-active');
			group.querySelectorAll('.sn-var-btn').forEach(function (b) { b.classList.remove('is-active'); });
			select.value = wasActive ? '' : btn.getAttribute('data-value');
			if (!wasActive) btn.classList.add('is-active');
			select.dispatchEvent(new Event('change', { bubbles: true }));
		});
		// Woo's "Clear" resets the selects silently — sync our pills/value text
		// (and restore the default image) ourselves.
		document.addEventListener('click', function (e) {
			var reset = e.target.closest('.reset_variations');
			if (!reset) return;
			var form = reset.closest('form');
			if (!form) return;
			form.querySelectorAll('.sn-var-group select').forEach(function (s) {
				if (s.value) {
					s.value = '';
					s.dispatchEvent(new Event('change', { bubbles: true }));
				}
			});
			var firstThumb = galleryThumbs()[0];
			if (firstThumb) firstThumb.click();
		});
		// Set initial active pills + value text from any pre-selected defaults.
		$all('.sn-var-group select').forEach(function (select) {
			snSyncVarGroup(select);
		});
	}

	/* ---------------- Variable product image swap ---------------- */
	/* Woo's own swap targets flexslider markup; our gallery listens to the
	   same jQuery events (found_variation / reset_image) instead. */
	function initVariationImages() {
		if (!window.jQuery) return;
		var main = document.getElementById('sn-pdp-main-img');
		if (!main) return;
		var defaultSrc = main.getAttribute('src');
		window.jQuery('.variations_form')
			.on('found_variation', function (e, variation) {
				if (variation && variation.image && variation.image.src && main.src !== variation.image.src) {
					main.src = variation.image.src;
					main.removeAttribute('srcset');
					$all('.sn-pdp-thumb').forEach(function (t) { t.classList.remove('is-active'); });
					// Activate + reveal the matching thumb when one exists.
					var match = galleryThumbs().filter(function (t) {
						return t.getAttribute('data-src') === variation.image.src
							|| t.getAttribute('data-src') === (variation.image.src || '').split('?')[0];
					})[0];
					if (match) {
						match.classList.add('is-active');
						galleryCenterThumb(match);
					}
				}
			})
			.on('reset_image', function () {
				main.src = defaultSrc;
				main.removeAttribute('srcset');
				var first = document.querySelector('.sn-pdp-thumb');
				if (first) {
					$all('.sn-pdp-thumb').forEach(function (t) { t.classList.remove('is-active'); });
					first.classList.add('is-active');
					galleryCenterThumb(first);
				}
			});
	}

	/* ---------------- Drawer submenu + misc on DOM ready ---------------- */
	document.addEventListener('DOMContentLoaded', function () {
		initSearch();
		initDrawer();
		initHero();
		initQty();
		initGallery();
		initScrollRows();
		initOrderby();
		initVariationPills();
		initVariationImages();
	});
})();
