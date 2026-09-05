<?php
session_start();

require_once(__DIR__ . "/database.php");
require_once(__DIR__ . "/mailer.php");
require_once(__DIR__ . "/form.php");
require_once(dirname(__DIR__) . "/seo_helpers.php");

$form = new Form;

class Session
{
   var $username;
   var $userid;
   var $userlevel;
   var $time;
   var $logged_in;
   var $userinfo = array();
   var $url;
   var $referrer;

   function __construct()
   {
      date_default_timezone_set('Asia/Karachi');
      $this->time = time();
      $this->startSession();
   }

   function startSession()
   {
      global $database;
      
      $this->logged_in = $this->checkLogin();

      if (!$this->logged_in) {
         $this->username = $_SESSION['username'] = GUEST_NAME;
         $this->userlevel = GUEST_LEVEL;
         $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
         $database->addActiveGuest($ip, $this->time);
      } else {
         $database->addActiveUser($this->username, $this->time);
      }

      $database->removeInactiveUsers();
      $database->removeInactiveGuests();

      if (isset($_SESSION['url'])) {
         $this->referrer = $_SESSION['url'];
      } else {
         $this->referrer = "/";
      }

      $this->url = $_SESSION['url'] = $_SERVER['PHP_SELF'];
   }

   function checkLogin()
   {
      global $database;
      $current_time = time();

      if (isset($_COOKIE['cookname']) && isset($_COOKIE['cookid'])) {
         $_SESSION['username'] = $_COOKIE['cookname'];
         $_SESSION['userid'] = $_COOKIE['cookid'];
         $_SESSION['login_time'] = $_SESSION['login_time'] ?? $current_time;
      }

      if (isset($_SESSION['username']) && isset($_SESSION['userid']) && $_SESSION['username'] != GUEST_NAME) {
         if ($database->confirmUserID($_SESSION['username'], $_SESSION['userid']) != 0) {
            $this->logout();
            return false;
         }

         $this->userinfo = $database->getUserInfo($_SESSION['username']);
         $this->username = $this->userinfo['username'];
         $this->userid = $_SESSION['userid'];
         $this->userlevel = $this->userinfo['userlevel'];
         return true;
      }

      return false;
   }

   function login($subuser, $subpass)
   {
      global $database, $form;

      if (!$subuser || trim($subuser) === '') {
         $form->setError("user", "* Username or Reg No not entered");
      }
      if (!$subpass) {
         $form->setError("pass", "* Password not entered");
      }
      if ($form->num_errors > 0) return false;

      $result = $database->confirmUserPass($subuser, $subpass);

      if ($result == 1) {
         $form->setError("user", "* User not found");
         return false;
      }
      if ($result == 2) {
         $form->setError("pass", "* Invalid password");
         return false;
      }

      $this->userinfo = $database->loginsession($subuser);
      $this->username = $_SESSION['username'] = $this->userinfo['username'];
      $this->userid = $_SESSION['userid'] = $this->generateRandID();
      $this->userlevel = $this->userinfo['userlevel'];

      $database->updateUserField($this->username, "userid", $this->userid);
      $database->updateUserField($this->username, "last_login", date("Y-m-d H:i:s"));
      $database->addActiveUser($this->username, $this->time);
      $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
      $database->removeActiveGuest($ip);

      $_SESSION['login_time'] = time();

      setcookie("cookname", $this->username, time() + COOKIE_EXPIRE, COOKIE_PATH);
      setcookie("cookid", $this->userid, time() + COOKIE_EXPIRE, COOKIE_PATH);

      return true;
   }

   function logout()
   {
      global $database;
      if (isset($_COOKIE['cookname']) && isset($_COOKIE['cookid'])) {
         setcookie("cookname", "", time() - COOKIE_EXPIRE, COOKIE_PATH);
         setcookie("cookid", "", time() - COOKIE_EXPIRE, COOKIE_PATH);
      }

      unset($_SESSION['username']);
      unset($_SESSION['userid']);
      session_unset();
      session_destroy();

      $this->logged_in = false;
      $database->removeActiveUser($this->username);
      $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
      $database->addActiveGuest($ip, $this->time);
      
      $this->username = GUEST_NAME;
      $this->userlevel = GUEST_LEVEL;
   }

