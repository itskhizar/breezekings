<?php
include("include/classes/session.php");
if (!$session->logged_in) {
    header('location: index.php');
    exit;
}

$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Connection failed");
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT * FROM careers WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header('location: careers-management.php');
    exit;
}

$application = mysqli_fetch_assoc($result);
$username = $session->username;
$user_result = $database->getUserInfo($username);
$display_name = $user_result['display_name'];
$image = $user_result['parent_directory'];
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
    <title>Application Details - <?php echo htmlspecialchars($application['fullname']); ?></title>
    
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fd 0%, #eef1f9 100%);
            font-family: 'Circular Std', sans-serif;
        }

        .details-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }

        .section-title {
            color: #0e0c28;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #6c5ce7;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 15px;
        }

        .info-item label {
            font-weight: 600;
            color: #666;
            font-size: 13px;
            text-transform: uppercase;
            margin-bottom: 5px;
            display: block;
        }

        .info-item p {
            color: #0e0c28;
            font-size: 15px;
            font-weight: 500;
            margin: 0;
        }

        .btn-download {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 10px 25px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-download:hover {
            background: linear-gradient(135deg, #5568d3, #6a3f8f);
            color: white;
            transform: translateY(-2px);
        }

        .dashboard-wrapper{
            margin-left: 0px;
        }
        .dashboard-main-wrapper{
            padding-top: 0px !important;
        }

        @media (max-width: 768px) {
            .info-row {
                grid-template-columns: 1fr;
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
                    
                    <div class="mb-4">
                        <a href="careers-management.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Applications
                        </a>
                    </div>

                    <!-- Personal Information -->
                    <div class="details-card">
                        <h3 class="section-title">
                            <i class="fas fa-user"></i>
                            Personal Information
                        </h3>
                        <div class="info-row">
                            <div class="info-item">
                                <label>Full Name</label>
                                <p><?php echo htmlspecialchars($application['fullname']); ?></p>
                            </div>
                            <div class="info-item">
                                <label>Email</label>
                                <p><?php echo htmlspecialchars($application['email']); ?></p>
                            </div>
                            <div class="info-item">
                                <label>Phone</label>
                                <p><?php echo htmlspecialchars($application['phone']); ?></p>
                            </div>
                            <div class="info-item">
                                <label>City</label>
                                <p><?php echo htmlspecialchars($application['city']); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Professional Information -->
                    <div class="details-card">
                        <h3 class="section-title">
                            <i class="fas fa-briefcase"></i>
                            Professional Information
                        </h3>
                        <div class="info-row">
                            <div class="info-item">
                                <label>Position Applied</label>
                                <p><?php echo htmlspecialchars($application['position']); ?></p>
                            </div>
                            <div class="info-item">
                                <label>Education</label>
                                <p><?php echo htmlspecialchars($application['education']); ?></p>
                            </div>
                            <div class="info-item">
                                <label>Experience</label>
                                <p><?php echo htmlspecialchars($application['experience_years']); ?></p>
                            </div>
                            <div class="info-item">
                                <label>Application Date</label>
                                <p><?php echo date('d M Y, h:i A', strtotime($application['created_at'])); ?></p>
                            </div>
                        </div>

                        <?php if (!empty($application['linkedin'])) { ?>
                        <div class="info-row">
                            <div class="info-item">
                                <label>LinkedIn Profile</label>
                                <p><a href="<?php echo htmlspecialchars($application['linkedin']); ?>" target="_blank"><?php echo htmlspecialchars($application['linkedin']); ?></a></p>
                            </div>
                        </div>
                        <?php } ?>

                        <?php if (!empty($application['portfolio'])) { ?>
                        <div class="info-row">
                            <div class="info-item">
                                <label>Portfolio/Website</label>
                                <p><a href="<?php echo htmlspecialchars($application['portfolio']); ?>" target="_blank"><?php echo htmlspecialchars($application['portfolio']); ?></a></p>
                            </div>
                        </div>
                        <?php } ?>
                    </div>

                    <!-- Skills -->
                    <?php if (!empty($application['skills'])) { ?>
                    <div class="details-card">
                        <h3 class="section-title">
                            <i class="fas fa-tools"></i>
                            Skills & Expertise
                        </h3>
                        <div class="info-item">
                            <p><?php echo nl2br(htmlspecialchars($application['skills'])); ?></p>
                        </div>
                    </div>
                    <?php } ?>

                    <!-- CV -->
                    <?php if (!empty($application['cv'])) { ?>
                    <div class="details-card">
                        <h3 class="section-title">
                            <i class="fas fa-file-pdf"></i>
                            Resume / CV
                        </h3>
                        <a href="cvs/<?php echo htmlspecialchars($application['cv']); ?>" class="btn-download" target="_blank">
                            <i class="fas fa-download me-2"></i>Download CV
                        </a>
                    </div>
                    <?php } ?>

                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="assetss/libs/js/main-js.js"></script>
        
    <?php } else {
        header("location: index.php");
    } ?>
</body>
</html>