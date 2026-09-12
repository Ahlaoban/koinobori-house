<?php
/**
 * CLI-only, offline cleanup of the named private recovery database.
 * Does not bootstrap WordPress, load plugins, expose a Web root or touch files.
 * plan: read only; rehearse: mutate, validate and roll back; apply: commit.
 * The wrapper must supply a verified, current private SQL backup for mutations.
 */
declare(strict_types=1);

const KH_DB = 'sc3heal3867_kh2027drill';
const KH_ROOT = '/home3/sc3heal3867/kh2027-private';
const KH_CONTROL = KH_ROOT . '/runtime-control';

function must(bool $ok, string $message): void {
    if (!$ok) { throw new RuntimeException($message); }
}
function ident(string $name): string {
    must((bool) preg_match('/^[a-zA-Z0-9_]+$/D', $name), 'Invalid identifier');
    return '`' . $name . '`';
}
function rows(mysqli $db, string $sql): array {
    $r = $db->query($sql);
    must($r instanceof mysqli_result, 'Expected row result');
    return $r->fetch_all(MYSQLI_ASSOC);
}
function digest(mysqli $db, string $query): string {
    // Sort row hashes so the result is independent of the storage engine order.
    $hashes = [];
    foreach (rows($db, $query) as $row) {
        $hashes[] = hash('sha256', serialize($row));
    }
    sort($hashes, SORT_STRING);
    return hash('sha256', implode('', $hashes));
}
function db_state(mysqli $db, array $tables): array {
    $out = [];
    foreach ($tables as $table => $engine) {
        $out[$table] = digest($db, 'SELECT * FROM ' . ident($table));
    }
    return $out;
}
function save_report(string $path, array $value): void {
    $f = fopen($path, 'x');
    must($f !== false, 'Report already exists or cannot be created');
    $json = json_encode($value, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR) . "\n";
    must(fwrite($f, $json) === strlen($json), 'Incomplete report');
    fclose($f);
}

must(PHP_SAPI === 'cli', 'CLI only');
umask(0077);
$mode = $argv[1] ?? 'plan';
must(in_array($mode, ['plan', 'rehearse', 'apply'], true), 'Unknown mode');
must(realpath(__DIR__) === KH_CONTROL, 'Must run from the private control directory');
$webGuard = file_get_contents('/home3/sc3heal3867/public_html/.htaccess');
must(is_string($webGuard) && preg_match('/^\s*Require\s+all\s+denied\s*$/mi', $webGuard) === 1,
    'Web root must remain denied');
$creds = json_decode(file_get_contents(KH_ROOT . '/sql-drill-20260910/credentials.json'), true, 512, JSON_THROW_ON_ERROR);
must($creds['database'] === KH_DB && $creds['user'] === 'sc3heal3867_khdrill', 'Wrong database credentials');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = new mysqli($creds['host'], $creds['user'], $creds['password'], KH_DB);
unset($creds);
$db->set_charset('utf8mb4');
$identity = rows($db, 'SELECT DATABASE() AS db, CURRENT_USER() AS account')[0];
must($identity['db'] === KH_DB && strpos($identity['account'], 'sc3heal3867_khdrill@') === 0, 'Wrong SQL identity');
$tables = [];
foreach (rows($db, "SELECT TABLE_NAME, ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() ORDER BY TABLE_NAME") as $t) {
    must(strpos($t['TABLE_NAME'], 'wprs_') === 0, 'Unexpected table prefix');
    $tables[$t['TABLE_NAME']] = $t['ENGINE'];
}
must(count($tables) === 94, 'Schema changed: review required');
$ops = [];
function op(string $label, string $table, array $columns, string $sql, string $check): void {
    global $db, $tables, $ops;
    $name = 'wprs_' . $table;
    must(isset($tables[$name]) && $tables[$name] === 'InnoDB', 'Missing or nontransactional target: ' . $name);
    $actual = array_column(rows($db, 'SHOW COLUMNS FROM ' . ident($name)), 'Field');
    must(count(array_diff($columns, $actual)) === 0, 'Required columns missing: ' . $name);
    $ops[] = ['label' => $label, 'table' => $name, 'sql' => $sql, 'check' => $check];
}
function wipe(string $table): void {
    op('empty_' . $table, $table, [], 'DELETE FROM ' . ident('wprs_' . $table), 'SELECT COUNT(*) AS n FROM ' . ident('wprs_' . $table));
}

