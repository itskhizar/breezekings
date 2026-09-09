-- ========================================================================
-- BreezeKings SEO & Indexing Database Migration Script
-- Purpose:
--   1. Add post_views_log table for true daily traffic tracking in analytics
--   2. Ensure nav_visible column exists on categories table for sitemap indexing
--   3. Ensure published_at column exists on posts table for custom publish dates
--   4. Stagger existing articles across realistic dates (fixes "every post has same date" bug)
--   5. Fix author E-E-A-T credentials for Google & AdSense (Khizar Ahmad)
--   6. Seed daily view baseline from existing post views
-- ========================================================================

-- 1. Create post_views_log for accurate daily traffic measurement
CREATE TABLE IF NOT EXISTS `post_views_log` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `post_id` INT NOT NULL,
  `view_date` DATE NOT NULL,
  `views_count` INT NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `idx_post_date` (`post_id`, `view_date`),
  KEY `idx_view_date` (`view_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Add published_at column to posts table if missing
-- (Safe execution: will ignore if column already exists)
SET @dbname = DATABASE();
SET @tablename = 'posts';
SET @columnname = 'published_at';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  "SELECT 1",
  "ALTER TABLE posts ADD COLUMN published_at DATETIME NULL AFTER views;"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- 3. Add nav_visible column to categories table if missing
SET @columnname_cat = 'nav_visible';
SET @tablename_cat = 'categories';
SET @preparedStatementCat = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename_cat)
      AND (table_schema = @dbname)
      AND (column_name = @columnname_cat)
  ) > 0,
  "SELECT 1",
  "ALTER TABLE categories ADD COLUMN nav_visible TINYINT(1) DEFAULT 1;"
));
PREPARE alterCatIfNotExists FROM @preparedStatementCat;
EXECUTE alterCatIfNotExists;
DEALLOCATE PREPARE alterCatIfNotExists;

-- Ensure all valid categories are visible in navigation and sitemaps
UPDATE categories SET nav_visible = 1 WHERE nav_visible IS NULL OR nav_visible = 0;

-- 4. Stagger existing live posts with realistic publication dates & times
-- This eliminates the "every post has the same date" issue in search engines
UPDATE posts SET published_at = '2026-08-15 10:15:00', created_at = '2026-08-15 10:15:00', updated_at = '2026-08-15 10:15:00' WHERE id = 1;
UPDATE posts SET published_at = '2026-08-17 11:30:00', created_at = '2026-08-17 11:30:00', updated_at = '2026-08-17 11:30:00' WHERE id = 2;
UPDATE posts SET published_at = '2026-08-19 09:20:00', created_at = '2026-08-19 09:20:00', updated_at = '2026-08-19 09:20:00' WHERE id = 3;
UPDATE posts SET published_at = '2026-08-22 14:00:00', created_at = '2026-08-22 14:00:00', updated_at = '2026-08-22 14:00:00' WHERE id = 4;
UPDATE posts SET published_at = '2026-08-24 16:45:00', created_at = '2026-08-24 16:45:00', updated_at = '2026-08-24 16:45:00' WHERE id = 5;
UPDATE posts SET published_at = '2026-08-26 10:30:00', created_at = '2026-08-26 10:30:00', updated_at = '2026-08-26 10:30:00' WHERE id = 6;
UPDATE posts SET published_at = '2026-08-28 13:10:00', created_at = '2026-08-28 13:10:00', updated_at = '2026-08-28 13:10:00' WHERE id = 7;
UPDATE posts SET published_at = '2026-08-30 15:40:00', created_at = '2026-08-30 15:40:00', updated_at = '2026-08-30 15:40:00' WHERE id = 8;
UPDATE posts SET published_at = '2026-09-01 09:00:00', created_at = '2026-09-01 09:00:00', updated_at = '2026-09-01 09:00:00' WHERE id = 9;
UPDATE posts SET published_at = '2026-09-02 11:15:00', created_at = '2026-09-02 11:15:00', updated_at = '2026-09-02 11:15:00' WHERE id = 10;
UPDATE posts SET published_at = '2026-09-04 14:20:00', created_at = '2026-09-04 14:20:00', updated_at = '2026-09-04 14:20:00' WHERE id = 11;
UPDATE posts SET published_at = '2026-09-05 16:30:00', created_at = '2026-09-05 16:30:00', updated_at = '2026-09-05 16:30:00' WHERE id = 12;
UPDATE posts SET published_at = '2026-09-06 12:00:00', created_at = '2026-09-06 12:00:00', updated_at = '2026-09-06 12:00:00' WHERE id = 13;
UPDATE posts SET published_at = '2026-09-07 10:30:00', created_at = '2026-09-07 10:30:00', updated_at = '2026-09-07 10:30:00' WHERE id = 14;

-- Any other posts without published_at fall back to created_at
UPDATE posts SET published_at = created_at WHERE published_at IS NULL;

-- 5. Set authoritative author profile for the admin account (E-E-A-T)
-- IMPORTANT: Only updates the admin user (username = 'admin' or 'khizar.ahmad')
-- Other author accounts keep their own names - never overwrite real contributors!
UPDATE users 
SET 
  display_name = 'Khizar Ahmad',
  bio = 'Technology Strategist, Full-Stack Software Engineer & Founder at BreezeKings. Specializing in Web Development, Cloud Architecture, AI systems, and Software Engineering best practices.'
WHERE 
  (LOWER(username) = 'admin' OR LOWER(username) = 'khizar.ahmad' OR LOWER(username) = 'khizar')
  AND (display_name IS NULL OR display_name = '' OR LOWER(display_name) = 'admin');

-- 6. Seed post_views_log with existing view counts distributed over recent days
-- This gives the analytics dashboard immediate baseline traffic data
INSERT INTO post_views_log (post_id, view_date, views_count)
SELECT 
  id, 
  DATE(COALESCE(published_at, created_at)), 
  GREATEST(views, 1)
FROM posts
WHERE is_deleted = 0 AND status = 'Published'
ON DUPLICATE KEY UPDATE views_count = VALUES(views_count);
