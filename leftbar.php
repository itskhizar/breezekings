<!-- ============================================================== -->
<!-- Modern Bootstrap 5 Left Sidebar -->
<!-- ============================================================== -->

<!-- Mobile Offcanvas Sidebar -->
<div class="offcanvas offcanvas-start sidebar-dark" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel" style="width: 264px;">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title text-white" id="sidebarOffcanvasLabel">Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="menu-list">
            <ul class="navbar-nav flex-column">
                <?php include 'leftbar-menu-content.php'; ?>
            </ul>
        </div>
    </div>
</div>

<!-- Desktop Fixed Sidebar -->
<div class="nav-left-sidebar sidebar-dark d-none d-lg-block">
    <div class="menu-list">
        <ul class="navbar-nav flex-column">
            <?php include 'leftbar-menu-content.php'; ?>
        </ul>
    </div>
</div>

<style type="text/css">
    /* Desktop Sidebar Styles */
    .nav-left-sidebar {
        position: fixed;
        width: 264px;
        height: calc(100vh - 60px);
        top: 60px;
        left: 0;
        overflow-y: auto;
        overflow-x: hidden;
        background-color: #0e0c28;
        box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
        z-index: 1020;
        transition: transform 0.3s ease;
    }

    .nav-left-sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .nav-left-sidebar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
    }

    .nav-left-sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 3px;
    }

    .nav-left-sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }

    .menu-list {
        padding: 0;
    }

    .nav-divider {
        padding: 12px 20px;
        line-height: 1.5;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
        color: #a4aadb;
        letter-spacing: 0.5px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .nav-left-sidebar .navbar-nav,
    .offcanvas-body .navbar-nav {
        width: 100%;
        padding: 8px 0;
    }

    .nav-left-sidebar .nav-item,
    .offcanvas-body .nav-item {
        margin-bottom: 15px;
    }

    .nav-left-sidebar .nav-link,
    .offcanvas-body .nav-link {
        font-size: 14px;
        padding: 12px 20px;
        color: #a4aadb;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        position: relative;
        border-radius: 0;
    }

    .nav-left-sidebar .nav-link i,
    .offcanvas-body .nav-link i {
        font-size: 16px;
        margin-right: 12px;
        width: 20px;
        text-align: center;
    }

    .nav-left-sidebar .nav-link:hover,
    .offcanvas-body .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: #ffffff;
    }

    .nav-left-sidebar .nav-link.active,
    .offcanvas-body .nav-link.active {
        background-color: rgba(255, 255, 255, 0.15);
        color: #ffffff;
        font-weight: 500;
    }

    /* Hide number badges on small screens (mobile) */
@media (max-width: 991.98px) {
    .nav-left-sidebar .nav-link .badge,
    .offcanvas-body .nav-link .badge {
        display: none !important;
    }
}


    /* Submenu Styles */
    .nav-left-sidebar .submenu,
    .offcanvas-body .submenu {
        padding-left: 0;
        padding-right: 0;
        background-color: rgba(0, 0, 0, 0.2);
        border-left: 3px solid transparent;
    }

    .nav-left-sidebar .submenu .nav-link,
    .offcanvas-body .submenu .nav-link {
        padding: 10px 20px 10px 52px;
        font-size: 13px;
        color: #c4c9e8;
    }

    .nav-left-sidebar .submenu .nav-link:hover,
    .offcanvas-body .submenu .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        border-left-color: #4a90e2;
    }

    .nav-left-sidebar .submenu .nav-link .badge,
    .offcanvas-body .submenu .nav-link .badge {
        font-size: 9px;
        padding: 3px 5px;
    }

    /* Collapse Arrow Indicator */
    .nav-left-sidebar .nav-link[data-bs-toggle="collapse"],
    .offcanvas-body .nav-link[data-bs-toggle="collapse"] {
        position: relative;
    }

    .nav-left-sidebar .nav-link[data-bs-toggle="collapse"]::after,
    .offcanvas-body .nav-link[data-bs-toggle="collapse"]::after {
        content: "";
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%) rotate(-90deg);
        width: 0;
        height: 0;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-top: 5px solid currentColor;
        transition: transform 0.3s ease, opacity 0.3s ease;
        opacity: 0.6;
    }

    .nav-left-sidebar .nav-link[data-bs-toggle="collapse"][aria-expanded="true"]::after,
    .offcanvas-body .nav-link[data-bs-toggle="collapse"][aria-expanded="true"]::after {
        transform: translateY(-50%) rotate(0deg);
    }

    /* Mobile Offcanvas Styles */
    .offcanvas.sidebar-dark {
        background-color: #0e0c28;
    }

    .offcanvas-header {
        padding: 1rem 1.25rem;
    }

    .offcanvas-title {
        font-weight: 600;
        font-size: 16px;
    }

    .offcanvas-body {
        padding: 0;
    }

    /* Content Margin Adjustment */
    .dashboard-wrapper,
    .dashboard-main-wrapper .main-content {
        margin-left: 264px;
        transition: margin-left 0.3s ease;
    }

    @media (max-width: 991.98px) {
        .dashboard-wrapper,
        .dashboard-main-wrapper .main-content {
            margin-left: 0;
        }
    }

    /* Remove inline breaks */
    .nav-item br {
        display: none;
    }
</style>
<!-- ============================================================== -->
<!-- end left sidebar -->
<!-- ============================================================== -->
