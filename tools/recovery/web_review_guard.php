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
$kh_review_notification_test = PHP_SAPI === 'cli'
    && getenv('KH2027_NOTIFICATION_TEST_APPROVED') === 'twelve-notifications-once'
    && defined('KH2027_MAIL_TEST') && KH2027_MAIL_TEST === true
    && defined('KH2027_MAIL_TEST_RECIPIENT')
    && is_string(KH2027_MAIL_TEST_RECIPIENT)
    && filter_var(KH2027_MAIL_TEST_RECIPIENT, FILTER_VALIDATE_EMAIL);
$kh_review_mail_test = !$kh_review_notification_test && PHP_SAPI === 'cli'
    && defined('KH2027_MAIL_TEST') && KH2027_MAIL_TEST === true
    && defined('KH2027_MAIL_TEST_RECIPIENT')
    && is_string(KH2027_MAIL_TEST_RECIPIENT)
    && filter_var(KH2027_MAIL_TEST_RECIPIENT, FILTER_VALIDATE_EMAIL);
$kh_review_smtp_test = $kh_review_mail_test || $kh_review_notification_test;
$kh_review_form_ids = array(5, 6, 7, 8, 9, 10);
$kh_review_form_test_until = false;
if (defined('KH2027_FORM_TEST_UNTIL') && is_string(KH2027_FORM_TEST_UNTIL)) {
    $kh_review_form_test_date = DateTimeImmutable::createFromFormat('!Y-m-d\TH:i:sP', KH2027_FORM_TEST_UNTIL);
    $kh_review_form_test_issues = DateTimeImmutable::getLastErrors();
    if ($kh_review_form_test_date
        && (!$kh_review_form_test_issues
            || (!$kh_review_form_test_issues['warning_count'] && !$kh_review_form_test_issues['error_count']))
        && $kh_review_form_test_date->format('Y-m-d\TH:i:sP') === KH2027_FORM_TEST_UNTIL) {
        $kh_review_form_test_until = $kh_review_form_test_date->getTimestamp();
    }
}
unset($kh_review_form_test_date, $kh_review_form_test_issues);
$kh_review_form_test = PHP_SAPI !== 'cli'
    && $kh_review_form_test_until !== false
    && $kh_review_form_test_until >= time()
    && $kh_review_form_test_until <= time() + 2 * HOUR_IN_SECONDS;
$kh_review_disabled_functions = array('mail', 'curl_exec', 'curl_multi_exec', 'pfsockopen',
    'socket_create', 'ftp_connect', 'ftp_ssl_connect',
    'exec', 'shell_exec', 'system', 'passthru', 'popen', 'proc_open', 'pcntl_exec', 'dl');
if (!$kh_review_smtp_test) {
    $kh_review_disabled_functions[] = 'fsockopen';
    $kh_review_disabled_functions[] = 'stream_socket_client';
} elseif (!function_exists('fsockopen') || !function_exists('stream_socket_client')) {
    $kh_review_valid = false;
}
foreach ($kh_review_disabled_functions as $function) {
    if (function_exists($function)) { $kh_review_valid = false; }
}
unset($kh_review_disabled_functions);
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

function kh_review_form_notifications_are_inert($form_id) {
    global $wpdb;
    $rows = $wpdb->get_col($wpdb->prepare(
        "SELECT value FROM {$wpdb->prefix}fluentform_form_meta WHERE form_id = %d AND meta_key = 'notifications'",
        $form_id
    ));
    if (count($rows) !== 2) { return false; }
    foreach ($rows as $row) {
        $notification = json_decode($row, true);
        if (!is_array($notification) || ($notification['enabled'] ?? null) !== false
            || !empty($notification['cc']) || !empty($notification['bcc'])
            || !empty($notification['attachments'])) {
            return false;
        }
    }
    return true;
}

