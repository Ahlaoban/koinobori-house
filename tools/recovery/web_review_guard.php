<?php
/** Temporary MU-plugin for an authenticated, read-only visual review. Not production code. */
if (!defined('ABSPATH')) { exit; }

$kh_review_valid = defined('KH2027_WEB_REVIEW') && KH2027_WEB_REVIEW === true
    && defined('KH2027_SANDBOX') && KH2027_SANDBOX === true
    && defined('KH2027_REVIEW_HOST') && defined('KH2027_REVIEW_DATABASE')
    && defined('KH2027_REVIEW_ORIGINS') && is_array(KH2027_REVIEW_ORIGINS)
    && WP_ENVIRONMENT_TYPE === 'local' && DB_NAME === KH2027_REVIEW_DATABASE
    && DB_HOST === 'localhost' && WP_HOME === 'https://' . KH2027_REVIEW_HOST
    && WP_SITEURL === WP_HOME && DISABLE_WP_CRON === true
    && !ini_get('allow_url_fopen') && !ini_get('allow_url_include');
foreach (array('mail', 'curl_exec', 'curl_multi_exec', 'fsockopen', 'pfsockopen',
    'stream_socket_client', 'socket_create', 'ftp_connect', 'ftp_ssl_connect',
    'exec', 'shell_exec', 'system', 'passthru', 'popen', 'proc_open', 'pcntl_exec', 'dl') as $function) {
    if (function_exists($function)) { $kh_review_valid = false; }
}
foreach (array('ffi', 'imap', 'ldap', 'memcached', 'redis', 'soap', 'sockets', 'pgsql', 'pdo_pgsql', 'pdo_mysql', 'imagick') as $extension) {
    if (extension_loaded($extension)) { $kh_review_valid = false; }
}
if (PHP_SAPI !== 'cli') {
    $kh_review_valid = $kh_review_valid
        && (!empty($_SERVER['REMOTE_USER']) || !empty($_SERVER['REDIRECT_REMOTE_USER']))
        && ($_SERVER['HTTPS'] ?? '') === 'on'
        && ($_SERVER['HTTP_HOST'] ?? '') === KH2027_REVIEW_HOST;
}
if (!$kh_review_valid) {
    http_response_code(503);
    exit('Site de test fermé : contrôle de configuration requis.');
}
unset($kh_review_valid);

if (PHP_SAPI !== 'cli' && (!in_array($_SERVER['REQUEST_METHOD'] ?? '', array('GET', 'HEAD'), true)
    || isset($_GET['add-to-cart']) || isset($_GET['wc-ajax']) || isset($_GET['wc-api']))) {
    http_response_code(405);
    header('Allow: GET, HEAD');
    exit('La première visite du site de test est en lecture seule.');
}

add_filter('option_active_plugins', function ($plugins) {
    return array_values(array_filter($plugins, function ($plugin) {
        return in_array($plugin, array('woocommerce/woocommerce.php', 'polylang/polylang.php',
            'polylang-wc/polylang-wc.php', 'kh-single-variation-display/kh-single-variation-display.php'), true);
    }));
});
add_filter('pre_http_request', function () { return new WP_Error('kh2027_offline', 'External requests disabled for review.'); }, PHP_INT_MAX);
add_filter('pre_wp_mail', '__return_true', PHP_INT_MAX);
add_filter('woocommerce_available_payment_gateways', '__return_empty_array', PHP_INT_MAX);
add_filter('action_scheduler_allow_async_request_runner', '__return_false', PHP_INT_MAX);
add_filter('xmlrpc_enabled', '__return_false');
add_filter('rest_authentication_errors', function () { return new WP_Error('kh2027_review', 'REST closed for visual review.', array('status' => 403)); }, PHP_INT_MAX);
add_filter('pre_option_blog_public', function () { return '0'; });

// Select the trial layouts without changing page metadata or theme settings in SQL.
add_action('after_setup_theme', function () {
    require_once get_stylesheet_directory() . '/inc/editorial.php';
});
add_filter('get_post_metadata', function ($value, $id, $key) {
    if ($key === '_wp_page_template' && in_array((int) $id, array(318, 319), true)) {
        return array('page-templates/kh-home.php');
    }
    return $value;
}, 10, 3);
add_filter('theme_mod_kh_editorial_products', '__return_true');

function kh_review_local_url($url) {
    foreach (KH2027_REVIEW_ORIGINS as $origin) {
        if ($url === $origin || strpos($url, $origin . '/') === 0) {
            return WP_HOME . substr($url, strlen($origin));
        }
    }
    return $url;
}
add_filter('wp_redirect', function ($url) {
    $url = kh_review_local_url($url);
    $host = wp_parse_url($url, PHP_URL_HOST);
    return !$host || $host === KH2027_REVIEW_HOST ? $url : false;
}, PHP_INT_MAX);

add_action('send_headers', function () {
    header('X-Robots-Tag: noindex, nofollow, noarchive');
    header('Cache-Control: no-store, private');
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self' data:; connect-src 'self'; frame-src 'none'; frame-ancestors 'none'; form-action 'self'; base-uri 'self'");
});
add_action('template_redirect', function () {
    // Temporary view-only URL mapping. Original serialized values stay untouched.
    ob_start(function ($html) {
        foreach (KH2027_REVIEW_ORIGINS as $origin) {
            $html = str_replace($origin . '/', WP_HOME . '/', $html);
        }
        return $html;
    });
}, 0);
add_action('wp_body_open', function () {
    $english = function_exists('pll_current_language') && pll_current_language() === 'en';
    echo '<div role="note" style="padding:9px 18px;background:#f8f4ee;color:#1a1410;text-align:center;font:13px/1.5 sans-serif;border-bottom:1px solid #d5cfc5">';
    echo $english ? 'Private preview · Work in progress · Browsing only' : 'Aperçu privé · Site en cours de réalisation · Consultation uniquement';
    echo '</div>';
});
