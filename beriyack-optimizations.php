<?php
/**
 * Plugin Name:       Beriyack Optimizations
 * Description:       Plugin d'optimisation pour WordPress. Gère la limitation des révisions, la désactivation des emojis, de XML-RPC et nettoie les scripts.
 * Version:           1.1.0
 * Author:            Beriyack
 * Author URI:        https://x.com/Beriyack
 * Text Domain:       beriyack-optimizations
 * License:           GPLv3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Requires at least: 5.0
 * Tested up to:      6.8
 * Requires PHP:      7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Charge le fichier de la page de réglages.
require_once plugin_dir_path( __FILE__ ) . 'includes/settings.php';

/**
 * Initialise les optimisations en fonction des réglages.
 */
function beriyack_optimizations_init() {
	// Charge les traductions du plugin.
	load_plugin_textdomain(
		'beriyack-optimizations',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages/'
	);

	$options = get_option( 'beriyack_optimizations_settings', array() );

	// --- Optimisations de la base de données ---
	if ( ! empty( $options['limit_revisions'] ) ) {
		add_filter( 'wp_revisions_to_keep', 'beriyack_optimizations_limit_revisions_number', 10, 1 );
	}

	// --- Optimisations de performance et de sécurité ---
	if ( ! empty( $options['disable_xmlrpc'] ) ) {
		add_filter( 'xmlrpc_enabled', '__return_false' );
	}

	if ( ! empty( $options['disable_emojis'] ) ) {
		add_action( 'init', 'beriyack_optimizations_disable_emojis_actions' );
	}

	if ( ! empty( $options['remove_wp_version'] ) ) {
		remove_action( 'wp_head', 'wp_generator' );
	}

	if ( ! empty( $options['disable_self_pings'] ) ) {
		add_action( 'pre_ping', 'beriyack_optimizations_disable_self_pings' );
	}

	if ( ! empty( $options['remove_feed_links'] ) ) {
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'feed_links_extra', 3 );
	}
}
add_action( 'plugins_loaded', 'beriyack_optimizations_init' );

function beriyack_optimizations_limit_revisions_number( $num ) {
	$options = get_option( 'beriyack_optimizations_settings', array() );
	$revisions_count = isset( $options['revisions_count'] ) ? absint( $options['revisions_count'] ) : 5;
	return $revisions_count;
}

function beriyack_optimizations_disable_emojis_actions() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'beriyack_optimizations_disable_emojis_tinymce' );
	add_filter( 'wp_resource_hints', 'beriyack_optimizations_remove_emoji_dns_prefetch', 10, 2 );
}

function beriyack_optimizations_disable_emojis_tinymce( $plugins ) {
	return is_array( $plugins ) ? array_diff( $plugins, array( 'wpemoji' ) ) : array();
}

function beriyack_optimizations_remove_emoji_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {
		$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/14.0.0/svg/' );
		return array_diff( $urls, array( $emoji_svg_url ) );
	}
	return $urls;
}

/**
 * Désactive les auto-pings (pingbacks) sur le site.
 *
 * @param array $links Les liens à vérifier.
 */
function beriyack_optimizations_disable_self_pings( &$links ) {
	$home = get_option( 'home' );
	foreach ( $links as $l => $link ) {
		if ( 0 === strpos( $link, $home ) ) {
			unset( $links[ $l ] );
		}
	}
}
