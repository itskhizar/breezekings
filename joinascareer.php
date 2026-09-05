<?php
// Database connection
include 'include/classes/session.php';
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . mysqli_connect_error()]);
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => ''];
    
    // Validate required fields
    $required_fields = ['full_name', 'email', 'phone', 'position', 'education', 'experience_years'];
    $missing_fields = [];
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $missing_fields[] = ucwords(str_replace('_', ' ', $field));
        }
    }
    
    if (!empty($missing_fields)) {
        $response['message'] = 'Please fill in all required fields: ' . implode(', ', $missing_fields);
        echo json_encode($response);
        exit;
    }
    
    // Sanitize inputs
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $fullname = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $education = mysqli_real_escape_string($conn, $_POST['education']);
    $experience_years = mysqli_real_escape_string($conn, $_POST['experience_years']);
    $linkedin = mysqli_real_escape_string($conn, $_POST['linkedin']);
    $portfolio = mysqli_real_escape_string($conn, $_POST['portfolio']);
    $skills = mysqli_real_escape_string($conn, $_POST['skills']);
    
    // Handle file upload
    $cv_filename = '';
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
        $allowed_extensions = ['pdf', 'doc', 'docx'];
        $file_extension = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));
        
        if (in_array($file_extension, $allowed_extensions)) {
            $cv_filename = time() . '_' . basename($_FILES['cv']['name']);
            $upload_path = 'cvs/' . $cv_filename;
            
            if (!file_exists('cvs')) {
                mkdir('cvs', 0777, true);
            }
            
            if (!move_uploaded_file($_FILES['cv']['tmp_name'], $upload_path)) {
                $cv_filename = '';
            }
        }
    }
    
    // Insert into database
    $query = "INSERT INTO `careers` 
              (`position`, `fullname`, `email`, `phone`, `city`, `education`, `experience_years`, `linkedin`, `portfolio`, `skills`, `cv`) 
              VALUES 
              ('$position', '$fullname', '$email', '$phone', '$city', '$education', '$experience_years', '$linkedin', '$portfolio', '$skills', '$cv_filename')";
    
    if (mysqli_query($conn, $query)) {
    	$to = "asadhussain3300@gmail.com";
$subject = "New Career Application Received";

$message = "
<html>
<head>
  <title>New Career Application</title>
</head>
<body style='font-family: Arial, sans-serif;'>
  <h2>New Career Application Submitted</h2>
  <table cellpadding='8' cellspacing='0' border='0'>
    <tr>
      <td><strong>Position Applied:</strong></td>
      <td>" . htmlspecialchars($position) . "</td>
    </tr>
    <tr>
      <td><strong>Full Name:</strong></td>
      <td>" . htmlspecialchars($fullname) . "</td>
    </tr>
    <tr>
      <td><strong>Email:</strong></td>
      <td>" . htmlspecialchars($email) . "</td>
    </tr>
    <tr>
      <td><strong>Phone:</strong></td>
      <td>" . htmlspecialchars($phone) . "</td>
    </tr>
    <tr>
      <td><strong>City:</strong></td>
      <td>" . htmlspecialchars($city) . "</td>
    </tr>
    <tr>
      <td><strong>Education:</strong></td>
      <td>" . htmlspecialchars($education) . "</td>
    </tr>
    <tr>
      <td><strong>Experience (Years):</strong></td>
      <td>" . htmlspecialchars($experience_years) . "</td>
    </tr>
    <tr>
      <td><strong>Skills:</strong></td>
      <td>" . nl2br(htmlspecialchars($skills)) . "</td>
    </tr>
    <tr>
      <td><strong>LinkedIn:</strong></td>
      <td>" . htmlspecialchars($linkedin) . "</td>
    </tr>
    <tr>
      <td><strong>Portfolio:</strong></td>
      <td>" . htmlspecialchars($portfolio) . "</td>
    </tr>
    <tr>
      <td><strong>CV File:</strong></td>
      <td>" . ($cv_filename ? $cv_filename : 'Not Uploaded') . "</td>
    </tr>
  </table>

  <br>
  <p>
    <a href='https://academy.filenod.com/admin' 
       style='background:#173663;color:#fff;padding:10px 15px;text-decoration:none;border-radius:4px;'>
       View in Admin Panel
    </a>
  </p>
</body>
</html>
";

// Email headers
$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type:text/html;charset=UTF-8\r\n";
$headers .= "From: Filenod Academy <admin@filenod.com>\r\n";

// Send email
mail($to, $subject, $message, $headers);

        $response['success'] = true;
        $response['message'] = 'Application submitted successfully! We will contact you soon.';
    } else {
        $response['success'] = false;
        $response['message'] = 'Failed to submit application: ' . mysqli_error($conn);
    }
    
    echo json_encode($response);
    exit;
}

