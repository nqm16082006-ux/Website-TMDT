/**
 * =======================================================================
 * JAVASCRIPT CHO TRANG QUẢN TRỊ ADMIN (ADMIN.JS)
 * =======================================================================
 */

document.addEventListener('DOMContentLoaded', function () {
    const sidebarToggle = document.getElementById('adminSidebarToggle');
    const sidebar = document.getElementById('adminSidebar');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('collapsed');
            if (sidebar.style.display === 'none') {
                sidebar.style.display = 'flex';
            } else if (window.innerWidth < 768) {
                sidebar.style.display = sidebar.style.display === 'none' ? 'flex' : 'none';
            }
        });
    }
});
