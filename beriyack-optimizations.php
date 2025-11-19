<?php
/**
 * Plugin Name:       Beriyack Optimizations
 * Description:       Plugin d'optimisation pour WordPress. Gère la limitation des révisions, la désactivation des emojis, de XML-RPC et nettoie les scripts.
 * Version:           1.4.2
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

	// Applique toujours le filtre pour le nombre de révisions. La valeur est gérée dans la fonction.
	add_filter( 'wp_revisions_to_keep', 'beriyack_optimizations_limit_revisions_number', 10, 1 );

	// --- Optimisations de performance et de sécurité ---

	// La désactivation de XML-RPC doit se faire le plus tôt possible.
	if ( ! empty( $options['disable_xmlrpc'] ) ) {
		// Méthode 1: Le filtre standard de WordPress pour désactiver les fonctionnalités.
		add_filter( 'xmlrpc_enabled', '__return_false' );

		// Méthode 2: Supprime l'en-tête de pingback pour empêcher la découverte.
		add_filter( 'wp_headers', function( $headers ) { unset( $headers['X-Pingback'] ); return $headers; } );

	}

	if ( ! empty( $options['disable_emojis'] ) ) {
		add_action( 'init', 'beriyack_optimizations_disable_emojis_actions' );
	}

	if ( ! empty( $options['remove_wp_version'] ) ) {
		remove_action( 'wp_head', 'wp_generator' );
	}

	if ( ! empty( $options['remove_feed_links'] ) ) {
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'feed_links_extra', 3 );
	}
}
// On utilise une priorité de 0 pour s'assurer que le filtre XML-RPC est appliqué le plus tôt possible.
add_action( 'plugins_loaded', 'beriyack_optimizations_init', 0 );

/**
 * Méthode de blocage agressive pour XML-RPC.
 * Intercepte l'accès au fichier et arrête l'exécution.
 */
$beriyack_optimizations_options = get_option( 'beriyack_optimizations_settings', array() );
if ( ! empty( $beriyack_optimizations_options['disable_xmlrpc'] ) ) {
	if ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) {
		header( 'HTTP/1.1 403 Forbidden' );
		exit( 'Accès à XML-RPC désactivé par l\'administrateur du site.' );
	}
}

function beriyack_optimizations_limit_revisions_number( $num ) {
	$options = get_option( 'beriyack_optimizations_settings', array() );
	// Si la valeur est définie dans les options, on l'utilise. Sinon, on ne modifie pas la valeur par défaut de WordPress.
	if ( isset( $options['revisions_count'] ) ) {
		return intval( $options['revisions_count'] );
	}
	// Si l'option n'a jamais été enregistrée, on ne change rien.
	return $num;
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
 * Ajoute un lien vers la page de réglages directement sur la page des plugins.
 *
 * @param array $links Les liens d'action existants.
 * @return array Les liens d'action modifiés.
 */
function beriyack_optimizations_add_settings_link( $links ) {
	$settings_link = '<a href="' . admin_url( 'options-general.php?page=beriyack-optimizations' ) . '">' . __( 'Réglages', 'beriyack-optimizations' ) . '</a>';
	array_unshift( $links, $settings_link );
	return $links;
}
$beriyack_optimizations_plugin_basename = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_{$beriyack_optimizations_plugin_basename}", 'beriyack_optimizations_add_settings_link' );