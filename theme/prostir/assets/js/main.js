/* Простір: хедер, мобільне меню, поява секцій, форма запису, лайтбокс. Без залежностей. */
(function () {
	'use strict';
	var d = document;
	d.documentElement.classList.add('js');

	/* Хедер: фон після прокрутки */
	var header = d.getElementById('site-header');
	function onScroll() { if (header) { header.classList.toggle('is-scrolled', window.scrollY > 24); } }
	onScroll();
	window.addEventListener('scroll', onScroll, { passive: true });

	/* Мобільне меню */
	var toggle = d.querySelector('.nav-toggle');
	var menu = d.getElementById('mobile-menu');
	function setMenu(open) {
		if (!toggle || !menu) { return; }
		toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		menu.hidden = !open;
		d.body.classList.toggle('menu-open', open);
		header.classList.toggle('menu-open', open);
	}
	if (toggle && menu) {
		toggle.addEventListener('click', function () { setMenu(menu.hidden); });
		menu.addEventListener('click', function (e) { if (e.target.closest('a')) { setMenu(false); } });
		d.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !menu.hidden) { setMenu(false); toggle.focus(); } });
		window.addEventListener('resize', function () { if (window.innerWidth >= 1024 && !menu.hidden) { setMenu(false); } });
	}

	/* Поява блоків при прокрутці */
	var reveals = d.querySelectorAll('.reveal');
	if ('IntersectionObserver' in window) {
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (en) {
				if (en.isIntersecting) { en.target.classList.add('is-visible'); io.unobserve(en.target); }
			});
		}, { rootMargin: '0px 0px -6% 0px', threshold: 0.04 });
		reveals.forEach(function (el) { io.observe(el); });
	} else {
		reveals.forEach(function (el) { el.classList.add('is-visible'); });
	}

	/* Форма запису */
	var form = d.querySelector('.booking-form');
	var select = form ? form.querySelector('[name="package"]') : null;
	var comment = form ? form.querySelector('[name="comment"]') : null;

	function preselect(value, note) {
		if (select && value) {
			var has = Array.prototype.some.call(select.options, function (o) { return o.value === value; });
			if (has) { select.value = value; }
		}
		if (comment && note && !comment.value) { comment.value = note; }
	}

	d.addEventListener('click', function (e) {
		var a = e.target.closest('[data-package]');
		if (!a || !form) { return; }
		e.preventDefault();
		preselect(a.getAttribute('data-package'), a.getAttribute('data-comment'));
		var booking = d.getElementById('booking');
		if (booking) { booking.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
		if (select) { setTimeout(function () { select.focus({ preventScroll: true }); }, 700); }
	});

	var params = new URLSearchParams(window.location.search);
	if (params.get('package')) { preselect(params.get('package'), params.get('package') === 'rent' ? '' : null); }

	if (form && window.fetch && form.getAttribute('data-endpoint')) {
		var err = form.querySelector('.booking-form__error');
		var fields = form.querySelector('.booking-form__fields');
		var success = form.querySelector('.booking-form__success');
		function showError(msg) { err.textContent = msg; err.hidden = false; }

		form.addEventListener('submit', function (e) {
			e.preventDefault();
			err.hidden = true;
			var name = form.elements.name.value.trim();
			var phone = form.elements.phone.value.trim();
			if (name.length < 2) { showError('Вкажіть, будь ласка, ім’я.'); form.elements.name.focus(); return; }
			if (phone.replace(/\D/g, '').length < 9) { showError('Вкажіть коректний номер телефону.'); form.elements.phone.focus(); return; }

			var data = {};
			new FormData(form).forEach(function (v, k) { data[k] = v; });
			form.classList.add('is-loading');
			var headers = { 'Content-Type': 'application/json' };
			if (window.prostirConfig && window.prostirConfig.nonce) { headers['X-WP-Nonce'] = window.prostirConfig.nonce; }

			fetch(form.getAttribute('data-endpoint'), { method: 'POST', headers: headers, body: JSON.stringify(data), credentials: 'same-origin' })
				.then(function (res) { return res.json().then(function (json) { return { ok: res.ok, json: json }; }); })
				.then(function (r) {
					if (!r.ok || !r.json.ok) { throw new Error(r.json && r.json.message ? r.json.message : 'Не вдалося надіслати заявку.'); }
					fields.hidden = true;
					if (r.json.message) { success.querySelector('p').textContent = r.json.message; }
					success.hidden = false;
					success.scrollIntoView({ behavior: 'smooth', block: 'center' });
				})
				.catch(function (ex) { showError(ex.message || 'Не вдалося надіслати заявку. Зателефонуйте нам.'); })
				.finally(function () { form.classList.remove('is-loading'); });
		});
	}

	/* Лайтбокс для портфоліо */
	var lb = d.getElementById('lightbox');
	var items = Array.prototype.slice.call(d.querySelectorAll('.js-lightbox'));
	if (lb && typeof lb.showModal === 'function' && items.length) {
		var img = lb.querySelector('.lightbox__img');
		var cap = lb.querySelector('.lightbox__caption');
		var idx = 0;
		function showAt(i) {
			idx = (i + items.length) % items.length;
			var a = items[idx];
			img.src = a.href;
			img.alt = a.getAttribute('data-caption') || '';
			cap.textContent = a.getAttribute('data-caption') || '';
		}
		items.forEach(function (a, i) {
			a.addEventListener('click', function (e) { e.preventDefault(); showAt(i); lb.showModal(); });
		});
		lb.querySelector('[data-lightbox-close]').addEventListener('click', function () { lb.close(); });
		lb.querySelector('[data-lightbox-prev]').addEventListener('click', function () { showAt(idx - 1); });
		lb.querySelector('[data-lightbox-next]').addEventListener('click', function () { showAt(idx + 1); });
		lb.addEventListener('click', function (e) { if (e.target === lb) { lb.close(); } });
		lb.addEventListener('keydown', function (e) {
			if (e.key === 'ArrowLeft') { showAt(idx - 1); }
			if (e.key === 'ArrowRight') { showAt(idx + 1); }
		});
		var touchX = null;
		lb.addEventListener('touchstart', function (e) { touchX = e.touches[0].clientX; }, { passive: true });
		lb.addEventListener('touchend', function (e) {
			if (touchX === null) { return; }
			var dx = e.changedTouches[0].clientX - touchX;
			if (Math.abs(dx) > 50) { showAt(dx < 0 ? idx + 1 : idx - 1); }
			touchX = null;
		});
	}
})();
