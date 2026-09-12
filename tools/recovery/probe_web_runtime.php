<?php
/**
 * Temporary, authenticated preflight page. No WordPress bootstrap, credentials,
 * phpinfo(), network calls, email attempts, filesystem writes or database access.
 * Keep behind Apache Basic authentication; remove from Web after the check.
 */
declare(strict_types=1);

header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-store, private');
header('X-Robots-Tag: noindex, nofollow, noarchive');
header("Content-Security-Policy: default-src 'none'; style-src 'unsafe-inline'; frame-ancestors 'none'; base-uri 'none'; form-action 'none'");
header('X-Content-Type-Options: nosniff');

$authenticated = !empty($_SERVER['REMOTE_USER']) || !empty($_SERVER['REDIRECT_REMOTE_USER']);
$https = isset($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) === 'on';
if (PHP_SAPI !== 'cli' && (!$authenticated || !$https)) {
    http_response_code(403);
    exit('Accès privé HTTPS requis.');
}

function kh_preflight_unavailable(array $functions): bool {
    foreach ($functions as $function) {
        if (function_exists($function)) {
            return false;
        }
    }
    return true;
}

$checks = array(
    'Authentification Apache' => $authenticated,
    'Connexion HTTPS' => $https,
    'PHP 8.1 ou supérieur' => PHP_VERSION_ID >= 80100,
    'Envoi mail PHP désactivé' => !function_exists('mail'),
    'Appels HTTP PHP directs désactivés' => kh_preflight_unavailable(array('curl_exec', 'curl_multi_exec', 'fsockopen', 'pfsockopen', 'stream_socket_client')),
    'Sockets et FTP désactivés' => kh_preflight_unavailable(array('socket_create', 'socket_connect', 'ftp_connect', 'ftp_ssl_connect')),
    'Commandes système désactivées' => kh_preflight_unavailable(array('exec', 'shell_exec', 'system', 'passthru', 'popen', 'proc_open', 'pcntl_exec', 'dl')),
    'Lecture et inclusion d’URL désactivées' => !filter_var(ini_get('allow_url_fopen'), FILTER_VALIDATE_BOOLEAN) && !filter_var(ini_get('allow_url_include'), FILTER_VALIDATE_BOOLEAN),
    'Restriction des chemins PHP configurée' => (string) ini_get('open_basedir') !== '',
    'Affichage des erreurs désactivé' => !filter_var(ini_get('display_errors'), FILTER_VALIDATE_BOOLEAN),
);
$passed = !in_array(false, $checks, true);
if (PHP_SAPI === 'cli') {
    echo json_encode(array('passed' => $passed, 'checks' => $checks), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . PHP_EOL;
    exit($passed ? 0 : 1);
}
?>
<!doctype html>
<html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>KH2027 — Accès privé</title>
<style>body{margin:0;background:#f8f4ee;color:#1a1410;font:17px/1.6 Georgia,serif}main{max-width:760px;margin:7vh auto;padding:28px}small{font:12px/1.5 system-ui;letter-spacing:.15em}h1{font-weight:400;font-size:clamp(32px,7vw,48px);line-height:1.1}table{width:100%;border-collapse:collapse;font:15px/1.5 system-ui}th,td{padding:12px 0;text-align:left;border-bottom:1px solid #d5cfc5}th{font-weight:400}td{text-align:right;padding-left:16px;white-space:nowrap}p{max-width:65ch}</style></head>
<body><main><small>KOINOBORI HOUSE · ESPACE DE TEST</small><h1>Accès privé confirmé.</h1>
<p>Votre connexion est reconnue. Cette page vérifie les réglages PHP avant l’installation du site de test.</p>
<table aria-label="Contrôles PHP"><tbody>
<?php foreach ($checks as $label => $ok) : ?>
<tr><th scope="row"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></th><td><?= $ok ? '✓ Vérifié' : 'À configurer' ?></td></tr>
<?php endforeach; ?>
</tbody></table>
<p><?= $passed ? 'Ces contrôles PHP sont satisfaits.' : 'Certains réglages restent à terminer.' ?> Le site WordPress n’est pas encore ouvert. Les parcours de commande et les protections applicatives feront l’objet de tests séparés.</p>
<p>Vous pouvez revenir dans Codex pour poursuivre.</p></main></body></html>
