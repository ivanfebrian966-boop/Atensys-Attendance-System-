/* ============================================================
   ATTENSYS — HRprofile.js
   Sidebar helper (form submit sekarang ditangani server-side)
   ============================================================ */

/* ---- SIDEBAR ---- */
function openSidebar() {
    document.getElementById('sidebar')?.classList.add('open');
    document.getElementById('sidebarOverlay')?.classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeSidebar() {
    document.getElementById('sidebar')?.classList.remove('open');
    document.getElementById('sidebarOverlay')?.classList.remove('open');
    document.body.style.overflow = '';
}
window.addEventListener('resize', () => {
    if (window.innerWidth > 1024) closeSidebar();
});