$kh_review_form_test_request = false;
if (PHP_SAPI !== 'cli' && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && $kh_review_form_test) {
    $kh_review_request_path = wp_parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    $kh_review_form_id = filter_input(INPUT_POST, 'form_id', FILTER_VALIDATE_INT);
    $kh_review_form_data = isset($_POST['data']) && is_string($_POST['data']) ? $_POST['data'] : '';
    $kh_review_form_values = array();
    if ($kh_review_form_data !== '' && strlen($kh_review_form_data) <= 16384) {
        parse_str($kh_review_form_data, $kh_review_form_values);
    }
    $kh_review_nonce_key = '_fluentform_' . (int) $kh_review_form_id . '_fluentformnonce';
    $kh_review_test_email = $kh_review_form_values['email'] ?? '';
    $kh_review_form_test_request = $kh_review_request_path === '/wp-admin/admin-ajax.php'
        && ($_POST['action'] ?? '') === 'fluentform_submit'
        && in_array((int) $kh_review_form_id, $kh_review_form_ids, true)
        && kh_review_form_notifications_are_inert((int) $kh_review_form_id)
        && empty($_FILES)
        && is_string($kh_review_test_email)
        && preg_match('/^kh2027-http-[a-z0-9-]+@example\.invalid$/', $kh_review_test_email)
        && isset($kh_review_form_values[$kh_review_nonce_key])
        && is_string($kh_review_form_values[$kh_review_nonce_key]);
}
if (PHP_SAPI !== 'cli' && ((!in_array($_SERVER['REQUEST_METHOD'] ?? '', array('GET', 'HEAD'), true)
        && !$kh_review_form_test_request)
    || isset($_GET['add-to-cart']) || isset($_GET['wc-ajax']) || isset($_GET['wc-api']))) {
    http_response_code(405);
    header('Allow: GET, HEAD');
    exit('La première visite du site de test est en lecture seule.');
}
unset($kh_review_form_test_request, $kh_review_request_path, $kh_review_form_id,
    $kh_review_form_data, $kh_review_form_values, $kh_review_nonce_key, $kh_review_test_email);

