<?php 
include("include/classes/session.php");
    $username = $session->username;
    $result = $database->getUserInfo($username);
    $userlevel = ($result['userlevel']);
    // $courseID = $database->getCourseID($username);
    // $stdInfo = $database->getStdData($username);
    // if($session->userlevel == 2){
    //     $courses = $database->getEnrolledCourses($stdInfo['course']);
    // }
      $display_name = $result['display_name'];
$email = $result['email'];
$phone = $result['phone'];
$image = $result['parent_directory'];
$password = $result['password'];
?>
<!doctype html>
<html lang="en">

 
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assetss/vendor/bootstrap/css/bootstrap.min.css">
    <link href="assetss/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="assetss/libs/css/style.css">
    <link rel="stylesheet" href="assetss/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <link rel="stylesheet" href="assetss/vendor/vector-map/jqvmap.css">
    <link rel="stylesheet" href="assetss/vendor/jvectormap/jquery-jvectormap-2.0.2.css">
    <link rel="stylesheet" href="assetss/vendor/fonts/flag-icon-css/flag-icon.min.css">
      <!-- <link href="assets/img/favicon-filenod.png" rel="icon"> -->
    <title>Dashboard</title>
    <style type="text/css">
        .cardHeader{
            background-color: #ea212e; 
            color: white;
        }
        .cardBody{
            border-radius: 10px;
            text-align: center;
        }
        #cardHeader{
            border-top-right-radius: 10px;
            border-top-left-radius: 10px;
        }
        .btn-outline-primary{
            color: #242849;
            border-color: #242849;
        }
        .btn-outline-primary:hover{
            color: #ffffff !important;
            background-color: #242849 !important;
            border-color: #242849 !important;
        }

        

   .search-bar input {
    border: 1px solid #173663; /* Match your theme color */
    border-radius: 10px; /* More compact rounded corners */
    padding: 6px 10px; /* Reduced padding for a more compact look */
    font-size: 0.85rem; /* Smaller font size */
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1); /* Subtle shadow for minimal depth */
    transition: all 0.2s ease-in-out; /* Smooth hover effect */
}

/* Add hover and focus effect */
.search-bar input:focus,
.search-bar input:hover {
    border-color: #0a58ca; /* Change border color on focus/hover */
    box-shadow: 0 2px 3px rgba(0, 0, 0, 0.2); /* Enhance shadow on hover */
    outline: none; /* Remove default outline */
}

/* Center and control search bar width */
.search-bar {
    margin: 0 auto;
    max-width: 100%; /* Allow to stretch the full width of the container */
}

/* Flexbox setup to ensure all inputs align in a row */
.search-bar .form-inline {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap; /* Wrap if screen size is small */
}

/* Control the input widths for flexibility */
.search-bar .form-group {
    flex: 1 1 18%; /* Reduced the width for more compact form */
    margin: 3px; /* Reduced margin between inputs */
}

.search-bar .form-group input {
    width: 100%; /* Make inputs take up the full width of their container */
}


.search-bar .form-group input {
    padding-left: 2.5rem; /* Adjust padding for space on the left side for the icon */
}

.search-bar .form-group i {
    font-size: 1.1rem; /* Slightly bigger icons */
    color: #173663; /* Match the theme color */
}


    </style>
    

</head>

