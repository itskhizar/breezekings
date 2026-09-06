<?php
/**
 * Breezekings — SEO & Permalink Helper Functions
 * 
 * Provides slug generation, ID encoding/decoding, clean URL builders,
 * and canonical helpers for top-tier Google search ranking.
 */

if (!function_exists('bk_slugify')) {
    /**
     * Converts a string into a clean, URL-friendly slug.
     */
    function bk_slugify($text, $divider = '-') {
        if (empty($text)) {
            return 'article';
        }
        // Replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
        // Transliterate
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }
        // Remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // Trim
        $text = trim($text, $divider);
        // Remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);
        // Lowercase
        $text = strtolower($text);

        return empty($text) ? 'article' : $text;
    }
}

if (!function_exists('bk_encode_id')) {
    /**
     * Obfuscate / encode numeric ID for URL-safe permalinks.
     */
    function bk_encode_id($id) {
        $id = (int)$id;
        // Simple, clean URL-safe base36 encoding with salt
        $salted = ($id * 7829) + 4091;
        return 'bk' . base_convert((string)$salted, 10, 36);
    }
}

if (!function_exists('bk_decode_id')) {
    /**
     * Decode an encoded ID back into its integer.
     */
    function bk_decode_id($encoded) {
        if (is_numeric($encoded)) {
            return (int)$encoded;
        }
        if (strpos($encoded, 'bk') === 0) {
            $raw = substr($encoded, 2);
            $num = base_convert($raw, 36, 10);
            $id = ($num - 4091) / 7829;
            if (is_int($id) || floor($id) == $id) {
                return (int)$id;
            }
        }
        // Try base64 url-safe
        $decoded = base64_decode(strtr($encoded, '-_', '+/'));
        if (is_numeric($decoded)) {
            return (int)$decoded;
        }
        return 0;
    }
}

if (!function_exists('bk_base_url')) {
    /**
     * Returns the absolute site base URL with HTTPS protocol.
     */
    function bk_base_url() {
        static $base = null;
        if ($base === null) {
            $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
                        (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
                        ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'breezekings.com';
            $base = $protocol . '://' . $host;
        }
        return $base;
    }
}

if (!function_exists('bk_post_url')) {
    /**
     * Returns clean SEO permalink for a blog post with encoded ID:
     * e.g. /post/bk2lrq/mastering-seo-guide
     */
    function bk_post_url($post_or_id, $title = '', $slug = '') {
        $id = 0;
        if (is_array($post_or_id)) {
            $id = (int)($post_or_id['id'] ?? 0);
            $title = $post_or_id['title'] ?? '';
            $slug = $post_or_id['slug'] ?? '';
        } else {
            $id = (int)$post_or_id;
        }

        if (empty($slug) && !empty($title)) {
            $slug = bk_slugify($title);
        } elseif (empty($slug)) {
            $slug = 'post';
        }

        $encoded_id = bk_encode_id($id);
        return '/post/' . $encoded_id . '/' . $slug;
    }
}

if (!function_exists('bk_category_url')) {
    /**
     * Returns clean SEO permalink for a category:
     * e.g. /category/5/technology
     */
    function bk_category_url($cat_or_id, $category_name = '') {
        $id = 0;
        if (is_array($cat_or_id)) {
            $id = (int)($cat_or_id['id'] ?? 0);
            $category_name = $cat_or_id['category'] ?? '';
        } else {
            $id = (int)$cat_or_id;
        }

        $slug = bk_slugify($category_name ?: 'category');
        return '/category/' . $id . '/' . $slug;
    }
}

if (!function_exists('bk_thumb_url')) {
    /**
     * Returns absolute thumbnail URL with fallback for post images
     */
    function bk_thumb_url($featured_image = null) {
        if (!empty($featured_image)) {
            $img = trim($featured_image);
            if (strpos($img, 'http://') === 0 || strpos($img, 'https://') === 0) {
                return $img;
            }
            $img = ltrim($img, '/');
            if (strpos($img, 'images/') === 0) {
                return '/' . $img;
            }
            return '/images/posts/' . $img;
        }
        return '/images/blog-default.jpg';
    }
}

if (!function_exists('bk_avatar_url')) {
    /**
     * Returns absolute avatar URL with initials-based demo avatar fallback
     */
    function bk_avatar_url($profile_image = null, $author_name = 'Breezekings') {
        if (!empty($profile_image)) {
            $img = trim($profile_image);
            if (strpos($img, 'http://') === 0 || strpos($img, 'https://') === 0) {
                return $img;
            }
            $clean = ltrim($img, '/');
            if (strpos($clean, 'images/') === 0) {
                return '/' . $clean;
            }
            if (file_exists('images/profiles/' . $clean)) {
                return '/images/profiles/' . $clean;
            }
            if (file_exists('images/' . $clean)) {
                return '/images/' . $clean;
            }
        }
        if (file_exists('images/avatar.png')) {
            return '/images/avatar.png';
        }
        $name = rawurlencode(trim($author_name ?: 'Breezekings'));
        return "https://ui-avatars.com/api/?name={$name}&background=0B1F3A&color=ffffff&bold=true&size=128";
    }
}
