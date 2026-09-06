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

if (!function_exists('bk_category_description')) {
    /**
     * Returns rich, unique 100-150 word editorial intro description for core categories
     */
    function bk_category_description($category_name = '') {
        $key = strtolower(trim($category_name));
        $descriptions = [
            'news' => "Stay ahead of global developments with Breezekings News. Our editorial team delivers fast, fact-checked reporting on breaking stories, geopolitical shifts, policy updates, and pressing cultural events shaping society today. We prioritize balanced reporting and verified sources over sensationalism, providing clear context that explains not just what happened, but why it matters to you. Whether you're tracking international affairs or local community breakthroughs, count on our timely analysis to keep you informed with objectivity and depth.",
            'business' => "Navigate modern economics, startup breakthroughs, and global markets with Breezekings Business. We decode corporate strategy, venture capital trends, inflation dynamics, entrepreneurship, and personal finance strategies for ambitious professionals. Our investigative pieces look beyond quarterly earnings to reveal how shifting consumer behaviors and disruptive tech affect real enterprises. From bootstrapping a scalable venture to understanding modern supply chains, our guides provide actionable intelligence for founders, investors, and leaders.",
            'technology' => "Explore the vanguard of digital transformation with Breezekings Technology. From breakthroughs in generative artificial intelligence and quantum computing to cybersecurity defenses, cloud architecture, consumer electronics, and ethical tech governance, we translate complex technical concepts into actionable insights. Our researchers evaluate emerging developer tools, mobile ecosystems, and automated enterprise workflows to help engineers, executives, and curious minds stay competitive in an ever-accelerating digital ecosystem.",
            'fashion' => "Step into contemporary style, sustainable aesthetics, and design innovation with Breezekings Fashion. We cover runway retrospectives, modern wardrobe architecture, ethical textiles, streetwear subcultures, and luxury craftsmanship. Our fashion correspondents explore how digital culture, circular manufacturing, and timeless tailoring intersect to redefine modern apparel. Whether you are seeking capsule wardrobe essentials, seasonal styling advice, or cultural critique of emerging designers, Breezekings curates style with intelligence and individuality.",
            'games' => "Level up your gaming knowledge with Breezekings Games. We offer comprehensive coverage of PC gaming, console releases, mobile gaming innovation, indie masterpieces, esports tournaments, and game design theory. Our editorial team analyzes gameplay mechanics, narrative worldbuilding, hardware benchmarks, and industry dynamics without the marketing hype. Whether you are looking for in-depth walkthroughs, unbiased critical reviews, or retrospectives on legendary game franchises, we celebrate interactive entertainment with passionate gamers.",
            'health' => "Empower your physical and mental well-being with Breezekings Health. Backed by peer-reviewed research and medical expert insights, we publish accessible guides on preventive wellness, mental resilience, nutritional science, fitness optimization, and holistic sleep hygiene. We demystify modern healthcare trends, debunk viral medical misinformation, and offer evidence-based habits to sustain daily vitality. Discover practical routines and compassionate guidance designed to support a balanced, energetic, and longevity-focused life.",
            'entertainment' => "Immerse yourself in cinematic storytelling, television analysis, musical culture, and pop culture commentary with Breezekings Entertainment. Our critics delve into streaming premieres, box office phenomena, artist discographies, film festival retrospectives, and the creative minds behind modern media. We examine how visual media reflects societal changes, unpacking hidden themes and narrative arcs in your favorite films and series. Experience engaging reviews, creator spotlights, and cultural critiques crafted for media enthusiasts.",
            'sports' => "Experience the passion, tactical strategy, and athletic triumph of modern competition with Breezekings Sports. From football leagues, basketball championships, tennis grand slams, and Formula 1 to combat sports and collegiate athletics, our writers provide sharp tactical analysis, match breakdowns, and player profiles. We explore the human stories behind world-class records, sports science innovations, and team management philosophies. Whether celebrating heroic victories or dissecting tactical game plans, Breezekings brings you closer to the action.",
            'lifestyle' => "Curate a fulfilling, intentional daily life with Breezekings Lifestyle. Our writers share insightful essays and practical advice on interior design, intentional living, travel adventures, culinary discoveries, career balance, and relationship dynamics. We advocate for mindfulness, sustainable habits, and mindful consumption in an overstimulated world. Explore practical tips on personal organization, creative hobbies, and cultural experiences that enrich your everyday routine with purpose, elegance, and joy."
        ];

        return $descriptions[$key] ?? ("Explore comprehensive, fact-checked reporting, expert commentary, and timely analysis in " . htmlspecialchars($category_name) . " on Breezekings. Our editorial contributors bring you rigorous articles and practical insights designed to inform, educate, and inspire our global readership.");
    }
}

if (!function_exists('bk_category_meta_description')) {
    /**
     * Returns concise, complete meta description strictly under 155 characters for SEO
     */
    function bk_category_meta_description($category_name = '') {
        $key = strtolower(trim($category_name));
        $meta_descriptions = [
            'technology'    => "Explore tech breakthroughs, AI, software engineering, gadgets, and digital security with expert reporting on Breezekings.",
            'business'      => "Insightful reporting on global markets, startup strategies, economics, and corporate leadership on Breezekings.",
            'entertainment' => "In-depth reviews and cultural commentary on cinema, television, streaming media, and music on Breezekings.",
            'health'        => "Evidence-based guides on physical fitness, mental resilience, nutritional science, and daily vitality on Breezekings.",
            'lifestyle'     => "Practical advice on digital minimalism, travel, intentional living, interior design, and personal growth on Breezekings.",
            'news'          => "Timely, balanced reporting and verified analysis on essential global events and current affairs on Breezekings."
        ];

        return $meta_descriptions[$key] ?? ("Explore comprehensive articles, analysis, and expert perspectives in " . htmlspecialchars($category_name) . " on Breezekings.");
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
