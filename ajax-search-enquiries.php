<?php
// Enable error reporting for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Set to 0 in production

// Include session and database
include("include/classes/session.php");

// Check if user is logged in
if (!$session->logged_in) {
    echo '<tr><td colspan="9" class="text-center text-danger">Session expired. Please login again.</td></tr>';
    exit;
}

// Database connection
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    echo '<tr><td colspan="9" class="text-center text-danger">Database connection failed.</td></tr>';
    exit;
}

// Get filter parameters
$search = isset($_POST['search']) ? mysqli_real_escape_string($conn, trim($_POST['search'])) : '';
$status = isset($_POST['status']) ? mysqli_real_escape_string($conn, $_POST['status']) : '';
$source = isset($_POST['source']) ? mysqli_real_escape_string($conn, $_POST['source']) : '';
$date = isset($_POST['date']) ? $_POST['date'] : '';

// Build query
// $query = "SELECT 
//     id, 
//     name, 
//     mobile_no, 
//     email, 
//     enquiry_status, 
//     enquiry_source, 
//     priority,
//     timestamp, 
//     next_follow_up,
//     field_interest,
//     institute,
//     assigned_counsellor,
//     total_calls
// FROM admissions 
// WHERE 1=1";
$query = "SELECT 
    a.*, 
    c.course_title
FROM admissions a
LEFT JOIN courses c ON a.course_id = c.id
WHERE 1=1";

// Add search condition
if (!empty($search)) {
    $query .= " AND (
        name LIKE '%$search%' OR 
        mobile_no LIKE '%$search%' OR 
        email LIKE '%$search%' OR 
        institute LIKE '%$search%' OR
        field_interest LIKE '%$search%'
    )";
}

// Add status filter
if (!empty($status)) {
    $query .= " AND enquiry_status = '$status'";
}

// Add source filter
if (!empty($source)) {
    $query .= " AND enquiry_source = '$source'";
}

// Add date filter
if (!empty($date)) {
    switch ($date) {
        case 'today':
            $query .= " AND DATE(timestamp) = CURDATE()";
            break;
        case 'yesterday':
            $query .= " AND DATE(timestamp) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
            break;
        case 'week':
            $query .= " AND timestamp >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
            break;
        case 'month':
            $query .= " AND timestamp >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
            break;
    }
}

// Order by latest first
$query .= " ORDER BY id DESC LIMIT 100";

// Execute query
$result = mysqli_query($conn, $query);

if (!$result) {
    echo '<tr><td colspan="9" class="text-center text-danger">Query error. Please contact administrator.</td></tr>';
    exit;
}

// Check if any results found
if (mysqli_num_rows($result) == 0) {
    echo '<tr><td colspan="9" class="text-center py-4">
            <i class="fas fa-inbox fa-3x text-muted mb-3" style="display:block;"></i>
            <p class="text-muted">No enquiries found matching your criteria.</p>
          </td></tr>';
    exit;
}

