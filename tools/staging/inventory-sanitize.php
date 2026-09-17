<?php
/**
 * Produce the publishable version of a private staging inventory.
 *
 * Usage (local machine, no WordPress):
 *   php tools/staging/inventory-sanitize.php <private.json> <output-dir>
 *
 * Removes every private key, reduces URLs to scheme://host/path, then refuses
 * to write if any secret, email, server path, database name or query string
 * survives. Writes <name>.public.json and <name>.public.json.sha256.
 */

declare(strict_types=1);

if ( 'cli' !== PHP_SAPI ) {
	fwrite( STDERR, "CLI only.\n" );
	exit( 2 );
}
if ( $argc < 3 ) {
	fwrite( STDERR, "Usage: php inventory-sanitize.php <private.json> <output-dir>\n" );
	exit( 2 );
}

$input_path = $argv[1];
$out_dir    = rtrim( $argv[2], "/\\" );
$raw        = file_get_contents( $input_path );
if ( false === $raw ) {
	fwrite( STDERR, "Cannot read input.\n" );
	exit( 2 );
}
$data = json_decode( $raw, true, 512, JSON_THROW_ON_ERROR );
if ( ! isset( $data['manifest'], $data['options'] ) ) {
	fwrite( STDERR, "Not an inventory file.\n" );
	exit( 2 );
}

function kh_san_url( string $url ): string {
	$parts = parse_url( $url );
	if ( ! is_array( $parts ) || empty( $parts['host'] ) ) {
		return '' !== $url && '/' === $url[0] ? (string) strtok( $url, '?#' ) : '';
	}
	return ( $parts['scheme'] ?? 'https' ) . '://' . $parts['host'] . ( $parts['path'] ?? '/' );
}

// 1. Drop private keys. Embedded checksums (legacy exports) are dropped too:
// the external .sha256 file is the only reference for the final file.
unset( $data['paths'], $data['orders_by_status'], $data['users_by_role'], $data['manifest']['sha256_private'], $data['manifest']['sha256_public'] );
foreach ( $data['forms'] ?? array() as $i => $form ) {
	unset( $data['forms'][ $i ]['notifications'], $data['forms'][ $i ]['submissions_count'] );
}

// 2. Reduce URLs.
foreach ( array( 'siteurl', 'home' ) as $key ) {
	if ( isset( $data['options'][ $key ] ) ) {
		$data['options'][ $key ] = kh_san_url( (string) $data['options'][ $key ] );
	}
}
foreach ( $data['menus'] ?? array() as $mi => $menu ) {
	foreach ( $menu['items'] ?? array() as $ii => $item ) {
		$data['menus'][ $mi ]['items'][ $ii ]['url'] = kh_san_url( (string) ( $item['url'] ?? '' ) );
	}
}

$data['manifest']['sections_included'] = array_values( array_diff( array_keys( $data ), array( 'manifest' ) ) );
$data['manifest']['sections_omitted']  = array_values( array_unique( array_merge(
	(array) ( $data['manifest']['sections_omitted'] ?? array() ),
	array( 'paths', 'orders_by_status', 'users_by_role', 'forms.notifications', 'forms.submissions_count' )
) ) );
$public = json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR ) . "\n";

// 3. Blocking scans on the serialized output.
$scans = array(
	'secrets'      => '/xkeysib-|\bsk_(live|test)_|\bpk_(live|test)_|\bwhsec_|BEGIN (RSA|EC|OPENSSH|PRIVATE) |\bBearer [A-Za-z0-9._\-]{20,}|\bAKIA[0-9A-Z]{12,}/',
	'emails'       => '/[A-Za-z0-9._%+\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}/',
	'server_paths' => '#/home3?/|public_html|kh2027-private#',
	'db_names'     => '/heal3867|sc3heal3867|wp320|wp354/',
	'query_string' => '/"url":\s*"[^"]*[?#]/',
);
$hits = array();
foreach ( $scans as $name => $pattern ) {
	if ( preg_match_all( $pattern, $public, $m, PREG_OFFSET_CAPTURE ) ) {
		foreach ( $m[0] as $match ) {
			$hits[] = $name . ' @' . $match[1] . ' (' . substr( $match[0], 0, 12 ) . '…)';
		}
	}
}
if ( $hits ) {
	fwrite( STDERR, "REFUSED, " . count( $hits ) . " hit(s):\n" . implode( "\n", $hits ) . "\n" );
	exit( 1 );
}
json_decode( $public, true, 512, JSON_THROW_ON_ERROR );

// 4. Write public file + checksum.
$base     = preg_replace( '/\.private\.json$/', '', basename( $input_path ) );
$out_path = $out_dir . '/' . $base . '.public.json';
if ( file_exists( $out_path ) ) {
	fwrite( STDERR, "Output exists, refusing to overwrite.\n" );
	exit( 1 );
}
file_put_contents( $out_path, $public );
chmod( $out_path, 0600 );
$sha = hash_file( 'sha256', $out_path );
file_put_contents( $out_path . '.sha256', $sha . '  ' . basename( $out_path ) . "\n" );

echo json_encode( array( 'written' => $out_path, 'sha256' => $sha, 'private_sha256_local_log_only' => hash( 'sha256', $raw ) ), JSON_PRETTY_PRINT ) . "\n";