add_filter('option_active_plugins', function ($plugins) {
    return array_values(array_filter($plugins, function ($plugin) {
        return in_array($plugin, array('woocommerce/woocommerce.php', 'polylang/polylang.php',
            'polylang-wc/polylang-wc.php', 'kh-single-variation-display/kh-single-variation-display.php',
            'fluentform/fluentform.php', 'fluent-smtp/fluent-smtp.php'), true);
    }));
});
add_filter('pre_http_request', function () { return new WP_Error('kh2027_offline', 'External requests disabled for review.'); }, PHP_INT_MAX);
if ($kh_review_notification_test) {
    function kh_review_notification_test_limits() {
        return array(
            '[Koinobori House] Nouvelle demande — Contact' => 1,
            '[Koinobori House] New enquiry — Contact' => 1,
            '[Koinobori House] Nouvelle demande — Entreprises' => 1,
            '[Koinobori House] New enquiry — Business' => 1,
            '[Koinobori House] Nouvelle demande — Collectivités' => 1,
            '[Koinobori House] New enquiry — Institutions' => 1,
            'Koinobori House — Nous avons reçu votre demande' => 3,
            'Koinobori House — We have received your enquiry' => 3,
        );
    }
    function kh_review_notification_test_headers($headers) {
        if (!is_array($headers)) {
            $headers = preg_split('/\r?\n/', (string) $headers);
        }
        return array_values(array_filter(array_map('trim', $headers), 'strlen'));
    }
    function kh_review_notification_test_header_values($headers, $name) {
        $values = array();
        foreach (kh_review_notification_test_headers($headers) as $header) {
            if (preg_match('/^' . preg_quote($name, '/') . '\s*:\s*(.+)$/i', $header, $match)) {
                $values[] = trim($match[1]);
            }
        }
        return $values;
    }
    function kh_review_notification_test_message_is_synthetic($message) {
        $string = is_string($message);
        $length = $string ? strlen($message) : -1;
        $addresses_allowed = true;
        $address_count = 0;
        if ($string) {
            preg_match_all('/[A-Z0-9._%+\-]+@[A-Z0-9.\-]+\.[A-Z]{2,}/i', $message, $matches);
            $addresses = array_unique($matches[0]);
            $address_count = count($addresses);
            foreach ($addresses as $address) {
                if (strcasecmp($address, 'contact@koinoborihouse.com') !== 0
                    && !preg_match('/^kh2027-http-[a-z0-9-]+@example\.invalid$/i', $address)) {
                    $addresses_allowed = false;
                }
            }
        }
        $checks = array(
            'is_string' => $string,
            'length_min' => $length >= 40,
            'length_max' => $length <= 50000,
            'html_present' => $string && strpos($message, '<') !== false,
            'addresses_allowed' => $addresses_allowed,
            'length' => $length,
            'address_count' => $address_count,
        );
        $GLOBALS['kh_review_notification_test_message_checks'] = $checks;
        return $checks['is_string'] && $checks['length_min'] && $checks['length_max']
            && $checks['html_present'] && $checks['addresses_allowed'];
    }
    function kh_review_notification_test_wp_mail($atts) {
        $limits = kh_review_notification_test_limits();
        $subject = isset($atts['subject']) && is_string($atts['subject']) ? $atts['subject'] : '';
        $headers = kh_review_notification_test_headers($atts['headers'] ?? array());
        $from = kh_review_notification_test_header_values($headers, 'From');
        $reply_to = kh_review_notification_test_header_values($headers, 'Reply-To');
        $copies = array_merge(
            kh_review_notification_test_header_values($headers, 'Cc'),
            kh_review_notification_test_header_values($headers, 'Bcc')
        );
        $reply_valid = count($reply_to) <= 1;
        if ($reply_to) {
            $reply_value = preg_replace('/^.*<([^>]+)>.*$/', '$1', $reply_to[0]);
            $reply_valid = strcasecmp($reply_value, 'contact@koinoborihouse.com') === 0
                || preg_match('/^kh2027-http-[a-z0-9-]+@example\.invalid$/i', $reply_value);
        }
        $checks = array(
            'subject_allowed' => isset($limits[$subject]),
            'subject_below_limit' => isset($limits[$subject])
                && ($GLOBALS['kh_review_notification_test_counts'][$subject] ?? 0) < $limits[$subject],
            'from_valid' => $from === array('Koinobori House <contact@koinoborihouse.com>'),
            'reply_to_valid' => $reply_valid,
            'copies_absent' => !$copies,
            'attachments_absent' => empty($atts['attachments']),
            'message_synthetic' => kh_review_notification_test_message_is_synthetic($atts['message'] ?? null),
        );
        if (in_array(false, $checks, true)) {
            $GLOBALS['kh_review_notification_test_last_block'] = $checks;
            $atts['subject'] = '[KH2027 BLOCKED]';
            return $atts;
        }
        ++$GLOBALS['kh_review_notification_test_counts'][$subject];
        $atts['to'] = array(KH2027_MAIL_TEST_RECIPIENT);
        $atts['subject'] = '[KH2027 NOTIFICATION TEST] ' . $subject;
        $atts['headers'] = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: Koinobori House <contact@koinoborihouse.com>',
            'Reply-To: Koinobori House <contact@koinoborihouse.com>',
        );
        $atts['attachments'] = array();
        return $atts;
    }
    function kh_review_notification_test_pre_wp_mail($return, $atts) {
        $subject = isset($atts['subject']) && is_string($atts['subject']) ? $atts['subject'] : '';
        $prefix = '[KH2027 NOTIFICATION TEST] ';
        $original_subject = 0 === strpos($subject, $prefix) ? substr($subject, strlen($prefix)) : '';
        $limits = kh_review_notification_test_limits();
        $to = $atts['to'] ?? array();
        if (!is_array($to)) { $to = array($to); }
        $headers = kh_review_notification_test_headers($atts['headers'] ?? array());
        $reply_to = kh_review_notification_test_header_values($headers, 'Reply-To');
        $reply_value = $reply_to
            ? preg_replace('/^.*<([^>]+)>.*$/', '$1', $reply_to[0])
            : '';
        $observed = $GLOBALS['kh_review_notification_test_counts'][$original_subject] ?? 0;
        $valid = isset($limits[$original_subject])
            && $observed >= 1 && $observed <= $limits[$original_subject]
            && $to === array(KH2027_MAIL_TEST_RECIPIENT)
            && kh_review_notification_test_header_values($headers, 'From')
                === array('Koinobori House <contact@koinoborihouse.com>')
            && count($reply_to) <= 1
            && (!$reply_to || strcasecmp($reply_value, 'contact@koinoborihouse.com') === 0
                || preg_match('/^kh2027-http-[a-z0-9-]+@example\.invalid$/i', $reply_value))
            && !kh_review_notification_test_header_values($headers, 'Cc')
            && !kh_review_notification_test_header_values($headers, 'Bcc')
            && empty($atts['attachments'])
            && kh_review_notification_test_message_is_synthetic($atts['message'] ?? null);
        return $valid ? $return : new WP_Error(
            'kh2027_notification_mail_blocked',
            'Mail outside the isolated notification test was blocked.'
        );
    }
    $GLOBALS['kh_review_notification_test_counts'] = array_fill_keys(
        array_keys(kh_review_notification_test_limits()),
        0
    );
    add_filter('wp_mail', 'kh_review_notification_test_wp_mail', PHP_INT_MAX);
    add_filter('pre_wp_mail', 'kh_review_notification_test_pre_wp_mail', -PHP_INT_MAX, 2);
    add_filter('fluentform/email_to', function () {
        return KH2027_MAIL_TEST_RECIPIENT;
    }, PHP_INT_MAX, 4);
} elseif ($kh_review_mail_test) {
    function kh_review_mail_test_subject() {
        return '[KH2027 SMTP TEST] Koinobori House';
    }
    function kh_review_mail_test_message() {
        return "Test technique du transport SMTP Brevo.\nAucune demande client n'est jointe a ce message.";
    }
    function kh_review_mail_test_wp_mail($atts) {
        $headers = $atts['headers'] ?? array();
        if (!is_array($headers)) {
            $headers = preg_split('/\r?\n/', (string) $headers);
        }
        $atts['headers'] = array_values(array_filter($headers, function ($header) {
            return !preg_match('/^\s*(Cc|Bcc)\s*:/i', (string) $header);
        }));
        $atts['to'] = array(KH2027_MAIL_TEST_RECIPIENT);
        $atts['attachments'] = array();
        return $atts;
    }
    function kh_review_mail_test_pre_wp_mail($return, $atts) {
        $to = $atts['to'] ?? array();
        $headers = $atts['headers'] ?? array();
        if (!is_array($to)) { $to = array($to); }
        if (!is_array($headers)) { $headers = array($headers); }
        $has_copy = array_filter($headers, function ($header) {
            return preg_match('/^\s*(Cc|Bcc)\s*:/i', (string) $header);
        });
        $valid = $to === array(KH2027_MAIL_TEST_RECIPIENT)
            && ($atts['subject'] ?? '') === kh_review_mail_test_subject()
            && ($atts['message'] ?? '') === kh_review_mail_test_message()
            && empty($atts['attachments']) && !$has_copy;
        return $valid ? $return : new WP_Error('kh2027_mail_blocked', 'Mail outside the isolated transport test was blocked.');
    }
    add_filter('wp_mail', 'kh_review_mail_test_wp_mail', PHP_INT_MAX);
    add_filter('pre_wp_mail', 'kh_review_mail_test_pre_wp_mail', -PHP_INT_MAX, 2);
} else {
    add_filter('pre_wp_mail', '__return_true', PHP_INT_MAX);
}
unset($kh_review_mail_test, $kh_review_notification_test, $kh_review_smtp_test);
add_filter('woocommerce_available_payment_gateways', '__return_empty_array', PHP_INT_MAX);
add_filter('action_scheduler_allow_async_request_runner', '__return_false', PHP_INT_MAX);
add_filter('xmlrpc_enabled', '__return_false');
add_filter('rest_authentication_errors', function () { return new WP_Error('kh2027_review', 'REST closed for visual review.', array('status' => 403)); }, PHP_INT_MAX);
add_filter('pre_option_blog_public', function () { return '0'; });
add_filter('comments_open', '__return_false');
add_filter('woocommerce_loop_add_to_cart_link', function ($html, $product) {
    $english = function_exists('pll_current_language') && pll_current_language() === 'en';
    return '<a class="button" href="' . esc_url($product->get_permalink()) . '">'
        . ($english ? 'View piece' : 'Voir la pièce') . '</a>';
}, 10, 2);
add_action('wp_enqueue_scripts', function () {
    foreach (array('wc-add-to-cart', 'wc-cart-fragments', 'wc-order-attribution') as $script) {
        wp_dequeue_script($script);
    }
}, 100);
add_action('wp_head', function () {
    echo '<style>.single-product .single_add_to_cart_button,.single-product form.cart .quantity{display:none!important}</style>';
});

