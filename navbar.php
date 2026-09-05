<?php ob_start();
    $userlevel = $session->userlevel;
?>

<!-- ============================================================== -->
<!-- Modern Bootstrap 5 Navbar -->
<!-- ============================================================== -->
<div class="dashboard-header">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top px-3 py-2" style="z-index: 1030;">
        <div class="container-fluid">
            <!-- Logo on Left -->
            <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
                <img src="images/logo.png" alt="Logo" class="logo-img" style="height: 35px; width: auto; max-width: 150px;">
            </a>

            <!-- Right-side Navbar Content -->
            <div class="d-flex align-items-center ms-auto">
                <ul class="navbar-nav flex-row align-items-center gap-2">
                    
                    <?php if ($session->userlevel == 1 OR $session->userlevel == 4) { ?>

    <!-- ================= MOBILE ONLY ================= -->
    <li class="nav-item d-lg-none">
        <button class="btn btn-link nav-link p-2" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
            <i class="fas fa-bars" style="font-size: 20px; color:#173663;"></i>
        </button>
    </li>

    <li class="nav-item dropdown d-lg-none">
        <a class="nav-link d-flex align-items-center p-0" href="#" id="userDropdownMobile"
            role="button" data-bs-toggle="dropdown" aria-expanded="false">

            <img src="<?php echo (!empty($image) && file_exists('images/' . $image)) 
                        ? 'images/' . $image 
                        : 'images/avatar.png'; ?>"
                 class="rounded-circle"
                 style="width:35px; height:35px; object-fit:cover; border:2px solid #242849;">
        </a>

        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
            <li><a class="dropdown-item py-2" href="settings.php">
                <i class="fas fa-cog me-2"></i>Account Settings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item py-2 text-danger" href="process.php">
                <i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
        </ul>
    </li>


    <!-- ================= DESKTOP ONLY ================= -->
    <li class="nav-item dropdown d-none d-lg-block">
        <a class="nav-link d-flex align-items-center p-0" href="#" id="userDropdownDesktop"
            role="button" data-bs-toggle="dropdown" aria-expanded="false">

            <img src="<?php echo (!empty($image) && file_exists('images/' . $image)) 
                        ? 'images/' . $image 
                        : 'images/avatar.png'; ?>"
                 class="rounded-circle me-2"
                 style="width:35px; height:35px; object-fit:cover; border:2px solid #242849;">
                 <span><?php echo $display_name; ?></span>&nbsp;
            <i class="fas fa-chevron-down" style="font-size:14px; color:#173663;"></i>
        </a>

        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
            <li><a class="dropdown-item py-2" href="settings.php">
                <i class="fas fa-cog me-2"></i>Account Settings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item py-2 text-danger" href="process.php">
                <i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
        </ul>
    </li>

<?php }elseif ($session->userlevel == 2) { ?>
                    <!-- TEACHER -->
                    
                    <!-- Desktop: Profile Image + Name + Dropdown -->
                    <li class="nav-item dropdown position-relative d-none d-lg-block">
                        <a class="nav-link dropdown-toggle d-flex align-items-center px-2" href="#" id="teacherDropdownDesktop" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #173663;">
                            <img 
                                src="<?php echo (!empty($image) && file_exists('images/' . $image)) ? 'images/' . $image : 'images/avatar.png'; ?>" 
                                class="rounded-circle me-2" 
                                style="width: 35px; height: 35px; object-fit: cover; border: 2px solid #242849;">
                            <span><?php echo $display_name; ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm user-dropdown-menu" aria-labelledby="teacherDropdownDesktop">
                           <li><a class="dropdown-item py-2" href="teacher-details.php?id=<?php echo $teacher_id; ?>"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item py-2" href="teacher-sessions.php"><i class="fas fa-calendar-alt me-2"></i>Sessions</a></li>
                            <!-- <li><a class="dropdown-item py-2" href="numberofstudents-teacher.php"><i class="fas fa-users me-2"></i>Students</a></li> -->
                            <!-- <li><a class="dropdown-item py-2" href="numberoftasks-teacher.php"><i class="fas fa-list-check me-2"></i>Tasks</a></li>
                            <li><a class="dropdown-item py-2" href="numberofexcercises.php"><i class="fas fa-pen-to-square"></i>Exercises</a></li> -->
                            <li><a class="dropdown-item py-2" href="teacher-timetable.php"><i class="fas fa-clock me-2"></i>Timetable</a></li>
                            <li><a class="dropdown-item py-2" href="view-attendance-teacher.php"><i class="fas fa-file-alt me-2"></i>Attendance</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2" href="settings.php"><i class="fas fa-cog me-2"></i>Account Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 text-danger" href="process.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>

                    <!-- Mobile: Menu Button (opens dropdown) -->
                    <li class="nav-item dropdown position-static d-lg-none">
                        <button class="btn btn-outline-primary d-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bars me-2"></i>
                            <span>Menu</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm mobile-menu-dropdown">
                            <li><a class="dropdown-item py-2" href="teacher-details.php?id=<?php echo $teacher_id; ?>"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item py-2" href="teacher-sessions.php"><i class="fas fa-calendar-alt me-2"></i>Sessions</a></li>
                            <!-- <li><a class="dropdown-item py-2" href="numberoftasks-teacher.php"><i class="fas fa-tasks me-2"></i>Tasks</a></li>
                            <li><a class="dropdown-item py-2" href="numberofexcercises.php"><i class="fas fa-tasks me-2"></i>Exercises</a></li> -->
                            <li><a class="dropdown-item py-2" href="teacher-timetable.php"><i class="fas fa-clock me-2"></i>Timetable</a></li>
                            <li><a class="dropdown-item py-2" href="view-attendance-teacher.php"><i class="fas fa-file-alt me-2"></i>Attendance</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2" href="settings.php"><i class="fas fa-cog me-2"></i>Account Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 text-danger" href="process.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>

                    <?php } else { ?>
                    <!-- STUDENT -->
                    
                    <!-- Desktop: Profile Image + Name + Dropdown -->
                    <li class="nav-item dropdown position-relative d-none d-lg-block">
                        <a class="nav-link dropdown-toggle d-flex align-items-center px-2" href="#" id="studentDropdownDesktop" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #173663;">
                            <img 
                                src="<?php echo (!empty($image) && file_exists('images/' . $image)) ? 'images/' . $image : 'images/avatar.png'; ?>" 
                                class="rounded-circle me-2" 
                                style="width: 35px; height: 35px; object-fit: cover; border: 2px solid #242849;">
                            <span><?php echo $display_name; ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm user-dropdown-menu" aria-labelledby="studentDropdownDesktop">
                            <li><a class="dropdown-item py-2" href="profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item py-2" href="task-overview.php?student_id=<?php echo $student_id ?>"><i class="fas fa-tasks me-2"></i>Tasks</a></li>
                            <li><a class="dropdown-item py-2" href="student-timetable.php"><i class="fas fa-clock me-2"></i>Timetable</a></li>
                            <li><a class="dropdown-item py-2" href="view-attendance-student.php"><i class="fas fa-file-alt me-2"></i>Attendance</a></li>
                            <!-- <li><a class="dropdown-item py-2" href="student-fees.php"><i class="fas fa-credit-card me-2"></i>Fees</a></li>
                            <li><hr class="dropdown-divider"></li> -->
                            <li><a class="dropdown-item py-2" href="settings.php"><i class="fas fa-cog me-2"></i>Account Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 text-danger" href="process.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>

                    <!-- Mobile: Menu Button (opens dropdown) -->
                    <li class="nav-item dropdown position-static d-lg-none">
                        <button class="btn btn-outline-primary d-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bars "></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm mobile-menu-dropdown">
                            <li><a class="dropdown-item py-2" href="profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item py-2" href="task-overview.php?student_id=<?php echo $student_id ?>"><i class="fas fa-tasks me-2"></i>Tasks</a></li>
                            <li><a class="dropdown-item py-2" href="student-timetable.php"><i class="fas fa-clock me-2"></i>Timetable</a></li>
                            <li><a class="dropdown-item py-2" href="view-attendance-student.php"><i class="fas fa-file-alt me-2"></i>Attendance</a></li>
                            <!-- <li><a class="dropdown-item py-2" href="student-fees.php"><i class="fas fa-credit-card me-2"></i>Fees</a></li>
                            <li><hr class="dropdown-divider"></li> -->
                            <li><a class="dropdown-item py-2" href="settings.php"><i class="fas fa-cog me-2"></i>Account Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 text-danger" href="process.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>

                    <?php } ?>
                    
                </ul>
            </div>
        </div>
    </nav>
