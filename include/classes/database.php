<?php

require_once(__DIR__ . "/constants.php");

class MySQLDB
{
   var $connection;
   var $num_active_users;
   var $num_active_guests;
   var $num_members;

   function __construct()
   {
      $this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME) or die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());

      mysqli_set_charset($this->connection, "utf8mb4");
      @mysqli_query($this->connection, "SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'");
      @mysqli_query($this->connection, "SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");

      $this->num_members = -1;

      // Ensure essential blogging tables exist
      $this->ensureSchema();

      if (TRACK_VISITORS) {
         $this->calcNumActiveUsers();
         $this->calcNumActiveGuests();
      }
   }

   function ensureSchema()
   {
      // Create categories table
      $q = "CREATE TABLE IF NOT EXISTS `categories` (
         `id` int(11) NOT NULL AUTO_INCREMENT,
         `category` varchar(255) NOT NULL,
         `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
         PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
      mysqli_query($this->connection, $q);

      // Create posts table
      $q = "CREATE TABLE IF NOT EXISTS `posts` (
         `id` int(11) NOT NULL AUTO_INCREMENT,
         `title` varchar(255) NOT NULL,
         `slug` varchar(255) DEFAULT NULL,
         `content` longtext NOT NULL,
         `excerpt` text DEFAULT NULL,
         `featured_image` varchar(255) DEFAULT NULL,
         `category_id` int(11) NOT NULL,
         `author` varchar(255) NOT NULL,
         `author_id` int(11) DEFAULT NULL,
         `status` varchar(20) DEFAULT 'Draft',
         `meta_title` varchar(255) DEFAULT NULL,
         `meta_description` text DEFAULT NULL,
         `tags` varchar(255) DEFAULT NULL,
         `views` int(11) DEFAULT 0,
         `is_featured` tinyint(1) DEFAULT 0,
         `published_at` timestamp NULL DEFAULT NULL,
         `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
         `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
         `is_deleted` tinyint(1) DEFAULT 0,
         PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
      mysqli_query($this->connection, $q);

      // Create comments table
      $q = "CREATE TABLE IF NOT EXISTS `comments` (
         `id` int(11) NOT NULL AUTO_INCREMENT,
         `post_id` int(11) NOT NULL,
         `name` varchar(255) NOT NULL,
         `email` varchar(255) NOT NULL,
         `comment` text NOT NULL,
         `status` varchar(20) DEFAULT 'Pending',
         `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
         PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
      mysqli_query($this->connection, $q);

      // Create active_users table
      $q = "CREATE TABLE IF NOT EXISTS `active_users` (
         `username` varchar(30) NOT NULL,
         `timestamp` int(11) UNSIGNED NOT NULL,
         PRIMARY KEY (`username`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
      mysqli_query($this->connection, $q);

      // Create active_guests table
      $q = "CREATE TABLE IF NOT EXISTS `active_guests` (
         `ip` varchar(45) NOT NULL,
         `timestamp` int(11) UNSIGNED NOT NULL,
         PRIMARY KEY (`ip`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
      mysqli_query($this->connection, $q);

      // Check users table for blogging fields
      $fields = [
         'registration_no' => "VARCHAR(100) UNIQUE",
         'display_name' => "VARCHAR(255)",
         'phone' => "VARCHAR(20)",
         'profile_image' => "VARCHAR(255)",
         'bio' => "TEXT",
         'created_at' => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
         'timestamp' => "INT(11) UNSIGNED"
      ];

      foreach ($fields as $field => $definition) {
         $res = mysqli_query($this->connection, "SHOW COLUMNS FROM `users` LIKE '$field'");
         if (mysqli_num_rows($res) == 0) {
            mysqli_query($this->connection, "ALTER TABLE `users` ADD `$field` $definition");
         }
      }

      // AdSense & Launch Readiness: Remove placeholder posts
      mysqli_query($this->connection, "DELETE FROM `posts` WHERE `title` = 'Lorem Ipsum' OR `content` LIKE '%What is Lorem Ipsum?%'");

      // ── Category Architecture: add nav_visible column ────────────────
      $res_nv = mysqli_query($this->connection, "SHOW COLUMNS FROM `categories` LIKE 'nav_visible'");
      if (mysqli_num_rows($res_nv) == 0) {
         mysqli_query($this->connection, "ALTER TABLE `categories` ADD `nav_visible` TINYINT(1) NOT NULL DEFAULT 1");
      }

      // Ensure 6 Phase-1 core categories exist and are nav_visible
      $phase1_cats = ['News', 'Business', 'Technology', 'Health', 'Entertainment', 'Lifestyle'];
      foreach ($phase1_cats as $cc) {
         $cc_esc = mysqli_real_escape_string($this->connection, $cc);
         $chk = mysqli_query($this->connection, "SELECT id FROM `categories` WHERE LOWER(`category`) = LOWER('$cc_esc') LIMIT 1");
         if (mysqli_num_rows($chk) == 0) {
            mysqli_query($this->connection, "INSERT INTO `categories` (`category`, `nav_visible`) VALUES ('$cc_esc', 1)");
         } else {
            mysqli_query($this->connection, "UPDATE `categories` SET `nav_visible` = 1 WHERE LOWER(`category`) = LOWER('$cc_esc')");
         }
      }

      // Phase-2 categories: keep in DB, hide from nav
      $phase2_cats = ['Sports', 'Games', 'Fashion'];
      foreach ($phase2_cats as $cc) {
         $cc_esc = mysqli_real_escape_string($this->connection, $cc);
         $chk = mysqli_query($this->connection, "SELECT id FROM `categories` WHERE LOWER(`category`) = LOWER('$cc_esc') LIMIT 1");
         if (mysqli_num_rows($chk) == 0) {
            mysqli_query($this->connection, "INSERT INTO `categories` (`category`, `nav_visible`) VALUES ('$cc_esc', 0)");
         } else {
            mysqli_query($this->connection, "UPDATE `categories` SET `nav_visible` = 0 WHERE LOWER(`category`) = LOWER('$cc_esc')");
         }
      }

      // Merge duplicate Sports (id=9 → id=4), then ensure Phase-2 nav_visible=0
      mysqli_query($this->connection, "UPDATE `posts` SET `category_id` = 4 WHERE `category_id` = 9");
      mysqli_query($this->connection, "DELETE FROM `categories` WHERE `id` = 9 AND LOWER(`category`) = 'sports'");

      // Drop template-drift categories (Education, Home Improvement, Travel)
      // Delete their posts first to avoid FK issues, then delete the category
      $drop_cats = ['Education', 'Home Improvement', 'Travel'];
      foreach ($drop_cats as $dc) {
         $dc_esc = mysqli_real_escape_string($this->connection, $dc);
         $dc_row = mysqli_fetch_assoc(mysqli_query($this->connection, "SELECT id FROM `categories` WHERE LOWER(`category`) = LOWER('$dc_esc') LIMIT 1"));
         if ($dc_row) {
            $dc_id = (int)$dc_row['id'];
            mysqli_query($this->connection, "DELETE FROM `posts` WHERE `category_id` = $dc_id");
            mysqli_query($this->connection, "DELETE FROM `categories` WHERE `id` = $dc_id");
         }
      }

      // Migration: clean up legacy lowercase names and ensure display_name preserves user casing
      mysqli_query($this->connection, "UPDATE `users` SET `display_name` = 'Admin' WHERE `username` = 'admin' AND (`display_name` = 'admin' OR `display_name` IS NULL OR `display_name` = '')");
      mysqli_query($this->connection, "UPDATE `users` SET `display_name` = 'Khizar Ahmad' WHERE `username` = 'khizar.ahmad' AND (`display_name` = 'khizar.ahmad' OR `display_name` IS NULL OR `display_name` = '')");
      mysqli_query($this->connection, "UPDATE `users` SET `display_name` = 'Waseem Azam' WHERE `username` = 'azam.waseem' AND (`display_name` = 'azam.waseem' OR `display_name` IS NULL OR `display_name` = '')");
      mysqli_query($this->connection, "UPDATE `users` SET `display_name` = CONCAT(UPPER(SUBSTRING(REPLACE(username, '.', ' '), 1, 1)), SUBSTRING(REPLACE(username, '.', ' '), 2)) WHERE (`display_name` IS NULL OR `display_name` = '')");
   }

   function generateExcerpt($content, $length = 160)
   {
      $content = strip_tags($content);
      if (mb_strlen($content) <= $length) return $content;
      $excerpt = mb_substr($content, 0, $length);
      $last_space = mb_strrpos($excerpt, ' ');
      if ($last_space !== false) {
         $excerpt = mb_substr($excerpt, 0, $last_space);
      }
      return $excerpt . '...';
   }

   function getReadingTime($content)
   {
      $words = str_word_count(strip_tags($content));
      $reading_time = ceil($words / 200);
      return $reading_time;
   }

   /**
    * WordPress-style unique slug generator (e.g. tech-trends, tech-trends-2, tech-trends-3)
    */
   function get_unique_slug($string, $exclude_id = 0)
   {
      $slug = bk_slugify($string);
      if (empty($slug)) $slug = 'post';
      $original_slug = $slug;
      $count = 1;
      $exclude_sql = $exclude_id > 0 ? " AND id != " . (int)$exclude_id : "";

      while (true) {
         $escaped = mysqli_real_escape_string($this->connection, $slug);
         $chk = mysqli_query($this->connection, "SELECT id FROM `posts` WHERE `slug` = '$escaped' $exclude_sql LIMIT 1");
         if (!$chk || mysqli_num_rows($chk) == 0) {
            break;
         }
         $count++;
         $slug = $original_slug . '-' . $count;
      }
      return $slug;
   }

   /* ---------- Post Methods ---------- */

   function addpost($data)
   {
      $title = mysqli_real_escape_string($this->connection, $data['title']);
      $raw_slug = !empty($data['slug']) ? $data['slug'] : $data['title'];
      $slug = mysqli_real_escape_string($this->connection, $this->get_unique_slug($raw_slug));
      $content = mysqli_real_escape_string($this->connection, $data['content']);
      $excerpt = $data['excerpt'];
      if (empty($excerpt)) {
         $excerpt = $this->generateExcerpt($data['content']);
      }
      $meta_title = !empty($data['meta_title']) ? $data['meta_title'] : $data['title'];
      $meta_description = !empty($data['meta_description']) ? $data['meta_description'] : $excerpt;
      $excerpt = mysqli_real_escape_string($this->connection, $excerpt);
      $category_id = (int) $data['category_id'];
      $author = mysqli_real_escape_string($this->connection, $data['author']);
      $status = mysqli_real_escape_string($this->connection, $data['status']);
      $featured_image = mysqli_real_escape_string($this->connection, $data['featured_image']);
      $meta_title = mysqli_real_escape_string($this->connection, $meta_title);
      $meta_description = mysqli_real_escape_string($this->connection, $meta_description);
      $tags = mysqli_real_escape_string($this->connection, $data['tags']);
      $is_featured = (int) ($data['is_featured'] ?? 0);
      $published_at = ($status == 'Published') ? "NOW()" : "NULL";

      $q = "INSERT INTO `posts` (`title`, `slug`, `content`, `excerpt`, `category_id`, `author`, `status`, `featured_image`, `meta_title`, `meta_description`, `tags`, `is_featured`, `published_at`) 
            VALUES ('$title', '$slug', '$content', '$excerpt', '$category_id', '$author', '$status', '$featured_image', '$meta_title', '$meta_description', '$tags', '$is_featured', $published_at)";

      return mysqli_query($this->connection, $q);
   }

   function updatepost($id, $data)
   {
      $id = (int) $id;
      $title = mysqli_real_escape_string($this->connection, $data['title']);
      $raw_slug = !empty($data['slug']) ? $data['slug'] : $data['title'];
      $slug = mysqli_real_escape_string($this->connection, $this->get_unique_slug($raw_slug, $id));
      $content = mysqli_real_escape_string($this->connection, $data['content']);
      $excerpt = $data['excerpt'];
      if (empty($excerpt)) {
         $excerpt = $this->generateExcerpt($data['content']);
      }
      $meta_title = !empty($data['meta_title']) ? $data['meta_title'] : $data['title'];
      $meta_description = !empty($data['meta_description']) ? $data['meta_description'] : $excerpt;
      $excerpt = mysqli_real_escape_string($this->connection, $excerpt);
      $category_id = (int) $data['category_id'];
      $status = mysqli_real_escape_string($this->connection, $data['status']);
      $meta_title = mysqli_real_escape_string($this->connection, $meta_title);
      $meta_description = mysqli_real_escape_string($this->connection, $meta_description);
      $tags = mysqli_real_escape_string($this->connection, $data['tags']);
      $is_featured = (int) ($data['is_featured'] ?? 0);

      $img_sql = "";
      if (isset($data['featured_image']) && $data['featured_image']) {
         $featured_image = mysqli_real_escape_string($this->connection, $data['featured_image']);
         $img_sql = ", `featured_image` = '$featured_image'";
      }

      $pub_sql = "";
      if ($status === 'Published') {
         $pub_sql = ", `published_at` = IFNULL(`published_at`, NOW())";
      }

      $q = "UPDATE `posts` SET 
            `title` = '$title', 
            `slug` = '$slug', 
            `content` = '$content', 
            `excerpt` = '$excerpt', 
            `category_id` = '$category_id', 
            `status` = '$status', 
            `meta_title` = '$meta_title', 
            `meta_description` = '$meta_description', 
            `tags` = '$tags', 
            `is_featured` = '$is_featured'
            $img_sql
            $pub_sql
            WHERE `id` = $id";

      return mysqli_query($this->connection, $q);
   }

   function get_post($id)
   {
      $id = (int)$id;
      $q = "SELECT p.*, c.category, COALESCE(NULLIF(u.display_name, ''), u.username, p.author) as author_name FROM posts p 
            LEFT JOIN categories c ON p.category_id = c.id 
            LEFT JOIN users u ON (p.author = u.registration_no OR p.author = u.username) 
            WHERE p.id = $id AND p.is_deleted = 0";
      $res = mysqli_query($this->connection, $q);
      if ($res && mysqli_num_rows($res) > 0) {
         return mysqli_fetch_assoc($res);
      }
      return null;
   }

   function get_all_posts($status = NULL)
   {
      $q = "SELECT p.*, c.category, COALESCE(NULLIF(u.display_name, ''), u.username, p.author) as author_name FROM posts p 
            LEFT JOIN categories c ON p.category_id = c.id 
            LEFT JOIN users u ON (p.author = u.registration_no OR p.author = u.username)
            WHERE p.is_deleted = 0";

      if (!empty($status)) {
         $status = mysqli_real_escape_string($this->connection, $status);
         $q .= " AND p.status = '$status'";
      }

      $q .= " ORDER BY p.created_at DESC";
      return mysqli_query($this->connection, $q);
   }

   function get_posts_by_category($cat_id, $status = 'Published')
   {
      $cat_id = (int) $cat_id;
      $status_sql = "";
      if (!empty($status)) {
         $status = mysqli_real_escape_string($this->connection, $status);
         $status_sql = " AND p.status = '$status'";
      }
      $q = "SELECT p.*, c.category, COALESCE(NULLIF(u.display_name, ''), u.username, p.author) as author_name FROM posts p 
            LEFT JOIN categories c ON p.category_id = c.id 
            LEFT JOIN users u ON (p.author = u.registration_no OR p.author = u.username)
            WHERE p.category_id = $cat_id AND p.is_deleted = 0 $status_sql
            ORDER BY p.created_at DESC";
      return mysqli_query($this->connection, $q);
   }

   function search_posts($query, $status = 'Published')
   {
      $query = mysqli_real_escape_string($this->connection, $query);
      $status_sql = "";
      if (!empty($status)) {
         $status_esc = mysqli_real_escape_string($this->connection, $status);
         $status_sql = " AND p.status = '$status_esc'";
      }
      $q = "SELECT p.*, c.category, COALESCE(NULLIF(u.display_name, ''), u.username, p.author) as author_name FROM posts p 
            LEFT JOIN categories c ON p.category_id = c.id 
            LEFT JOIN users u ON (p.author = u.registration_no OR p.author = u.username)
            WHERE (p.title LIKE '%$query%' OR p.content LIKE '%$query%' OR p.tags LIKE '%$query%' OR p.slug LIKE '%$query%') 
            $status_sql AND p.is_deleted = 0
            ORDER BY p.created_at DESC";
      return mysqli_query($this->connection, $q);
   }

   function get_popular_posts($limit = 5)
   {
      $limit = (int) $limit;
      $q = "SELECT p.*, c.category, COALESCE(NULLIF(u.display_name, ''), u.username, p.author) as author_name FROM posts p 
            LEFT JOIN categories c ON p.category_id = c.id 
            LEFT JOIN users u ON (p.author = u.registration_no OR p.author = u.username) 
            WHERE p.status = 'Published' AND p.is_deleted = 0
            ORDER BY p.views DESC, p.created_at DESC LIMIT $limit";
      return mysqli_query($this->connection, $q);
   }

   function get_related_posts($category_id, $exclude_id, $limit = 3)
   {
      $category_id = (int) $category_id;
      $exclude_id = (int) $exclude_id;
      $limit = (int) $limit;
      $q = "SELECT p.*, c.category, COALESCE(NULLIF(u.display_name, ''), u.username, p.author) as author_name FROM posts p 
            LEFT JOIN categories c ON p.category_id = c.id 
            LEFT JOIN users u ON (p.author = u.registration_no OR p.author = u.username) 
            WHERE p.category_id = $category_id AND p.id != $exclude_id 
            AND p.status = 'Published' AND p.is_deleted = 0
            ORDER BY p.created_at DESC LIMIT $limit";
      return mysqli_query($this->connection, $q);
   }

   function delete_post($id)
   {
      $id = (int) $id;
      return mysqli_query($this->connection, "UPDATE posts SET is_deleted = 1 WHERE id = $id");
   }

   function increment_views($post_id)
   {
      $post_id = (int) $post_id;
      return mysqli_query($this->connection, "UPDATE posts SET views = views + 1 WHERE id = $post_id");
   }

   /* ---------- Category Methods ---------- */

   function addcategory($category)
   {
      $category = mysqli_real_escape_string($this->connection, $category);
      $q = "INSERT INTO `categories` (`category`) VALUES ('$category')";
      return mysqli_query($this->connection, $q);
   }

   /**
    * Public nav: only Phase-1 (nav_visible=1) categories, ordered by name.
    */
   function get_all_categories()
   {
      $q = "SELECT c.*, (SELECT COUNT(*) FROM posts WHERE category_id = c.id AND status = 'Published' AND is_deleted = 0) as post_count FROM categories c WHERE c.nav_visible = 1 ORDER BY category ASC";
      return mysqli_query($this->connection, $q);
   }

   /**
    * Admin panel: all categories regardless of nav_visible.
    */
   function get_all_categories_admin()
   {
      $q = "SELECT c.*, (SELECT COUNT(*) FROM posts WHERE category_id = c.id AND status = 'Published' AND is_deleted = 0) as post_count FROM categories c ORDER BY category ASC";
      return mysqli_query($this->connection, $q);
   }

   /**
    * Sitemap: only Phase-1 nav-visible categories with at least 1 published post.
    */
   function get_nav_categories_for_sitemap()
   {
      $q = "SELECT c.*, (SELECT COUNT(*) FROM posts WHERE category_id = c.id AND status = 'Published' AND is_deleted = 0) as post_count FROM categories c WHERE c.nav_visible = 1 HAVING post_count > 0 ORDER BY category ASC";
      return mysqli_query($this->connection, $q);
   }

   function edit_category($id, $category)
   {
      $id = (int) $id;
      $category = mysqli_real_escape_string($this->connection, $category);
      $q = "UPDATE `categories` SET `category` = '$category' WHERE id = $id";
      return mysqli_query($this->connection, $q);
   }

   function delete_category($id)
   {
      $id = (int) $id;
      return mysqli_query($this->connection, "DELETE FROM categories WHERE id = $id");
   }

   /* ---------- User & Admin Methods ---------- */

   function confirmUserPass($username, $password)
   {
      $username = mysqli_real_escape_string($this->connection, $username);
      $q = "SELECT password FROM users WHERE registration_no = '$username' OR username = '$username'";
      $result = mysqli_query($this->connection, $q);

      if (!$result || mysqli_num_rows($result) < 1)
         return 1;

      $row = mysqli_fetch_assoc($result);
      if (password_verify($password, $row['password']))
         return 0;
      return 2;
   }

   function loginsession($subuser)
   {
      $subuser = mysqli_real_escape_string($this->connection, $subuser);
      $q = "SELECT * FROM users WHERE registration_no = '$subuser' OR username = '$subuser'";
      $result = mysqli_query($this->connection, $q);
      if (!$result || (mysqli_num_rows($result) < 1))
         return NULL;
      return mysqli_fetch_array($result);
   }

   function getUserInfo($identifier)
   {
      $identifier = mysqli_real_escape_string($this->connection, $identifier);
      $q = "SELECT * FROM users WHERE username = '$identifier' OR registration_no = '$identifier' LIMIT 1";
      $result = mysqli_query($this->connection, $q);
      if (!$result || (mysqli_num_rows($result) < 1))
         return NULL;
      return mysqli_fetch_array($result);
   }

   function confirmUserID($username, $userid)
   {
      $username = mysqli_real_escape_string($this->connection, $username);
      $userid = mysqli_real_escape_string($this->connection, $userid);
      $q = "SELECT userid FROM users WHERE username = '$username'";
      $result = mysqli_query($this->connection, $q);
      if (!$result || (mysqli_num_rows($result) < 1))
         return 1;

      $row = mysqli_fetch_array($result);
      if ($userid == $row['userid'])
         return 0;
      return 2;
   }

   function updateUserField($username, $field, $value)
   {
      $username = mysqli_real_escape_string($this->connection, $username);
      $field = mysqli_real_escape_string($this->connection, $field);
      $value = mysqli_real_escape_string($this->connection, $value);
      $q = "UPDATE users SET $field = '$value' WHERE username = '$username'";
      return mysqli_query($this->connection, $q);
   }

   function get_all_users()
   {
      $q = "SELECT * FROM users ORDER BY userlevel DESC, username ASC";
      return mysqli_query($this->connection, $q);
   }

   function addadmin($name, $mobile_no, $email, $level = 1)
   {
      $name = trim($name);
      $name_esc = mysqli_real_escape_string($this->connection, $name);
      $mobile_no = mysqli_real_escape_string($this->connection, $mobile_no);
      $email = mysqli_real_escape_string($this->connection, $email);

      // Username is generated for login, but display_name preserves exact case (uppercase, lowercase, mixed combinations)
      $base_username = strtolower(str_replace(' ', '.', $name));
      $base_username = preg_replace('/[^a-z0-9.]/', '', $base_username);
      if (empty($base_username)) $base_username = 'user';
      $username = $base_username;
      $count = 1;
      while (true) {
         $check = mysqli_query($this->connection, "SELECT username FROM users WHERE username = '$username'");
         if (mysqli_num_rows($check) == 0)
            break;
         $username = $base_username . $count++;
      }

      $password = password_hash($mobile_no, PASSWORD_DEFAULT);

      // Generate registration_no like AUTH-1024
      $res = mysqli_query($this->connection, "SELECT id FROM users ORDER BY id DESC LIMIT 1");
      $last_id = 1023; // Start from 1024 if no users exist
      if ($res && mysqli_num_rows($res) > 0) {
         $last_id = mysqli_fetch_row($res)[0];
         if ($last_id < 1023)
            $last_id = 1023;
      }
      $registration_no = "AUTH-" . str_pad($last_id + 1, 4, '0', STR_PAD_LEFT);

      $userid = md5($username . time());

      $q = "INSERT INTO users (username, password, userid, userlevel, email, timestamp, registration_no, display_name, phone) 
            VALUES ('$username', '$password', '$userid', '$level', '$email', " . time() . ", '$registration_no', '$name_esc', '$mobile_no')";
      return mysqli_query($this->connection, $q);
   }

   function delete_user($username)
   {
      $username = mysqli_real_escape_string($this->connection, $username);
      return mysqli_query($this->connection, "DELETE FROM users WHERE username = '$username'");
   }

   /* ---------- Tracking & Session Methods ---------- */

   function calcNumActiveUsers()
   {
      $q = "SELECT count(*) FROM active_users";
      $result = mysqli_query($this->connection, $q);
      $this->num_active_users = $result ? mysqli_fetch_row($result)[0] : 0;
   }

   function calcNumActiveGuests()
   {
      $q = "SELECT count(*) FROM active_guests";
      $result = mysqli_query($this->connection, $q);
      $this->num_active_guests = $result ? mysqli_fetch_row($result)[0] : 0;
   }

   function addActiveUser($username, $time)
   {
      $username = mysqli_real_escape_string($this->connection, $username);
      $time = (int) $time;
      $this->updateUserField($username, 'timestamp', $time);

      if (!TRACK_VISITORS)
         return;
      $q = "REPLACE INTO active_users VALUES ('$username', '$time')";
      mysqli_query($this->connection, $q);
      $this->calcNumActiveUsers();
   }

   function addActiveGuest($ip, $time)
   {
      if (!TRACK_VISITORS)
         return;
      $ip = mysqli_real_escape_string($this->connection, $ip);
      $time = (int) $time;
      $q = "REPLACE INTO active_guests VALUES ('$ip', '$time')";
      mysqli_query($this->connection, $q);
      $this->calcNumActiveGuests();
   }

   function removeActiveUser($username)
   {
      $username = mysqli_real_escape_string($this->connection, $username);
      mysqli_query($this->connection, "DELETE FROM active_users WHERE username = '$username'");
      $this->calcNumActiveUsers();
   }

   function removeActiveGuest($ip)
   {
      $ip = mysqli_real_escape_string($this->connection, $ip);
      mysqli_query($this->connection, "DELETE FROM active_guests WHERE ip = '$ip'");
      $this->calcNumActiveGuests();
   }

   function removeInactiveUsers()
   {
      $timeout = time() - USER_TIMEOUT * 60;
      mysqli_query($this->connection, "DELETE FROM active_users WHERE timestamp < $timeout");
      $this->calcNumActiveUsers();
   }

   function removeInactiveGuests()
   {
      $timeout = time() - GUEST_TIMEOUT * 60;
      mysqli_query($this->connection, "DELETE FROM active_guests WHERE timestamp < $timeout");
      $this->calcNumActiveGuests();
   }

   /* ---------- Comment Methods ---------- */

   function add_comment($post_id, $name, $email, $comment)
   {
      $post_id = (int) $post_id;
      $name = mysqli_real_escape_string($this->connection, $name);
      $email = mysqli_real_escape_string($this->connection, $email);
      $comment = mysqli_real_escape_string($this->connection, $comment);
      $q = "INSERT INTO comments (post_id, name, email, comment, status) VALUES ('$post_id', '$name', '$email', '$comment', 'Pending')";
      return mysqli_query($this->connection, $q);
   }

   function get_comments($post_id)
   {
      $post_id = (int) $post_id;
      $q = "SELECT * FROM comments WHERE post_id = $post_id AND (status = 'Approved' OR status = 'Published') ORDER BY created_at DESC";
      return mysqli_query($this->connection, $q);
   }

   function query($query)
   {
      return mysqli_query($this->connection, $query);
   }
}

$database = new MySQLDB;
?>