   function generateRandID()
   {
      return md5($this->generateRandStr(16));
   }

   function generateRandStr($length)
   {
      $randstr = "";
      for ($i = 0; $i < $length; $i++) {
         $randnum = mt_rand(0, 61);
         if ($randnum < 10) {
            $randstr .= chr($randnum + 48);
         } else if ($randnum < 36) {
            $randstr .= chr($randnum + 55);
         } else {
            $randstr .= chr($randnum + 61);
         }
      }
      return $randstr;
   }

   function addcategory($category)
   {
      global $database, $form;
      if (empty($category)) {
         $form->setError("category", "* Category is required");
         return 1;
      }
      return $database->addcategory($category) ? 0 : 2;
   }

   function edit_category($id, $category)
   {
      global $database;
      return $database->edit_category($id, $category);
   }

   function createSlug($string)
   {
      return bk_slugify($string);
   }

   function addpost($data, $thumbnail)
   {
      global $database, $form;
      if (empty($data['title'])) $form->setError("title", "* Title is required");
      if (empty($data['category_id'])) $form->setError("category_id", "* Category is required");
      if (empty($data['content'])) $form->setError("content", "* Content is required");

      $featured_image = "";
      if ($thumbnail && $thumbnail['error'] === UPLOAD_ERR_OK) {
         $thumbnail_name = time() . "_" . $thumbnail['name'];
         $target_path = "images/posts/" . $thumbnail_name;
         if (!is_dir("images/posts")) mkdir("images/posts", 0777, true);
         if (move_uploaded_file($thumbnail['tmp_name'], $target_path)) {
            $featured_image = $thumbnail_name;
         }
      }

      // Draft fallback logic: Force draft if mandatory fields are missing
      $status = $data['status'];
      if ($status == 'Published') {
         if (empty($data['title']) || empty($data['category_id']) || empty($data['content']) || empty($featured_image)) {
            $status = 'Draft';
         }
      }

      if ($form->num_errors > 0) return 1;

      $post_data = [
         'title' => $data['title'],
         'slug' => !empty($data['slug']) ? $this->createSlug($data['slug']) : $this->createSlug($data['title']),
         'content' => $data['content'],
         'excerpt' => $data['excerpt'],
         'category_id' => $data['category_id'],
         'author' => $this->userinfo['registration_no'],
         'status' => $status,
         'featured_image' => $featured_image,
         'meta_title' => $data['meta_title'],
         'meta_description' => $data['meta_description'],
         'tags' => $data['tags'],
         'is_featured' => isset($data['is_featured']) ? 1 : 0
      ];

      return $database->addpost($post_data) ? 0 : 2;
   }

   function edit_post($id, $data, $thumbnail)
   {
      global $database, $form;
      $id = (int)$id;
      $post = mysqli_fetch_assoc($database->query("SELECT author FROM posts WHERE id = $id"));
      if (!$post) return 2;
      
      // Permission Check: Super Admin (4+) or Author can edit
      if ($this->userlevel < 4 && $post['author'] != $this->userinfo['registration_no']) {
         return 2;
      }

      if (empty($data['title'])) $form->setError("title", "* Title is required");
      
      $post_data = [
         'title' => $data['title'],
         'slug' => !empty($data['slug']) ? $this->createSlug($data['slug']) : $this->createSlug($data['title']),
         'content' => $data['content'],
         'excerpt' => $data['excerpt'],
         'category_id' => $data['category_id'],
         'status' => $data['status'],
         'meta_title' => $data['meta_title'],
         'meta_description' => $data['meta_description'],
         'tags' => $data['tags'],
         'is_featured' => isset($data['is_featured']) ? 1 : 0
      ];

      $featured_image = "";
      if ($thumbnail && $thumbnail['error'] === UPLOAD_ERR_OK) {
         $thumbnail_name = time() . "_" . $thumbnail['name'];
         $target_path = "images/posts/" . $thumbnail_name;
         if (move_uploaded_file($thumbnail['tmp_name'], $target_path)) {
            $featured_image = $thumbnail_name;
            $post_data['featured_image'] = $thumbnail_name;
         }
      }

      // Check existing image if no new one uploaded
      if (empty($featured_image)) {
         $existing = $database->query("SELECT featured_image FROM posts WHERE id = $id");
         if ($row = mysqli_fetch_assoc($existing)) {
            $featured_image = $row['featured_image'];
         }
      }

      // Draft fallback logic: Force draft if mandatory fields are missing
      if ($data['status'] == 'Published') {
         if (empty($data['title']) || empty($data['category_id']) || empty($data['content']) || empty($featured_image)) {
            $post_data['status'] = 'Draft';
         }
      }

      if ($form->num_errors > 0) return 1;
      return $database->updatepost($id, $post_data) ? 0 : 2;
   }