</div>

<style type="text/css">
    /* Navbar Styles */
    .dashboard-header {
        margin-bottom: 0;
    }

    .dashboard-header .navbar {
        padding: 0.5rem 1rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    }

    .navbar-brand {
        padding: 0.25rem 0;
    }

    .logo-img {
        transition: all 0.3s ease;
    }

    /* Menu Button Styles */
    .btn-outline-primary {
        border-radius: 6px;
        border: 1px solid #173663;
        color: #173663;
        background-color: transparent;
        transition: all 0.3s ease;
        font-weight: 500;
        padding: 0.5rem 1rem;
        font-size: 14px;
    }

    .btn-outline-primary:hover,
    .btn-outline-primary:focus,
    .btn-outline-primary:active {
        background-color: #173663 !important;
        color: #ffffff !important;
        border-color: #173663 !important;
    }

    .btn-outline-primary i {
        font-size: 16px;
    }

    /* Sidebar Toggle Button (Mobile - Admin only) */
    .btn-link.nav-link {
        text-decoration: none;
        border: none;
        background: transparent;
    }

    .btn-link.nav-link:hover {
        background-color: rgba(23, 54, 99, 0.1);
        border-radius: 4px;
    }

    /* Profile Link Styles */
    .navbar-nav .nav-link {
        color: #173663;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .navbar-nav .nav-link:hover {
        color: #1f4c8f;
    }

    /* Common Dropdown Menu Styles */
    .navbar-nav .dropdown-menu {
        border: none;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        margin-top: 0.5rem;
        min-width: 200px;
        max-width: 280px;
        max-height: 70vh;
        overflow-y: auto;
    }

    /* Menu Dropdown Specific */
    .menu-dropdown-menu {
        min-width: 250px;
    }

    .menu-dropdown-menu .dropdown-header {
        font-size: 11px;
        font-weight: 600;
        padding: 0.75rem 1rem 0.5rem;
        letter-spacing: 0.5px;
    }

    /* Mobile Menu Dropdown */
    .mobile-menu-dropdown {
        min-width: 250px;
        position: absolute !important;
        top: 100% !important;
        right: 0.75rem !important;
        left: auto !important;
    }

    /* Dropdown Items */
    .navbar-nav .dropdown-item,
    .mobile-menu-dropdown .dropdown-item {
        padding: 0.5rem 1rem;
        color: #495057;
        transition: background-color 0.2s ease;
        white-space: normal;
        word-wrap: break-word;
        font-size: 14px;
    }

    .navbar-nav .dropdown-item:hover,
    .mobile-menu-dropdown .dropdown-item:hover {
        background-color: #f8f9fa;
        color: #173663;
    }

    .navbar-nav .dropdown-item i,
    .mobile-menu-dropdown .dropdown-item i {
        width: 20px;
        text-align: center;
        font-size: 14px;
    }

    /* Scrollbar Styling */
    .dropdown-menu::-webkit-scrollbar {
        width: 6px;
    }

    .dropdown-menu::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .dropdown-menu::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .dropdown-menu::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* Desktop Styles */
    @media (min-width: 992px) {
        .nav-item.dropdown.position-relative {
            position: relative !important;
        }
        
        .navbar-nav .user-dropdown-menu,
        .navbar-nav .menu-dropdown-menu {
            position: absolute !important;
            top: 100% !important;
            right: 0 !important;
            left: auto !important;
        }
    }

    /* Mobile Styles */
    @media (max-width: 991.98px) {
        .navbar-brand .logo-img {
            max-width: 120px;
            height: 30px;
        }

        /* Position dropdowns correctly on mobile */
        .nav-item.dropdown.position-static {
            position: static;
        }

        .navbar-nav .user-dropdown-menu {
            position: absolute !important;
            top: 100% !important;
            right: 0.75rem !important;
            left: auto !important;
            margin-top: 0.25rem !important;
            min-width: 220px !important;
            max-width: calc(100vw - 1.5rem) !important;
        }

        /* Mobile menu button styling */
        .btn-outline-primary {
            padding: 0.4rem 0.8rem;
            font-size: 14px;
        }
    }

    @media (max-width: 575.98px) {
        .navbar-brand .logo-img {
            max-width: 100px;
            height: 25px;
        }
        
        .dashboard-header .navbar {
            padding: 0.5rem 0.75rem;
        }

        /* Compact menu button on very small screens */
        .btn-outline-primary span {
            display: none;
        }

        .btn-outline-primary .me-2 {
            margin-right: 0 !important;
        }

        .btn-outline-primary {
            padding: 0.4rem 0.6rem;
        }

        .mobile-menu-dropdown {
            right: 0.5rem !important;
            min-width: 200px !important;
        }
    }

    /* Body padding for fixed navbar */
    body {
        padding-top: 60px;
    }

    /* Prevent navbar from expanding */
    .navbar {
        min-height: 60px;
    }

    /* Remove gap on very small screens */
    @media (max-width: 575.98px) {
        .navbar-nav.gap-2 {
            gap: 0.5rem !important;
        }
    }
    /* Prevent mobile dropdown from expanding the navbar height */
.navbar .dropdown-menu {
    position: absolute !important;
    top: 100% !important;
    left: auto !important;
    right: 0 !important;

    transform: none !important;
    width: max-content;
    max-width: 250px;
    z-index: 2000;
}

/* Ensure mobile menu button doesn’t push navbar */
.nav-item.dropdown.position-static .dropdown-menu {
    position: absolute !important;
    width: 250px;
}

/* Ensure navbar stays fixed height on mobile */
.navbar {
    overflow: visible !important;
    height: auto !important;
}

</style>
<!-- ============================================================== -->
<!-- end navbar -->
<!-- ============================================================== -->