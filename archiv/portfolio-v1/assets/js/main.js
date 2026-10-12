/* ==========================================================================
   LÁSKE Automotive Photography — main.js
   Obsahuje: preloader, navigáciu, reveal animácie, počítadlá, filtrovanie
   galérie, lightbox a odoslanie rezervačného formulára (AJAX + fallback).
   ========================================================================== */
(function () {
  'use strict';

  var doc = document;
  var $ = function (sel, ctx) { return (ctx || doc).querySelector(sel); };
  var $$ = function (sel, ctx) { return Array.prototype.slice.call((ctx || doc).querySelectorAll(sel)); };

  /* ---------- 1. Preloader ---------- */
  function initPreloader() {
    var pre = $('#preloader');
    if (!pre) return;
    var hide = function () {
      setTimeout(function () { pre.classList.add('is-hidden'); }, 250);
    };
    if (doc.readyState === 'complete') hide();
    else window.addEventListener('load', hide);
    // poistka, aby preloader nikdy nezostal visieť
    setTimeout(hide, 4000);
  }

  /* ---------- 2. Header + mobilné menu ---------- */
  function initHeader() {
    var header = $('#header');
    var burger = $('#burger');
    var nav = $('#nav');

    var onScroll = function () {
      if (!header) return;
      header.classList.toggle('is-scrolled', window.scrollY > 10);
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });

    if (!burger || !nav) return;

    var close = function () {
      nav.classList.remove('is-open');
      burger.classList.remove('is-open');
      burger.setAttribute('aria-expanded', 'false');
      burger.setAttribute('aria-label', 'Otvoriť menu');
      doc.body.classList.remove('is-locked');
    };

    burger.addEventListener('click', function () {
      var open = nav.classList.toggle('is-open');
      burger.classList.toggle('is-open', open);
      burger.setAttribute('aria-expanded', open ? 'true' : 'false');
      burger.setAttribute('aria-label', open ? 'Zavrieť menu' : 'Otvoriť menu');
      doc.body.classList.toggle('is-locked', open);
    });

    $$('.nav__link, .nav__cta', nav).forEach(function (a) {
      a.addEventListener('click', close);
    });

    doc.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') close();
    });
  }

  /* ---------- 3. Aktívny odkaz podľa sekcie ---------- */
  function initActiveNav() {
    var links = $$('.nav__link');
    var sections = links.map(function (l) {
      var id = l.getAttribute('href');
      return id && id.charAt(0) === '#' ? doc.querySelector(id) : null;
    }).filter(Boolean);
    if (!sections.length) return;

    var setActive = function () {
      var pos = window.scrollY + 140;
      var current = sections[0];
      sections.forEach(function (sec) { if (sec.offsetTop <= pos) current = sec; });
      links.forEach(function (l) {
        l.classList.toggle('is-active', l.getAttribute('href') === '#' + current.id);
      });
    };
    setActive();
    window.addEventListener('scroll', setActive, { passive: true });
  }

  /* ---------- 4. Reveal on scroll ---------- */
  function initReveal() {
    var items = $$('.reveal');
    if (!items.length) return;

    if (!('IntersectionObserver' in window)) {
      items.forEach(function (el) { el.classList.add('is-visible'); });
      return;
    }

    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('is-visible');
        io.unobserve(entry.target);
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    items.forEach(function (el, i) {
      el.style.transitionDelay = (i % 4) * 70 + 'ms';
      io.observe(el);
    });
  }

  /* ---------- 5. Počítadlá ---------- */
  function initCounters() {
    var counters = $$('[data-count]');
    if (!counters.length) return;

    var animate = function (el) {
      var target = parseInt(el.getAttribute('data-count'), 10) || 0;
      var start = null;
      var dur = 1600;
      var step = function (ts) {
        if (!start) start = ts;
        var p = Math.min((ts - start) / dur, 1);
        el.textContent = Math.floor(p * target).toString();
        if (p < 1) requestAnimationFrame(step);
        else el.textContent = target.toString();
      };
      requestAnimationFrame(step);
    };

    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) { animate(entry.target); io.unobserve(entry.target); }
      });
    }, { threshold: 0.5 });
    counters.forEach(function (c) { io.observe(c); });
  }

  /* ---------- 6. Filtrovanie galérie ---------- */
  function initFilters() {
    var filters = $$('.filter');
    var items = $$('.gallery__item');
    if (!filters.length || !items.length) return;

    filters.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var filter = btn.getAttribute('data-filter');
        filters.forEach(function (b) {
          var active = b === btn;
          b.classList.toggle('is-active', active);
          b.setAttribute('aria-selected', active ? 'true' : 'false');
        });
        items.forEach(function (item) {
          var show = filter === 'all' || item.getAttribute('data-category') === filter;
          item.classList.toggle('is-hidden', !show);
        });
      });
    });
  }

  /* ---------- 7. Lightbox ---------- */
  function initLightbox() {
    var lb = $('#lightbox');
    if (!lb) return;
    var img = $('#lbImage');
    var cap = $('#lbCaption');
    var btnPrev = $('#lbPrev');
    var btnNext = $('#lbNext');
    var btnClose = $('#lbClose');
    var current = 0;

    var items = function () {
      return $$('.gallery__item').filter(function (i) { return !i.classList.contains('is-hidden'); });
    };

    var largeSrc = function (src) {
      if (!src) return src;
      if (src.indexOf('images.unsplash.com') !== -1) return src.replace(/w=\d+/, 'w=1600');
      if (src.indexOf('picsum.photos') !== -1) return src.replace(/\/\d+\/\d+$/, '/1600/1000');
      return src;
    };

    var show = function (index) {
      var list = items();
      if (!list.length) return;
      current = (index + list.length) % list.length;
      var figure = list[current];
      var image = figure.querySelector('img');
      var cat = figure.querySelector('.gallery__cat');
      var name = figure.querySelector('.gallery__name');
      img.src = largeSrc(image.getAttribute('src'));
      img.alt = image.getAttribute('alt') || '';
      cap.textContent = [cat && cat.textContent, name && name.textContent].filter(Boolean).join(' · ');
    };

    var open = function (index) {
      show(index);
      lb.classList.add('is-open');
      lb.setAttribute('aria-hidden', 'false');
      doc.body.classList.add('is-locked');
    };
    var close = function () {
      lb.classList.remove('is-open');
      lb.setAttribute('aria-hidden', 'true');
      doc.body.classList.remove('is-locked');
    };

    $$('.gallery__item').forEach(function (figure) {
      figure.addEventListener('click', function () { open(items().indexOf(figure)); });
      figure.setAttribute('tabindex', '0');
      figure.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); open(items().indexOf(figure)); }
      });
    });

    if (btnPrev) btnPrev.addEventListener('click', function () { show(current - 1); });
    if (btnNext) btnNext.addEventListener('click', function () { show(current + 1); });
    if (btnClose) btnClose.addEventListener('click', close);
    lb.addEventListener('click', function (e) { if (e.target === lb) close(); });

    doc.addEventListener('keydown', function (e) {
      if (!lb.classList.contains('is-open')) return;
      if (e.key === 'Escape') close();
      if (e.key === 'ArrowLeft') show(current - 1);
      if (e.key === 'ArrowRight') show(current + 1);
    });
  }

  /* ---------- 8. Rezervačný formulár ---------- */
  function initBookingForm() {
    var form = $('#bookingForm');
    if (!form) return;
    var note = $('#formNote');
    var submitBtn = $('#submitBtn');

    var emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    var setError = function (name, message) {
      var field = form.querySelector('[name="' + name + '"]');
      var slot = form.querySelector('[data-error-for="' + name + '"]');
      var wrap = field ? field.closest('.field') : null;
      if (wrap) wrap.classList.toggle('has-error', !!message);
      if (slot) slot.textContent = message || '';
    };

    var validate = function () {
      var data = new FormData(form);
      var errors = {};
      if (!(data.get('name') || '').trim()) errors.name = 'Prosím, vyplňte meno.';
      if (!emailRe.test((data.get('email') || '').trim())) errors.email = 'Prosím, zadajte platný e-mail.';
      if (!(data.get('message') || '').trim()) errors.message = 'Napíšte prosím krátku správu.';
      if (!data.get('gdpr')) errors.gdpr = 'Potvrďte prosím súhlas so spracovaním údajov.';
      Object.keys({ name: 1, email: 1, message: 1, gdpr: 1 }).forEach(function (k) {
        setError(k, errors[k]);
      });
      return Object.keys(errors).length === 0;
    };

    var showNote = function (text, type) {
      if (!note) return;
      note.textContent = text;
      note.classList.remove('is-success', 'is-error');
      if (type) note.classList.add('is-' + type);
    };

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      if (!validate()) {
        showNote('Skontrolujte prosím označené polia.', 'error');
        return;
      }

      var data = new FormData(form);
      var payload = {
        name: data.get('name') || '',
        email: data.get('email') || '',
        phone: data.get('phone') || '',
        car: data.get('car') || '',
        datum: data.get('date') || '',
        cas: data.get('time') || '',
        lokalita: data.get('location') || '',
        sluzba: data.get('package') || '',
        sprava: data.get('message') || '',
        _subject: 'Nová rezervácia fotenia — ' + (data.get('name') || ''),
        _template: 'table',
        _captcha: 'false',
        _honey: data.get('_honey') || ''
      };

      submitBtn.classList.add('is-loading');
      showNote('Odosielam…', '');

      // odoslanie e-mailu cez FormSubmit (príde do schránky, žiadny mailto)
      fetch('https://formsubmit.co/ajax/cizsamko@gmail.com', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(payload)
      })
        .then(function (res) { return res.json(); })
        .then(function (res) {
          if (res && (res.success === 'true' || res.success === true)) {
            form.reset();
            showNote('Ďakujeme! Rezervácia dorazila — ozvem sa čo najskôr.', 'success');
          } else {
            showNote((res && res.message) ? res.message : 'Odoslanie zlyhalo. Skús znova alebo napíš na cizsamko@gmail.com.', 'error');
          }
        })
        .catch(function () {
          showNote('Nepodarilo sa odoslať. Skús znova alebo napíš na cizsamko@gmail.com.', 'error');
        })
        .finally(function () {
          submitBtn.classList.remove('is-loading');
        });
    });

    // okamžité čistenie chýb pri písaní
    form.addEventListener('input', function (e) {
      var name = e.target && e.target.getAttribute && e.target.getAttribute('name');
      if (name && form.querySelector('[data-error-for="' + name + '"]')) setError(name, '');
    });
  }

  /* ---------- 9. Predvyplnenie balíka z kariet ---------- */
  function initPackageShortcut() {
    var select = $('#package');
    if (!select) return;
    $$('[data-package]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var val = btn.getAttribute('data-package');
        Array.prototype.forEach.call(select.options, function (o) {
          if (o.value === val) select.value = val;
        });
      });
    });
  }

  /* ---------- 10. Back to top + rok ---------- */
  function initMisc() {
    var toTop = $('#toTop');
    if (toTop) {
      var toggle = function () { toTop.classList.toggle('is-visible', window.scrollY > 600); };
      toggle();
      window.addEventListener('scroll', toggle, { passive: true });
      toTop.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }
    var year = $('#year');
    if (year) year.textContent = new Date().getFullYear();
  }

  /* ---------- Naštartovanie ---------- */
  doc.addEventListener('DOMContentLoaded', function () {
    initPreloader();
    initHeader();
    initActiveNav();
    initReveal();
    initCounters();
    initFilters();
    initLightbox();
    initBookingForm();
    initPackageShortcut();
    initMisc();
  });
})();