// Expunge copied execution payloads, sessions, personal submissions and logs.
// DELETE is intentional: unlike TRUNCATE it participates in the transaction.
$empty = [
    'actionscheduler_logs','actionscheduler_actions','actionscheduler_claims',
    'ff_scheduled_actions','fluentform_entry_details','fluentform_form_analytics',
    'fluentform_logs','fluentform_submissions','fluentform_submission_meta','fsmtp_email_logs',
    'commentmeta','wc_download_log','wc_email_unsubscribers','wc_rate_limits','wc_reserved_stock',
    'wc_webhooks','woocommerce_api_keys','woocommerce_downloadable_product_permissions',
    'woocommerce_log','woocommerce_payment_tokenmeta','woocommerce_payment_tokens','woocommerce_sessions',
    'wc_admin_note_actions','wc_admin_notes','litespeed_url_file','litespeed_url',
    'cmplz_dnsmpd','seopress_content_analysis',
];
// Wordfence's copied security history and authentication secrets are disposable
// in this offline clone. Its MEMORY role-count table is deliberately untouched.
foreach (array_keys($tables) as $table) {
    if (strpos($table, 'wprs_wf') === 0 && $table !== 'wprs_wfls_role_counts') { $empty[] = substr($table, 5); }
}
foreach ($empty as $table) { wipe($table); }

op('users', 'users', ['ID','user_login','user_pass','user_nicename','user_email','user_url','user_activation_key','display_name'],
    "UPDATE wprs_users SET user_login=CONCAT('test-user-',ID),user_pass=CONCAT('!disabled-kh2027-',ID),user_nicename=CONCAT('test-user-',ID),user_email=CONCAT('user-',ID,'@example.invalid'),user_url='',user_activation_key='',display_name=CONCAT('Test User ',ID)",
    "SELECT COUNT(*) AS n FROM wprs_users WHERE user_login<>CONCAT('test-user-',ID) OR user_pass<>CONCAT('!disabled-kh2027-',ID) OR user_nicename<>CONCAT('test-user-',ID) OR user_email<>CONCAT('user-',ID,'@example.invalid') OR user_url<>'' OR user_activation_key<>'' OR display_name<>CONCAT('Test User ',ID)");
$userKeep = "'wprs_capabilities','wprs_user_level','locale','rich_editing','syntax_highlighting','show_admin_bar_front','paying_customer'";
op('user_metadata', 'usermeta', ['meta_key'],
    "DELETE FROM wprs_usermeta WHERE meta_key NOT IN ($userKeep)",
    "SELECT COUNT(*) AS n FROM wprs_usermeta WHERE meta_key NOT IN ($userKeep)");

$email = "CONCAT(IF(customer_id>0,CONCAT('user-',customer_id),CONCAT('order-',id)),'@example.invalid')";
op('orders', 'wc_orders', ['id','customer_id','billing_email','transaction_id','ip_address','user_agent','customer_note'],
    "UPDATE wprs_wc_orders SET billing_email=$email,transaction_id='',ip_address='',user_agent='',customer_note=''",
    "SELECT COUNT(*) AS n FROM wprs_wc_orders WHERE NOT(billing_email <=> $email) OR transaction_id<>'' OR ip_address<>'' OR user_agent<>'' OR customer_note<>''");
op('order_metadata', 'wc_orders_meta', ['meta_key'],
    "DELETE FROM wprs_wc_orders_meta WHERE meta_key<>'is_vat_exempt'",
    "SELECT COUNT(*) AS n FROM wprs_wc_orders_meta WHERE meta_key<>'is_vat_exempt'");
op('addresses', 'wc_order_addresses', ['order_id','first_name','last_name','company','address_1','address_2','city','state','postcode','email','phone'],
    "UPDATE wprs_wc_order_addresses a JOIN wprs_wc_orders o ON o.id=a.order_id SET a.first_name='Test',a.last_name=CONCAT('Customer ',IF(o.customer_id>0,o.customer_id,o.id)),a.company='',a.address_1='Test address',a.address_2='',a.city='Test city',a.state='',a.postcode='',a.email=o.billing_email,a.phone=''",
    "SELECT COUNT(*) AS n FROM wprs_wc_order_addresses a LEFT JOIN wprs_wc_orders o ON o.id=a.order_id WHERE o.id IS NULL OR a.first_name<>'Test' OR a.last_name<>CONCAT('Customer ',IF(o.customer_id>0,o.customer_id,o.id)) OR a.company<>'' OR a.address_1<>'Test address' OR a.address_2<>'' OR a.city<>'Test city' OR a.state<>'' OR a.postcode<>'' OR NOT(a.email <=> o.billing_email) OR a.phone<>''");
