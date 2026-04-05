/**
 * JSPS Accounting Solutions — panel.js
 * JavaScript for Admin & User panel pages.
 */

'use strict';

/* ── Mobile Sidebar Toggle ──────────────────────────────── */
function toggleMobileSidebar() {
  const sidebar = document.getElementById('adminSidebar') || document.getElementById('userSidebar');
  const overlay = document.getElementById('sidebarOverlay');
  if (!sidebar) return;
  const isOpen = sidebar.classList.toggle('mobile-open');
  if (overlay) overlay.classList.toggle('visible', isOpen);
  document.body.style.overflow = isOpen ? 'hidden' : '';
}

function closeMobileSidebar() {
  const sidebar = document.getElementById('adminSidebar') || document.getElementById('userSidebar');
  const overlay = document.getElementById('sidebarOverlay');
  if (sidebar) sidebar.classList.remove('mobile-open');
  if (overlay) overlay.classList.remove('visible');
  document.body.style.overflow = '';
}

/* ── Close sidebar on ESC ───────────────────────────────── */
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeMobileSidebar();
});

/* ── Auto-resize textareas ──────────────────────────────── */
document.querySelectorAll('textarea').forEach(ta => {
  ta.addEventListener('input', function () {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 600) + 'px';
  });
});

/* ── Confirm dialogs for delete forms ───────────────────── */
document.querySelectorAll('form[data-confirm]').forEach(form => {
  form.addEventListener('submit', function (e) {
    if (!confirm(this.dataset.confirm || 'Are you sure?')) {
      e.preventDefault();
    }
  });
});

/* ── Highlight active table row on click ────────────────── */
document.querySelectorAll('.data-table tbody tr').forEach(row => {
  row.style.cursor = 'default';
});