// Output results
$counter = 1;
while ($row = mysqli_fetch_assoc($result)) {
    // Status badge class
    $statusClass = '';
    switch ($row['enquiry_status']) {
        case 'New':
            $statusClass = 'badge-new';
            break;
        case 'Attempted Contact':
            $statusClass = 'badge-attempted';
            break;
        case 'Connected':
            $statusClass = 'badge-connected';
            break;
        case 'Interested':
            $statusClass = 'badge-interested';
            break;
        case 'Scheduled Visit':
            $statusClass = 'badge-scheduled';
            break;
        case 'Visited':
            $statusClass = 'badge-visited';
            break;
        case 'Enrolled':
            $statusClass = 'badge-enrolled';
            break;
        case 'Waiting List':
            $statusClass = 'badge-waiting';
            break;
        case 'Not Interested':
            $statusClass = 'badge-not-interested';
            break;
       case 'Follow-up Required':
            $statusClass = 'badge-attempted';
            break;
        default:
            $statusClass = 'badge-new';
    }

    // Priority icon
    $priorityIcon = '';
    if ($row['priority'] == 'Urgent') {
        $priorityIcon = '<i class="fas fa-exclamation-circle priority-urgent" title="Urgent"></i> ';
    } elseif ($row['priority'] == 'High') {
        $priorityIcon = '<i class="fas fa-exclamation-triangle priority-high" title="High Priority"></i> ';
    }

    // Format date
    $date = date('d M Y', strtotime($row['timestamp']));
    
    // Follow-up date
    $followUp = 'Not set';
    if (!empty($row['next_follow_up'])) {
        $followUpDate = strtotime($row['next_follow_up']);
        $today = strtotime('today');
        
        if ($followUpDate < $today) {
            $followUp = '<span class="text-danger fw-bold">Overdue</span>';
        } else {
            $followUp = date('d M Y', $followUpDate);
        }
    }

    // Clean phone number for WhatsApp
    $phone = preg_replace('/[^0-9]/', '', $row['mobile_no']);
    if (!empty($phone) && substr($phone, 0, 2) != '92') {
        if (substr($phone, 0, 1) == '0') {
            $phone = '92' . substr($phone, 1);
        } else {
            $phone = '92' . $phone;
        }
    }

    // Truncate name if too long
    $displayName = strlen($row['name']) > 25 ? substr($row['name'], 0, 25) . '...' : $row['name'];
    
    // Truncate email if too long
    $displayEmail = strlen($row['email']) > 25 ? substr($row['email'], 0, 25) . '...' : $row['email'];

    echo '<tr>';
    echo '<td>' . $counter . '</td>';
    echo '<td>' . $priorityIcon . '<strong>' . htmlspecialchars($displayName) . '</strong></td>';
    
    // Phone number with actions
    echo '<td>
            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: nowrap;">
                <a href="tel:' . htmlspecialchars($row['mobile_no']) . '" class="phone-link" title="Call" style="white-space: nowrap;">
                    ' . htmlspecialchars($row['mobile_no']) . '
                </a>
                <button onclick="copyPhone(\'' . htmlspecialchars($row['mobile_no']) . '\')" class="btn-icon-small" title="Copy">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
          </td>';
    
            echo '<td><small>' . htmlspecialchars($displayEmail) . '</small></td>';
            echo '<td><span class="badge-status ' . $statusClass . '">' . htmlspecialchars($row['enquiry_status']) . '</span></td>';
            echo '<td><small>' . ucfirst(htmlspecialchars($row['enquiry_source'])) . '</small></td>';
            echo '<td><small>' . $date . '</small></td>';
            echo '<td><small>' . $followUp . '</small></td>';
            echo '<td class="text-nowrap">
            <a href="enquiry-details.php?id=' . $row['id'] . '" class="btn-action btn-view" title="View Details">
                <i class="fas fa-eye"></i>
            </a>
            <a href="add-student.php?id=' . $row['id'] . '" class="btn-action btn-view" title="Register">
                <i class="fas fa-plus-circle"></i>
            </a>
            <a href="javascript:void(0);" 
                onclick="openWhatsApp(\'' . $phone . '\', \'' . htmlspecialchars(addslashes($row['name'])) . '\', \'' . 
                    htmlspecialchars(addslashes($row['mobile_no'])) . '\', \'' . 
                    htmlspecialchars(addslashes($row['email'])) . '\', \'' . 
                    htmlspecialchars(addslashes($row['field_education'])) . '\', \'' .
                    htmlspecialchars(addslashes($row['institute'])) . '\', \'' . 
                    htmlspecialchars(addslashes($row['field_interest'])) . '\', \'' . 
                    htmlspecialchars(addslashes($row['course_title'])) . '\')" 
                class="btn-action btn-whatsapp" title="WhatsApp">
                    <i class="fab fa-whatsapp"></i>
            </a>


            <a href="delete-admission.php?id='.$row['id'].'" 
        class="btn btn-sm btn-outline-danger rounded"
        title="Delete"
        onclick="return confirm(\'Are you sure you want to delete this enquiry?\');">
        <i class="fas fa-trash-alt"></i>
      </a>
          </td>';
    echo '</tr>';
    
    $counter++;
}

// Close connection
mysqli_close($conn);
?>

<script>
// Copy phone number function (if not already defined)
if (typeof copyPhone !== 'function') {
    function copyPhone(phone) {
        const textarea = document.createElement('textarea');
        textarea.value = phone;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        
        // Show notification
        showNotification('Phone number copied!');
    }
    
    function showNotification(message) {
        const existing = document.querySelector('.copy-notification');
        if (existing) existing.remove();
        
        const notification = document.createElement('div');
        notification.className = 'copy-notification';
        notification.innerHTML = `<i class="fas fa-check-circle"></i><span>${message}</span>`;
        document.body.appendChild(notification);
        
        setTimeout(() => notification.classList.add('show'), 100);
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 2000);
    }
}
</script>

<style>
.btn-icon-small {
    padding: 2px 6px;
    border: none;
    background: #e8ecf4;
    color: #666;
    border-radius: 4px;
    cursor: pointer;
    font-size: 11px;
    transition: all 0.2s ease;
}

.btn-icon-small:hover {
    background: var(--accent-color);
    color: white;
}

.copy-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: linear-gradient(135deg, #1dd1a1, #10ac84);
    color: white;
    padding: 12px 20px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(29, 209, 161, 0.3);
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    opacity: 0;
    transform: translateY(-20px);
    transition: all 0.3s ease;
    z-index: 10000;
}

.copy-notification.show {
    opacity: 1;
    transform: translateY(0);
}
</style>