   function addadmin($name, $mobile_no, $email, $level = 1)
   {
      global $database, $form;
      if (empty($name)) $form->setError("name", "* Name is required");
      if (empty($mobile_no)) $form->setError("mobile_no", "* Mobile number is required");
      if (empty($email)) $form->setError("email", "* Email is required");

      if ($form->num_errors > 0) return 1;
      return $database->addadmin($name, $mobile_no, $email, $level) ? 0 : 2;
   }

   function edit_admin($username, $name, $mobile_no, $email, $userlevel)
   {
      global $database;
      $database->updateUserField($username, 'display_name', $name);
      $database->updateUserField($username, 'phone', $mobile_no);
      $database->updateUserField($username, 'email', $email);
      $database->updateUserField($username, 'userlevel', $userlevel);
      return 0;
   }

   function delete_post($id) { 
      global $database; 
      $id = (int)$id;
      $post = mysqli_fetch_assoc($database->query("SELECT author FROM posts WHERE id = $id"));
      if (!$post) return false;
      if ($this->userlevel < 4 && $post['author'] != $this->userinfo['registration_no']) return false;
      return $database->delete_post($id); 
   }
   function delete_category($id) { global $database; return $database->delete_category($id); }
   function delete_user($username) { global $database; return $database->delete_user($username); }

   function change_accountdetails($username, $displayname, $email, $phone, $profile_image = null)
   {
      global $database, $form;
      if (empty($displayname)) $form->setError("displayname", "* Display name is required");
      if (empty($email)) $form->setError("email", "* Email is required");

      if ($form->num_errors > 0) return 1;

      $database->updateUserField($username, 'display_name', $displayname);
      $database->updateUserField($username, 'email', $email);
      $database->updateUserField($username, 'phone', $phone);

      if ($profile_image && $profile_image['error'] === UPLOAD_ERR_OK) {
         $img_name = time() . "_" . $profile_image['name'];
         $target = "images/profiles/" . $img_name;
         if (!is_dir("images/profiles")) mkdir("images/profiles", 0777, true);
         if (move_uploaded_file($profile_image['tmp_name'], $target)) {
            $database->updateUserField($username, 'profile_image', $img_name);
         }
      }
      
      // Update session userinfo
      $this->userinfo = $database->getUserInfo($username);
      return 0;
   }

   function changepassword($username, $curpass, $newpass)
   {
      global $database, $form;
      if (empty($curpass)) $form->setError("curpass", "* Current password is required");
      if (empty($newpass)) $form->setError("newpass", "* New password is required");

      if ($form->num_errors > 0) return 1;

      if ($database->confirmUserPass($username, $curpass) != 0) {
         $form->setError("curpass", "* Incorrect current password");
         return 1;
      }

      $hashed_pass = password_hash($newpass, PASSWORD_DEFAULT);
      return $database->updateUserField($username, 'password', $hashed_pass) ? 0 : 2;
   }
}


$session = new Session;
?>
