<?php
/**
 * Read-only inventory of the Koinobori House STAGING WordPress.
 *
 * Run with WP-CLI only, plugins and themes skipped, cron disabled:
 *   KH_INVENTORY_OUT=~/kh2027-private/inventory \
 *   wp eval-file ~/kh2027-private/tools/inventory.php \
 *      --skip-plugins --skip-themes --exec="define('DISABLE_WP_CRON', true);"
 *
 * Fails closed on every guard. Reads inside a READ ONLY transaction that is
 * always rolled back. Writes one private JSON (0600, exclusive create) outside
 * the web root. The public version is produced separately by inventory-sanitize.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ---------------------------------------------------------------------------
// Expected staging identity. KH_STAGING_ROOT stays empty until Alain provides
// the real document root: an empty value makes the realpath guard fail.
// ---------------------------------------------------------------------------
const KH_STAGING_HOST   = 'staging.koinoborihouse.com';
const KH_STAGING_DB     = 'heal3867_wp551';
const KH_FORBIDDEN_DB   = 'wp354';
const KH_STAGING_PREFIX = 'wprs_';
const KH_STAGING_ROOT   = '/home3/heal3867/staging.koinoborihouse.com';
const KH_PRIVATE_ROOT   = '/home3/heal3867/kh2027-private';

const KH_OPTION_ALLOWLIST = array(
	'siteurl', 'home', 'blogname', 'show_on_front', 'page_on_front', 'page_for_posts',
	'permalink_structure', 'WPLANG', 'timezone_string', 'template', 'stylesheet',
	'active_plugins', 'wp_page_for_privacy_policy',
	'woocommerce_currency', 'woocommerce_default_country', 'woocommerce_allowed_countries',
	'woocommerce_specific_allowed_countries', 'woocommerce_ship_to_countries',
	'woocommerce_specific_ship_to_countries', 'woocommerce_calc_taxes',
	'woocommerce_shop_page_id', 'woocommerce_cart_page_id', 'woocommerce_checkout_page_id',
	'woocommerce_myaccount_page_id', 'woocommerce_version',
);
const KH_POLYLANG_KEYS = array( 'default_lang', 'force_lang', 'hide_default', 'redirect_lang', 'browser', 'rewrite', 'media_support' );

global $wpdb;

umask( 0077 );

// ---------------------------------------------------------------------------
// Guards
// ---------------------------------------------------------------------------
$home_host    = (string) wp_parse_url( (string) get_option( 'home' ), PHP_URL_HOST );
$home_scheme  = (string) wp_parse_url( (string) get_option( 'home' ), PHP_URL_SCHEME );
$site_host    = (string) wp_parse_url( (string) get_option( 'siteurl' ), PHP_URL_HOST );
$site_scheme  = (string) wp_parse_url( (string) get_option( 'siteurl' ), PHP_URL_SCHEME );
$out_dir_env  = (string) getenv( 'KH_INVENTORY_OUT' );
$out_dir_real = '' === $out_dir_env ? '' : (string) realpath( $out_dir_env );
$abspath_real = (string) realpath( ABSPATH );

$guards = array(
	'php_sapi_cli'        => 'cli' === PHP_SAPI,
	'wp_cli'              => defined( 'WP_CLI' ) && WP_CLI,
	'environment_staging' => 'staging' === wp_get_environment_type(),
	'home_host'           => KH_STAGING_HOST === $home_host && 'https' === $home_scheme,
	'siteurl_host'        => KH_STAGING_HOST === $site_host && 'https' === $site_scheme,
	'db_name_constant'    => defined( 'DB_NAME' ) && KH_STAGING_DB === DB_NAME,
	'db_name_connection'  => KH_STAGING_DB === $wpdb->dbname,
	'db_not_production'   => ! defined( 'DB_NAME' ) || false === strpos( DB_NAME, KH_FORBIDDEN_DB ),
	'table_prefix'        => KH_STAGING_PREFIX === $wpdb->prefix,
	'abspath_realpath'    => '' !== KH_STAGING_ROOT && $abspath_real === KH_STAGING_ROOT,
	'not_multisite'       => ! is_multisite(),
	'out_dir_private'     => '' !== $out_dir_real && $out_dir_real === (string) realpath( KH_PRIVATE_ROOT . '/inventory' ),
	'out_dir_outside_web' => '' !== $out_dir_real && '' !== $abspath_real && 0 !== strpos( $out_dir_real, $abspath_real ),
);

fwrite( STDERR, 'guards ' . wp_json_encode( $guards ) . PHP_EOL );
foreach ( $guards as $name => $ok ) {
	if ( ! $ok ) {
		throw new RuntimeException( 'Guard failed: ' . $name );
	}
}

$out_path = $out_dir_real . '/staging-inventory-' . gmdate( 'Ymd\THis\Z' ) . '.private.json';
if ( file_exists( $out_path ) ) {
	throw new RuntimeException( 'Output already exists.' );
}

// ---------------------------------------------------------------------------
// Helpers (read only). Every SQL read goes through a wrapper that fails the
// whole run on the first database error: a failed query must never surface as
// an empty section in an inventory presented as complete.
// ---------------------------------------------------------------------------
function kh_inv_db_check( $sql ) {
	global $wpdb;
	if ( '' !== (string) $wpdb->last_error ) {
		throw new RuntimeException( 'Database error on: ' . substr( preg_replace( '/\s+/', ' ', $sql ), 0, 120 ) . ' -> ' . $wpdb->last_error );
	}
}

function kh_inv_results( $sql, $output = OBJECT ) {
	global $wpdb;
	$wpdb->last_error = '';
	$rows = $wpdb->get_results( $sql, $output ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	kh_inv_db_check( $sql );
	return $rows;
}

function kh_inv_col( $sql ) {
	global $wpdb;
	$wpdb->last_error = '';
	$col = $wpdb->get_col( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	kh_inv_db_check( $sql );
	return $col;
}

function kh_inv_var( $sql ) {
	global $wpdb;
	$wpdb->last_error = '';
	$var = $wpdb->get_var( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	kh_inv_db_check( $sql );
	return $var;
}

function kh_inv_table_exists( $table ) {
	global $wpdb;
	return $table === kh_inv_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table ) ) );
}

function kh_inv_unserialize( $value ) {
	$data = maybe_unserialize( $value );
	return is_array( $data ) ? $data : array();
}

function kh_inv_url( $url ) {
	$parts = wp_parse_url( (string) $url );
	if ( ! is_array( $parts ) || empty( $parts['host'] ) ) {
		return is_string( $url ) && '' !== $url && '/' === $url[0] ? strtok( $url, '?#' ) : '';
	}
	return ( $parts['scheme'] ?? 'https' ) . '://' . $parts['host'] . ( $parts['path'] ?? '/' );
}

function kh_inv_text( $value ) {
	return trim( wp_strip_all_tags( (string) $value ) );
}

/** Map post ID => language slug from Polylang's `language` taxonomy. */
function kh_inv_post_languages() {
	global $wpdb;
	$rows = kh_inv_results(
		"SELECT tr.object_id, t.slug FROM {$wpdb->term_relationships} tr
		 JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
		 JOIN {$wpdb->terms} t ON t.term_id = tt.term_id
		 WHERE tt.taxonomy = 'language'"
	);
	$map = array();
	foreach ( (array) $rows as $row ) {
		$map[ (int) $row->object_id ] = (string) $row->slug;
	}
	return $map;
}