op('customer_lookup', 'wc_customer_lookup', ['customer_id','user_id','username','first_name','last_name','email','city','state','postcode'],
    "UPDATE wprs_wc_customer_lookup SET username=CONCAT('test-user-',COALESCE(NULLIF(user_id,0),customer_id)),first_name='Test',last_name=CONCAT('Customer ',customer_id),email=CONCAT(IF(user_id>0,CONCAT('user-',user_id),CONCAT('customer-',customer_id)),'@example.invalid'),city='Test city',state='',postcode=''",
    "SELECT COUNT(*) AS n FROM wprs_wc_customer_lookup WHERE username<>CONCAT('test-user-',COALESCE(NULLIF(user_id,0),customer_id)) OR first_name<>'Test' OR last_name<>CONCAT('Customer ',customer_id) OR email<>CONCAT(IF(user_id>0,CONCAT('user-',user_id),CONCAT('customer-',customer_id)),'@example.invalid') OR city<>'Test city' OR state<>'' OR postcode<>''");
op('order_keys', 'wc_order_operational_data', ['order_id','cart_hash','order_key'],
    "UPDATE wprs_wc_order_operational_data SET cart_hash='',order_key=CONCAT('wc_order_test_',order_id)",
    "SELECT COUNT(*) AS n FROM wprs_wc_order_operational_data WHERE cart_hash<>'' OR order_key<>CONCAT('wc_order_test_',order_id)");
op('comments', 'comments', ['comment_ID','comment_author','comment_author_email','comment_author_url','comment_author_IP','comment_content','comment_agent'],
    "UPDATE wprs_comments SET comment_author='Test User',comment_author_email=CONCAT('comment-',comment_ID,'@example.invalid'),comment_author_url='',comment_author_IP='',comment_content='Test comment',comment_agent=''",
    "SELECT COUNT(*) AS n FROM wprs_comments WHERE comment_author<>'Test User' OR comment_author_email<>CONCAT('comment-',comment_ID,'@example.invalid') OR comment_author_url<>'' OR comment_author_IP<>'' OR comment_content<>'Test comment' OR comment_agent<>''");

// Empty integration settings as complete serialized values; never regex-edit
// serialized strings. Shipping/tax/theme/language configuration is preserved.
$dropOptions = "option_name REGEXP '^(updraft|ppcp|wordfence|wfls|fsmtp|fluent_smtp|_fluentsmtp|litespeed|wpseo|softaculous)'"
    . " OR option_name REGEXP '^woocommerce_(ppcp|paypal|stripe|pay-later)'"
    . " OR option_name REGEXP '^(_transient_|_site_transient_)'"
    . " OR option_name IN ('cron','recovery_keys','recently_edited','polylang_licenses','mailserver_login','mailserver_pass','mailserver_url','mailserver_port','cmplz_wsc_webhook_secret','cmplz_wsc_webhook','cmplz_wsc_scan_id','cmplz_wsc_scan','cmplz_wsc_scan_createdAt','_fluentform_global_form_settings','jetpack_options','jetpack_active_plugins','auto_update_plugins')"
    . " OR option_name REGEXP '^edd_sl_'";
op('integration_options', 'options', ['option_name'],
    "DELETE FROM wprs_options WHERE $dropOptions", "SELECT COUNT(*) AS n FROM wprs_options WHERE $dropOptions");
op('test_admin_email', 'options', ['option_name','option_value'],
    "UPDATE wprs_options SET option_value='admin@example.invalid' WHERE option_name IN ('admin_email','new_admin_email','woocommerce_email_from_address','woocommerce_email_reply_to_address','woocommerce_stock_email_recipient')",
    "SELECT COUNT(*) AS n FROM wprs_options WHERE option_name IN ('admin_email','new_admin_email','woocommerce_email_from_address','woocommerce_email_reply_to_address','woocommerce_stock_email_recipient') AND option_value<>'admin@example.invalid'");
op('no_index', 'options', ['option_name','option_value'],
    "UPDATE wprs_options SET option_value='0' WHERE option_name='blog_public'",
    "SELECT COUNT(*) AS n FROM wprs_options WHERE option_name='blog_public' AND option_value<>'0'");
op('form_notifications', 'fluentform_form_meta', ['meta_key'],
    "DELETE FROM wprs_fluentform_form_meta WHERE meta_key IN ('notifications','_revision')",
    "SELECT COUNT(*) AS n FROM wprs_fluentform_form_meta WHERE meta_key IN ('notifications','_revision')");

