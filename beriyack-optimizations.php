<?php
/**
 * Plugin Name:       Beriyack Optimizations
 * Description:       Plugin d'optimisation pour WordPress. Gère la limitation des révisions, la désactivation des emojis, de XML-RPC et nettoie les scripts.
 * Version:           1.3.0
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

	if ( ! empty( $options['remove_jquery_migrate'] ) ) {
		add_action( 'wp_default_scripts', 'beriyack_optimizations_remove_jquery_migrate' );
	}

	if ( ! empty( $options['disable_embeds'] ) ) {
		add_action( 'init', 'beriyack_optimizations_disable_embeds', 9999 );
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
	// Supprime le prefetch DNS pour le domaine des emojis.
	remove_action( 'wp_head', 'wp_resource_hints', 2 );
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

/**
 * Supprime le script jQuery Migrate.
 *
 * @param WP_Scripts $scripts L'objet WP_Scripts.
 */
function beriyack_optimizations_remove_jquery_migrate( $scripts ) {
	if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
		$scripts->registered['jquery']->deps = array_diff( $scripts->registered['jquery']->deps, array( 'jquery-migrate' ) );
	}
}

/**
 * Désactive la fonctionnalité d'intégration (Embeds) de WordPress.
 */
function beriyack_optimizations_disable_embeds() {
	// Désinscrit le script wp-embed.
	wp_deregister_script( 'wp-embed' );

	// Retire les actions liées aux embeds.
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
	remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );
}
