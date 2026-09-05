<?php

include("constants.php");
      
class MySQLDB
{
   var $connection;        
   var $num_active_users;  
   var $num_active_guests; 
   var $num_members;      

   function __construct(){
   
	  $this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME) or die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());

 
      $this->num_members = -1;
      
      if(TRACK_VISITORS){
     
         $this->calcNumActiveUsers();
      
     
         $this->calcNumActiveGuests();
      }
   }


   function confirmUserPass($username, $password){
  
  $username = str_replace('&lt;',"~",str_replace('<',"~&gt;",strip_tags(mysqli_real_escape_string($this->connection, $username))));
  $password = str_replace('&lt;',"~",str_replace('<',"~&gt;",strip_tags(mysqli_real_escape_string($this->connection, $password))));
  
  //     if(!get_magic_quotes_gpc()) {
// 	      $username = addslashes($username);
  //     }

   
      $q = "SELECT password FROM ".TBL_USERS." WHERE username = '$username'";
      $result = mysqli_query($this->connection, $q);
      if(!$result || (mysqli_num_rows($result) < 1)){
         return 1;
      }

   
      $dbarray = mysqli_fetch_array($result);
      $dbarray['password'] = stripslashes($dbarray['password']);
      $password = stripslashes($password);

   
      if($password == $dbarray['password']){
         return 0;
      }
      else{
         return 2;
      }
   }
   
  
   function confirmUserID($username, $userid){
   //  $username = str_replace('&lt;',"~",str_replace('<',"~&gt;",strip_tags(mysqli_real_escape_string($this->connection, $username))));
	// $userid = str_replace('&lt;',"~",str_replace('<',"~&gt;",strip_tags(mysqli_real_escape_string($this->connection, $userid))));
   //    if(!get_magic_quotes_gpc()) {
	//       $username = addslashes($username);
   //    }

   
      $q = "SELECT userid FROM ".TBL_USERS." WHERE username = '$username'";
      $result = mysqli_query($this->connection, $q);
      if(!$result || (mysqli_num_rows($result) < 1)){
         return 1;
      }

    
      $dbarray = mysqli_fetch_array($result);
      $dbarray['userid'] = stripslashes($dbarray['userid']);
      $userid = stripslashes($userid);

   
      if($userid == $dbarray['userid']){
         return 0;
      }
      else{
         return 2;
      }
   }
   
  
   function usernameTaken($username){
   $username = str_replace('&lt;',"~",str_replace('<',"~&gt;",strip_tags(mysqli_real_escape_string($this->connection, $username))));
      // if(!get_magic_quotes_gpc()){
      //    $username = addslashes($username);
      // }
      $q = "SELECT username FROM ".TBL_USERS." WHERE username = '$username'";
      $result = mysqli_query($this->connection, $q);
      return (mysqli_num_rows($result) > 0);
   }
   
  
   function usernameBanned($username){
   $username = str_replace('&lt;',"~",str_replace('<',"~&gt;",strip_tags(mysqli_real_escape_string($this->connection, $username))));
      if(!get_magic_quotes_gpc()){
         $username = addslashes($username);
      }
      $q = "SELECT username FROM ".TBL_BANNED_USERS." WHERE username = '$username'";
      $result = mysqli_query($this->connection, $q);
      return (mysqli_num_rows($result) > 0);
   }
   
   
   ////// START Custom Functions
   
   
   function clientdata($username){
 $username = str_replace('&lt;',"~",str_replace('<',"~&gt;",strip_tags(mysqli_real_escape_string($this->connection, $username))));
      $q = "SELECT * FROM users where username='$username'";
      $result = mysqli_query($this->connection, $q);

      if(!$result || (mysqli_num_rows($result) < 1)){
         return NULL;
      }

      $dbarray = mysqli_fetch_array($result);
      return $dbarray;
   }
  
   function addNewUser($username, $email, $password){
   
      $time = time();
     
      $q = "INSERT INTO ".TBL_USERS." VALUES ('$username', '$password', '0', '0', '$email', '$time', '')";
      return mysqli_query($this->connection, $q);
   }
   
 
 
   function updateUserField($username, $field, $value){
   $username = str_replace('&lt;',"~",str_replace('<',"~&gt;",strip_tags(mysqli_real_escape_string($this->connection, $username))));
   $field = str_replace('&lt;',"~",str_replace('<',"~&gt;",strip_tags(mysqli_real_escape_string($this->connection, $field))));
   $value = str_replace('&lt;',"~",str_replace('<',"~&gt;",strip_tags(mysqli_real_escape_string($this->connection, $value))));
      $q = "UPDATE ".TBL_USERS." SET ".$field." = '$value' WHERE username = '$username'";
      return mysqli_query($this->connection, $q);
   }






   // custom

   function addservice($title, $bprice, $bdescription, $bdelivery, $sprice, $sdescription, $sdelivery, $preprice, $predescription, $predelivery,$longdescription , $file_name){
    $id = "";
      $q = "INSERT INTO `services`(`id`, `title`, `basic_price`, `basic_description`, `delivery_basic`, `standard_price`, `standard_description`, `delivery_standard`, `premium_price`, `premium_description`, `delivery_premium`, `description`, `image`) VALUES ('$id','$title','$bprice','$bdescription','$bdelivery','$sprice','$sdescription','$sdelivery','$preprice','$predescription','$predelivery','$longdescription','$file_name')";
      return mysqli_query($this->connection, $q);
   }
   function addProject($id, $projectName, $email, $category, $description, $file, $budget, $duration){
     // $currentTime = date('Y-m-d H:i:s', time());
    $status = "requested";
      $q = "INSERT INTO `project`(`id`, `admin_name`, `client_name`, `project_name`, `email`, `category`, `description`, `file`, `budget`, `duration_in_days`, `time`, `status`) VALUES ('$id','','','$projectName','$email','$category',' $description','$file','$budget','$duration','','$status')";
      return mysqli_query($this->connection, $q);
   }
   function extendduration($id,$duration){
     // $currentTime = date('Y-m-d H:i:s', time());
      $q = "UPDATE `project` SET `duration_in_days`='$duration' WHERE id = '$id'";
      return mysqli_query($this->connection, $q);
   }
   function project_update($update,$id){
     // $currentTime = date('Y-m-d H:i:s', time());
      $q = "INSERT INTO `project_updt`(`id`, `updt`, `project_id`) VALUES ('','$update','$id')";
      return mysqli_query($this->connection, $q);
   }

   function addTeam($name, $designation, $joiningDate, $picture, $linkedinProfile, $instaProfile, $fbProfile){
      $q = "INSERT INTO team VALUES('', '$name', '$designation', '$joiningDate', '$picture', '$linkedinProfile', '$instaProfile', '$fbProfile')";
      return mysqli_query($this->connection, $q);
   }
   function addWork($title, $category, $url, $picture){
    $query = "INSERT INTO portfolio VALUES('', '$title', '$category', '$url', '$picture')";
    return mysqli_query($this->connection, $query);
  }

   function addreview($category,$reviewer_name, $country , $file_store,$review,$rating){
    // $cat = "webdevelopment";
    $review = mysqli_real_escape_string($this->connection, $review);
      $q = "INSERT INTO `reviews`(`category`, `reviewer_name`, `reviewer_country`, `reviewer_image`, `review`, `rating`) VALUES ('$category','$reviewer_name','$country','$file_store','$review','$rating')";
      return mysqli_query($this->connection, $q);
   }
   function addresponse($responser_name, $file_store,$response){
      $q = "INSERT INTO `addresponse`(`responser_name`, `responser_image`, `responser_response`) VALUES ('$responser_name','$file_store','$response')";
      return mysqli_query($this->connection, $q);
   }
   function buyHosting($firstName, $lastName, $country, $state, $city, $zip, $phone, $cnic, $email, $pdid, $price){
    $time = time();
    $q = "INSERT INTO hosting_sales VALUES ('', '$pdid', '$price', '$firstName', '$lastName', '$country', '$state', '$city', '$zip', '$phone', '$cnic', '$email', '$time')";
      return mysqli_query($this->connection, $q);
   }
   
   
   
   
   
   
   function groupdata($type, $CSRF_Code){
   $type = str_replace('&lt;',"~",str_replace('<',"~&gt;",strip_tags(mysqli_real_escape_string($this->connection, $type))));
   $CSRF_Code = str_replace('&lt;',"~",str_replace('<',"~&gt;",strip_tags(mysqli_real_escape_string($this->connection, $CSRF_Code))));
   
   // $jumo2 = md5($_SESSION['CSRF_Code'].'8j5j&*&K5jrffgF9wAJDIH'.'JKHds998954(*)(*dfkjll');
   
   // if($CSRF_Code == $jumo2){
// in CKRF

if($type == "stp_fieldset"){
$q = "SELECT username FROM users order by username ASC";
// }


// End in CKRF
}



// Out of CKRF
if($type == "stp_fieldset_more"){
$q = "SELECT username FROM users order by username ASC";
}

//custom  
if($type == "stp_fieldset_more"){
$q = "SELECT username, password FROM users order by username ASC";
}
if($type == "requestedprojects"){
   $q = "SELECT * FROM project WHERE status = 'requested'";
}
if($type == "view_projects"){
   $q = "SELECT * FROM project WHERE username != '0'";
}
if($type == "view_services"){
   $q = "SELECT * FROM services";
}

if($type == "hosting_sales"){
  $q = "SELECT * FROM hosting_sales";
}
if($type == "assignment_summary_student"){
  $q = "SELECT * FROM `assignment` WHERE `section_id` = '$CSRF_Code' ORDER By id DESC";
}

if($type == "assignment_summary_teacher"){
  $q = "SELECT * FROM `assignment` WHERE `teacher_id` = '$CSRF_Code' ORDER By id DESC";
}

if($type == "assignment_summary_admin"){
  $q = "SELECT * FROM assignment ORDER BY id DESC";
}

if($type == "view_portfolio"){
  $q = "SELECT * FROM portfolio";
}
if($type == "view_courses"){
  $q = "SELECT * FROM enro  lled_course WHERE course_id = '$CSRF_Code'";
}
if($type == "services"){
   $q = "SELECT * FROM services";
} if($type == "view_plans"){
    $q = "SELECT * FROM plans";
}
if($type == "view_material"){
  $q = "SELECT * FROM course_material WHERE course = '$CSRF_Code'";
}
if($type == "view_batch"){
  $q = "SELECT * FROM batch";
}
if($type == "view_students"){
  if($CSRF_Code != ""){
    $q = "SELECT * FROM student_details WHERE std_name LIKE '%$CSRF_Code%' OR std_id LIKE '%$CSRF_Code%' OR father_name LIKE '%$CSRF_Code%' OR grd_name LIKE '%$CSRF_Code%' OR std_phone LIKE '%$CSRF_Code%' OR grd_phone LIKE '%$CSRF_Code%' OR grd_cnic LIKE '%$CSRF_Code%'";
  }
  else{
    $q = "SELECT * FROM student_details";
  }
}
if($type == "view_projects_user"){
   $q = "SELECT * FROM project WHERE client_name = '$CSRF_Code'";
}
if($type == "view_projectdetails_user"){
   $q = "SELECT * FROM project WHERE client_name = '$CSRF_Code'";
}
if ($type == "accepted_projects") {
  $q = "SELECT * FROM project WHERE status = 'accepted'";
}
if($type == "recent_projects"){
  $q = "SELECT * FROM portfolio";
}
if($type == "view_team"){
   $q = "SELECT * FROM team";
}
if($type == "view_teacher"){
   $q = "SELECT * FROM teacher_details";
}

if($type == "get_teacher"){
  $q = "SELECT * FROM teacher_details";
}

if($type == "get_section_drop"){
  $q = "SELECT * FROM section_detail";
}

if($type == "get_section_drop_chk"){
  $q = "SELECT * FROM section_detail WHERE section_id = '$CSRF_Code'";
}

if($type == "get_section"){
  $q = "SELECT * FROM section_teacher WHERE teacher_id = '$CSRF_Code'";
}

if($type == "view_plan_offers"){
    $q = "SELECT * FROM plan_details WHERE plan_id = '$CSRF_Code'";
}
if($type == "team"){
   $q = "SELECT * FROM team";
}
if ($type == "pending_projects") {
  $q = "SELECT * FROM project WHERE status = 'pending'";
}
if($type == "completed_assignments_std"){
  $time = time();
  $q = "SELECT * FROM assignment WHERE student = '$CSRF_Code' AND (deadline < '$time' OR submission != '') ORDER BY id DESC";
}
if($type == "view_quotes"){
   $q = "SELECT * FROM get_a_quote";
}
if($type == "services_foot"){
   $q = "SELECT * FROM services";
}
if ($type == "view_updates") {
  $q = "SELECT * FROM project_updt WHERE project_id = '$CSRF_Code'";
}
if ($type == "declined_projects") {
  $q = "SELECT * FROM project WHERE status = 'declined'";
}
if ($type == "completed_projects") {
  $q = "SELECT * FROM project WHERE status = 'completed'";
}
if($type == "select_students"){
  $q = "SELECT * FROM student_details";
}
if($type == "pending_assignments_std"){
  $time = time();
  $q = "SELECT * FROM assignment WHERE student = '$CSRF_Code' AND upload_time <= '$time' AND deadline >= '$time' AND submission = ''";
}
if($type == "services_dropdown"){
  $q = "SELECT * FROM services";
}
if ($type == "tasks_in_progress") {
  $q = "SELECT * FROM project WHERE username = '$CSRF_Code'";
}
if ($type == "web_reviews") {
  $q = "SELECT * FROM reviews WHERE category = '$CSRF_Code'";
}
if ($type == "App_reviews") {
  $q = "SELECT * FROM reviews WHERE category = '$CSRF_Code'";
}
if ($type == "Logo_reviews") {
  $q = "SELECT * FROM reviews WHERE category = '$CSRF_Code'";
}
if ($type == "management_reviews") {
  $q = "SELECT * FROM reviews WHERE category = '$CSRF_Code'";
}
if ($type == "E-Commerce_reviews") {
  $q = "SELECT * FROM reviews WHERE category = '$CSRF_Code'";
}
if ($type == "Hosting_reviews") {
  $q = "SELECT * FROM reviews WHERE category = '$CSRF_Code'";
}
if ($type == "WordPress_reviews") {
  $q = "SELECT * FROM reviews WHERE category = '$CSRF_Code'";
}
if ($type == "revieweresponse") {
  $q = "SELECT * FROM revieweresponse";
}



//start query process



 $result = mysqli_query($this->connection, $q);
   $num_rows = mysqli_num_rows($result);
   if(!$result || ($num_rows < 0)){
      echo "";
      return;
   }
   if($num_rows == 0){
      echo "";
      return;
   }
   
   
   for($i=0; $i<$num_rows; $i++){

      mysqli_data_seek($result, $i);
      $row=mysqli_fetch_row($result);

//END query process


// if($CSRF_Code == $jumo2){
// //In CKRF
// if($type == "stp_fieldset"){
// echo $row[0];
// }

// //END In CKRF
// }




//Out of CKRF

if($type == "stp_fieldset_getnamereg"){
echo '<option value="'.$row[0].'">'.$row[1].'</option>';
}

//Custom

if($type == "stp_fieldset_more"){
echo '<tr><td>'.$row[0].'</td><td>'.$row[1].'</td></tr>';
}
if($type == "requestedprojects"){
  $url = $this->urlEncoder($row[0]);
   echo "<tr>
      <td>".$row[3]."</td>
      
      
      <td>".$row[5]."</td>
      <td>".$row[4]."</td>
      <td><a href='project-details.php?id=$url' class='btn btn-light'>Details</a></td>
   </tr>";
}
if($type == "view_projects"){
  $url = $this->urlEncoder($row[0]);
   echo "<tr>

      <td>".$row[4]."</td>
      
      
      <td>".$row[5]."</td>
      <td>".$row[3]."</td>
      <td><a href='project-details.php?id=$url' class='btn btn-light'>Details</a></td>
   </tr>";
}
if($type == "select_students"){
  echo '
        <option value='.$row[0].'>'.$row[1].'</option>
      ';
}
if($type == "completed_assignments_std"){
  $uploadTime = date("jS F, Y h:i:s A", $row[5]);
  $deadline = date("jS F, Y h:i:s A", $row[6]);
  $currTime = time();
  echo '
        <tr>
          <td>'.$row[1].'</td>
          <td>'.$row[3].'</td>
          <td>'.$uploadTime.'</td>
          <td>'.$deadline.'</td>
          <td><a href='.$row[4].'>Download</a></td>';
          if($row[7] == null){
            echo '<td style="color: red; font-weight: bold;">Not Submitted</td>';
          }
          else{
            echo '<td style="color: green; font-weight: bold;">Submitted</td>';
          }
          echo '
         </tr>
      ';
}
if($type == "pending_assignments_std"){
  $uploadTime = date("jS F, Y h:i:s A", $row[5]);
  $deadline = date("jS F, Y h:i:s A", $row[6]);
  $currTime = time();
  echo '
        <tr>
          <td>'.$row[1].'</td>
          <td>'.$row[3].'</td>
          <td>'.$uploadTime.'</td>
          <td>'.$deadline.'</td>
          <td><a href='.$row[4].'>Download</a></td>';
          if($row[7] == null){
            echo '<td><a href="">Upload</td>';
          }
          else{
            echo '<td style="color: green; font-weight: bold;">Submitted</td>';
          }
          
          
        echo '</tr>
      ';
}
if($type == "recent_projects"){
  echo '
        <tr>
          <td>'.$row[1].'</td>
          <td>'.$row[2].'</td>
          <td><a href='.$row[3].'>Visit '.$row[1].'</a></td>
        </tr>
      ';
}
if($type == "accepted_projects"){
  $url = $this->urlEncoder($row[0]);
  $info = mysqli_fetch_array(mysqli_query($this->connection, "SELECT * FROM project WHERE status='accepted'"));
  $status = $info['status'];
   echo "<tr>
      <td>".$row[4]."</td>
      <td>".$row[3]."</td>
      <td>".$row[1]."</td>";
      if($status == null){
        echo "<td><a href='project-details.php?id=$url' class='btn btn-light'>Details</a></td>";
      }
      else{
        echo "<td><a href='project-details.php?id=$url&r=apprvd' class='btn btn-light'>Details</a></td>";
      }
      
      echo "
   </tr>";
}
if($type == "pending_projects"){
  $url = $this->urlEncoder($row[0]);
  // $info = mysqli_fetch_array(mysqli_query($this->connection, "SELECT * FROM project WHERE status='pending'"));
  // $status = $info['status'];
   echo "<tr>
      <td>".$row[3]."</td>
      <td>".$row[5]."</td>
      <td>".$row[1]."</td>";
      
        echo "<td><a href='project-details.php?id=$url' class='btn btn-light'>Details</a></td>";
      
      
       "
   </tr>";
}
if($type == "declined_projects"){
  $url = $this->urlEncoder($row[0]);
  $info = mysqli_fetch_array(mysqli_query($this->connection, "SELECT * FROM project WHERE status='declined'"));
  $status = $info['status'];
   echo "<tr>
      <td>".$row[3]."</td>
      <td>".$row[5]."</td>
      <td>".$row[1]."</td>";
      if($status == null){
        echo "<td><a href='project-declinedetails?id=$url' class='btn btn-light'>Details</a></td>";
      }
      else{
        echo "<td><a href='project-details.php?id=$url' class='btn btn-light'>Details</a></td>";
      }
      
      echo "
   </tr>";
}

if($type == "completed_projects"){
  $url = $this->urlEncoder($row[0]);
  $info = mysqli_fetch_array(mysqli_query($this->connection, "SELECT * FROM project WHERE status='completed'"));
  $status = $info['status'];
   echo "<tr>
      <td>".$row[3]."</td>
      <td>".$row[5]."</td>
      <td>".$row[1]."</td>";
      if($status == null){
        echo "<td><a href='project-details?id=$url' class='btn btn-light'>Details</a></td>";
      }
      else{
        echo "<td><a href='project-details.php?id=$url&r=apprvd' class='btn btn-light'>Details</a></td>";
      }
      
      echo "
   </tr>";
}


if($type == "assignment_summary_student"){
  $username = $session->username;
  $status = "";
  $std = mysqli_fetch_array(mysqli_query($this->connection, "SELECT * FROM section_detail WHERE section_id = '$row[3]'"));
  $std_up = mysqli_fetch_array(mysqli_query($this->connection, "SELECT * FROM uploaded_assignments WHERE uploader_id = '$username' and assig_id = '$row[0]'"));
  $status = $std_up['status'];
  $uploadTime = date("jS F, Y h:i:s A", $row[6]);
  $deadline = date("jS F, Y h:i:s A", $row[7]);
  $time = time();
  echo '<tr>
        <td>'.$row[1].'</td>
        <td>'.$row[2].'</td>
        <td>'.$std['section_name'].'</td>
        <td>'.$uploadTime.'</td>
        <td>'.$deadline.'</td>';
        if($status == "" && $time > $row[7]){
          echo '<td style="color: blue; font-weight: bold;">Pending</td>';
          echo '<td style="color: blue; font-weight: bold;"><a href="upload_assig.php?assig_id='.$row[0].'" >Upload Assignment</a></td>';
        }
        else if($status == "" && $time <= $row[7]){
          echo ' <td style="color: red; font-weight: bold;">Not Submitted</td>';
        }
        else if($status == "uploaded"){
          echo '<td style="color: red; font-weight: bold;">Submitted</td>';
          // <td>'.date("jS F, Y h:i:s A", $row[8]).'</td>
        }
        
      echo '</tr>';
}

if($type == "assignment_summary_teacher"){
  $std = mysqli_fetch_array(mysqli_query($this->connection, "SELECT * FROM section_detail WHERE section_id = '$row[3]'"));
  $uploadTime = date("jS F, Y h:i:s A", $row[6]);
  $deadline = date("jS F, Y h:i:s A", $row[7]);
  $time = time();
  echo '<tr>
        <td>'.$row[1].'</td>
        <td>'.$row[2].'</td>
        <td>'.$std['section_name'].'</td>
        <td>'.$uploadTime.'</td>
        <td>'.$deadline.'</td>';
        // if($row[7] == null AND $time > $deadline){
        //   echo '
        //     <td style="color: blue; font-weight: bold;">Pending</td>
        //     <td style="text-align: center;">---</td>
        //   ';
        // }
        // else if($row[7] == null AND $time <= $deadline){
        //   echo '
        //     <td style="color: red; font-weight: bold;">Not Submitted</td>
        //     <td style="text-align: center;">---</td>
        //   ';
        // }
        // else{
          echo '
            <td><a style="color: green; font-weight: bold;" href="assets/assignments/questions/'.$row[5].'" download>Download</a></td>
            <td><a href="edit_assig.php?assig_id='.$row[0].'"  class="btn btn-primary">Edit</a></td>
            <td><form action="process.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="assig_id" value="'.$row[0].'">
                <input type="submit"  name="delete_assig"  class="btn btn-danger" value="Delete">
            </form></td>
           
          ';
          // <td>'.date("jS F, Y h:i:s A", $row[8]).'</td>
        // }
        
      echo '</tr>';
}

if($type == "assignment_summary_admin"){
  $std = mysqli_fetch_array(mysqli_query($this->connection, "SELECT * FROM section_detail WHERE section_id = '$row[3]'"));
  $uploadTime = date("jS F, Y h:i:s A", $row[6]);
  $deadline = date("jS F, Y h:i:s A", $row[7]);
  $time = time();
  echo '<tr>
        <td>'.$row[1].'</td>
        <td>'.$row[2].'</td>
        <td>'.$std['section_name'].'</td>
        <td>'.$uploadTime.'</td>
        <td>'.$deadline.'</td>';
        // if($row[7] == null AND $time > $deadline){
        //   echo '
        //     <td style="color: blue; font-weight: bold;">Pending</td>
        //     <td style="text-align: center;">---</td>
        //   ';
        // }
        // else if($row[7] == null AND $time <= $deadline){
        //   echo '
        //     <td style="color: red; font-weight: bold;">Not Submitted</td>
        //     <td style="text-align: center;">---</td>
        //   ';
        // }
        // else{
          echo '
            <td><a style="color: green; font-weight: bold;" href="assets/assignments/questions/'.$row[5].'" download>Download</a></td>
            <td><a href="edit_assig.php?assig_id='.$row[0].'" class="btn btn-primary" >Edit</a></td>
            <td><form action="process.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="assig_id" value="'.$row[0].'">
                <input type="submit" class="btn btn-danger" name="delete_assig" value="Delete">
            </form></td>
           
          ';
          // <td>'.date("jS F, Y h:i:s A", $row[8]).'</td>
        // }
        
      echo '</tr>';
}
// if ($type == "view_updates") {
//   $date = date("d/m/Y", $row[4]);
  
//   echo '
//       <div class="row">
//     <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-2"><img style="max-width: 150px;" src='.$row[5].' alt="Image Description"></div>
//     <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-10">
//         <div class="media g-mb-30 media-comment">
//             <div class="media-body u-shadow-v18 g-bg-secondary g-pa-30 text-left">
//               <div class="g-mb-15">
//                 <h5 class="h5 g-color-gray-dark-v1 mb-0 ">'.$row[1].'</h5>
//                 <span class="g-color-gray-dark-v4 g-font-size-12">'.$date.'</span>
//               </div>
        
//               <p>'.$row[3].'</p>
          // <td><a href='view-updates.php?id=$row[2]' class='btn btn-light'>Details</a></td>
//             </div>
//         </div>
//     </div>
// </div>
//   ';
// }

if ($type == "view_updates") {
  // $date = date("d/m/Y", $row[4]);
   $proj = mysqli_fetch_array(mysqli_query($this->connection, "SELECT * FROM project WHERE id = '$row[2]'"));
  echo "<tr>
       <td>".$proj['project_name']."</td>
       <td>".$proj['category']."</td>
      <td>".$proj['budget']."</td>
      <td>".$row[1]."</td>
      
   </tr>";
}

if ($type == "tasks_in_progress") {
  echo "<tr>
  <td>".$row[2]."</td>
  <td>".$row[5]." days</td>
  <td>".$row[6]."</td>
  </tr>";
}

if($type == "view_material"){
  $time = date("jS, F Y h:i:s A", $row[4]);
  echo '
        <tr>
          <td>'.$row[1].'</td>
          <td>'.$time.'</td>
          <td><a href='.$row[3].' class="btn btn-light" download>Download</a></td>
        </tr>
      ';
}

if($type == "view_students"){
  $courses = $this->getEnrolledCourses($row[11]);
  echo '
        <tr>
          <td>'.$row[1].'</td>
          <td>'.$row[0].'</td>
          <td>'.$courses.'</td>
          <td><a href="" class="btn btn-light">View Details</a></td>
        </tr>
        ';
}
if($type == "view_services"){
  $img = $row[12];
   echo 
      '<tr>
         <td>'.$row[1].'</td>
         <td>'.$row[2].'</td>
         <td>'.$row[3].'</td>
         <td><a href="update-service.php?id='.$row[0].'">Edit</a></td>
         <td><a href="add-reviews.php?id='.$row[0].'">Review</a></td>
      </tr>';
}

if($type == "view_courses"){
  $url = $this->urlEncoder($row[2]);
  echo '
        <tr>
          <td>'.$row[2].'</td>
          <td><a href=view-contents.php?course='.$url.' class="btn btn-light">Course Material</a></td>
        </tr>
      ';
}
if($type == "view_portfolio"){
    $url = "'".$row[4].".webp'";
  echo '
        <div class="item">
                <div class="work">
                  <div class="img d-flex align-items-center justify-content-center rounded" style="background-image: url('.$url.');" title="Filenod '.$row[1].'">
                    <a href='.$row[3].' target="_blank" class="icon d-flex align-items-center justify-content-center">
                      <span class="ion-ios-search"></span>
                    </a>
                  </div>
                  <div class="text pt-3 w-100 text-center">
                    <h3><a href='.$row[3].' target="_blank">'.$row[1].'</a></h3>
                    <span>'.$row[2].'</span>
                  </div>
                </div>
              </div>
      ';
}
if($type == "hosting_sales"){
  $url = $this->urlEncoder($row[0]);
  $query1 = "SELECT * FROM plan_details WHERE id = '$row[1]'";
  $pdid = mysqli_fetch_array(mysqli_query($this->connection, $query1));
  $query2 = "SELECT * FROM plans WHERE id = '$pdid[1]'";
  $planName = mysqli_fetch_array(mysqli_query($this->connection, $query2));
  echo '
      <tr>
        <td>'.$planName[1].'</td>
        <td>'.$pdid[2].' Months'.'</td>
        <td>'.'$'.$row[2].'</td>
        <td>'.date("jS F, Y h:i:s A",$row[12]).'</td>
        <td><a class="btn btn-outline-light" href="hosting-sale-details.php?id='.$url.'">View Buyer<a></td>
      </tr>
      ';
}
if($type == "view_batch"){
  $url = $this->urlEncoder($row[0]);
  echo '
      <tr>
        <td>Batch '.$row[0].'</td>
        <td>'.$row[1].'</td>
        <td>'.$row[2].'</td>
        <td><a href="add-student.php?id='.$url.'" class="btn btn-light">Add Student</a></td>
      </tr>
      ';
}
if($type == "services"){
   echo '<div class="col-xl-4 col-md-6 d-flex align-items-stretch">
            <div class="icon-box">
              <div class="icon">
              <picture>
                 <img width="356" src="'.$row[12].'" alt="Filenod '.$row[1].'" height="237" >
              </picture>
              </div>
              <div class="serv-text">
              <h4><a href="service-details.php?id='.$row[0].'">'.$row[1].'</a></h4>
              <a class="getstarted scrollto btn btn-outline-primary" href="service-details.php?id='.$row[0].'" style="float: right;
    border-radius: 21px;
    margin-right: 23px;
    padding: 6px 10px;
    position: relative;
    top: -14px; border-radius: 15px;" >Get started</a>
              <span>From '.$row[2].' USD</span>
            </div>
            </div>
          </div>';
}
if($type == "view_plan_offers"){
    echo '
            <div class="col-lg-4">
            <div class="box pricingbox">
              <h3>'.$row[2].' Month</h3>
              <h4><sup>$</sup>'.$row[3].'<span>per month</span></h4>
               <a id="myBtn" href="hosting-details.php?id='.$CSRF_Code.'&planid='.$row[0].'" onclick="clickFun()" class="buy-btn">Select</a>
            </div>
            <h3 id="'.$row[0].'"></h3>
          </div>

        ';
}
if($type == "view_projects_user"){
   echo "<tr>
      <td>".$row[3]."</td>
      <td>".$row[8]."</td>
      <td>".$row[6]."</td>
      <td><a href='$row[7]' download>Download</a></td>";
      if($row[12] == "pending"){
        echo '<td style="color:blue; font-weight: bold;">In Process</td>';
      }
      else if($row[12] == "accepted"){
        echo '<td class="view_updates_green"><a href="view-updates.php?id='.$row[0].'">View Updates</a></td>';
      }
      else if($row[12] == "completed"){
        echo '<td style="color:green; font-weight: bold;">Completed</td>';
      }
      else{
        echo '<td style="color:red; font-weight: bold;">Declined</td>';
      }
     echo "
   </tr>";
}


if($type == "view_projectdetails_user"){
   echo "<tr>
      <td>".$row[3]."</td>
      <td>".$row[5]."</td>
      <td>".$row[8]."</td>
      <td>".$row[9]."</td>
      <td>".$row[10]."</td>
      <td>".$row[11]."</td>
      <td><a href='$row[7]' download>Download</a></td>";
       if($row[12] == "pending"){
        echo '<td class="view_updates_green"><a href="view-updates.php?id='.$row[0].'">View Updates</a></td>';
      }
      else if($row[12] == "completed"){
        echo '<td style="color:green; font-weight: bold;">Completed</td>';
      }
      else{
        echo '<td style="color:red; font-weight: bold;">Declined</td>';
      }
     echo "
   </tr>";
}


if($type == "get_section_drop_chk"){
  echo  
     '<option value="'.$row[3].'">'.$row[1].'</option>';
}

if($type == "get_section_drop"){
  echo  
     '<option value="'.$row[0].'">'.$row[1].'</option>';
}


if($type == "get_section"){
  $this->groupdata("get_section_drop_chk", $row[1]);
}
if($type == "get_teacher"){
  echo 
     '<option value="'.$row[2].'">'.$row[1].'</option>';
}

if($type == "view_teacher"){
   echo 
      '<tr>
         <td>'.$row[1].'</td>
         <td>'.$row[3].'</td>
         <td><a href="del-tc.php?username='.$row[2].'" class="btn btn-outline-danger">Delete</a></td>
      </tr>';
}
if($type == "view_team"){
   echo 
      '<tr>
         <td>'.$row[1].'</td>
         <td>'.$row[2].'</td>
         <td>'.$row[3].'</td>
         <td><a href="del-tm.php?id='.$row[0].'" class="btn btn-outline-danger">Delete</a></td>
      </tr>';
}
if($type == "view_plans"){
    echo '
        <div class="col-lg-4">
            <div class="box pricingbox">
              <h3>'.$row[1].'</h3>
              <h4><sup>$</sup>'.$row[2].'<span>per month</span></h4>
              <ul>
                <li><i class="bx bx-check"></i> <strong>'.$row[3].'</strong> Websites</li>
                <li><i class="bx bx-check"></i> <strong>'.$row[4].'</strong> SSD Storage</li>
                <li><i class="bx bx-check"></i> <strong>'.$row[5].'</strong> Email Accounts</li>
                <li><i class="bx bx-check"></i> <strong>'.$row[6].'</strong> Sub Domains</li>
                <li><i class="bx bx-check"></i> <span> <strong>Free</strong> SSL</span></li>
                <li><i class="bx bx-check"></i> <strong>Managed</strong> WordPress</li>
                <li><i class="bx bx-check"></i> <span> <strong>'.$row[7].'</strong> Databases</span></li>
                <li><i class="bx bx-check"></i> <strong>24/7</strong> Support</li>
              </ul>
              <a href="hosting-details.php?id='.$row[0].'" class="buy-btn">Get Started</a>
            </div>
          </div>
        ';
}
if($type == "team"){
   echo 
      '
         <div class="col-lg-4 team-box">
            <div class="member d-flex align-items-start">
              <div class="pic">
                <img src='.$row[4].' class="img-fluid" alt="Filenod Team" width="145" height="145">
              </div>
              <div class="member-info">
                <h4>'.$row[1].'</h4>
                <span>'.$row[2].'</span>
                <p>Member since: '.$row[3].'</p>
                <div class="social">
                  <a href='.$row[5].'> <i class="ri-linkedin-box-fill"></i> </a>
                </div>
              </div>
            </div>
          </div>
      ';
}
if($type == "view_quotes"){
   echo 
      "<tr>
         <td>".$row[2]."</td>
         <td>".$row[4]."</td>
         <td>".$row[5]."</td>
         <td>".$row[6]."</td>
      </tr>";
}
if($type == "services_foot"){
   echo '<li>
            <i class="bx bx-chevron-right"></i> 
            <a href="service-details.php?id='.$row[0].'">'.$row[1].'</a>
         </li>';
}
if($type == "services_dropdown"){
  echo '
          <option>'.$row[1].'</option>
       ';
}
if ($type == "web_reviews") {
  
  $countryFlags = [
    'Afghanistan' => 'afghanistan.webp',
    'Albania' => 'albania.webp',
    'Algeria' => 'algeria.webp',
    'American Samoa' => 'american_samoa.webp',
    'Andorra' => 'andorra.webp',
    'Angola' => 'angola.webp',
    'Anguilla' => 'anguilla.webp',
    'Antarctica' => 'antarctica.webp',
    'Antigua and Barbuda' => 'antigua_and_barbuda.webp',
    'Argentina' => 'argentina.webp',
    'Armenia' => 'armenia.webp',
    'Aruba' => 'aruba.webp',
    'Australia' => 'australia.webp',
    'Austria' => 'austria.webp',
    'Azerbaijan' => 'azerbaijan.webp',
    'Bahamas' => 'bahamas.webp',
    'Bahrain' => 'bahrain.webp',
    'Bangladesh' => 'bangladesh.webp',
    'Barbados' => 'barbados.webp',
    'Belarus' => 'belarus.webp',
    'Belgium' => 'belgium.webp',
    'Belize' => 'belize.webp',
    'Benin' => 'benin.webp',
    'Bermuda' => 'bermuda.webp',
    'Bhutan' => 'bhutan.webp',
    'Bolivia' => 'bolivia.webp',
    'Bosnia and Herzegovina' => 'bosnia_and_herzegovina.webp',
    'Botswana' => 'botswana.webp',
    'Bouvet Island' => 'bouvet_island.webp',
    'Brazil' => 'brazil.webp',
    'British Indian Ocean Territory' => 'british_indian_ocean_territory.webp',
    'Brunei Darussalam' => 'brunei_darussalam.webp',
    'Bulgaria' => 'bulgaria.webp',
    'Burkina Faso' => 'burkina_faso.webp',
    'Burundi' => 'burundi.webp',
    'Cabo Verde' => 'cabo_verde.webp',
    'Cambodia' => 'cambodia.webp',
    'Cameroon' => 'cameroon.webp',
    'Canada' => 'canada.webp',
    'Cayman Islands' => 'cayman_islands.webp',
    'Central African Republic' => 'central_african_republic.webp',
    'Chad' => 'chad.webp',
    'Chile' => 'chile.webp',
    'China' => 'china.webp',
    'Christmas Island' => 'christmas_island.webp',
    'Cocos (Keeling) Islands' => 'cocos_islands.webp',
    'Colombia' => 'colombia.webp',
    'Comoros' => 'comoros.webp',
    'Congo, Democratic Republic of the' => 'congo_democratic_republic.webp',
    'Congo, Republic of the' => 'congo_republic.webp',
    'Cook Islands' => 'cook_islands.webp',
    'Costa Rica' => 'costa_rica.webp',
    'Croatia' => 'croatia.webp',
    'Cuba' => 'cuba.webp',
    'Curaçao' => 'curacao.webp',
    'Cyprus' => 'cyprus.webp',
    'Czechia' => 'czechia.webp',
    'Denmark' => 'denmark.webp',
    'Djibouti' => 'djibouti.webp',
    'Dominica' => 'dominica.webp',
    'Dominican Republic' => 'dominican_republic.webp',
    'Ecuador' => 'ecuador.webp',
    'Egypt' => 'egypt.webp',
    'El Salvador' => 'el_salvador.webp',
    'Equatorial Guinea' => 'equatorial_guinea.webp',
    'Eritrea' => 'eritrea.webp',
    'Estonia' => 'estonia.webp',
    'Eswatini' => 'eswatini.webp',
    'Ethiopia' => 'ethiopia.webp',
    'Fiji' => 'fiji.webp',
    'Finland' => 'finland.webp',
    'France' => 'france.webp',
    'French Guiana' => 'french_guiana.webp',
    'French Polynesia' => 'french_polynesia.webp',
    'French Southern Territories' => 'french_southern_territories.webp',
    'Gabon' => 'gabon.webp',
    'Gambia' => 'gambia.webp',
    'Georgia' => 'georgia.webp',
    'Germany' => 'germany.webp',
    'Ghana' => 'ghana.webp',
    'Gibraltar' => 'gibraltar.webp',
    'Greece' => 'greece.webp',
    'Greenland' => 'greenland.webp',
    'Grenada' => 'grenada.webp',
    'Guadeloupe' => 'guadeloupe.webp',
    'Guam' => 'guam.webp',
    'Guatemala' => 'guatemala.webp',
    'Guernsey' => 'guernsey.webp',
    'Guinea' => 'guinea.webp',
    'Guinea-Bissau' => 'guinea_bissau.webp',
    'Guyana' => 'guyana.webp',
    'Haiti' => 'haiti.webp',
    'Heard Island and McDonald Islands' => 'heard_mcdonald_islands.webp',
    'Holy See' => 'holy_see.webp',
    'Honduras' => 'honduras.webp',
    'Hong Kong' => 'hong_kong.webp',
    'Hungary' => 'hungary.webp',
    'Iceland' => 'iceland.webp',
    'India' => 'india.webp',
    'Indonesia' => 'indonesia.webp',
    'Iran' => 'iran.webp',
    'Iraq' => 'iraq.webp',
    'Ireland' => 'ireland.webp',
    'Isle of Man' => 'isle_of_man.webp',
    'Israel' => 'israel.webp',
    'Italy' => 'italy.webp',
    'Ivory Coast' => 'ivory_coast.webp',
    'Jamaica' => 'jamaica.webp',
    'Japan' => 'japan.webp',
    'Jersey' => 'jersey.webp',
    'Jordan' => 'jordan.webp',
    'Kazakhstan' => 'kazakhstan.webp',
    'Kenya' => 'kenya.webp',
    'Kiribati' => 'kiribati.webp',
    'Korea (North)' => 'north_korea.webp',
    'Korea (South)' => 'south_korea.webp',
    'Kuwait' => 'kuwait.webp',
    'Kyrgyzstan' => 'kyrgyzstan.webp',
    'Laos' => 'laos.webp',
    'Latvia' => 'latvia.webp',
    'Lebanon' => 'lebanon.webp',
    'Lesotho' => 'lesotho.webp',
    'Liberia' => 'liberia.webp',
    'Libya' => 'libya.webp',
    'Liechtenstein' => 'liechtenstein.webp',
    'Lithuania' => 'lithuania.webp',
    'Luxembourg' => 'luxembourg.webp',
    'Macao' => 'macao.webp',
    'Madagascar' => 'madagascar.webp',
    'Malawi' => 'malawi.webp',
    'Malaysia' => 'malaysia.webp',
    'Maldives' => 'maldives.webp',
    'Mali' => 'mali.webp',
    'Malta' => 'malta.webp',
    'Marshall Islands' => 'marshall_islands.webp',
    'Martinique' => 'martinique.webp',
    'Mauritania' => 'mauritania.webp',
    'Mauritius' => 'mauritius.webp',
    'Mayotte' => 'mayotte.webp',
    'Mexico' => 'mexico.webp',
    'Micronesia' => 'micronesia.webp',
    'Moldova' => 'moldova.webp',
    'Monaco' => 'monaco.webp',
    'Mongolia' => 'mongolia.webp',
    'Montenegro' => 'montenegro.webp',
    'Montserrat' => 'montserrat.webp',
    'Morocco' => 'morocco.webp',
    'Mozambique' => 'mozambique.webp',
    'Myanmar' => 'myanmar.webp',
    'Namibia' => 'namibia.webp',
    'Nauru' => 'nauru.webp',
    'Nepal' => 'nepal.webp',
    'Netherlands' => 'netherlands.webp',
    'New Caledonia' => 'new_caledonia.webp',
    'New Zealand' => 'new_zealand.webp',
    'Nicaragua' => 'nicaragua.webp',
    'Niger' => 'niger.webp',
    'Nigeria' => 'nigeria.webp',
    'Niue' => 'niue.webp',
    'Norfolk Island' => 'norfolk_island.webp',
    'Northern Mariana Islands' => 'northern_mariana_islands.webp',
    'Norway' => 'norway.webp',
    'Oman' => 'oman.webp',
    'Pakistan' => 'pakistan.webp',
    'Palau' => 'palau.webp',
    'Palestine' => 'palestine.webp',
    'Panama' => 'panama.webp',
    'Papua New Guinea' => 'papua_new_guinea.webp',
    'Paraguay' => 'paraguay.webp',
    'Peru' => 'peru.webp',
    'Philippines' => 'philippines.webp',
    'Pitcairn' => 'pitcairn.webp',
    'Poland' => 'poland.webp',
    'Portugal' => 'portugal.webp',
    'Puerto Rico' => 'puerto_rico.webp',
    'Qatar' => 'qatar.webp',
    'Reunion' => 'reunion.webp',
    'Romania' => 'romania.webp',
    'Russian Federation' => 'russia.webp',
    'Rwanda' => 'rwanda.webp',
    'Saint Barthélemy' => 'saint_barthelemy.webp',
    'Saint Helena, Ascension and Tristan da Cunha' => 'saint_helena.webp',
    'Saint Kitts and Nevis' => 'saint_kitts_and_nevis.webp',
    'Saint Lucia' => 'saint_lucia.webp',
    'Saint Martin' => 'saint_martin.webp',
    'Saint Pierre and Miquelon' => 'saint_pierre_and_miquelon.webp',
    'Saint Vincent and the Grenadines' => 'saint_vincent_and_grenadines.webp',
    'Samoa' => 'samoa.webp',
    'San Marino' => 'san_marino.webp',
    'Sao Tome and Principe' => 'sao_tome_and_principe.webp',
    'Saudi Arabia' => 'saudi_arabia.webp',
    'Senegal' => 'senegal.webp',
    'Serbia' => 'serbia.webp',
    'Seychelles' => 'seychelles.webp',
    'Sierra Leone' => 'sierra_leone.webp',
    'Singapore' => 'singapore.webp',
    'Sint Maarten' => 'sint_maarten.webp',
    'Slovakia' => 'slovakia.webp',
    'Slovenia' => 'slovenia.webp',
    'Solomon Islands' => 'solomon_islands.webp',
    'Somalia' => 'somalia.webp',
    'South Africa' => 'south_africa.webp',
    'South Georgia and the South Sandwich Islands' => 'south_georgia_and_south_sandwich_islands.webp',
    'South Sudan' => 'south_sudan.webp',
    'Spain' => 'spain.webp',
    'Sri Lanka' => 'sri_lanka.webp',
    'Sudan' => 'sudan.webp',
    'Suriname' => 'suriname.webp',
    'Svalbard and Jan Mayen' => 'svalbard_and_jan_mayen.webp',
    'Sweden' => 'sweden.webp',
    'Switzerland' => 'switzerland.webp',
    'Syrian Arab Republic' => 'syria.webp',
    'Taiwan' => 'taiwan.webp',
    'Tajikistan' => 'tajikistan.webp',
    'Tanzania' => 'tanzania.webp',
    'Thailand' => 'thailand.webp',
    'Timor-Leste' => 'timor_leste.webp',
    'Togo' => 'togo.webp',
    'Tokelau' => 'tokelau.webp',
    'Tonga' => 'tonga.webp',
    'Trinidad and Tobago' => 'trinidad_and_tobago.webp',
    'Tunisia' => 'tunisia.webp',
    'Turkey' => 'turkey.webp',
    'Turkmenistan' => 'turkmenistan.webp',
    'Turks and Caicos Islands' => 'turks_and_caicos_islands.webp',
    'Tuvalu' => 'tuvalu.webp',
    'Uganda' => 'uganda.webp',
    'Ukraine' => 'ukraine.webp',
    'United Arab Emirates' => 'uae.webp',
    'United Kingdom' => 'uk.webp',
    'United States' => 'usa.webp',
    'Uruguay' => 'uruguay.webp',
    'Uzbekistan' => 'uzbekistan.webp',
    'Vanuatu' => 'vanuatu.webp',
    'Venezuela' => 'venezuela.webp',
    'Vietnam' => 'vietnam.webp',
    'Wallis and Futuna' => 'wallis_and_futuna.webp',
    'Western Sahara' => 'western_sahara.webp',
    'Yemen' => 'yemen.webp',
    'Zambia' => 'zambia.webp',
    'Zimbabwe' => 'zimbabwe.webp',
];

$country = $row[3]; // Country name from the database
    $flagImage = isset($countryFlags[$country]) ? $countryFlags[$country] : 'default.jpg'; // Default flag if country is not in the array

    echo '
        <div class="container mt-5">
            <div class="reviews-section">
                <div class="review-item">
                    <div class="review-avatar">
                        <img src="' . $row[4] . '" alt="Reviewer" class="avatar-img">
                    </div>
                    <div class="review-content">
                        <div class="review-header">
                            <div class="review-info">
                                <h6 class="reviewer-name">' . $row[2] . '</h6>
                                <img src="assetss/reviews/flags/' . htmlspecialchars($flagImage) . '" alt="' . htmlspecialchars($country) . ' Flag" class="flag-img">
                                <p class="review-country">' . htmlspecialchars($country) . '</p>
                            </div>
                            <div class="review-stars">
                                <span class="star-filled">';

                          $count = (int)$row[6];
                          if ($count == 5) {
                              echo "★★★★★";
                          } elseif ($count == 4) {
                              echo "★★★★☆";
                          } elseif ($count == 3) {
                              echo "★★★☆☆";
                          } elseif ($count == 2) {
                              echo "★★☆☆☆";
                          } elseif ($count == 1) {
                              echo "★☆☆☆☆";
                          }

                          echo '              </span>
                            </div>
                        </div>
                        <div class="review-text-container">
                            <p class="review-text">' . $row[5] . '</p>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    ';
}









if ($type == "App_reviews") {
  
 $countryFlags = [
    'Afghanistan' => 'afghanistan.webp',
    'Albania' => 'albania.webp',
    'Algeria' => 'algeria.webp',
    'American Samoa' => 'american_samoa.webp',
    'Andorra' => 'andorra.webp',
    'Angola' => 'angola.webp',
    'Anguilla' => 'anguilla.webp',
    'Antarctica' => 'antarctica.webp',
    'Antigua and Barbuda' => 'antigua_and_barbuda.webp',
    'Argentina' => 'argentina.webp',
    'Armenia' => 'armenia.webp',
    'Aruba' => 'aruba.webp',
    'Australia' => 'australia.webp',
    'Austria' => 'austria.webp',
    'Azerbaijan' => 'azerbaijan.webp',
    'Bahamas' => 'bahamas.webp',
    'Bahrain' => 'bahrain.webp',
    'Bangladesh' => 'bangladesh.webp',
    'Barbados' => 'barbados.webp',
    'Belarus' => 'belarus.webp',
    'Belgium' => 'belgium.webp',
    'Belize' => 'belize.webp',
    'Benin' => 'benin.webp',
    'Bermuda' => 'bermuda.webp',
    'Bhutan' => 'bhutan.webp',
    'Bolivia' => 'bolivia.webp',
    'Bosnia and Herzegovina' => 'bosnia_and_herzegovina.webp',
    'Botswana' => 'botswana.webp',
    'Bouvet Island' => 'bouvet_island.webp',
    'Brazil' => 'brazil.webp',
    'British Indian Ocean Territory' => 'british_indian_ocean_territory.webp',
    'Brunei Darussalam' => 'brunei_darussalam.webp',
    'Bulgaria' => 'bulgaria.webp',
    'Burkina Faso' => 'burkina_faso.webp',
    'Burundi' => 'burundi.webp',
    'Cabo Verde' => 'cabo_verde.webp',
    'Cambodia' => 'cambodia.webp',
    'Cameroon' => 'cameroon.webp',
    'Canada' => 'canada.webp',
    'Cayman Islands' => 'cayman_islands.webp',
    'Central African Republic' => 'central_african_republic.webp',
    'Chad' => 'chad.webp',
    'Chile' => 'chile.webp',
    'China' => 'china.webp',
    'Christmas Island' => 'christmas_island.webp',
    'Cocos (Keeling) Islands' => 'cocos_islands.webp',
    'Colombia' => 'colombia.webp',
    'Comoros' => 'comoros.webp',
    'Congo, Democratic Republic of the' => 'congo_democratic_republic.webp',
    'Congo, Republic of the' => 'congo_republic.webp',
    'Cook Islands' => 'cook_islands.webp',
    'Costa Rica' => 'costa_rica.webp',
    'Croatia' => 'croatia.webp',
    'Cuba' => 'cuba.webp',
    'Curaçao' => 'curacao.webp',
    'Cyprus' => 'cyprus.webp',
    'Czechia' => 'czechia.webp',
    'Denmark' => 'denmark.webp',
    'Djibouti' => 'djibouti.webp',
    'Dominica' => 'dominica.webp',
    'Dominican Republic' => 'dominican_republic.webp',
    'Ecuador' => 'ecuador.webp',
    'Egypt' => 'egypt.webp',
    'El Salvador' => 'el_salvador.webp',
    'Equatorial Guinea' => 'equatorial_guinea.webp',
    'Eritrea' => 'eritrea.webp',
    'Estonia' => 'estonia.webp',
    'Eswatini' => 'eswatini.webp',
    'Ethiopia' => 'ethiopia.webp',
    'Fiji' => 'fiji.webp',
    'Finland' => 'finland.webp',
    'France' => 'france.webp',
    'French Guiana' => 'french_guiana.webp',
    'French Polynesia' => 'french_polynesia.webp',
    'French Southern Territories' => 'french_southern_territories.webp',
    'Gabon' => 'gabon.webp',
    'Gambia' => 'gambia.webp',
    'Georgia' => 'georgia.webp',
    'Germany' => 'germany.webp',
    'Ghana' => 'ghana.webp',
    'Gibraltar' => 'gibraltar.webp',
    'Greece' => 'greece.webp',
    'Greenland' => 'greenland.webp',
    'Grenada' => 'grenada.webp',
    'Guadeloupe' => 'guadeloupe.webp',
    'Guam' => 'guam.webp',
    'Guatemala' => 'guatemala.webp',
    'Guernsey' => 'guernsey.webp',
    'Guinea' => 'guinea.webp',
    'Guinea-Bissau' => 'guinea_bissau.webp',
    'Guyana' => 'guyana.webp',
    'Haiti' => 'haiti.webp',
    'Heard Island and McDonald Islands' => 'heard_mcdonald_islands.webp',
    'Holy See' => 'holy_see.webp',
    'Honduras' => 'honduras.webp',
    'Hong Kong' => 'hong_kong.webp',
    'Hungary' => 'hungary.webp',
    'Iceland' => 'iceland.webp',
    'India' => 'india.webp',
    'Indonesia' => 'indonesia.webp',
    'Iran' => 'iran.webp',
    'Iraq' => 'iraq.webp',
    'Ireland' => 'ireland.webp',
    'Isle of Man' => 'isle_of_man.webp',
    'Israel' => 'israel.webp',
    'Italy' => 'italy.webp',
    'Ivory Coast' => 'ivory_coast.webp',
    'Jamaica' => 'jamaica.webp',
    'Japan' => 'japan.webp',
    'Jersey' => 'jersey.webp',
    'Jordan' => 'jordan.webp',
    'Kazakhstan' => 'kazakhstan.webp',
    'Kenya' => 'kenya.webp',
    'Kiribati' => 'kiribati.webp',
    'Korea (North)' => 'north_korea.webp',
    'Korea (South)' => 'south_korea.webp',
    'Kuwait' => 'kuwait.webp',
    'Kyrgyzstan' => 'kyrgyzstan.webp',
    'Laos' => 'laos.webp',
    'Latvia' => 'latvia.webp',
    'Lebanon' => 'lebanon.webp',
    'Lesotho' => 'lesotho.webp',
    'Liberia' => 'liberia.webp',
    'Libya' => 'libya.webp',
    'Liechtenstein' => 'liechtenstein.webp',
    'Lithuania' => 'lithuania.webp',
    'Luxembourg' => 'luxembourg.webp',
    'Macao' => 'macao.webp',
    'Madagascar' => 'madagascar.webp',
    'Malawi' => 'malawi.webp',
    'Malaysia' => 'malaysia.webp',
    'Maldives' => 'maldives.webp',
    'Mali' => 'mali.webp',
    'Malta' => 'malta.webp',
    'Marshall Islands' => 'marshall_islands.webp',
    'Martinique' => 'martinique.webp',
    'Mauritania' => 'mauritania.webp',
    'Mauritius' => 'mauritius.webp',
    'Mayotte' => 'mayotte.webp',
    'Mexico' => 'mexico.webp',
    'Micronesia' => 'micronesia.webp',
    'Moldova' => 'moldova.webp',
    'Monaco' => 'monaco.webp',
    'Mongolia' => 'mongolia.webp',
    'Montenegro' => 'montenegro.webp',
    'Montserrat' => 'montserrat.webp',
    'Morocco' => 'morocco.webp',
    'Mozambique' => 'mozambique.webp',
    'Myanmar' => 'myanmar.webp',
    'Namibia' => 'namibia.webp',
    'Nauru' => 'nauru.webp',
    'Nepal' => 'nepal.webp',
    'Netherlands' => 'netherlands.webp',
    'New Caledonia' => 'new_caledonia.webp',
    'New Zealand' => 'new_zealand.webp',
    'Nicaragua' => 'nicaragua.webp',
    'Niger' => 'niger.webp',
    'Nigeria' => 'nigeria.webp',
    'Niue' => 'niue.webp',
    'Norfolk Island' => 'norfolk_island.webp',
    'Northern Mariana Islands' => 'northern_mariana_islands.webp',
    'Norway' => 'norway.webp',
    'Oman' => 'oman.webp',
    'Pakistan' => 'pakistan.webp',
    'Palau' => 'palau.webp',
    'Palestine' => 'palestine.webp',
    'Panama' => 'panama.webp',
    'Papua New Guinea' => 'papua_new_guinea.webp',
    'Paraguay' => 'paraguay.webp',
    'Peru' => 'peru.webp',
    'Philippines' => 'philippines.webp',
    'Pitcairn' => 'pitcairn.webp',
    'Poland' => 'poland.webp',
    'Portugal' => 'portugal.webp',
    'Puerto Rico' => 'puerto_rico.webp',
    'Qatar' => 'qatar.webp',
    'Reunion' => 'reunion.webp',
    'Romania' => 'romania.webp',
    'Russian Federation' => 'russia.webp',
    'Rwanda' => 'rwanda.webp',
    'Saint Barthélemy' => 'saint_barthelemy.webp',
    'Saint Helena, Ascension and Tristan da Cunha' => 'saint_helena.webp',
    'Saint Kitts and Nevis' => 'saint_kitts_and_nevis.webp',
    'Saint Lucia' => 'saint_lucia.webp',
    'Saint Martin' => 'saint_martin.webp',
    'Saint Pierre and Miquelon' => 'saint_pierre_and_miquelon.webp',
    'Saint Vincent and the Grenadines' => 'saint_vincent_and_grenadines.webp',
    'Samoa' => 'samoa.webp',
    'San Marino' => 'san_marino.webp',
    'Sao Tome and Principe' => 'sao_tome_and_principe.webp',
    'Saudi Arabia' => 'saudi_arabia.webp',
    'Senegal' => 'senegal.webp',
    'Serbia' => 'serbia.webp',
    'Seychelles' => 'seychelles.webp',
    'Sierra Leone' => 'sierra_leone.webp',
    'Singapore' => 'singapore.webp',
    'Sint Maarten' => 'sint_maarten.webp',
    'Slovakia' => 'slovakia.webp',
    'Slovenia' => 'slovenia.webp',
    'Solomon Islands' => 'solomon_islands.webp',
    'Somalia' => 'somalia.webp',
    'South Africa' => 'south_africa.webp',
    'South Georgia and the South Sandwich Islands' => 'south_georgia_and_south_sandwich_islands.webp',
    'South Sudan' => 'south_sudan.webp',
    'Spain' => 'spain.webp',
    'Sri Lanka' => 'sri_lanka.webp',
    'Sudan' => 'sudan.webp',
    'Suriname' => 'suriname.webp',
    'Svalbard and Jan Mayen' => 'svalbard_and_jan_mayen.webp',
    'Sweden' => 'sweden.webp',
    'Switzerland' => 'switzerland.webp',
    'Syrian Arab Republic' => 'syria.webp',
    'Taiwan' => 'taiwan.webp',
    'Tajikistan' => 'tajikistan.webp',
    'Tanzania' => 'tanzania.webp',
    'Thailand' => 'thailand.webp',
    'Timor-Leste' => 'timor_leste.webp',
    'Togo' => 'togo.webp',
    'Tokelau' => 'tokelau.webp',
    'Tonga' => 'tonga.webp',
    'Trinidad and Tobago' => 'trinidad_and_tobago.webp',
    'Tunisia' => 'tunisia.webp',
    'Turkey' => 'turkey.webp',
    'Turkmenistan' => 'turkmenistan.webp',
    'Turks and Caicos Islands' => 'turks_and_caicos_islands.webp',
    'Tuvalu' => 'tuvalu.webp',
    'Uganda' => 'uganda.webp',
    'Ukraine' => 'ukraine.webp',
    'United Arab Emirates' => 'uae.webp',
    'United Kingdom' => 'uk.webp',
    'United States' => 'usa.webp',
    'Uruguay' => 'uruguay.webp',
    'Uzbekistan' => 'uzbekistan.webp',
    'Vanuatu' => 'vanuatu.webp',
    'Venezuela' => 'venezuela.webp',
    'Vietnam' => 'vietnam.webp',
    'Wallis and Futuna' => 'wallis_and_futuna.webp',
    'Western Sahara' => 'western_sahara.webp',
    'Yemen' => 'yemen.webp',
    'Zambia' => 'zambia.webp',
    'Zimbabwe' => 'zimbabwe.webp',
];

$country = $row[3]; // Country name from the database
    $flagImage = isset($countryFlags[$country]) ? $countryFlags[$country] : 'default.jpg'; // Default flag if country is not in the array

    echo '
        <div class="container mt-5">
            <div class="reviews-section">
                <div class="review-item">
                    <div class="review-avatar">
                        <img src="' . $row[4] . '" alt="Reviewer" class="avatar-img">
                    </div>
                    <div class="review-content">
                        <div class="review-header">
                            <div class="review-info">
                                <h6 class="reviewer-name">' . $row[2] . '</h6>
                                <img src="assetss/reviews/flags/' . htmlspecialchars($flagImage) . '" alt="' . htmlspecialchars($country) . ' Flag" class="flag-img">
                                <p class="review-country">' . htmlspecialchars($country) . '</p>
                            </div>
                            <div class="review-stars">
                                <span class="star-filled">';

                          $count = (int)$row[6];
                          if ($count == 5) {
                              echo "★★★★★";
                          } elseif ($count == 4) {
                              echo "★★★★☆";
                          } elseif ($count == 3) {
                              echo "★★★☆☆";
                          } elseif ($count == 2) {
                              echo "★★☆☆☆";
                          } elseif ($count == 1) {
                              echo "★☆☆☆☆";
                          }

                          echo '              </span>
                            </div>
                        </div>
                        <div class="review-text-container">
                            <p class="review-text">' . $row[5] . '</p>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    ';
}



if ($type == "Logo_reviews") {
  
  $countryFlags = [
    'Afghanistan' => 'afghanistan.webp',
    'Albania' => 'albania.webp',
    'Algeria' => 'algeria.webp',
    'American Samoa' => 'american_samoa.webp',
    'Andorra' => 'andorra.webp',
    'Angola' => 'angola.webp',
    'Anguilla' => 'anguilla.webp',
    'Antarctica' => 'antarctica.webp',
    'Antigua and Barbuda' => 'antigua_and_barbuda.webp',
    'Argentina' => 'argentina.webp',
    'Armenia' => 'armenia.webp',
    'Aruba' => 'aruba.webp',
    'Australia' => 'australia.webp',
    'Austria' => 'austria.webp',
    'Azerbaijan' => 'azerbaijan.webp',
    'Bahamas' => 'bahamas.webp',
    'Bahrain' => 'bahrain.webp',
    'Bangladesh' => 'bangladesh.webp',
    'Barbados' => 'barbados.webp',
    'Belarus' => 'belarus.webp',
    'Belgium' => 'belgium.webp',
    'Belize' => 'belize.webp',
    'Benin' => 'benin.webp',
    'Bermuda' => 'bermuda.webp',
    'Bhutan' => 'bhutan.webp',
    'Bolivia' => 'bolivia.webp',
    'Bosnia and Herzegovina' => 'bosnia_and_herzegovina.webp',
    'Botswana' => 'botswana.webp',
    'Bouvet Island' => 'bouvet_island.webp',
    'Brazil' => 'brazil.webp',
    'British Indian Ocean Territory' => 'british_indian_ocean_territory.webp',
    'Brunei Darussalam' => 'brunei_darussalam.webp',
    'Bulgaria' => 'bulgaria.webp',
    'Burkina Faso' => 'burkina_faso.webp',
    'Burundi' => 'burundi.webp',
    'Cabo Verde' => 'cabo_verde.webp',
    'Cambodia' => 'cambodia.webp',
    'Cameroon' => 'cameroon.webp',
    'Canada' => 'canada.webp',
    'Cayman Islands' => 'cayman_islands.webp',
    'Central African Republic' => 'central_african_republic.webp',
    'Chad' => 'chad.webp',
    'Chile' => 'chile.webp',
    'China' => 'china.webp',
    'Christmas Island' => 'christmas_island.webp',
    'Cocos (Keeling) Islands' => 'cocos_islands.webp',
    'Colombia' => 'colombia.webp',
    'Comoros' => 'comoros.webp',
    'Congo, Democratic Republic of the' => 'congo_democratic_republic.webp',
    'Congo, Republic of the' => 'congo_republic.webp',
    'Cook Islands' => 'cook_islands.webp',
    'Costa Rica' => 'costa_rica.webp',
    'Croatia' => 'croatia.webp',
    'Cuba' => 'cuba.webp',
    'Curaçao' => 'curacao.webp',
    'Cyprus' => 'cyprus.webp',
    'Czechia' => 'czechia.webp',
    'Denmark' => 'denmark.webp',
    'Djibouti' => 'djibouti.webp',
    'Dominica' => 'dominica.webp',
    'Dominican Republic' => 'dominican_republic.webp',
    'Ecuador' => 'ecuador.webp',
    'Egypt' => 'egypt.webp',
    'El Salvador' => 'el_salvador.webp',
    'Equatorial Guinea' => 'equatorial_guinea.webp',
    'Eritrea' => 'eritrea.webp',
    'Estonia' => 'estonia.webp',
    'Eswatini' => 'eswatini.webp',
    'Ethiopia' => 'ethiopia.webp',
    'Fiji' => 'fiji.webp',
    'Finland' => 'finland.webp',
    'France' => 'france.webp',
    'French Guiana' => 'french_guiana.webp',
    'French Polynesia' => 'french_polynesia.webp',
    'French Southern Territories' => 'french_southern_territories.webp',
    'Gabon' => 'gabon.webp',
    'Gambia' => 'gambia.webp',
    'Georgia' => 'georgia.webp',
    'Germany' => 'germany.webp',
    'Ghana' => 'ghana.webp',
    'Gibraltar' => 'gibraltar.webp',
    'Greece' => 'greece.webp',
    'Greenland' => 'greenland.webp',
    'Grenada' => 'grenada.webp',
    'Guadeloupe' => 'guadeloupe.webp',
    'Guam' => 'guam.webp',
    'Guatemala' => 'guatemala.webp',
    'Guernsey' => 'guernsey.webp',
    'Guinea' => 'guinea.webp',
    'Guinea-Bissau' => 'guinea_bissau.webp',
    'Guyana' => 'guyana.webp',
    'Haiti' => 'haiti.webp',
    'Heard Island and McDonald Islands' => 'heard_mcdonald_islands.webp',
    'Holy See' => 'holy_see.webp',
    'Honduras' => 'honduras.webp',
    'Hong Kong' => 'hong_kong.webp',
    'Hungary' => 'hungary.webp',
    'Iceland' => 'iceland.webp',
    'India' => 'india.webp',
    'Indonesia' => 'indonesia.webp',
    'Iran' => 'iran.webp',
    'Iraq' => 'iraq.webp',
    'Ireland' => 'ireland.webp',
    'Isle of Man' => 'isle_of_man.webp',
    'Israel' => 'israel.webp',
    'Italy' => 'italy.webp',
    'Ivory Coast' => 'ivory_coast.webp',
    'Jamaica' => 'jamaica.webp',
    'Japan' => 'japan.webp',
    'Jersey' => 'jersey.webp',
    'Jordan' => 'jordan.webp',
    'Kazakhstan' => 'kazakhstan.webp',
    'Kenya' => 'kenya.webp',
    'Kiribati' => 'kiribati.webp',
    'Korea (North)' => 'north_korea.webp',
    'Korea (South)' => 'south_korea.webp',
    'Kuwait' => 'kuwait.webp',
    'Kyrgyzstan' => 'kyrgyzstan.webp',
    'Laos' => 'laos.webp',
    'Latvia' => 'latvia.webp',
    'Lebanon' => 'lebanon.webp',
    'Lesotho' => 'lesotho.webp',
    'Liberia' => 'liberia.webp',
    'Libya' => 'libya.webp',
    'Liechtenstein' => 'liechtenstein.webp',
    'Lithuania' => 'lithuania.webp',
    'Luxembourg' => 'luxembourg.webp',
    'Macao' => 'macao.webp',
    'Madagascar' => 'madagascar.webp',
    'Malawi' => 'malawi.webp',
    'Malaysia' => 'malaysia.webp',
    'Maldives' => 'maldives.webp',
    'Mali' => 'mali.webp',
    'Malta' => 'malta.webp',
    'Marshall Islands' => 'marshall_islands.webp',
    'Martinique' => 'martinique.webp',
    'Mauritania' => 'mauritania.webp',
    'Mauritius' => 'mauritius.webp',
    'Mayotte' => 'mayotte.webp',
    'Mexico' => 'mexico.webp',
    'Micronesia' => 'micronesia.webp',
    'Moldova' => 'moldova.webp',
    'Monaco' => 'monaco.webp',
    'Mongolia' => 'mongolia.webp',
    'Montenegro' => 'montenegro.webp',
    'Montserrat' => 'montserrat.webp',
    'Morocco' => 'morocco.webp',
    'Mozambique' => 'mozambique.webp',
    'Myanmar' => 'myanmar.webp',
    'Namibia' => 'namibia.webp',
    'Nauru' => 'nauru.webp',
    'Nepal' => 'nepal.webp',
    'Netherlands' => 'netherlands.webp',
    'New Caledonia' => 'new_caledonia.webp',
    'New Zealand' => 'new_zealand.webp',
    'Nicaragua' => 'nicaragua.webp',
    'Niger' => 'niger.webp',
    'Nigeria' => 'nigeria.webp',
    'Niue' => 'niue.webp',
    'Norfolk Island' => 'norfolk_island.webp',
    'Northern Mariana Islands' => 'northern_mariana_islands.webp',
    'Norway' => 'norway.webp',
    'Oman' => 'oman.webp',
    'Pakistan' => 'pakistan.webp',
    'Palau' => 'palau.webp',
    'Palestine' => 'palestine.webp',
    'Panama' => 'panama.webp',
    'Papua New Guinea' => 'papua_new_guinea.webp',
    'Paraguay' => 'paraguay.webp',
    'Peru' => 'peru.webp',
    'Philippines' => 'philippines.webp',
    'Pitcairn' => 'pitcairn.webp',
    'Poland' => 'poland.webp',
    'Portugal' => 'portugal.webp',
    'Puerto Rico' => 'puerto_rico.webp',
    'Qatar' => 'qatar.webp',
    'Reunion' => 'reunion.webp',
    'Romania' => 'romania.webp',
    'Russian Federation' => 'russia.webp',
    'Rwanda' => 'rwanda.webp',
    'Saint Barthélemy' => 'saint_barthelemy.webp',
    'Saint Helena, Ascension and Tristan da Cunha' => 'saint_helena.webp',
    'Saint Kitts and Nevis' => 'saint_kitts_and_nevis.webp',
    'Saint Lucia' => 'saint_lucia.webp',
    'Saint Martin' => 'saint_martin.webp',
    'Saint Pierre and Miquelon' => 'saint_pierre_and_miquelon.webp',
    'Saint Vincent and the Grenadines' => 'saint_vincent_and_grenadines.webp',
    'Samoa' => 'samoa.webp',
    'San Marino' => 'san_marino.webp',
    'Sao Tome and Principe' => 'sao_tome_and_principe.webp',
    'Saudi Arabia' => 'saudi_arabia.webp',
    'Senegal' => 'senegal.webp',
    'Serbia' => 'serbia.webp',
    'Seychelles' => 'seychelles.webp',
    'Sierra Leone' => 'sierra_leone.webp',
    'Singapore' => 'singapore.webp',
    'Sint Maarten' => 'sint_maarten.webp',
    'Slovakia' => 'slovakia.webp',
    'Slovenia' => 'slovenia.webp',
    'Solomon Islands' => 'solomon_islands.webp',
    'Somalia' => 'somalia.webp',
    'South Africa' => 'south_africa.webp',
    'South Georgia and the South Sandwich Islands' => 'south_georgia_and_south_sandwich_islands.webp',
    'South Sudan' => 'south_sudan.webp',
    'Spain' => 'spain.webp',
    'Sri Lanka' => 'sri_lanka.webp',
    'Sudan' => 'sudan.webp',
    'Suriname' => 'suriname.webp',
    'Svalbard and Jan Mayen' => 'svalbard_and_jan_mayen.webp',
    'Sweden' => 'sweden.webp',
    'Switzerland' => 'switzerland.webp',
    'Syrian Arab Republic' => 'syria.webp',
    'Taiwan' => 'taiwan.webp',
    'Tajikistan' => 'tajikistan.webp',
    'Tanzania' => 'tanzania.webp',
    'Thailand' => 'thailand.webp',
    'Timor-Leste' => 'timor_leste.webp',
    'Togo' => 'togo.webp',
    'Tokelau' => 'tokelau.webp',
    'Tonga' => 'tonga.webp',
    'Trinidad and Tobago' => 'trinidad_and_tobago.webp',
    'Tunisia' => 'tunisia.webp',
    'Turkey' => 'turkey.webp',
    'Turkmenistan' => 'turkmenistan.webp',
    'Turks and Caicos Islands' => 'turks_and_caicos_islands.webp',
    'Tuvalu' => 'tuvalu.webp',
    'Uganda' => 'uganda.webp',
    'Ukraine' => 'ukraine.webp',
    'United Arab Emirates' => 'uae.webp',
    'United Kingdom' => 'uk.webp',
    'United States' => 'usa.webp',
    'Uruguay' => 'uruguay.webp',
    'Uzbekistan' => 'uzbekistan.webp',
    'Vanuatu' => 'vanuatu.webp',
    'Venezuela' => 'venezuela.webp',
    'Vietnam' => 'vietnam.webp',
    'Wallis and Futuna' => 'wallis_and_futuna.webp',
    'Western Sahara' => 'western_sahara.webp',
    'Yemen' => 'yemen.webp',
    'Zambia' => 'zambia.webp',
    'Zimbabwe' => 'zimbabwe.webp',
];


$country = $row[3]; // Country name from the database
    $flagImage = isset($countryFlags[$country]) ? $countryFlags[$country] : 'default.jpg'; // Default flag if country is not in the array

    echo '
        <div class="container mt-5">
            <div class="reviews-section">
                <div class="review-item">
                    <div class="review-avatar">
                        <img src="' . $row[4] . '" alt="Reviewer" class="avatar-img">
                    </div>
                    <div class="review-content">
                        <div class="review-header">
                            <div class="review-info">
                                <h6 class="reviewer-name">' . $row[2] . '</h6>
                                <img src="assetss/reviews/flags/' . htmlspecialchars($flagImage) . '" alt="' . htmlspecialchars($country) . ' Flag" class="flag-img">
                                <p class="review-country">' . htmlspecialchars($country) . '</p>
                            </div>
                            <div class="review-stars">
                                <span class="star-filled">';

                          $count = (int)$row[6];
                          if ($count == 5) {
                              echo "★★★★★";
                          } elseif ($count == 4) {
                              echo "★★★★☆";
                          } elseif ($count == 3) {
                              echo "★★★☆☆";
                          } elseif ($count == 2) {
                              echo "★★☆☆☆";
                          } elseif ($count == 1) {
                              echo "★☆☆☆☆";
                          }

                          echo '              </span>
                            </div>
                        </div>
                        <div class="review-text-container">
                            <p class="review-text">' . $row[5] . '</p>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    ';
}


if ($type == "management_reviews") {
  
  $countryFlags = [
    'Afghanistan' => 'afghanistan.webp',
    'Albania' => 'albania.webp',
    'Algeria' => 'algeria.webp',
    'American Samoa' => 'american_samoa.webp',
    'Andorra' => 'andorra.webp',
    'Angola' => 'angola.webp',
    'Anguilla' => 'anguilla.webp',
    'Antarctica' => 'antarctica.webp',
    'Antigua and Barbuda' => 'antigua_and_barbuda.webp',
    'Argentina' => 'argentina.webp',
    'Armenia' => 'armenia.webp',
    'Aruba' => 'aruba.webp',
    'Australia' => 'australia.webp',
    'Austria' => 'austria.webp',
    'Azerbaijan' => 'azerbaijan.webp',
    'Bahamas' => 'bahamas.webp',
    'Bahrain' => 'bahrain.webp',
    'Bangladesh' => 'bangladesh.webp',
    'Barbados' => 'barbados.webp',
    'Belarus' => 'belarus.webp',
    'Belgium' => 'belgium.webp',
    'Belize' => 'belize.webp',
    'Benin' => 'benin.webp',
    'Bermuda' => 'bermuda.webp',
    'Bhutan' => 'bhutan.webp',
    'Bolivia' => 'bolivia.webp',
    'Bosnia and Herzegovina' => 'bosnia_and_herzegovina.webp',
    'Botswana' => 'botswana.webp',
    'Bouvet Island' => 'bouvet_island.webp',
    'Brazil' => 'brazil.webp',
    'British Indian Ocean Territory' => 'british_indian_ocean_territory.webp',
    'Brunei Darussalam' => 'brunei_darussalam.webp',
    'Bulgaria' => 'bulgaria.webp',
    'Burkina Faso' => 'burkina_faso.webp',
    'Burundi' => 'burundi.webp',
    'Cabo Verde' => 'cabo_verde.webp',
    'Cambodia' => 'cambodia.webp',
    'Cameroon' => 'cameroon.webp',
    'Canada' => 'canada.webp',
    'Cayman Islands' => 'cayman_islands.webp',
    'Central African Republic' => 'central_african_republic.webp',
    'Chad' => 'chad.webp',
    'Chile' => 'chile.webp',
    'China' => 'china.webp',
    'Christmas Island' => 'christmas_island.webp',
    'Cocos (Keeling) Islands' => 'cocos_islands.webp',
    'Colombia' => 'colombia.webp',
    'Comoros' => 'comoros.webp',
    'Congo, Democratic Republic of the' => 'congo_democratic_republic.webp',
    'Congo, Republic of the' => 'congo_republic.webp',
    'Cook Islands' => 'cook_islands.webp',
    'Costa Rica' => 'costa_rica.webp',
    'Croatia' => 'croatia.webp',
    'Cuba' => 'cuba.webp',
    'Curaçao' => 'curacao.webp',
    'Cyprus' => 'cyprus.webp',
    'Czechia' => 'czechia.webp',
    'Denmark' => 'denmark.webp',
    'Djibouti' => 'djibouti.webp',
    'Dominica' => 'dominica.webp',
    'Dominican Republic' => 'dominican_republic.webp',
    'Ecuador' => 'ecuador.webp',
    'Egypt' => 'egypt.webp',
    'El Salvador' => 'el_salvador.webp',
    'Equatorial Guinea' => 'equatorial_guinea.webp',
    'Eritrea' => 'eritrea.webp',
    'Estonia' => 'estonia.webp',
    'Eswatini' => 'eswatini.webp',
    'Ethiopia' => 'ethiopia.webp',
    'Fiji' => 'fiji.webp',
    'Finland' => 'finland.webp',
    'France' => 'france.webp',
    'French Guiana' => 'french_guiana.webp',
    'French Polynesia' => 'french_polynesia.webp',
    'French Southern Territories' => 'french_southern_territories.webp',
    'Gabon' => 'gabon.webp',
    'Gambia' => 'gambia.webp',
    'Georgia' => 'georgia.webp',
    'Germany' => 'germany.webp',
    'Ghana' => 'ghana.webp',
    'Gibraltar' => 'gibraltar.webp',
    'Greece' => 'greece.webp',
    'Greenland' => 'greenland.webp',
    'Grenada' => 'grenada.webp',
    'Guadeloupe' => 'guadeloupe.webp',
    'Guam' => 'guam.webp',
    'Guatemala' => 'guatemala.webp',
    'Guernsey' => 'guernsey.webp',
    'Guinea' => 'guinea.webp',
    'Guinea-Bissau' => 'guinea_bissau.webp',
    'Guyana' => 'guyana.webp',
    'Haiti' => 'haiti.webp',
    'Heard Island and McDonald Islands' => 'heard_mcdonald_islands.webp',
    'Holy See' => 'holy_see.webp',
    'Honduras' => 'honduras.webp',
    'Hong Kong' => 'hong_kong.webp',
    'Hungary' => 'hungary.webp',
    'Iceland' => 'iceland.webp',
    'India' => 'india.webp',
    'Indonesia' => 'indonesia.webp',
    'Iran' => 'iran.webp',
    'Iraq' => 'iraq.webp',
    'Ireland' => 'ireland.webp',
    'Isle of Man' => 'isle_of_man.webp',
    'Israel' => 'israel.webp',
    'Italy' => 'italy.webp',
    'Ivory Coast' => 'ivory_coast.webp',
    'Jamaica' => 'jamaica.webp',
    'Japan' => 'japan.webp',
    'Jersey' => 'jersey.webp',
    'Jordan' => 'jordan.webp',
    'Kazakhstan' => 'kazakhstan.webp',
    'Kenya' => 'kenya.webp',
    'Kiribati' => 'kiribati.webp',
    'Korea (North)' => 'north_korea.webp',
    'Korea (South)' => 'south_korea.webp',
    'Kuwait' => 'kuwait.webp',
    'Kyrgyzstan' => 'kyrgyzstan.webp',
    'Laos' => 'laos.webp',
    'Latvia' => 'latvia.webp',
    'Lebanon' => 'lebanon.webp',
    'Lesotho' => 'lesotho.webp',
    'Liberia' => 'liberia.webp',
    'Libya' => 'libya.webp',
    'Liechtenstein' => 'liechtenstein.webp',
    'Lithuania' => 'lithuania.webp',
    'Luxembourg' => 'luxembourg.webp',
    'Macao' => 'macao.webp',
    'Madagascar' => 'madagascar.webp',
    'Malawi' => 'malawi.webp',
    'Malaysia' => 'malaysia.webp',
    'Maldives' => 'maldives.webp',
    'Mali' => 'mali.webp',
    'Malta' => 'malta.webp',
    'Marshall Islands' => 'marshall_islands.webp',
    'Martinique' => 'martinique.webp',
    'Mauritania' => 'mauritania.webp',
    'Mauritius' => 'mauritius.webp',
    'Mayotte' => 'mayotte.webp',
    'Mexico' => 'mexico.webp',
    'Micronesia' => 'micronesia.webp',
    'Moldova' => 'moldova.webp',
    'Monaco' => 'monaco.webp',
    'Mongolia' => 'mongolia.webp',
    'Montenegro' => 'montenegro.webp',
    'Montserrat' => 'montserrat.webp',
    'Morocco' => 'morocco.webp',
    'Mozambique' => 'mozambique.webp',
    'Myanmar' => 'myanmar.webp',
    'Namibia' => 'namibia.webp',
    'Nauru' => 'nauru.webp',
    'Nepal' => 'nepal.webp',
    'Netherlands' => 'netherlands.webp',
    'New Caledonia' => 'new_caledonia.webp',
    'New Zealand' => 'new_zealand.webp',
    'Nicaragua' => 'nicaragua.webp',
    'Niger' => 'niger.webp',
    'Nigeria' => 'nigeria.webp',
    'Niue' => 'niue.webp',
    'Norfolk Island' => 'norfolk_island.webp',
    'Northern Mariana Islands' => 'northern_mariana_islands.webp',
    'Norway' => 'norway.webp',
    'Oman' => 'oman.webp',
    'Pakistan' => 'pakistan.webp',
    'Palau' => 'palau.webp',
    'Palestine' => 'palestine.webp',
    'Panama' => 'panama.webp',
    'Papua New Guinea' => 'papua_new_guinea.webp',
    'Paraguay' => 'paraguay.webp',
    'Peru' => 'peru.webp',
    'Philippines' => 'philippines.webp',
    'Pitcairn' => 'pitcairn.webp',
    'Poland' => 'poland.webp',
    'Portugal' => 'portugal.webp',
    'Puerto Rico' => 'puerto_rico.webp',
    'Qatar' => 'qatar.webp',
    'Reunion' => 'reunion.webp',
    'Romania' => 'romania.webp',
    'Russian Federation' => 'russia.webp',
    'Rwanda' => 'rwanda.webp',
    'Saint Barthélemy' => 'saint_barthelemy.webp',
    'Saint Helena, Ascension and Tristan da Cunha' => 'saint_helena.webp',
    'Saint Kitts and Nevis' => 'saint_kitts_and_nevis.webp',
    'Saint Lucia' => 'saint_lucia.webp',
    'Saint Martin' => 'saint_martin.webp',
    'Saint Pierre and Miquelon' => 'saint_pierre_and_miquelon.webp',
    'Saint Vincent and the Grenadines' => 'saint_vincent_and_grenadines.webp',
    'Samoa' => 'samoa.webp',
    'San Marino' => 'san_marino.webp',
    'Sao Tome and Principe' => 'sao_tome_and_principe.webp',
    'Saudi Arabia' => 'saudi_arabia.webp',
    'Senegal' => 'senegal.webp',
    'Serbia' => 'serbia.webp',
    'Seychelles' => 'seychelles.webp',
    'Sierra Leone' => 'sierra_leone.webp',
    'Singapore' => 'singapore.webp',
    'Sint Maarten' => 'sint_maarten.webp',
    'Slovakia' => 'slovakia.webp',
    'Slovenia' => 'slovenia.webp',
    'Solomon Islands' => 'solomon_islands.webp',
    'Somalia' => 'somalia.webp',
    'South Africa' => 'south_africa.webp',
    'South Georgia and the South Sandwich Islands' => 'south_georgia_and_south_sandwich_islands.webp',
    'South Sudan' => 'south_sudan.webp',
    'Spain' => 'spain.webp',
    'Sri Lanka' => 'sri_lanka.webp',
    'Sudan' => 'sudan.webp',
    'Suriname' => 'suriname.webp',
    'Svalbard and Jan Mayen' => 'svalbard_and_jan_mayen.webp',
    'Sweden' => 'sweden.webp',
    'Switzerland' => 'switzerland.webp',
    'Syrian Arab Republic' => 'syria.webp',
    'Taiwan' => 'taiwan.webp',
    'Tajikistan' => 'tajikistan.webp',
    'Tanzania' => 'tanzania.webp',
    'Thailand' => 'thailand.webp',
    'Timor-Leste' => 'timor_leste.webp',
    'Togo' => 'togo.webp',
    'Tokelau' => 'tokelau.webp',
    'Tonga' => 'tonga.webp',
    'Trinidad and Tobago' => 'trinidad_and_tobago.webp',
    'Tunisia' => 'tunisia.webp',
    'Turkey' => 'turkey.webp',
    'Turkmenistan' => 'turkmenistan.webp',
    'Turks and Caicos Islands' => 'turks_and_caicos_islands.webp',
    'Tuvalu' => 'tuvalu.webp',
    'Uganda' => 'uganda.webp',
    'Ukraine' => 'ukraine.webp',
    'United Arab Emirates' => 'uae.webp',
    'United Kingdom' => 'uk.webp',
    'United States' => 'usa.webp',
    'Uruguay' => 'uruguay.webp',
    'Uzbekistan' => 'uzbekistan.webp',
    'Vanuatu' => 'vanuatu.webp',
    'Venezuela' => 'venezuela.webp',
    'Vietnam' => 'vietnam.webp',
    'Wallis and Futuna' => 'wallis_and_futuna.webp',
    'Western Sahara' => 'western_sahara.webp',
    'Yemen' => 'yemen.webp',
    'Zambia' => 'zambia.webp',
    'Zimbabwe' => 'zimbabwe.webp',
];

$country = $row[3]; // Country name from the database
    $flagImage = isset($countryFlags[$country]) ? $countryFlags[$country] : 'default.jpg'; // Default flag if country is not in the array

    echo '
        <div class="container mt-5">
            <div class="reviews-section">
                <div class="review-item">
                    <div class="review-avatar">
                        <img src="' . $row[4] . '" alt="Reviewer" class="avatar-img">
                    </div>
                    <div class="review-content">
                        <div class="review-header">
                            <div class="review-info">
                                <h6 class="reviewer-name">' . $row[2] . '</h6>
                                <img src="assetss/reviews/flags/' . htmlspecialchars($flagImage) . '" alt="' . htmlspecialchars($country) . ' Flag" class="flag-img">
                                <p class="review-country">' . htmlspecialchars($country) . '</p>
                            </div>
                            <div class="review-stars">
                                <span class="star-filled">';

                          $count = (int)$row[6];
                          if ($count == 5) {
                              echo "★★★★★";
                          } elseif ($count == 4) {
                              echo "★★★★☆";
                          } elseif ($count == 3) {
                              echo "★★★☆☆";
                          } elseif ($count == 2) {
                              echo "★★☆☆☆";
                          } elseif ($count == 1) {
                              echo "★☆☆☆☆";
                          }

                          echo '              </span>
                            </div>
                        </div>
                        <div class="review-text-container">
                            <p class="review-text">' . $row[5] . '</p>
                            
                      </div>
                    </div>
                </div>
            </div>
        </div>
    ';
}


if ($type == "E-Commerce_reviews") {
  
  $countryFlags = [
    'Afghanistan' => 'afghanistan.webp',
    'Albania' => 'albania.webp',
    'Algeria' => 'algeria.webp',
    'American Samoa' => 'american_samoa.webp',
    'Andorra' => 'andorra.webp',
    'Angola' => 'angola.webp',
    'Anguilla' => 'anguilla.webp',
    'Antarctica' => 'antarctica.webp',
    'Antigua and Barbuda' => 'antigua_and_barbuda.webp',
    'Argentina' => 'argentina.webp',
    'Armenia' => 'armenia.webp',
    'Aruba' => 'aruba.webp',
    'Australia' => 'australia.webp',
    'Austria' => 'austria.webp',
    'Azerbaijan' => 'azerbaijan.webp',
    'Bahamas' => 'bahamas.webp',
    'Bahrain' => 'bahrain.webp',
    'Bangladesh' => 'bangladesh.webp',
    'Barbados' => 'barbados.webp',
    'Belarus' => 'belarus.webp',
    'Belgium' => 'belgium.webp',
    'Belize' => 'belize.webp',
    'Benin' => 'benin.webp',
    'Bermuda' => 'bermuda.webp',
    'Bhutan' => 'bhutan.webp',
    'Bolivia' => 'bolivia.webp',
    'Bosnia and Herzegovina' => 'bosnia_and_herzegovina.webp',
    'Botswana' => 'botswana.webp',
    'Bouvet Island' => 'bouvet_island.webp',
    'Brazil' => 'brazil.webp',
    'British Indian Ocean Territory' => 'british_indian_ocean_territory.webp',
    'Brunei Darussalam' => 'brunei_darussalam.webp',
    'Bulgaria' => 'bulgaria.webp',
    'Burkina Faso' => 'burkina_faso.webp',
    'Burundi' => 'burundi.webp',
    'Cabo Verde' => 'cabo_verde.webp',
    'Cambodia' => 'cambodia.webp',
    'Cameroon' => 'cameroon.webp',
    'Canada' => 'canada.webp',
    'Cayman Islands' => 'cayman_islands.webp',
    'Central African Republic' => 'central_african_republic.webp',
    'Chad' => 'chad.webp',
    'Chile' => 'chile.webp',
    'China' => 'china.webp',
    'Christmas Island' => 'christmas_island.webp',
    'Cocos (Keeling) Islands' => 'cocos_islands.webp',
    'Colombia' => 'colombia.webp',
    'Comoros' => 'comoros.webp',
    'Congo, Democratic Republic of the' => 'congo_democratic_republic.webp',
    'Congo, Republic of the' => 'congo_republic.webp',
    'Cook Islands' => 'cook_islands.webp',
    'Costa Rica' => 'costa_rica.webp',
    'Croatia' => 'croatia.webp',
    'Cuba' => 'cuba.webp',
    'Curaçao' => 'curacao.webp',
    'Cyprus' => 'cyprus.webp',
    'Czechia' => 'czechia.webp',
    'Denmark' => 'denmark.webp',
    'Djibouti' => 'djibouti.webp',
    'Dominica' => 'dominica.webp',
    'Dominican Republic' => 'dominican_republic.webp',
    'Ecuador' => 'ecuador.webp',
    'Egypt' => 'egypt.webp',
    'El Salvador' => 'el_salvador.webp',
    'Equatorial Guinea' => 'equatorial_guinea.webp',
    'Eritrea' => 'eritrea.webp',
    'Estonia' => 'estonia.webp',
    'Eswatini' => 'eswatini.webp',
    'Ethiopia' => 'ethiopia.webp',
    'Fiji' => 'fiji.webp',
    'Finland' => 'finland.webp',
    'France' => 'france.webp',
    'French Guiana' => 'french_guiana.webp',
    'French Polynesia' => 'french_polynesia.webp',
    'French Southern Territories' => 'french_southern_territories.webp',
    'Gabon' => 'gabon.webp',
    'Gambia' => 'gambia.webp',
    'Georgia' => 'georgia.webp',
    'Germany' => 'germany.webp',
    'Ghana' => 'ghana.webp',
    'Gibraltar' => 'gibraltar.webp',
    'Greece' => 'greece.webp',
    'Greenland' => 'greenland.webp',
    'Grenada' => 'grenada.webp',
    'Guadeloupe' => 'guadeloupe.webp',
    'Guam' => 'guam.webp',
    'Guatemala' => 'guatemala.webp',
    'Guernsey' => 'guernsey.webp',
    'Guinea' => 'guinea.webp',
    'Guinea-Bissau' => 'guinea_bissau.webp',
    'Guyana' => 'guyana.webp',
    'Haiti' => 'haiti.webp',
    'Heard Island and McDonald Islands' => 'heard_mcdonald_islands.webp',
    'Holy See' => 'holy_see.webp',
    'Honduras' => 'honduras.webp',
    'Hong Kong' => 'hong_kong.webp',
    'Hungary' => 'hungary.webp',
    'Iceland' => 'iceland.webp',
    'India' => 'india.webp',
    'Indonesia' => 'indonesia.webp',
    'Iran' => 'iran.webp',
    'Iraq' => 'iraq.webp',
    'Ireland' => 'ireland.webp',
    'Isle of Man' => 'isle_of_man.webp',
    'Israel' => 'israel.webp',
    'Italy' => 'italy.webp',
    'Ivory Coast' => 'ivory_coast.webp',
    'Jamaica' => 'jamaica.webp',
    'Japan' => 'japan.webp',
    'Jersey' => 'jersey.webp',
    'Jordan' => 'jordan.webp',
    'Kazakhstan' => 'kazakhstan.webp',
    'Kenya' => 'kenya.webp',
    'Kiribati' => 'kiribati.webp',
    'Korea (North)' => 'north_korea.webp',
    'Korea (South)' => 'south_korea.webp',
    'Kuwait' => 'kuwait.webp',
    'Kyrgyzstan' => 'kyrgyzstan.webp',
    'Laos' => 'laos.webp',
    'Latvia' => 'latvia.webp',
    'Lebanon' => 'lebanon.webp',
    'Lesotho' => 'lesotho.webp',
    'Liberia' => 'liberia.webp',
    'Libya' => 'libya.webp',
    'Liechtenstein' => 'liechtenstein.webp',
    'Lithuania' => 'lithuania.webp',
    'Luxembourg' => 'luxembourg.webp',
    'Macao' => 'macao.webp',
    'Madagascar' => 'madagascar.webp',
    'Malawi' => 'malawi.webp',
    'Malaysia' => 'malaysia.webp',
    'Maldives' => 'maldives.webp',
    'Mali' => 'mali.webp',
    'Malta' => 'malta.webp',
    'Marshall Islands' => 'marshall_islands.webp',
    'Martinique' => 'martinique.webp',
    'Mauritania' => 'mauritania.webp',
    'Mauritius' => 'mauritius.webp',
    'Mayotte' => 'mayotte.webp',
    'Mexico' => 'mexico.webp',
    'Micronesia' => 'micronesia.webp',
    'Moldova' => 'moldova.webp',
    'Monaco' => 'monaco.webp',
    'Mongolia' => 'mongolia.webp',
    'Montenegro' => 'montenegro.webp',
    'Montserrat' => 'montserrat.webp',
    'Morocco' => 'morocco.webp',
    'Mozambique' => 'mozambique.webp',
    'Myanmar' => 'myanmar.webp',
    'Namibia' => 'namibia.webp',
    'Nauru' => 'nauru.webp',
    'Nepal' => 'nepal.webp',
    'Netherlands' => 'netherlands.webp',
    'New Caledonia' => 'new_caledonia.webp',
    'New Zealand' => 'new_zealand.webp',
    'Nicaragua' => 'nicaragua.webp',
    'Niger' => 'niger.webp',
    'Nigeria' => 'nigeria.webp',
    'Niue' => 'niue.webp',
    'Norfolk Island' => 'norfolk_island.webp',
    'Northern Mariana Islands' => 'northern_mariana_islands.webp',
    'Norway' => 'norway.webp',
    'Oman' => 'oman.webp',
    'Pakistan' => 'pakistan.webp',
    'Palau' => 'palau.webp',
    'Palestine' => 'palestine.webp',
    'Panama' => 'panama.webp',
    'Papua New Guinea' => 'papua_new_guinea.webp',
    'Paraguay' => 'paraguay.webp',
    'Peru' => 'peru.webp',
    'Philippines' => 'philippines.webp',
    'Pitcairn' => 'pitcairn.webp',
    'Poland' => 'poland.webp',
    'Portugal' => 'portugal.webp',
    'Puerto Rico' => 'puerto_rico.webp',
    'Qatar' => 'qatar.webp',
    'Reunion' => 'reunion.webp',
    'Romania' => 'romania.webp',
    'Russian Federation' => 'russia.webp',
    'Rwanda' => 'rwanda.webp',
    'Saint Barthélemy' => 'saint_barthelemy.webp',
    'Saint Helena, Ascension and Tristan da Cunha' => 'saint_helena.webp',
    'Saint Kitts and Nevis' => 'saint_kitts_and_nevis.webp',
    'Saint Lucia' => 'saint_lucia.webp',
    'Saint Martin' => 'saint_martin.webp',
    'Saint Pierre and Miquelon' => 'saint_pierre_and_miquelon.webp',
    'Saint Vincent and the Grenadines' => 'saint_vincent_and_grenadines.webp',
    'Samoa' => 'samoa.webp',
    'San Marino' => 'san_marino.webp',
    'Sao Tome and Principe' => 'sao_tome_and_principe.webp',
    'Saudi Arabia' => 'saudi_arabia.webp',
    'Senegal' => 'senegal.webp',
    'Serbia' => 'serbia.webp',
    'Seychelles' => 'seychelles.webp',
    'Sierra Leone' => 'sierra_leone.webp',
    'Singapore' => 'singapore.webp',
    'Sint Maarten' => 'sint_maarten.webp',
    'Slovakia' => 'slovakia.webp',
    'Slovenia' => 'slovenia.webp',
    'Solomon Islands' => 'solomon_islands.webp',
    'Somalia' => 'somalia.webp',
    'South Africa' => 'south_africa.webp',
    'South Georgia and the South Sandwich Islands' => 'south_georgia_and_south_sandwich_islands.webp',
    'South Sudan' => 'south_sudan.webp',
    'Spain' => 'spain.webp',
    'Sri Lanka' => 'sri_lanka.webp',
    'Sudan' => 'sudan.webp',
    'Suriname' => 'suriname.webp',
    'Svalbard and Jan Mayen' => 'svalbard_and_jan_mayen.webp',
    'Sweden' => 'sweden.webp',
    'Switzerland' => 'switzerland.webp',
    'Syrian Arab Republic' => 'syria.webp',
    'Taiwan' => 'taiwan.webp',
    'Tajikistan' => 'tajikistan.webp',
    'Tanzania' => 'tanzania.webp',
    'Thailand' => 'thailand.webp',
    'Timor-Leste' => 'timor_leste.webp',
    'Togo' => 'togo.webp',
    'Tokelau' => 'tokelau.webp',
    'Tonga' => 'tonga.webp',
    'Trinidad and Tobago' => 'trinidad_and_tobago.webp',
    'Tunisia' => 'tunisia.webp',
    'Turkey' => 'turkey.webp',
    'Turkmenistan' => 'turkmenistan.webp',
    'Turks and Caicos Islands' => 'turks_and_caicos_islands.webp',
    'Tuvalu' => 'tuvalu.webp',
    'Uganda' => 'uganda.webp',
    'Ukraine' => 'ukraine.webp',
    'United Arab Emirates' => 'uae.webp',
    'United Kingdom' => 'uk.webp',
    'United States' => 'usa.webp',
    'Uruguay' => 'uruguay.webp',
    'Uzbekistan' => 'uzbekistan.webp',
    'Vanuatu' => 'vanuatu.webp',
    'Venezuela' => 'venezuela.webp',
    'Vietnam' => 'vietnam.webp',
    'Wallis and Futuna' => 'wallis_and_futuna.webp',
    'Western Sahara' => 'western_sahara.webp',
    'Yemen' => 'yemen.webp',
    'Zambia' => 'zambia.webp',
    'Zimbabwe' => 'zimbabwe.webp',
];
$country = $row[3]; // Country name from the database
    $flagImage = isset($countryFlags[$country]) ? $coun