// Assert exact invariants, including all untouched tables and selected columns
// in changed tables. Raw values and hashes of individual people are not logged.
$invariants = [
    'orders' => 'SELECT id,status,currency,type,tax_amount,total_amount,customer_id,date_created_gmt,date_updated_gmt,parent_order_id,payment_method,payment_method_title FROM wprs_wc_orders',
    'order_operations' => 'SELECT id,order_id,created_via,woocommerce_version,prices_include_tax,coupon_usages_are_counted,download_permission_granted,new_order_email_sent,order_stock_reduced,date_paid_gmt,date_completed_gmt,shipping_tax_amount,shipping_total_amount,discount_tax_amount,discount_total_amount,recorded_sales FROM wprs_wc_order_operational_data',
    'address_links' => 'SELECT id,order_id,address_type,country FROM wprs_wc_order_addresses',
    'customer_links' => 'SELECT customer_id,user_id,date_last_active,date_registered,country FROM wprs_wc_customer_lookup',
    'user_links' => 'SELECT ID,user_registered,user_status FROM wprs_users',
    'user_roles' => "SELECT * FROM wprs_usermeta WHERE meta_key IN ($userKeep)",
    'comment_links' => 'SELECT comment_ID,comment_post_ID,comment_date,comment_date_gmt,comment_approved,comment_type,comment_parent,user_id FROM wprs_comments',
    'shipping_options' => "SELECT * FROM wprs_options WHERE option_name REGEXP '^woocommerce_(flat_rate|free_shipping|local_pickup|shipping|tax)'",
    'language_options' => "SELECT * FROM wprs_options WHERE option_name IN ('polylang','polylang-wc','show_on_front','page_on_front','page_for_posts')",
];
$changed = array_unique(array_column($ops, 'table'));
foreach ($tables as $table => $engine) {
    if (!in_array($table, $changed, true)) { $invariants[$table] = 'SELECT * FROM ' . ident($table); }
}
$before = db_state($db, $tables);
$baseline = [];
foreach ($invariants as $name => $query) { $baseline[$name] = digest($db, $query); }
$fingerprint = hash('sha256', serialize($before));
$report = ['mode'=>$mode,'database'=>KH_DB,'script_sha256'=>hash_file('sha256',__FILE__),
    'before_fingerprint'=>$fingerprint,'operations'=>array_column($ops,'label'),
    'invariants'=>array_keys($invariants),'warning'=>'Files and residual content still require separate review; not a Web exposure approval.'];
if ($mode === 'plan') {
    echo json_encode($report, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR) . "\n";
    exit(0);
}
$backupPath = $argv[2] ?? '';
must(realpath(dirname($backupPath)) === KH_CONTROL, 'Backup receipt must be private');
$backup = json_decode(file_get_contents($backupPath), true, 512, JSON_THROW_ON_ERROR);
must($backup['database'] === KH_DB && $backup['fingerprint'] === $fingerprint, 'Backup does not match current database');
must(realpath(dirname($backup['file'])) === KH_CONTROL, 'Wrong backup directory');
must(hash_file('sha256', $backup['file']) === $backup['sha256'] && filesize($backup['file']) > 1000, 'Backup checksum failed');
must($backup['gzip_verified'] === true, 'Backup was not verified');
$db->begin_transaction();
try {
    $counts = [];
    foreach ($ops as $operation) {
        $db->query($operation['sql']);
        $counts[$operation['label']] = $db->affected_rows;
        must((int) rows($db, $operation['check'])[0]['n'] === 0, 'Residual check failed: ' . $operation['label']);
    }
    foreach ($invariants as $name => $query) {
        must(digest($db, $query) === $baseline[$name], 'Invariant changed: ' . $name);
    }
    $report['affected_rows'] = $counts;
    $report['invariants_pass'] = true;
    if ($mode === 'rehearse') {
        $db->rollback();
        must(db_state($db, $tables) === $before, 'Rollback verification failed');
        $report['rollback_verified'] = true;
    } else {
        $db->commit();
        $report['committed'] = true;
    }
} catch (Throwable $error) {
    $db->rollback();
    // No SQL values, credentials or personal data in console output.
    fwrite(STDERR, "Sanitization failed; transaction rolled back. Inspect private execution log.\n");
    throw $error;
}
$report['after_fingerprint'] = hash('sha256', serialize(db_state($db, $tables)));
$report['backup_sha256'] = $backup['sha256'];
save_report(KH_CONTROL . '/sanitize-' . $mode . '-' . gmdate('Ymd\THis\Z') . '.json', $report);
echo json_encode($report, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR) . "\n";