<body>
   <?php if ($session->logged_in) {?>
       <div class="dashboard-main-wrapper">
        <!-- ============================================================== -->
        <!-- navbar -->
        <!-- ============================================================== -->
        <?php include("navbar.php"); ?>
        <?php include("leftbar.php"); ?>
        <!-- ============================================================== -->
        <!-- end left sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- wrapper  -->
        <!-- ============================================================== -->
        
        <div class="dashboard-wrapper">
            <div class="container-fluid  dashboard-content">
                <!-- <?php if(isset($_GET['reg'])){
                    if($_GET['reg'] == "success"){ ?>
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Welcome</strong> Login successfully.
                        </div>
                    <?php }
                    else { header('Location: dashboard.php'); } 
                } ?> -->
                <div class="page-header">
                            <!--<h3 class="text-primary">Dashboard</h3>                            -->
                           <div class="page-breadcrumb">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb"></ol>
    </nav>
    <div class="search-bar mt-3">
        <h5 class="text-dark">Search Filters</h5>
        <form class="form-inline w-100 d-flex justify-content-between" id="searchForm">
            <div class="form-group mx-2 flex-fill position-relative">
                <i class="fas fa-id-badge  position-absolute left-0 top-50 translate-middle-y mx-2"></i>
                <input
                    type="text"
                    id="customer_id"
                    class="form-control form-control-sm w-100 pl-5"
                    placeholder="Customer ID"
                    aria-label="Search Customer ID"
                />
            </div>
            <div class="form-group mx-2 flex-fill position-relative">
                <i class="fas fa-barcode position-absolute left-0 top-50 translate-middle-y mx-2"></i>
                <input
                    type="text"
                    id="product_id"
                    class="form-control form-control-sm w-100 pl-5"
                    placeholder="Product ID"
                    aria-label="Search Product ID"
                />
            </div>
            <div class="form-group mx-2 flex-fill position-relative">
                <i class="fas fa-box position-absolute left-0 top-50 translate-middle-y mx-2"></i>
                <input
                    type="text"
                    id="product_name"
                    class="form-control form-control-sm w-100 pl-5"
                    placeholder="Product Name"
                    aria-label="Search Product Name"
                />
            </div>
            <div class="form-group mx-2 flex-fill position-relative">
                <i class="fas fa-user position-absolute left-0 top-50 translate-middle-y mx-2"></i>
                <input
                    type="text"
                    id="sales_person"
                    class="form-control form-control-sm w-100 pl-5"
                    placeholder="Sales Person"
                    aria-label="Search Sales Person"
                />
            </div>
        </form>
    </div>
</div>



                        </div>
                        <div class="card-block table-border-style">
    <div class="table-responsive">
        <table class="table table-hover table-styled">
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>Customer Name</th>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Sale Person</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="table-data">
                <!-- Dynamic content will load here -->
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Table styles */
    .table {
        border-collapse: collapse;
        width: 100%;
        margin-bottom: 1rem;
        background-color: #fff;
    }
    
    .table th,
    .table td {
        padding: 12px 15px;
        text-align: left;
        vertical-align: middle;
        border: 1px solid #e1e1e1;
    }

    /* Header styling */
    .table th {
        background-color: #afb0b7;
        color: white;
        font-weight: bold;
    }

    /* Row hover effect */
    .table tbody tr:hover {
        background-color: #f1f1f1;
        cursor: pointer;
    }

    /* Alternating row colors */
    .table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .table tbody tr:nth-child(odd) {
        background-color: #ffffff;
    }

    /* Action button styles */
    .table .action-btn {
        padding: 6px 12px;
        background-color: #0a58ca;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .table .action-btn:hover {
        background-color: #0056b3;
    }
    
    /* Table responsive on smaller screens */
    .table-responsive {
        overflow-x: auto;
    }

    /* Optional: Border radius for the table container */
    .table-responsive {
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>


                <!-- ============================================================== -->
                <!-- pagehader  -->
                <!-- ============================================================== -->
               
                <!-- ============================================================== -->
                <!-- pagehader  -->
                <!-- ============================================================== -->
                
                <!-- ============================================================== -->
                <!-- revenue  -->
                <!-- ============================================================== -->
                
                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- end wrapper  -->
        <!-- ============================================================== -->
    </div>

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script type="text/javascript">
 $(document).ready(function() {
    // Function to load table records
    function loadTable() {
        $.ajax({
            url: "ajax-load-sales.php", // The server-side script
            type: "POST",
            success: function(data) {
                $("#table-data").html(data); // Load data into the table body
            },
            error: function(xhr, status, error) {
                console.error("Error fetching data:", error); // Log errors
                $("#table-data").html("<tr><td colspan='6' class='text-center'>Error loading data.</td></tr>");
            }
        });
    }

    // Initial load
    loadTable();

    // Live Search (optional)
    $("#customer_id").on("keyup", function() {
        const searchTerm = $(this).val().trim();

        if (searchTerm.length > 0) {
            $.ajax({
                url: "ajax-live-search-sales.php", // Optional search script
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
            loadTable(); // Reload all data if search input is cleared
        }
    });


     $("#product_id").on("keyup", function() {
        const searchTerm = $(this).val().trim();

        if (searchTerm.length > 0) {
            $.ajax({
                url: "ajax-live-search-sales.php", // Optional search script
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
            loadTable(); // Reload all data if search input is cleared
        }
    });


      $("#product_name").on("keyup", function() {
        const searchTerm = $(this).val().trim();

        if (searchTerm.length > 0) {
            $.ajax({
                url: "ajax-live-search-sales.php", // Optional search script
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
            loadTable(); // Reload all data if search input is cleared
        }
    });


       $("#sales_person").on("keyup", function() {
        const searchTerm = $(this).val().trim();

        if (searchTerm.length > 0) {
            $.ajax({
                url: "ajax-live-search-sales.php", // Optional search script
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
            loadTable(); // Reload all data if search input is cleared
        }
    });
});

</script>

    <!-- ============================================================== -->
    <!-- end main wrapper  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <!-- jquery 3.3.1 js-->
    <script src="assetss/vendor/jquery/jquery-3.3.1.min.js"></script>
    <!-- bootstrap bundle js-->
    <script src="assetss/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- slimscroll js-->
    <script src="assetss/vendor/slimscroll/jquery.slimscroll.js"></script>
    <!-- chartjs js-->
    <script src="assetss/vendor/charts/charts-bundle/Chart.bundle.js"></script>
    <script src="assetss/vendor/charts/charts-bundle/chartjs.js"></script>
   
    <!-- main js-->
    <script src="assetss/libs/js/main-js.js"></script>
    <!-- jvactormap js-->
    <script src="assetss/vendor/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="assetss/vendor/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- sparkline js-->
    <script src="assetss/vendor/charts/sparkline/jquery.sparkline.js"></script>
    <script src="assetss/vendor/charts/sparkline/spark-js.js"></script>
     <!-- dashboard sales js-->
    <script src="assetss/libs/js/dashboard-sales.js"></script>

     
   <?php }else{
    header("location: index.php");
   } ?> 
 
</body>
 
</html>