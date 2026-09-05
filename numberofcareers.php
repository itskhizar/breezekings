<?php
include("include/classes/session.php");
if (!$session->logged_in) {
    header('location: index.php');
    exit;
}

$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$username = $session->username;
$user_result = $database->getUserInfo($username);
$display_name = $user_result['display_name'];
$image = $user_result['parent_directory'];

// Get statistics
$stats_query = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN position = 'Assistant Supervisor' THEN 1 ELSE 0 END) as supervisor_count,
    SUM(CASE WHEN position = 'Admission Advisor' THEN 1 ELSE 0 END) as advisor_count,
    SUM(CASE WHEN position = 'Coach' THEN 1 ELSE 0 END) as coach_count,
    SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today_count
FROM careers";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assetss/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="assetss/libs/css/style.css">
    <link rel="stylesheet" href="assetss/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <link href="assets/img/favicon-filenod.png" rel="icon">
    <title>Career Applications - Filenod Academy</title>
    
    <style>
        :root {
            --primary-dark: #0e0c28;
            --primary-gradient: linear-gradient(135deg, #0e0c28 0%, #1a1742 50%, #2d2463 100%);
            --accent-color: #6c5ce7;
            --accent-light: #a29bfe;
        }

        body {
            background: linear-gradient(135deg, #f8f9fd 0%, #eef1f9 100%);
            font-family: 'Circular Std', sans-serif;
        }

        /* Page Header */
        .page-header-modern {
            background: var(--primary-gradient);
            border-radius: 20px;
            padding: 30px 35px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(14, 12, 40, 0.15);
            position: relative;
            overflow: hidden;
        }

        .page-header-modern::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .page-header-modern h2 {
            color: white;
            font-weight: 600;
            margin: 0 0 10px 0;
            font-size: 26px;
            position: relative;
            z-index: 1;
        }

        .page-header-modern p {
            color: rgba(255,255,255,0.8);
            margin: 0;
            font-size: 14px;
            position: relative;
            z-index: 1;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card-modern {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border-left: 4px solid;
        }

        .stat-card-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .stat-card-modern.total { border-left-color: #667eea; }
        .stat-card-modern.supervisor { border-left-color: #48dbfb; }
        .stat-card-modern.advisor { border-left-color: #1dd1a1; }
        .stat-card-modern.coach { border-left-color: #feca57; }
        .stat-card-modern.today { border-left-color: #ee5a6f; }

        .stat-number {
            font-size: 36px;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 5px;
        }

        .stat-label {
            color: #888;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Filter Card */
        .filter-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .filter-title {
            color: var(--primary-dark);
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .search-input-group {
            position: relative;
        }

        .search-input-group i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--accent-color);
            z-index: 2;
        }

        .search-input-group input,
        .search-input-group select {
            width: 100%;
            padding: 12px 20px 12px 45px;
            border: 2px solid #e8ecf4;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .search-input-group input:focus,
        .search-input-group select:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(108, 92, 231, 0.1);
        }

        /* Table */
        .table-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .table-modern {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-modern thead tr {
            background: var(--primary-gradient);
        }

        .table-modern thead th {
            padding: 16px 15px;
            color: white;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
            border: none;
        }

        .table-modern thead th:first-child {
            border-top-left-radius: 12px;
        }

        .table-modern thead th:last-child {
            border-top-right-radius: 12px;
        }

        .table-modern tbody tr {
            background: white;
            transition: all 0.3s ease;
        }

        .table-modern tbody tr:hover {
            background: #f8f9fd;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .table-modern tbody td {
            padding: 16px 15px;
            color: var(--primary-dark);
            font-size: 13px;
            border-bottom: 1px solid #f0f2f8;
            vertical-align: middle;
        }

        /* Position Badges */
        .badge-position {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            display: inline-block;
        }

        .badge-supervisor { background: linear-gradient(135deg, #667eea, #764ba2); color: white; }
        .badge-advisor { background: linear-gradient(135deg, #1dd1a1, #10ac84); color: white; }
        .badge-coach { background: linear-gradient(135deg, #feca57, #ff9ff3); color: var(--primary-dark); }
        .badge-other { background: linear-gradient(135deg, #c8d6e5, #8395a7); color: white; }

        /* Action Buttons */
        .btn-action {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 13px;
            margin: 0 2px;
        }

        .btn-view {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-view:hover {
            background: linear-gradient(135deg, #5568d3, #6a3f8f);
            color: white;
            transform: translateY(-2px);
        }

        .btn-delete {
            background: linear-gradient(135deg, #ee5a6f, #f53b57);
            color: white;
        }

        .btn-delete:hover {
            background: linear-gradient(135deg, #d94758, #dc2f47);
            color: white;
            transform: translateY(-2px);
        }

        .phone-link, .email-link {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
        }

        .phone-link:hover, .email-link:hover {
            color: var(--primary-dark);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .table-responsive-modern {
                overflow-x: auto;
            }
        }

        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loading-overlay.active {
            display: flex;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f0f2f8;
            border-top-color: var(--accent-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
         
        .dashboard-wrapper{
            margin-left: 0px;
        }
        .dashboard-main-wrapper{
            padding-top: 0px !important;
        }
    </style>
</head>

<body>
    <?php if ($session->logged_in) { ?>
        <div class="dashboard-main-wrapper">
            <?php include("navbar.php"); ?>
            <?php include("leftbar.php"); ?>
            
            <div class="dashboard-wrapper">
                <div class="container-fluid dashboard-content">
                    
                    <!-- Page Header -->
                    <div class="page-header-modern">
                        <h2><i class="fas fa-briefcase me-2"></i>Career Applications Management</h2>
                        <p>Review and manage job applications from potential candidates</p>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="stats-grid">
                        <div class="stat-card-modern total">
                            <div class="stat-number"><?php echo $stats['total']; ?></div>
                            <div class="stat-label">Total Applications</div>
                        </div>
                        <div class="stat-card-modern supervisor">
                            <div class="stat-number"><?php echo $stats['supervisor_count']; ?></div>
                            <div class="stat-label">Assistant Supervisor</div>
                        </div>
                        <div class="stat-card-modern advisor">
                            <div class="stat-number"><?php echo $stats['advisor_count']; ?></div>
                            <div class="stat-label">Admission Advisor</div>
                        </div>
                        <div class="stat-card-modern coach">
                            <div class="stat-number"><?php echo $stats['coach_count']; ?></div>
                            <div class="stat-label">Coach</div>
                        </div>
                        <div class="stat-card-modern today">
                            <div class="stat-number"><?php echo $stats['today_count']; ?></div>
                            <div class="stat-label">Today's Applications</div>
                        </div>
                    </div>

                    <!-- Filter Card -->
                    <div class="filter-card">
                        <div class="filter-title">
                            <i class="fas fa-filter"></i>
                            Search & Filter Applications
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="search-input-group">
                                    <i class="fas fa-search"></i>
                                    <input type="text" id="searchInput" placeholder="Search name, phone, email...">
                                </div>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <div class="search-input-group">
                                    <i class="fas fa-briefcase"></i>
                                    <select id="positionFilter">
                                        <option value="">All Positions</option>
                                        <option value="Assistant Supervisor">Assistant Supervisor</option>
                                        <option value="Admission Advisor">Admission Advisor</option>
                                        <option value="Coach">Coach</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <div class="search-input-group">
                                    <i class="fas fa-calendar"></i>
                                    <select id="dateFilter">
                                        <option value="">All Time</option>
                                        <option value="today">Today</option>
                                        <option value="yesterday">Yesterday</option>
                                        <option value="week">This Week</option>
                                        <option value="month">This Month</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2 mb-3">
                                <button class="btn btn-primary w-100" onclick="loadApplications()">
                                    <i class="fas fa-search me-2"></i>Apply Filters
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Applications Table -->
                    <div class="table-card">
                        <div class="table-responsive-modern">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Education</th>
                                        <th>Experience</th>
                                        <th>Applied Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="applicationsTableBody">
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="spinner mx-auto"></div>
                                            <p class="mt-3 text-muted">Loading applications...</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div class="loading-overlay" id="loadingOverlay">
            <div class="spinner"></div>
        </div>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="assetss/libs/js/main-js.js"></script>

        <script>
            // Load applications on page load
            $(document).ready(function() {
                loadApplications();
                
                // Auto-search on input
                $('#searchInput').on('keyup', function() {
                    loadApplications();
                });
            });

            // Load applications function
            function loadApplications() {
                $('#loadingOverlay').addClass('active');
                
                $.ajax({
                    url: 'ajax-search-careers.php',
                    type: 'POST',
                    data: {
                        search: $('#searchInput').val(),
                        position: $('#positionFilter').val(),
                        date: $('#dateFilter').val()
                    },
                    success: function(response) {
                        $('#applicationsTableBody').html(response);
                        $('#loadingOverlay').removeClass('active');
                    },
                    error: function() {
                        $('#applicationsTableBody').html(`
                            <tr>
                                <td colspan="9" class="text-center text-danger py-4">
                                    <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                                    <p>Error loading applications. Please try again.</p>
                                </td>
                            </tr>
                        `);
                        $('#loadingOverlay').removeClass('active');
                    }
                });
            }

            // Delete application
            function deleteApplication(id) {
                if (confirm('Are you sure you want to delete this application?')) {
                    $.ajax({
                        url: 'delete-career.php',
                        type: 'POST',
                        data: { id: id },
                        success: function(response) {
                            loadApplications();
                            alert('Application deleted successfully!');
                        },
                        error: function() {
                            alert('Error deleting application. Please try again.');
                        }
                    });
                }
            }
        </script>
        
    <?php } else {
        header("location: index.php");
    } ?>
</body>
</html>