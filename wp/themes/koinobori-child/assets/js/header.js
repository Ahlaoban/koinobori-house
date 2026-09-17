// Shared by FR/EN and all pages in this tab; no visitor identifier or cookie.
function khHeaderClaimFirstVisit() {
  try {
    const storage = window.sessionStorage;
    const key = 'kh-header-visited';
    if (storage.getItem(key)) return false;
    storage.setItem(key, '1');
    return true;
  } catch {
    // If storage is unavailable, omit the introduction instead of repeating it.
    return false;
  }
}

(() => {
  'use strict';
  const header = document.getElementById('kh-header');
  const dialog = document.getElementById('kh-menu-dialog');
  if (!header || !dialog || typeof dialog.showModal !== 'function') return;
  const firstVisit = khHeaderClaimFirstVisit();
  const left = header.querySelector('.kh-word-left');
  const right = header.querySelector('.kh-word-right');
  const center = header.querySelector('.kh-header-center');
  const toggle = header.querySelector('.kh-menu-toggle');
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)');
  const mobile = window.matchMedia('(max-width: 767px)');
  const touch = window.matchMedia('(hover: none)');
  let introStart, introEnd, frame, savedOverflow;
  const stopIntro = () => {
    clearTimeout(introStart); clearTimeout(introEnd);
    header.classList.remove('header-onboarding');
  };
  function layout() {
    frame = null;
    header.classList.toggle('is-scrolled', window.scrollY > 20);
    const admin = document.getElementById('wpadminbar');
    const offset = admin ? Math.max(0, admin.getBoundingClientRect().bottom) : 0;
    header.style.setProperty('--kh-admin-offset', `${offset}px`);
    // Normalize measured glyph widths to desktop font size even while compact.
    const font = (window.scrollY > 20 ? 2.6 : 3.4) * parseFloat(getComputedStyle(document.documentElement).fontSize);
    const scale = font / parseFloat(getComputedStyle(left).fontSize);
    const a = left.getBoundingClientRect().width * scale;
    const b = right.getBoundingClientRect().width * scale;
    // The full menu uses wider spacing; avoid measuring a compact menu as full.
    const nav = center.querySelector('.kh-nav-list');
    const gap = Math.min(32, Math.max(12, innerWidth * .02));
    const count = nav ? nav.children.length : 0;
    const actions = center.querySelector('.kh-header-actions');
    const menuWidth = count * 44 + Math.max(0, count - 1) * gap + 24 + Math.max(208, actions ? actions.scrollWidth : 0);
    const compact = innerWidth < 1024 || touch.matches || (menuWidth / 2 + 28 + Math.max(a,b) + 24 > innerWidth / 2);
    header.classList.toggle('is-compact', compact);
    const width = center.getBoundingClientRect().width;
    const seam = (a - b) / 2;
    header.style.setProperty('--kh-pan-left', `${-width / 2 - 28 - seam}px`);
    header.style.setProperty('--kh-pan-right', `${width / 2 + 28 - seam}px`);
    header.classList.add('is-ready');
    if (!mobile.matches && dialog.open) dialog.close();
  }
  const schedule = () => { if (!frame) frame = requestAnimationFrame(layout); };
  header.addEventListener('pointerenter', stopIntro);
  header.addEventListener('focusin', stopIntro);
  window.addEventListener('scroll', schedule, { passive: true });
  window.addEventListener('resize', schedule, { passive: true });
  reduce.addEventListener('change', stopIntro);
  touch.addEventListener('change', schedule);
  toggle.addEventListener('click', () => {
    stopIntro();
    savedOverflow = document.body.style.overflow;
    dialog.showModal();
    document.body.style.overflow = 'hidden';
    toggle.setAttribute('aria-expanded', 'true');
    dialog.querySelector('.kh-menu-close').focus();
  });
  dialog.querySelector('.kh-menu-close').addEventListener('click', () => dialog.close());
  dialog.addEventListener('close', () => {
    document.body.style.overflow = savedOverflow ?? '';
    toggle.setAttribute('aria-expanded', 'false');
    if (mobile.matches) toggle.focus();
  });
  // Native modal dialog supplies inert background and Escape; wrap Tab explicitly.
  dialog.addEventListener('keydown', event => {
    if (event.key !== 'Tab') return;
    const links = [...dialog.querySelectorAll('a[href],button:not([disabled])')].filter(el => el.getClientRects().length);
    const first = links[0], last = links[links.length - 1];
    if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
    else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
  });
  dialog.addEventListener('click', event => { if (event.target.closest('a')) dialog.close(); });
  window.addEventListener('pagehide', () => { stopIntro(); if (dialog.open) dialog.close(); });
  layout();
  document.fonts?.ready.then(layout);
  if (firstVisit && !reduce.matches && !header.matches(':hover,:focus-within') && !header.classList.contains('is-compact')) {
    introStart = setTimeout(() => {
      header.classList.add('header-onboarding');
      introEnd = setTimeout(stopIntro, 700);
    }, 50);
  }
})();
