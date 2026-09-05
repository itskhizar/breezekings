<?php
// This file contains the menu items that are shared between desktop sidebar and mobile offcanvas
?>

<li class="nav-divider">Menu</li>

<?php if ($session->userlevel == 1 OR $session->userlevel == 4) { ?>
    <li class="nav-item">
        <a class="nav-link active" href="#" data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#submenu-14" aria-controls="submenu-14">
            <i class="fas fa-building"></i>Front Office<span class="badge bg-success">6</span>
        </a>
        <div id="submenu-14" class="collapse submenu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="add-visitor.php">Add Visitor<span class="badge bg-secondary">New</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="numberofvisitors.php">View Visitors<span class="badge bg-secondary">New</span></a>
                </li>
                
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="#" data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#submenu-careers" aria-controls="submenu-careers">
            <i class="fas fa-briefcase"></i> Careers
            <span class="badge bg-success">6</span>
        </a>
        <div id="submenu-careers" class="collapse submenu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="#">
                        Add Career Post <span class="badge bg-secondary">New</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="numberofcareers.php">
                        View Applications <span class="badge bg-secondary">New</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="#">
                        Interview / Call Log <span class="badge bg-secondary">New</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="#" data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#submenu-9" aria-controls="submenu-9">
            <i class="fas fa-user-plus"></i>Admission Enquiry<span class="badge bg-success">6</span>
        </a>
        <div id="submenu-9" class="collapse submenu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="add-admission.php">Add Admission <span class="badge bg-secondary">New</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="numberofadmissions.php">View Admission Enquiries <span class="badge bg-secondary">New</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="call-log.php">Phone Call Log <span class="badge bg-secondary">New</span></a>
                </li>
            </ul>
        </div>
    </li>
    

    <li class="nav-item">
        <a class="nav-link active" href="#" data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#submenu-6" aria-controls="submenu-6">
            <i class="fas fa-users"></i>Registrations<span class="badge bg-success">6</span>
        </a>
        <div id="submenu-6" class="collapse submenu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="add-student.php">Register Student <span class="badge bg-secondary">New</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="numberofstudents.php">View Registered Students <span class="badge bg-secondary">New</span></a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link active" href="#" data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#submenu-16" aria-controls="submenu-16">
            <i class="fas fa-users"></i>Students<span class="badge bg-success">6</span>
        </a>
        <div id="submenu-16" class="collapse submenu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="numberofstudents-admin.php">View Students <span class="badge bg-secondary">New</span></a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link active" href="#" data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#submenu-4" aria-controls="submenu-4">
            <i class="fas fa-book"></i> Courses<span class="badge bg-success">6</span>
        </a>
        <div id="submenu-4" class="collapse submenu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="add-course.php">Add Course <span class="badge bg-secondary">New</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="numberofcourses.php">View Courses <span class="badge bg-secondary">New</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="#" data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#submenu-categories" aria-controls="submenu-categories">
                        <i class="fas fa-tags"></i> Categories <span class="badge bg-secondary">New</span>
                    </a>
                    <div id="submenu-categories" class="collapse submenu">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="add-category.php">Add Category</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="numberofcategories.php">View Categories</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </li>
    
    <li class="nav-item">
        <a class="nav-link active" href="#" data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#submenu-1" aria-controls="submenu-1">
            <i class="fas fa-university"></i>Classes<span class="badge bg-success">6</span>
        </a>
        <div id="submenu-1" class="collapse submenu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="add-class.php">Add class<span class="badge bg-secondary">New</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="numberofclasses.php">View classes<span class="badge bg-secondary">New</span></a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link active" href="#" data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#submenu-3" aria-controls="submenu-3">
            <i class="fas fa-calendar-alt"></i>Sessions<span class="badge bg-success">6</span>
        </a>
        <div id="submenu-3" class="collapse submenu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="add-session.php">Add Session <span class="badge bg-secondary">New</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="numberofsessions.php">View Session <span class="badge bg-secondary">New</span></a>
                </li>
            </ul>
        </div>
    </li>

    


    <?php if ($session->userlevel == 4) { ?>
    <li class="nav-item">
        <a class="nav-link active" href="#" data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#submenu-15" aria-controls="submenu-15">
            <i class="fas fa-users"></i> Users<span class="badge bg-success">7</span>
        </a>
        <div id="submenu-15" class="collapse submenu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="add-admin.php">Add Admin <span class="badge bg-secondary">New</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="numberofusers.php">View Users <span class="badge bg-secondary">New</span></a>
                </li>
            </ul>
        </div>
    </li>
    <?php } ?>

    <li class="nav-item">
        <a class="nav-link active" href="#" data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#submenu-13" aria-controls="submenu-13">
            <i class="fas fa-cog"></i>System Settings<span class="badge bg-success">6</span>
        </a>
        <div id="submenu-13" class="collapse submenu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="settings.php">Account settings <span class="badge bg-secondary">New</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="process.php">Logout <span class="badge bg-secondary">New</span></a>
                </li>
            </ul>
        </div>
    </li>
<?php } ?>