/** Map post ID => array( lang => id ) from Polylang's `post_translations` taxonomy. */
function kh_inv_post_translations() {
	global $wpdb;
	$rows = kh_inv_results(
		"SELECT tr.object_id, tt.description FROM {$wpdb->term_relationships} tr
		 JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
		 WHERE tt.taxonomy = 'post_translations'"
	);
	$map = array();
	foreach ( (array) $rows as $row ) {
		$group = kh_inv_unserialize( $row->description );
		$clean = array();
		foreach ( $group as $lang => $id ) {
			$clean[ (string) $lang ] = (int) $id;
		}
		$map[ (int) $row->object_id ] = $clean;
	}
	return $map;
}

function kh_inv_meta_map( $post_ids, $meta_key ) {
	global $wpdb;
	if ( ! $post_ids ) {
		return array();
	}
	$ids  = implode( ',', array_map( 'intval', $post_ids ) );
	$rows = kh_inv_results( $wpdb->prepare( "SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key = %s AND post_id IN ($ids)", $meta_key ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	$map  = array();
	foreach ( (array) $rows as $row ) {
		$map[ (int) $row->post_id ] = (string) $row->meta_value;
	}
	return $map;
}

function kh_inv_posts( $post_type, $languages, $translations ) {
	global $wpdb;
	$rows = kh_inv_results( $wpdb->prepare(
		"SELECT ID, post_status, post_name, post_title, post_parent, post_modified_gmt FROM {$wpdb->posts}
		 WHERE post_type = %s AND post_status IN ('publish','draft','pending','private','future') ORDER BY ID",
		$post_type
	) );
	$ids       = array_map( static function ( $r ) { return (int) $r->ID; }, (array) $rows );
	$templates = kh_inv_meta_map( $ids, '_wp_page_template' );
	$items     = array();
	foreach ( (array) $rows as $row ) {
		$id      = (int) $row->ID;
		$items[] = array(
			'id'           => $id,
			'status'       => (string) $row->post_status,
			'slug'         => (string) $row->post_name,
			'title'        => kh_inv_text( $row->post_title ),
			'template'     => $templates[ $id ] ?? '',
			'parent'       => (int) $row->post_parent,
			'lang'         => $languages[ $id ] ?? '',
			'translations' => $translations[ $id ] ?? array(),
			'modified_utc' => (string) $row->post_modified_gmt,
		);
	}
	return $items;
}

/** Flatten Fluent Forms field definitions to name/element/label only. */
function kh_inv_form_fields( $nodes, &$out ) {
	foreach ( (array) $nodes as $node ) {
		if ( ! is_array( $node ) ) {
			continue;
		}
		if ( isset( $node['columns'] ) && is_array( $node['columns'] ) ) {
			foreach ( $node['columns'] as $column ) {
				kh_inv_form_fields( $column['fields'] ?? array(), $out );
			}
			continue;
		}
		if ( isset( $node['fields'] ) && is_array( $node['fields'] ) && ! isset( $node['element'] ) ) {
			kh_inv_form_fields( $node['fields'], $out );
			continue;
		}
		$out[] = array(
			'name'    => (string) ( $node['attributes']['name'] ?? '' ),
			'element' => (string) ( $node['element'] ?? '' ),
			'label'   => kh_inv_text( $node['settings']['label'] ?? '' ),
		);
	}
}

// ---------------------------------------------------------------------------
// Collection inside a READ ONLY transaction
// ---------------------------------------------------------------------------
$prefix = $wpdb->prefix;
$data   = array();

if ( false === $wpdb->query( 'START TRANSACTION READ ONLY' ) ) {
	throw new RuntimeException( 'READ ONLY transaction unsupported: ' . $wpdb->last_error );
}

try {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
	$child = wp_get_theme( (string) get_option( 'stylesheet' ) );

	$data['versions'] = array(
		'wp'               => get_bloginfo( 'version' ),
		'php'              => PHP_VERSION,
		'mysql_server'     => (string) $wpdb->db_server_info(),
		'theme_template'   => (string) get_option( 'template' ),
		'theme_stylesheet' => (string) get_option( 'stylesheet' ),
		'child_version'    => (string) $child->get( 'Version' ),
	);

	$active  = (array) get_option( 'active_plugins', array() );
	$plugins = array();
	foreach ( get_plugins() as $file => $meta ) {
		$plugins[] = array(
			'slug'    => dirname( $file ) === '.' ? $file : dirname( $file ),
			'name'    => kh_inv_text( $meta['Name'] ?? '' ),
			'version' => (string) ( $meta['Version'] ?? '' ),
			'active'  => in_array( $file, $active, true ),
		);
	}
	$data['plugins'] = $plugins;

	$mu = array();
	foreach ( get_mu_plugins() as $file => $meta ) {
		$mu[] = array( 'file' => $file, 'name' => kh_inv_text( $meta['Name'] ?? '' ), 'version' => (string) ( $meta['Version'] ?? '' ) );
	}
	$data['mu_plugins'] = $mu;

	$options = array();
	foreach ( KH_OPTION_ALLOWLIST as $name ) {
		$options[ $name ] = get_option( $name );
	}
	$polylang = kh_inv_unserialize( get_option( 'polylang' ) );
	$options['polylang'] = array_intersect_key( $polylang, array_flip( KH_POLYLANG_KEYS ) );
	$litespeed = get_option( 'litespeed.conf.cache' );
	$options['litespeed.conf.cache'] = null === $litespeed || false === $litespeed ? null : (bool) $litespeed;
	$seopress = kh_inv_unserialize( get_option( 'seopress_xml_sitemap_option_name' ) );
	// Key name per SEOPress src/Services/Options/SitemapOption.php (prefixed inside the option array).
	$options['seopress_xml_sitemap_general_enable'] = isset( $seopress['seopress_xml_sitemap_general_enable'] ) ? (bool) $seopress['seopress_xml_sitemap_general_enable'] : null;
	$options['cmplz_wizard_completed'] = (bool) get_option( 'cmplz_wizard_completed' );
	$data['options'] = $options;

	$lang_rows = kh_inv_results(
		"SELECT t.slug, tt.description FROM {$wpdb->term_taxonomy} tt JOIN {$wpdb->terms} t ON t.term_id = tt.term_id WHERE tt.taxonomy = 'language'"
	);
	$languages = array();
	foreach ( (array) $lang_rows as $row ) {
		$desc        = kh_inv_unserialize( $row->description );
		$languages[] = array( 'slug' => (string) $row->slug, 'locale' => (string) ( $desc['locale'] ?? '' ), 'is_default' => ( $options['polylang']['default_lang'] ?? '' ) === $row->slug );
	}
	$data['languages'] = $languages;

	$post_langs   = kh_inv_post_languages();
	$post_trans   = kh_inv_post_translations();
	$data['pages'] = kh_inv_posts( 'page', $post_langs, $post_trans );
	$posts        = kh_inv_posts( 'post', $post_langs, $post_trans );
	$data['posts'] = array( 'count' => count( $posts ), 'items' => $posts );

	$theme_mods = kh_inv_unserialize( get_option( 'theme_mods_' . get_option( 'stylesheet' ) ) );
	$locations  = (array) ( $theme_mods['nav_menu_locations'] ?? array() );
	$menu_terms = kh_inv_results(
		"SELECT t.term_id, t.name FROM {$wpdb->term_taxonomy} tt JOIN {$wpdb->terms} t ON t.term_id = tt.term_id WHERE tt.taxonomy = 'nav_menu' ORDER BY t.term_id"
	);
	$menus = array();
	foreach ( (array) $menu_terms as $term ) {
		$term_id  = (int) $term->term_id;
		$item_ids = kh_inv_col( $wpdb->prepare(
			"SELECT p.ID FROM {$wpdb->posts} p JOIN {$wpdb->term_relationships} tr ON tr.object_id = p.ID
			 JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
			 WHERE tt.taxonomy = 'nav_menu' AND tt.term_id = %d AND p.post_type = 'nav_menu_item' AND p.post_status = 'publish' ORDER BY p.menu_order",
			$term_id
		) );
		$item_ids = array_map( 'intval', (array) $item_ids );
		$types    = kh_inv_meta_map( $item_ids, '_menu_item_type' );
		$objects  = kh_inv_meta_map( $item_ids, '_menu_item_object' );
		$obj_ids  = kh_inv_meta_map( $item_ids, '_menu_item_object_id' );
		$urls     = kh_inv_meta_map( $item_ids, '_menu_item_url' );
		$items    = array();
		foreach ( $item_ids as $order => $id ) {
			$title = kh_inv_text( kh_inv_var( $wpdb->prepare( "SELECT post_title FROM {$wpdb->posts} WHERE ID = %d", $id ) ) );
			if ( '' === $title && ( $types[ $id ] ?? '' ) === 'post_type' ) {
				$title = kh_inv_text( kh_inv_var( $wpdb->prepare( "SELECT post_title FROM {$wpdb->posts} WHERE ID = %d", (int) ( $obj_ids[ $id ] ?? 0 ) ) ) );
			}
			$items[] = array(
				'order'     => $order + 1,
				'title'     => $title,
				'type'      => $types[ $id ] ?? '',
				'object'    => $objects[ $id ] ?? '',
				'object_id' => (int) ( $obj_ids[ $id ] ?? 0 ),
				'url'       => kh_inv_url( $urls[ $id ] ?? '' ),
			);
		}
		$menus[] = array(
			'id'        => $term_id,
			'name'      => kh_inv_text( $term->name ),
			'locations' => array_keys( array_filter( $locations, static function ( $v ) use ( $term_id ) { return (int) $v === $term_id; } ) ),
			'items'     => $items,
		);
	}
	$data['menus'] = $menus;

	$product_rows = kh_inv_results(
		"SELECT ID, post_status FROM {$wpdb->posts} WHERE post_type = 'product' AND post_status IN ('publish','draft','pending','private','future') ORDER BY ID"
	);
	$product_ids = array_map( static function ( $r ) { return (int) $r->ID; }, (array) $product_rows );
	$skus        = kh_inv_meta_map( $product_ids, '_sku' );
	$thumbs      = kh_inv_meta_map( $product_ids, '_thumbnail_id' );
	$galleries   = kh_inv_meta_map( $product_ids, '_product_image_gallery' );
	$type_rows   = kh_inv_results(
		"SELECT tr.object_id, t.slug FROM {$wpdb->term_relationships} tr JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
		 JOIN {$wpdb->terms} t ON t.term_id = tt.term_id WHERE tt.taxonomy = 'product_type'"
	);
	$types = array();
	foreach ( (array) $type_rows as $row ) {
		$types[ (int) $row->object_id ] = (string) $row->slug;
	}
	$var_rows = kh_inv_results(
		"SELECT ID, post_parent FROM {$wpdb->posts} WHERE post_type = 'product_variation' AND post_status IN ('publish','private') ORDER BY ID"
	);
	$var_ids   = array_map( static function ( $r ) { return (int) $r->ID; }, (array) $var_rows );
	$var_skus  = kh_inv_meta_map( $var_ids, '_sku' );
	$var_stock = kh_inv_meta_map( $var_ids, '_stock_status' );
	$variations_by_parent = array();
	foreach ( (array) $var_rows as $row ) {
		$id = (int) $row->ID;
		$variations_by_parent[ (int) $row->post_parent ][] = array( 'id' => $id, 'sku' => $var_skus[ $id ] ?? '', 'stock_status' => $var_stock[ $id ] ?? '' );
	}
	$products = array();
	foreach ( (array) $product_rows as $row ) {
		$id         = (int) $row->ID;
		$gallery    = trim( (string) ( $galleries[ $id ] ?? '' ) );
		$products[] = array(
			'id'            => $id,
			'sku'           => $skus[ $id ] ?? '',
			'status'        => (string) $row->post_status,
			'type'          => $types[ $id ] ?? '',
			'lang'          => $post_langs[ $id ] ?? '',
			'translations'  => $post_trans[ $id ] ?? array(),
			'has_thumbnail' => (int) ( $thumbs[ $id ] ?? 0 ) > 0,
			'gallery_count' => '' === $gallery ? 0 : count( explode( ',', $gallery ) ),
			'variations'    => $variations_by_parent[ $id ] ?? array(),
		);
	}
	$data['products'] = $products;

	$forms = array();
	if ( kh_inv_table_exists( $prefix . 'fluentform_forms' ) ) {
		$form_rows = kh_inv_results( "SELECT id, title, status, form_fields FROM {$prefix}fluentform_forms ORDER BY id" );
		foreach ( (array) $form_rows as $row ) {
			$form_id = (int) $row->id;
			$fields  = array();
			$decoded = json_decode( (string) $row->form_fields, true );
			kh_inv_form_fields( $decoded['fields'] ?? array(), $fields );
			$notifications = array();
			if ( kh_inv_table_exists( $prefix . 'fluentform_form_meta' ) ) {
				$meta_rows = kh_inv_col( $wpdb->prepare( "SELECT value FROM {$prefix}fluentform_form_meta WHERE form_id = %d AND meta_key = 'notifications'", $form_id ) );
				foreach ( (array) $meta_rows as $value ) {
					$n = json_decode( (string) $value, true );
					if ( ! is_array( $n ) ) {
						continue;
					}
					$to = $n['sendTo'] ?? array();
					$notifications[] = array(
						'name'    => kh_inv_text( $n['name'] ?? '' ),
						'subject' => kh_inv_text( $n['subject'] ?? '' ),
						'to'      => kh_inv_text( is_array( $to ) ? ( $to['email'] ?? $to['field'] ?? $to['type'] ?? '' ) : $to ),
						'enabled' => ! empty( $n['enabled'] ),
					);
				}
			}
			$submissions = kh_inv_table_exists( $prefix . 'fluentform_submissions' )
				? (int) kh_inv_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$prefix}fluentform_submissions WHERE form_id = %d", $form_id ) )
				: 0;
			$forms[] = array(
				'id'                        => $form_id,
				'title'                     => kh_inv_text( $row->title ),
				'status'                    => (string) $row->status,
				'fields'                    => $fields,
				'notifications_count'       => count( $notifications ),
				'notifications_enabled_any' => (bool) array_filter( array_column( $notifications, 'enabled' ) ),
				'notifications'             => $notifications,
				'submissions_count'         => $submissions,
			);
		}
	}
	$data['forms'] = $forms;

	$zones = array();
	if ( kh_inv_table_exists( $prefix . 'woocommerce_shipping_zones' ) ) {
		$zone_rows = kh_inv_results( "SELECT zone_id, zone_name FROM {$prefix}woocommerce_shipping_zones ORDER BY zone_order, zone_id" );
		$zone_rows[] = (object) array( 'zone_id' => 0, 'zone_name' => 'Rest of the world' );
		foreach ( $zone_rows as $zone ) {
			$zone_id   = (int) $zone->zone_id;
			$locs      = kh_inv_results( $wpdb->prepare( "SELECT location_code, location_type FROM {$prefix}woocommerce_shipping_zone_locations WHERE zone_id = %d", $zone_id ) );
			$methods   = kh_inv_results( $wpdb->prepare( "SELECT instance_id, method_id, is_enabled FROM {$prefix}woocommerce_shipping_zone_methods WHERE zone_id = %d ORDER BY method_order", $zone_id ) );
			$locations = array();
			foreach ( (array) $locs as $loc ) {
				$locations[] = array( 'type' => (string) $loc->location_type, 'code' => (string) $loc->location_code );
			}
			$method_list = array();
			foreach ( (array) $methods as $m ) {
				$settings      = kh_inv_unserialize( get_option( 'woocommerce_' . $m->method_id . '_' . (int) $m->instance_id . '_settings' ) );
				$method_list[] = array( 'type' => (string) $m->method_id, 'title' => kh_inv_text( $settings['title'] ?? '' ), 'enabled' => (bool) $m->is_enabled );
			}
			$zones[] = array( 'id' => $zone_id, 'name' => kh_inv_text( $zone->zone_name ), 'locations' => $locations, 'methods' => $method_list );
		}
	}
	$data['shipping_zones'] = $zones;

	$orders = array();
	if ( kh_inv_table_exists( $prefix . 'wc_orders' ) ) {
		foreach ( (array) kh_inv_results( "SELECT status, COUNT(*) AS n FROM {$prefix}wc_orders WHERE type = 'shop_order' GROUP BY status" ) as $row ) {
			$orders[ (string) $row->status ] = (int) $row->n;
		}
	}
	foreach ( (array) kh_inv_results( "SELECT post_status, COUNT(*) AS n FROM {$wpdb->posts} WHERE post_type = 'shop_order' GROUP BY post_status" ) as $row ) {
		$orders[ 'legacy:' . $row->post_status ] = (int) $row->n;
	}
	$data['orders_by_status'] = $orders;

	$roles = array();
	foreach ( (array) kh_inv_col( $wpdb->prepare( "SELECT meta_value FROM {$wpdb->usermeta} WHERE meta_key = %s", $prefix . 'capabilities' ) ) as $caps ) {
		foreach ( array_keys( array_filter( kh_inv_unserialize( $caps ) ) ) as $role ) {
			$roles[ (string) $role ] = ( $roles[ (string) $role ] ?? 0 ) + 1;
		}
	}
	$data['users_by_role'] = $roles;

	$failed = array();
	if ( kh_inv_table_exists( $prefix . 'actionscheduler_actions' ) ) {
		foreach ( (array) kh_inv_results( "SELECT hook, COUNT(*) AS n FROM {$prefix}actionscheduler_actions WHERE status = 'failed' GROUP BY hook" ) as $row ) {
			$failed[ (string) $row->hook ] = (int) $row->n;
		}
	}
	$data['action_scheduler'] = array( 'failed_by_hook' => $failed );
} finally {
	$wpdb->query( 'ROLLBACK' );
}

// ---------------------------------------------------------------------------
// Manifest and private output
// ---------------------------------------------------------------------------
$data['paths'] = array( 'abspath' => $abspath_real, 'db_name' => DB_NAME, 'out_dir' => $out_dir_real );

$manifest = array(
	'generated_utc'     => gmdate( 'c' ),
	'host'              => $home_host,
	'git_sha'           => (string) getenv( 'KH_INVENTORY_GIT_SHA' ),
	'script_sha256'     => hash_file( 'sha256', __FILE__ ),
	'wp_cli_version'    => defined( 'WP_CLI_VERSION' ) ? WP_CLI_VERSION : '',
	'php_version'       => PHP_VERSION,
	'wp_version'        => get_bloginfo( 'version' ),
	'guards'            => $guards,
	'sections_included' => array_keys( $data ),
	'sections_omitted'  => array( 'page_content', 'post_content', 'order_content', 'submissions_content', 'users', 'payment_settings', 'smtp_settings', 'wordfence', 'stock_quantities' ),
	// File checksums live in the external .sha256 files only: a checksum embedded
	// in the file it describes can never match that file.
);
$output = array( 'manifest' => $manifest ) + $data;

$json = wp_json_encode( $output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
if ( false === $json ) {
	throw new RuntimeException( 'JSON encoding failed.' );
}
$handle = fopen( $out_path, 'x' );
if ( false === $handle ) {
	throw new RuntimeException( 'Cannot create output file.' );
}
fwrite( $handle, $json . PHP_EOL );
fclose( $handle );
chmod( $out_path, 0600 );

$summary = array();
foreach ( $data as $section => $value ) {
	$summary[ $section ] = is_array( $value ) ? count( $value ) : 1;
}
echo wp_json_encode( array( 'written' => basename( $out_path ), 'sha256' => hash_file( 'sha256', $out_path ), 'sections' => $summary ), JSON_PRETTY_PRINT ) . PHP_EOL;
