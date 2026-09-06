<?php

include("include/classes/session.php");

class Process
{

   function __construct()
   {
      global $session;

      if (isset($_POST['sublogin'])) {
         $this->procLogin();
      } else if (isset($_POST['add_admin'])) {
         $this->proadd_admin();
      } else if (isset($_POST['edit_admin'])) {
         $this->proedit_admin();
      } else if (isset($_POST['addcategory'])) {
         $this->proadd_category();
      } else if (isset($_POST['edit_category'])) {
         $this->proedit_category();
      } else if (isset($_POST['add_post'])) {
         $this->proadd_post();
      } else if (isset($_POST['edit_post'])) {
         $this->proedit_post();
      } else if (isset($_POST['add_comment'])) {
         $this->proadd_comment();
      } else if (isset($_POST['uploadimage'])) {
         $this->uploadimage();
      } else if (isset($_POST['changeaccountdetails'])) {
         $this->prochange_accountdetails();
      } else if (isset($_POST['changepassword'])) {
         $this->changepassword();
      } else if (isset($_GET['logout'])) {
         $this->procLogout();
      } else if (isset($_GET['del_post'])) {
         $this->prodelete_post();
      } else if (isset($_GET['del_cat'])) {
         $this->prodelete_category();
      } else if (isset($_GET['del_user'])) {
         $this->prodelete_user();
      } else if ($session->logged_in) {
         // Default logged in behavior if needed
      } else {
         header("Location: login.php");
      }
   }

   function procLogin()
   {
      global $session, $form;
      $retval = $session->login($_POST['user'], $_POST['pass']);

      if ($retval) {
         header("Location: dashboard.php");
      } else {
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: login.php");
      }
   }


   function procLogout()
   {
      global $session;
      $session->logout();
      header("Location: index.php");
   }




   function proadd_category()
   {
      global $session, $form;

      $retval = $session->addcategory($_POST['category']);

      if ($retval == 0) {
         header("Location: admin-categories.php?msg=success");
      } else if ($retval == 1) {
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: admin-categories.php?msg=error");
      } else if ($retval == 2) {
         header("Location: admin-categories.php?msg=db_error");
      }
   }

   function proedit_category()
   {
      global $session;
      if ($session->edit_category($_POST['id'], $_POST['category'])) {
         header("Location: admin-categories.php?msg=success");
      } else {
         header("Location: admin-categories.php?msg=error");
      }
   }

   function proadd_admin()
   {
      global $session, $form;

      if (ALL_LOWERCASE) {
         $_POST['name'] = strtolower($_POST['name']);
      }
      $retval = $session->addadmin($_POST['name'], $_POST['mobile_no'], $_POST['email'], $_POST['userlevel'] ?? 1);

      if ($retval == 0) {
         header("Location: admin-users.php?msg=success");
      } else if ($retval == 1) {
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: admin-users.php?msg=error");
      } else if ($retval == 2) {
         header("Location: admin-users.php?msg=db_error");
      }
   }

   function proedit_admin()
   {
      global $session, $form;
      $retval = $session->edit_admin($_POST['username'], $_POST['name'], $_POST['mobile_no'], $_POST['email'], $_POST['userlevel']);

      if ($retval == 0) {
         header("Location: admin-users.php?msg=success");
      } else {
         header("Location: admin-users.php?msg=error");
      }
   }

   function proadd_post()
   {
      global $session, $form;

      $retval = $session->addpost($_POST, $_FILES['thumbnail'] ?? null);

      if ($retval == 0) {
         header("Location: posts.php?msg=success");
      } else if ($retval == 1) {
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: create-post.php?msg=error");
      } else if ($retval == 2) {
         header("Location: create-post.php?msg=db_error");
      }
   }

   function proedit_post()
   {
      global $session;
      $retval = $session->edit_post($_POST['id'], $_POST, $_FILES['thumbnail'] ?? null);

      if ($retval == 0) {
         header("Location: posts.php?msg=success");
      } else {
         header("Location: edit-post.php?id=" . $_POST['id'] . "&msg=error");
      }
   }

