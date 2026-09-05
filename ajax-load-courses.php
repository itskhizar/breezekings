<?php
include("include/classes/constants.php");

function urlEncoder($value) {
    return urlencode(base64_encode($value));
}

// Database connection
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
} else {
    echo "Database connected successfully.";
}

$sql = "SELECT * FROM courses";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn)); // Debug SQL errors
}

$output = "";

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
    	$q = "SELECT * FROM categories WHERE id = '{$row['category_id']}'";
      $result2 = mysqli_query($conn, $q);
      $catarray = mysqli_fetch_assoc($result2);

      $q2 = "SELECT * FROM fees WHERE course_id = '{$row['id']}'";
      $result3 = mysqli_query($conn, $q2);
      $feearray = mysqli_fetch_assoc($result3);
      if (!empty($feearray)) {
          $fee = $feearray['monthly'];
      }else{
        $fee = "not set";
      }
        $output .= "<tr>
        <td>" . htmlspecialchars($row['course_title']) . "</td>
            <td>" . htmlspecialchars($catarray['category']) . "</td>
            
            <td>" . htmlspecialchars($row['duration']) . " month</td>
             <td><a href='course-details.php?id=".$row['id']."' class='btn-action btn-view'  title='View Details'>
                <i class='fas fa-eye'></i></a>
                <a href='delete-session.php?id=".$row['id']."' 
                class='btn btn-sm btn-outline-danger rounded'
                title='Delete'
                onclick='return confirm(\'Are you sure you want to delete this course?\');'>
                <i class='fas fa-trash-alt'></i>
              </a>
            </td>
        </tr>";
    }
} else {
    $output = "<tr><td colspan='6' class='text-center'>No Record Found.</td></tr>";
}

echo $output;

?>
