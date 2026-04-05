/**
 * JSPS Accounting Solutions — script.js
 * Global JavaScript for all public pages.
 */

'use strict';

/* ── Hamburger / Mobile Menu ────────────────────────────── */
const hamburgerBtn = document.getElementById('hamburgerBtn');
const mobileMenu   = document.getElementById('mobileMenu');

if (hamburgerBtn && mobileMenu) {
  hamburgerBtn.addEventListener('click', () => {
    const isOpen = mobileMenu.classList.toggle('open');
    hamburgerBtn.setAttribute('aria-expanded', isOpen);
    hamburgerBtn.querySelector('i').className = isOpen ? 'bi bi-x-lg' : 'bi bi-list';
  });
}

/* ── Sticky Navbar shadow on scroll ─────────────────────── */
const siteNav = document.getElementById('siteNavbar');
if (siteNav) {
  window.addEventListener('scroll', () => {
    siteNav.classList.toggle('scrolled', window.scrollY > 10);
  }, { passive: true });
}

/* ── Auth Tab Switching (login.php) ─────────────────────── */
document.querySelectorAll('.auth-tab').forEach(tab => {
  tab.addEventListener('click', () => {
    const target = tab.dataset.tab;

    document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
    tab.classList.add('active');

    document.getElementById('formLogin').style.display    = target === 'login'    ? '' : 'none';
    document.getElementById('formRegister').style.display = target === 'register' ? '' : 'none';
  });
});

/* ── Auto-dismiss Bootstrap toasts ─────────────────────── */
document.querySelectorAll('.toast').forEach(toastEl => {
  const bsToast = new bootstrap.Toast(toastEl, { delay: 4000 });
  bsToast.show();
});

/* ── Smooth scroll for anchor links ─────────────────────── */
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
});
