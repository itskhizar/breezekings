<?php 
include("include/classes/session.php");
if ($session->logged_in) {
    $username = $session->username;
    $result = $database->getUserInfo($username);
    $userlevel = ($result['userlevel']);
    $display_name = $result['display_name'];
    $email = $result['email'];
    $phone = $result['phone'];
    $image = $result['parent_directory'];
    $password = $result['password'];
} else {
    header('location: index.php');
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="assetss/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="assetss/libs/css/style.css">
    <link rel="stylesheet" href="assetss/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <link rel="stylesheet" href="assetss/vendor/vector-map/jqvmap.css">
    <link rel="stylesheet" href="assetss/vendor/jvectormap/jquery-jvectormap-2.0.2.min.css">
    <link rel="stylesheet" href="assetss/vendor/fonts/flag-icon-css/flag-icon.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-dyV+Z1KxN5YH1M6vKZvV8f1zL+v6D8XYO9N7jT+b1kEvH5Y7fPycdwqFZdKpc5v5N5hC0MjfEv7MnbHgIl7Z9A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="assets/img/favicon-filenod.png" rel="icon">
    <title>Visitors</title>
    <style type="text/css">
        :root {
            --primary-dark: #0e0c28;
            --primary-gradient: linear-gradient(135deg, #0e0c28 0%, #1a1742 50%, #2d2463 100%);
            --accent-color: #6c5ce7;
            --accent-light: #a29bfe;
            --text-light: #ffffff;
            --text-muted: #b8b8d4;
            --card-bg: #ffffff;
            --shadow: 0 10px 40px rgba(14, 12, 40, 0.15);
            --shadow-hover: 0 15px 50px rgba(14, 12, 40, 0.25);
        }

        body {
            background: linear-gradient(135deg, #f8f9fd 0%, #eef1f9 100%);
            font-family: 'Circular Std', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .dashboard-wrapper {
            background: transparent;
        }

        /* Page Header */
        .page-header-modern {
            background: var(--primary-gradient);
            border-radius: 20px;
            padding: 35px 40px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
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
            color: var(--text-light);
            font-weight: 600;
            margin: 0 0 10px 0;
            font-size: 28px;
            position: relative;
            z-index: 1;
        }

        .page-header-modern p {
            color: var(--text-muted);
            margin: 0;
            font-size: 14px;
            position: relative;
            z-index: 1;
        }

        /* Search Filter Card */
        .search-filter-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            border: none;
            transition: all 0.3s ease;
        }

        .search-filter-card:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-2px);
        }

        .filter-title {
            color: var(--primary-dark);
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-title i {
            color: var(--accent-color);
            font-size: 20px;
        }

        /* Search Input Groups */
        .search-input-group {
            position: relative;
            margin-bottom: 15px;
        }

        .search-input-group i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--accent-color);
            font-size: 16px;
            z-index: 2;
        }

        .search-input-group input {
            width: 100%;
            padding: 14px 20px 14px 50px;
            border: 2px solid #e8ecf4;
            border-radius: 12px;
            font-size: 14px;
            color: var(--primary-dark);
            background: #f8f9fd;
            transition: all 0.3s ease;
        }

        .search-input-group input:focus {
            outline: none;
            border-color: var(--accent-color);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(108, 92, 231, 0.1);
        }

        .search-input-group input::placeholder {
            color: #a8b3cf;
        }

        /* Table Card */
        .table-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 30px;
            box-shadow: var(--shadow);
            border: none;
        }

        /* Modern Table */
        .table-modern {
            width: 100%;
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-modern thead tr {
            background: var(--primary-gradient);
        }

        .table-modern thead th {
            padding: 18px 20px;
            color: var(--text-light);
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            white-space: nowrap;
        }

        .table-modern thead th:first-child {
            border-top-left-radius: 12px;
        }

        .table-modern thead th:last-child {
            border-top-right-radius: 12px;
        }

        .table-modern tbody tr {
            background: #ffffff;
            transition: all 0.3s ease;
        }

        .table-modern tbody tr:hover {
            background: #f8f9fd;
            transform: scale(1.01);
            box-shadow: 0 4px 12px rgba(14, 12, 40, 0.08);
        }

        .table-modern tbody td {
            padding: 18px 20px;
            color: var(--primary-dark);
            font-size: 14px;
            border-bottom: 1px solid #f0f2f8;
            vertical-align: middle;
        }

        .table-modern tbody tr:last-child td {
            border-bottom: none;
        }

        .table-modern tbody tr:last-child td:first-child {
            border-bottom-left-radius: 12px;
        }

        .table-modern tbody tr:last-child td:last-child {
            border-bottom-right-radius: 12px;
        }

        .table-responsive-modern {
    overflow-x: auto;
    border-radius: 12px;
}


        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge.active {
            background: linear-gradient(135deg, #00b894, #00cec9);
            color: white;
        }

        .status-badge.inactive {
            background: linear-gradient(135deg, #ff7675, #fd79a8);
            color: white;
        }

        .status-badge.pending {
            background: linear-gradient(135deg, #fdcb6e, #ffeaa7);
            color: var(--primary-dark);
        }

        /* Action Button */
        .action-btn-modern {
            padding: 8px 20px;
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .action-btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(108, 92, 231, 0.4);
            color: white;
            text-decoration: none;
        }

        .action-btn-modern i {
            margin-right: 5px;
        }

        /* Table Responsive */
       /* Default — large screens */
.table-responsive-modern {
    overflow-x: visible;
    border-radius: 12px;
}

/* Mobile — enable scrolling only on small screens */
@media (max-width: 768px) {
    .table-responsive-modern {
        overflow-x: auto;
    }
}


        .table-responsive-modern::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive-modern::-webkit-scrollbar-track {
            background: #f0f2f8;
            border-radius: 10px;
        }

        .table-responsive-modern::-webkit-scrollbar-thumb {
            background: var(--accent-light);
            border-radius: 10px;
        }

        .table-responsive-modern::-webkit-scrollbar-thumb:hover {
            background: var(--accent-color);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 64px;
            color: #0e0c28;
            margin-bottom: 20px;
        }

        .empty-state h4 {
            color: var(--primary-dark);
            font-weight: 600;
            margin-bottom: 10px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header-modern {
                padding: 25px 20px;
            }

            .page-header-modern h2 {
                font-size: 22px;
            }

            .search-filter-card,
            .table-card {
                padding: 20px;
            }

            .filter-title {
                font-size: 16px;
            }

            .table-modern thead th,
            .table-modern tbody td {
                padding: 12px 15px;
                font-size: 13px;
            }
        }

        /* Loading State */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            z-index: 10;
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f0f2f8;
            border-top-color: var(--accent-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body>
    <?php if ($session->logged_in) { ?>
        <div class="dashboard-main-wrapper">
            <!-- navbar -->
            <?php include("navbar.php"); ?>
            <?php include("leftbar.php"); ?>
            
            <!-- wrapper -->
            <div class="dashboard-wrapper">
                <div class="container-fluid dashboard-content">
                    
                    <!-- Page Header -->
                    <!-- <div class="page-header-modern">
                        <h2><i class="fas fa-user-graduate me-2"></i>Students Management</h2>
                        <p>Search, view and manage student records</p>
                    </div> -->

                    <!-- Search Filters -->
                    <div class="search-filter-card">
                        <div class="filter-title">
                            <i class="fas fa-filter"></i>
                            Search Filters
                        </div>
                        <form id="searchForm">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="search-input-group">
                                        <i class="fas fa-user"></i>
                                        <input
                                            type="text"
                                            id="company_name"
                                            class="form-control"
                                            placeholder="Search by Name"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="search-input-group">
                                        <i class="fas fa-tag"></i>
                                        <input
                                            type="text"
                                            id="amount_paid"
                                            class="form-control"
                                            placeholder="Search by Category"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="search-input-group">
                                        <i class="fas fa-clock"></i>
                                        <input
                                            type="text"
                                            id="duration"
                                            class="form-control"
                                            placeholder="Search by Course"
                                        />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Export Visitors Button -->
                    <div class="mb-3 text-right">
                        <a href="export-visitors.php" class="btn " style="background-color: #0e0c28; color: white;">
                            <i class="fas fa-file me-2"></i> Export Visitors
                        </a>
                    </div>



                    <!-- Students Table -->
                    <div class="table-card">
                        <div class="table-responsive-modern">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>Mobile No</th>
                                        <th>Email</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="table-data">
                                    <!-- Dynamic content will load here -->
                                   <!--  <tr>
                                        <td colspan="6">
                                            <div class="loading-overlay">
                                                <div class="loading-spinner"></div>
                                            </div>
                                        </td>
                                    </tr> -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Scripts -->
        <!-- jQuery (latest, for plugins that still require it) -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <!-- Bootstrap 5 JS Bundle (includes Popper) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script src="assetss/vendor/slimscroll/jquery.slimscroll.js"></script>
        <script src="assetss/vendor/charts/charts-bundle/Chart.bundle.js"></script>
        <script src="assetss/vendor/charts/charts-bundle/chartjs.js"></script>
        <script src="assetss/libs/js/main-js.js"></script>
        <script src="assetss/vendor/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
        <script src="assetss/vendor/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
        <script src="assetss/vendor/charts/sparkline/jquery.sparkline.js"></script>
        <script src="assetss/vendor/charts/sparkline/spark-js.js"></script>
        <script src="assetss/libs/js/dashboard-sales.js"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                // Function to load table records
                function loadTable() {
                    $.ajax({
                        url: "ajax-load-visitors.php",
                        type: "POST",
                        success: function(data) {
                            $("#table-data").html(data);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching data:", error);
                            $("#table-data").html("<tr><td colspan='6' class='text-center'><div class='empty-state'><i class='fas fa-exclamation-circle'></i><h4>Error loading data</h4><p>Please try again later</p></div></td></tr>");
                        }
                    });
                }

                // Initial load
                loadTable();

                // Live Search for Name
                $("#company_name").on("keyup", function() {
                    const searchTerm = $(this).val().trim();

                    if (searchTerm.length > 0) {
                        $.ajax({
                            url: "ajax-live-search-students.php",
                            type: "POST",
                            data: { search: searchTerm },
                            success: function(data) {
                                $("#table-data").html(data);
                            },
                            error: function(xhr, status, error) {
                                console.error("Search error:", error);
                            }
                        });
                    } else {
                        loadTable();
                    }
                });

                // Live Search for Category
                $("#amount_paid").on("keyup", function() {
                    const searchTerm = $(this).val().trim();

                    if (searchTerm.length > 0) {
                        $.ajax({
                            url: "ajax-live-search-students.php",
                            type: "POST",
                            data: { search: searchTerm },
                            success: function(data) {
                                $("#table-data").html(data);
                            },
                            error: function(xhr, status, error) {
                                console.error("Search error:", error);
                            }
                        });
                    } else {
                        loadTable();
                    }
                });

                // Live Search for Duration
                $("#duration").on("keyup", function() {
                    const searchTerm = $(this).val().trim();

                    if (searchTerm.length > 0) {
                        $.ajax({
                            url: "ajax-live-search-students.php",
                            type: "POST",
                            data: { search: searchTerm },
                            success: function(data) {
                                $("#table-data").html(data);
                            },
                            error: function(xhr, status, error) {
                                console.error("Search error:", error);
                            }
                        });
                    } else {
                        loadTable();
                    }
                });
            });
        </script>

    <?php } else {
        header("location: index.php");
    } ?>
</body>

</html>