$position = isset($_GET['position']) ? htmlspecialchars($_GET['position']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career Application - Filenod Academy</title>
    <link href="assets/img/favicon-filenod.png" rel="icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        
        .form-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .form-group { margin-bottom: 1.5rem; }
        
        .form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .required::after {
            content: ' *';
            color: #ef4444;
        }
        
        .form-input, .form-textarea, .form-select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        .form-input:focus, .form-textarea:focus, .form-select:focus {
            outline: none;
            border-color: #173663;
            box-shadow: 0 0 0 3px rgba(23, 54, 99, 0.1);
        }
        
        .file-upload {
            position: relative;
            display: block;
            width: 100%;
        }
        
        .file-upload input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .file-upload-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            border: 2px dashed #173663;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f9fafb;
        }
        
        .file-upload-label:hover {
            background: #f3f4f6;
        }
        
        .btn-primary {
            width: 100%;
            background: #173663;
            color: white;
            padding: 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: #0f2444;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(23, 54, 99, 0.3);
        }
    </style>
</head>
<body class="bg-gray-50">
    
    <!-- Simple Header -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="index.php">
                    <img src="images/logo.png" alt="Filenod Logo" class="h-12">
                </a>
                <a href="career.php" class="text-gray-600 hover:text-gray-900 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Careers
                </a>
            </div>
        </div>
    </nav>

    <!-- Form Section -->
    <section class="py-12">
        <div class="max-w-4xl mx-auto px-4">
            
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Career Application</h1>
                <p class="text-gray-600">Please fill out the form below. Fields marked with <span class="text-red-500">*</span> are required.</p>
            </div>

            <!-- Alert Container -->
            <div id="formAlert" class="mb-6"></div>

            <!-- Application Form -->
            <form id="careerForm" enctype="multipart/form-data" class="form-card">
                
                <!-- Position -->
                <div class="form-group">
                    <label class="form-label required">Position Applied For</label>
					<select name="position" class="form-select" required>
					    <option value="">Select Position</option>
					    <option value="Assistant Supervisor" <?php echo $position == 'Assistant Supervisor' ? 'selected' : ''; ?>>Assistant Supervisor</option>
					    <option value="Admission Advisor" <?php echo $position == 'Admission Advisor' ? 'selected' : ''; ?>>Admission Advisor</option>
					    <option value="Coach" <?php echo $position == 'Coach' ? 'selected' : ''; ?>>Coach / Instructor</option>
					    <option value="Office Boy" <?php echo $position == 'OfficeBoy' ? 'selected' : ''; ?>>Office Boy</option>
					    <option value="Other">Other Position</option>
					</select>
                </div>

                <!-- Personal Information -->
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="form-label required">Full Name</label>
                        <input type="text" name="full_name" class="form-input" placeholder="John Doe" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Email Address</label>
                        <input type="email" name="email" class="form-input" placeholder="john@example.com" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Phone Number</label>
                        <input type="tel" name="phone" class="form-input" placeholder="+92 300 1234567" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-input" placeholder="Abbottabad">
                    </div>
                </div>

                <!-- Education & Experience -->
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="form-label required">Highest Education</label>
                        <select name="education" class="form-select" required>
                            <option value="">Select Education</option>
                            <option value="Matriculation">Matriculation</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Bachelor's">Bachelor's Degree</option>
                            <option value="Master's">Master's Degree</option>
                            <option value="PhD">PhD</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Years of Experience</label>
                        <select name="experience_years" class="form-select" required>
                            <option value="">Select Experience</option>
                            <option value="0-1">0-1 Years (Fresh Graduate)</option>
                            <option value="1-3">1-3 Years</option>
                            <option value="3-5">3-5 Years</option>
                            <option value="5+">5+ Years</option>
                        </select>
                    </div>
                </div>

                <!-- Professional Links -->
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="form-label">LinkedIn Profile</label>
                        <input type="url" name="linkedin" class="form-input" placeholder="https://linkedin.com/in/yourprofile">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Portfolio/Website</label>
                        <input type="url" name="portfolio" class="form-input" placeholder="https://yourportfolio.com">
                    </div>
                </div>

                <!-- Skills -->
                <div class="form-group">
                    <label class="form-label">Key Skills</label>
                    <textarea name="skills" class="form-textarea" rows="3" placeholder="e.g., JavaScript, React, Python, Digital Marketing, etc. (Separate with commas)"></textarea>
                </div>

                <!-- CV Upload -->
                <div class="form-group">
                    <label class="form-label">Upload Resume/CV</label>
                    <div class="file-upload">
                        <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" onchange="updateFileName(this)">
                        <label for="cv" class="file-upload-label">
                            <div class="text-center">
                                <i class="fas fa-cloud-upload-alt text-3xl text-blue-600 mb-2"></i>
                                <p class="text-sm font-medium text-gray-700">Click to upload CV (PDF, DOC, DOCX)</p>
                                <p id="fileName" class="text-sm text-blue-600 font-semibold mt-2"></p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Terms -->
                <div class="form-group">
                    <label class="flex items-start gap-3">
                        <input type="checkbox" name="terms" required class="mt-1">
                        <span class="text-sm text-gray-700">I confirm that the information provided is accurate and complete. I understand that false information may result in rejection of my application. <span class="text-red-500">*</span></span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-primary">
                    <i class="fas fa-paper-plane mr-2"></i> Submit Application
                </button>

            </form>

        </div>
    </section>

    <!-- WhatsApp Button -->
    <a href="https://wa.me/923454955590" target="_blank" class="fixed bottom-6 right-6 bg-green-500 text-white p-4 rounded-full shadow-lg hover:scale-110 transition z-50">
        <i class="fab fa-whatsapp text-2xl"></i>
    </a>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function updateFileName(input) {
            const fileName = document.getElementById('fileName');
            if (input.files && input.files[0]) {
                fileName.textContent = 'Selected: ' + input.files[0].name;
            }
        }

        $(document).ready(function() {
            $('#careerForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    url: '',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        try {
                            var res = JSON.parse(response);
                            if(res.success) {
                                $('#formAlert').html(`
                                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                                        <div class="flex items-center gap-3">
                                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                                            <p class="text-green-800 font-medium">${res.message}</p>
                                        </div>
                                    </div>
                                `);
                                $('#careerForm')[0].reset();
                                $('#fileName').text('');
                                window.scrollTo({ top: 0, behavior: 'smooth' });
                            } else {
                                $('#formAlert').html(`
                                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                                        <div class="flex items-center gap-3">
                                            <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                                            <p class="text-red-800 font-medium">${res.message}</p>
                                        </div>
                                    </div>
                                `);
                                window.scrollTo({ top: 0, behavior: 'smooth' });
                            }
                        } catch (e) {
                            $('#formAlert').html(`
                                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                                    <p class="text-red-800 font-medium">Unexpected error occurred. Please try again.</p>
                                </div>
                            `);
                        }
                    },
                    error: function() {
                        $('#formAlert').html(`
                            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                                <p class="text-red-800 font-medium">Could not submit form. Please try again later.</p>
                            </div>
                        `);
                    }
                });
            });
        });
    </script>
</body>
</html>