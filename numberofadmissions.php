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
    SUM(CASE WHEN enquiry_status = 'New' THEN 1 ELSE 0 END) as new_count,
    SUM(CASE WHEN enquiry_status = 'Interested' THEN 1 ELSE 0 END) as interested_count,
    SUM(CASE WHEN next_follow_up < NOW() AND enquiry_status NOT IN ('Enrolled', 'Not Interested') THEN 1 ELSE 0 END) as overdue_count
FROM admissions";
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
    <title>Enquiry Management - Filenod Academy</title>
    
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
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
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
        .stat-card-modern.new { border-left-color: #48dbfb; }
        .stat-card-modern.interested { border-left-color: #1dd1a1; }
        .stat-card-modern.overdue { border-left-color: #ee5a6f; }

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

        /* Status Badges */
        .badge-status {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            display: inline-block;
        }

        .badge-new { background: linear-gradient(135deg, #667eea, #764ba2); color: white; }
        .badge-attempted { background: linear-gradient(135deg, #feca57, #ff9ff3); color: var(--primary-dark); }
        .badge-connected { background: linear-gradient(135deg, #48dbfb, #0abde3); color: white; }
        .badge-interested { background: linear-gradient(135deg, #1dd1a1, #10ac84); color: white; }
        .badge-scheduled { background: linear-gradient(135deg, #00d2d3, #54a0ff); color: white; }
        .badge-visited { background: linear-gradient(135deg, #5f27cd, #341f97); color: white; }
        .badge-enrolled { background: linear-gradient(135deg, #00b894, #00cec9); color: white; }
        .badge-waiting { background: linear-gradient(135deg, #ee5a6f, #f53b57); color: white; }
        .badge-not-interested { background: linear-gradient(135deg, #c8d6e5, #8395a7); color: white; }
        .badge-followup {
            background-color: #ff9800; /* Orange */
            color: #fff;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 12px;
        }

        /* Priority Icons */
        .priority-urgent { color: #e74c3c; font-size: 16px; animation: pulse 1.5s infinite; }
        .priority-high { color: #f39c12; font-size: 16px; }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

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

        .btn-whatsapp {
            background: linear-gradient(135deg, #25D366, #128C7E);
            color: white;
        }

        .btn-whatsapp:hover {
            background: linear-gradient(135deg, #1fc757, #0e6b5e);
            color: white;
            transform: translateY(-2px);
        }

        .phone-link {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
        }

        .phone-link:hover {
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
        .dashboard-wrapper {
    margin-left: 250px; /* same as sidebar width */
    /*padding: 20px;*/
    overflow-x: visible; /* allow inner scroll */
}

        .dashboard-main-wrapper{
            padding-top: 0px !important;
        }
        .dashboard-leftbar {
    width: 250px; /* sidebar width */
    flex-shrink: 0;
    background: white;
    box-shadow: 2px 0 8px rgba(0,0,0,0.05);
    position: fixed; /* keep it fixed */
    height: 100vh;
    overflow-y: auto;
    z-index: 10;
}
.table-responsive-modern {
    width: 100%;
    overflow-x: auto;  /* enables horizontal scroll */
    -webkit-overflow-scrolling: touch; /* smooth scroll on mobile */
    position: relative;
    z-index: 1; /* ensure it stays above background */
}
@media (max-width: 992px) {
    .dashboard-wrapper {
        margin-left: 0; /* sidebar stacks on top */
    }
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
                        <h2><i class="fas fa-users me-2"></i>Enquiry Management Dashboard</h2>
                        <p>Track, manage, and convert student enquiries into admissions</p>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="stats-grid">
                        <div class="stat-card-modern total">
                            <div class="stat-number"><?php echo $stats['total']; ?></div>
                            <div class="stat-label">Total Enquiries</div>
                        </div>
                        <div class="stat-card-modern new">
                            <div class="stat-number"><?php echo $stats['new_count']; ?></div>
                            <div class="stat-label">New Enquiries</div>
                        </div>
                        <div class="stat-card-modern interested">
                            <div class="stat-number"><?php echo $stats['interested_count']; ?></div>
                            <div class="stat-label">Interested</div>
                        </div>
                        <div class="stat-card-modern overdue">
                            <div class="stat-number"><?php echo $stats['overdue_count']; ?></div>
                            <div class="stat-label">Overdue Follow-ups</div>
                        </div>
                    </div>

                    <!-- Filter Card -->
                    <div class="filter-card">
                        <div class="filter-title">
                            <i class="fas fa-filter"></i>
                            Search & Filter Enquiries
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="search-input-group">
                                    <i class="fas fa-search"></i>
                                    <input type="text" id="searchInput" placeholder="Search name, phone, email...">
                                </div>
                            </div>
                            
                            <div class="col-md-2 mb-3">
                                <div class="search-input-group">
                                    <i class="fas fa-tag"></i>
                                    <select id="statusFilter">
                                        <option value="">All Status</option>
                                        <option value="New">New</option>
                                        <option value="Follow-up Required">Follow-up Required</option>
                                        <option value="Interested">Interested</option>
                                        <option value="Not Interested">Not Interested</option>
                                        <option value="Enrolled">Enrolled</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2 mb-3">
                                <div class="search-input-group">
                                    <i class="fas fa-bullhorn"></i>
                                    <select id="sourceFilter">
                                        <option value="">All Sources</option>
                                        <option value="website">Website</option>
                                        <option value="facebook">Facebook</option>
                                        <option value="instagram">Instagram</option>
                                        <option value="whatsapp">WhatsApp</option>
                                        <option value="referral">Referral</option>
                                        <option value="walk-in">Walk-in</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2 mb-3">
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
                            
                            <div class="col-md-3 mb-3">
                                <button class="btn btn-primary w-100" onclick="loadEnquiries()">
                                    <i class="fas fa-search me-2"></i>Apply Filters
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Enquiries Table -->
                    <div class="table-card">
                        <div class="table-responsive-modern">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Source</th>
                                        <th>Date</th>
                                        <th>Follow-up</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="enquiriesTableBody">
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="spinner mx-auto"></div>
                                            <p class="mt-3 text-muted">Loading enquiries...</p>
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
            // Load enquiries on page load
            $(document).ready(function() {
                loadEnquiries();
                
                // Auto-search on input
                $('#searchInput').on('keyup', function() {
                    loadEnquiries();
                });
            });

            // Load enquiries function
            function loadEnquiries() {
                $('#loadingOverlay').addClass('active');
                
                $.ajax({
                    url: 'ajax-search-enquiries.php',
                    type: 'POST',
                    data: {
                        search: $('#searchInput').val(),
                        status: $('#statusFilter').val(),
                        source: $('#sourceFilter').val(),
                        date: $('#dateFilter').val()
                    },
                    success: function(response) {
                        $('#enquiriesTableBody').html(response);
                        $('#loadingOverlay').removeClass('active');
                    },
                    error: function() {
                        $('#enquiriesTableBody').html(`
                            <tr>
                                <td colspan="9" class="text-center text-danger py-4">
                                    <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                                    <p>Error loading enquiries. Please try again.</p>
                                </td>
                            </tr>
                        `);
                        $('#loadingOverlay').removeClass('active');
                    }
                });
            }

            // WhatsApp function
           function openWhatsApp(phone, name, mobile, email, field_education, institute, interest, course) {
                const message = `
            👋 Hi ${name}!
            Thank you for showing interest in Filenod Academy.
            We have received your enquiry for: ${course}.

            Here are the details you provided:
            📱 Mobile: ${mobile}
            📧 Email: ${email}
            🏫 Field of Education: ${field_education}
            🏫 Institute: ${institute}

            One of our career counsellors will contact you shortly.

            Meanwhile, here’s what you get with Filenod Academy:
            ✔ Professional Mentorship
            ✔ Project-Based Learning
            ✔ Modern Online + Campus Portal
            ✔ Job & Freelancing Support
            ✔ Market Recognized Certificates
                    
            Would you like to schedule a campus visit or speak with a counsellor during office hours?

            Reply with:
            � Call me
            � Send fee details
            � Schedule visit
            `;

    const encodedMessage = encodeURIComponent(message);
    window.open(`https://wa.me/${phone}?text=${encodedMessage}`, '_blank');
}

        </script>
        
    <?php } else {
        header("location: index.php");
    } ?>
</body>
</html>