// Select the trial layouts without changing page metadata or theme settings in SQL.
add_action('after_setup_theme', function () {
    require_once get_stylesheet_directory() . '/inc/editorial.php';
    require_once get_stylesheet_directory() . '/inc/header.php';
    require_once get_stylesheet_directory() . '/inc/footer.php';
    require_once get_stylesheet_directory() . '/inc/enquiries.php';
    require_once WP_PLUGIN_DIR . '/kh-product-media/kh-product-media.php';
});
// Render the existing enquiry forms for review without presenting a working send action.
// Fluent Forms 6.2.13 Components/SubmitButton.php and FormBuilder.php.
if (!$kh_review_form_test) {
    foreach ($kh_review_form_ids as $kh_review_form_id) {
        add_filter('fluentform/is_hide_submit_btn_' . $kh_review_form_id, '__return_true');
    }
}
unset($kh_review_form_id);
add_filter('fluentform/nonce_verify', function ($enabled, $form_id) use ($kh_review_form_test, $kh_review_form_ids) {
    return $kh_review_form_test && in_array((int) $form_id, $kh_review_form_ids, true) ? true : $enabled;
}, PHP_INT_MAX, 2);
add_action('fluentform/before_form_render', function ($form) use ($kh_review_form_test, $kh_review_form_ids) {
    if (!in_array((int) $form->id, $kh_review_form_ids, true)) { return; }
    $english = in_array((int) $form->id, array(6, 8, 10), true);
    if ($kh_review_form_test) {
        echo '<p class="kh-enquiry-preview-note" role="note">' . esc_html($english
            ? 'Isolated test mode. Use synthetic data only; email delivery is disabled.'
            : 'Mode de recette isolé. Utilisez uniquement des données fictives ; les emails sont désactivés.') . '</p>';
    } else {
        echo '<p class="kh-enquiry-preview-note" role="note">' . esc_html($english
            ? 'Form preview. Sending is disabled in this private environment.'
            : 'Aperçu du formulaire. L’envoi est désactivé dans cet environnement privé.') . '</p>';
    }
});
add_action('wp_enqueue_scripts', function () {
    // The restored theme may predate registration of the editorial dependency.
    if (!wp_style_is('kh-charte-v3', 'registered')) {
        $file = '/assets/css/kh-charte-v3.css';
        wp_enqueue_style('kh-charte-v3', get_stylesheet_directory_uri() . $file,
            array('kh-foundations'), filemtime(get_stylesheet_directory() . $file));
    }
}, 24);
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
add_action('kadence_after_header', function () {
    $english = function_exists('pll_current_language') && pll_current_language() === 'en';
    echo '<div role="note" style="padding:9px 18px;background:#f8f4ee;color:#1a1410;text-align:center;font:13px/1.5 sans-serif;border-bottom:1px solid #d5cfc5">';
    echo $english ? 'Private preview · Work in progress · Browsing only' : 'Aperçu privé · Site en cours de réalisation · Consultation uniquement';
    if (function_exists('pll_home_url')) {
        echo ' · <a href="' . esc_url(pll_home_url('fr')) . '" lang="fr" aria-label="Accueil en français">FR</a>';
        echo ' / <a href="' . esc_url(pll_home_url('en')) . '" lang="en" aria-label="Home in English">EN</a>';
    }
    echo '</div>';
});