    function proadd_comment()
    {
       global $database;
              // Bot Protection Check
        if (!empty($_POST['hp_field'])) {
           header("Location: /");
           exit;
        }

        $post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
        $post = $database->get_post($post_id);
        $redirect_base = $post ? bk_post_url($post) : '/';

        $submit_time = time();
        $form_time = isset($_POST['form_time']) ? (int)$_POST['form_time'] : 0;
        
        if ($submit_time - $form_time < 3) {
           // Too fast, likely a bot
           header("Location: " . $redirect_base . "?msg=c_error#comments");
           exit;
        }

        $retval = $database->add_comment($post_id, $_POST['name'], $_POST['email'], $_POST['comment']);

        if ($retval) {
           header("Location: " . $redirect_base . "?msg=c_success#comments");
        } else {
           header("Location: " . $redirect_base . "?msg=c_error#comments");
        }
        exit;
     }

   function uploadimage()
   {
      global $session, $form;

      if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
         header("Location: settings.php?msg=upload_error");
         exit;
      }

      $image = $_FILES['image']['name'];
      $fileTmpPath = $_FILES['image']['tmp_name'];
      $fileType = mime_content_type($fileTmpPath);
      $fileExt = pathinfo($image, PATHINFO_EXTENSION);

      $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
      $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

      if (!in_array($fileType, $allowedMimeTypes) || !in_array(strtolower($fileExt), $allowedExtensions)) {
         header("Location: settings.php?msg=invalid_image_type");
         exit;
      }

      $path = "images/" . $image;

      if (!move_uploaded_file($fileTmpPath, $path)) {
         header("Location: settings.php?msg=upload_error");
         exit;
      }

      $retval = $session->uploadimage($_POST['username'], $image);

      if ($retval == 0) {
         header("Location: settings.php?msg=success");
      } elseif ($retval == 1) {
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: settings.php?msg=error");
      } elseif ($retval == 2) {
         header("Location: settings.php?msg=db_error");
      }
   }


   function prochange_accountdetails()
   {
      global $session, $form;

      $retval = $session->change_accountdetails($_POST['username'], $_POST['displayname'], $_POST['email'], $_POST['phone'], $_FILES['profile_image'] ?? null);

      if ($retval == 0) {
         header("Location: settings.php?msg=success");
      } else if ($retval == 1) {
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: settings.php?msg=error");
      } else if ($retval == 2) {
         header("Location: settings.php?msg=db_error");
      }
   }

   function changepassword()
   {
      global $session, $form;

      $retval = $session->changepassword($_POST['username'], $_POST['curpass'], $_POST['newpass']);

      if ($retval == 0) {
         header("Location: settings.php?msg=success");
      } else if ($retval == 1) {
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: settings.php?msg=error");
      } else if ($retval == 2) {
         header("Location: settings.php?msg=db_error");
      }
   }

   function prodelete_post()
   {
      global $session;
      if ($session->delete_post($_GET['del_post'])) {
         header("Location: posts.php?msg=deleted");
      } else {
         header("Location: posts.php?msg=error");
      }
   }

   function prodelete_category()
   {
      global $session;
      if ($session->delete_category($_GET['del_cat'])) {
         header("Location: admin-categories.php?msg=deleted");
      } else {
         header("Location: admin-categories.php?msg=error");
      }
   }

   function prodelete_user()
   {
      global $session;
      // Prevent deleting self
      if ($_GET['del_user'] == $session->username) {
         header("Location: admin-users.php?msg=self_delete");
         exit;
      }
      if ($session->delete_user($_GET['del_user'])) {
         header("Location: admin-users.php?msg=deleted");
      } else {
         header("Location: admin-users.php?msg=error");
      }
   }


}

$process = new Process;

?>