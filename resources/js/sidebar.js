const sidebar = document.getElementById('app-sidebar');
const mobileToggle = document.getElementById('mobile-sidebar-toggle');
const desktopToggle = document.getElementById('desktop-sidebar-toggle');
const sidebarBackdrop = document.getElementById('sidebar-backdrop');

if (sidebar && mobileToggle && desktopToggle && sidebarBackdrop) {
    function setMobileSidebarOpen(isOpen) {
        sidebar.classList.toggle('hidden', !isOpen);
        sidebarBackdrop.classList.toggle('hidden', !isOpen);
        mobileToggle.setAttribute('aria-expanded', String(isOpen));
        mobileToggle.setAttribute('aria-label', isOpen ? 'Tutup menu navigasi' : 'Buka menu navigasi');
        document.body.classList.toggle('overflow-hidden', isOpen);
    }

    mobileToggle.addEventListener('click', function () {
        setMobileSidebarOpen(mobileToggle.getAttribute('aria-expanded') !== 'true');
    });

    sidebarBackdrop.addEventListener('click', function () {
        setMobileSidebarOpen(false);
    });

    desktopToggle.addEventListener('click', function () {
        const isCollapsed = sidebar.dataset.collapsed !== 'true';
        sidebar.dataset.collapsed = String(isCollapsed);
        desktopToggle.setAttribute('aria-expanded', String(!isCollapsed));
        desktopToggle.setAttribute('aria-label', isCollapsed ? 'Perluas menu samping' : 'Ciutkan menu samping');
        desktopToggle.setAttribute('title', isCollapsed ? 'Perluas menu samping' : 'Ciutkan menu samping');
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && mobileToggle.getAttribute('aria-expanded') === 'true') {
            setMobileSidebarOpen(false);
            mobileToggle.focus();
        }
    });
}
