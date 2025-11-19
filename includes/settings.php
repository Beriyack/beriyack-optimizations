<?php
/**
 * Gère la page de réglages du plugin Beriyack Optimizations.
 *
 * @package Beriyack_Optimizations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Ajoute la page de réglages au menu d'administration.
 */
function beriyack_optimizations_add_settings_page() {
	add_options_page(
		'Beriyack Optimizations',
		'Beriyack Optimizations',
		'manage_options',
		'beriyack-optimizations',
		'beriyack_optimizations_render_settings_page'
	);
}
add_action( 'admin_menu', 'beriyack_optimizations_add_settings_page' );

/**
 * Enregistre les réglages du plugin.
 */
function beriyack_optimizations_register_settings() {
	register_setting(
		'beriyack_optimizations_group',
		'beriyack_optimizations_settings',
		'beriyack_optimizations_sanitize_settings'
	);
}
add_action( 'admin_init', 'beriyack_optimizations_register_settings' );

/**
 * Nettoie les réglages avant de les enregistrer dans la base de données.
 *
 * @param array $input Les données brutes envoyées par le formulaire.
 * @return array Les données nettoyées.
 */
function beriyack_optimizations_sanitize_settings( $input ) {
	$sanitized_input = array();

	// Cases à cocher (on vérifie si elles existent, sinon elles ne sont pas incluses).
	$checkboxes = array(
		'limit_revisions',
		'disable_emojis',
		'disable_self_pings',
		'remove_feed_links',
		'remove_jquery_migrate',
		'disable_embeds',
		'disable_xmlrpc',
		'remove_wp_version',
	);

	foreach ( $checkboxes as $key ) {
		$sanitized_input[ $key ] = ! empty( $input[ $key ] ) ? 1 : 0;
	}

	// Champ numérique pour le nombre de révisions.
	$sanitized_input['revisions_count'] = isset( $input['revisions_count'] ) ? intval( $input['revisions_count'] ) : 5;

	return $sanitized_input;
}

/**
 * Affiche le contenu de la page de réglages.
 */
function beriyack_optimizations_render_settings_page() {
	$options = get_option( 'beriyack_optimizations_settings', array() );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Réglages de Beriyack Optimizations', 'beriyack-optimizations' ); ?></h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'beriyack_optimizations_group' ); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Optimisations de la base de données', 'beriyack-optimizations' ); ?></th>
					<td>
						<fieldset>
							<label for="limit_revisions">
								<input type="checkbox" id="limit_revisions" name="beriyack_optimizations_settings[limit_revisions]" value="1" <?php checked( isset( $options['limit_revisions'] ), 1 ); ?> />
								<?php esc_html_e( 'Limiter le nombre de révisions', 'beriyack-optimizations' ); ?>
							</label>
							<br>
							<input type="number" name="beriyack_optimizations_settings[revisions_count]" value="<?php echo isset( $options['revisions_count'] ) ? esc_attr( $options['revisions_count'] ) : '5'; ?>" min="-1" step="1" class="small-text" />
							<p class="description"><?php esc_html_e( 'Nombre de révisions à conserver par article/page. Mettez -1 pour illimité.', 'beriyack-optimizations' ); ?></p>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Optimisations de performance', 'beriyack-optimizations' ); ?></th>
					<td>
						<fieldset>
							<label for="disable_emojis">
								<input type="checkbox" id="disable_emojis" name="beriyack_optimizations_settings[disable_emojis]" value="1" <?php checked( isset( $options['disable_emojis'] ), 1 ); ?> />
								<?php esc_html_e( 'Désactiver les Emojis', 'beriyack-optimizations' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Supprime le JavaScript et le CSS liés aux emojis pour améliorer la vitesse.', 'beriyack-optimizations' ); ?></p>
						</fieldset>
						<fieldset>
							<label for="disable_self_pings">
								<input type="checkbox" id="disable_self_pings" name="beriyack_optimizations_settings[disable_self_pings]" value="1" <?php checked( isset( $options['disable_self_pings'] ), 1 ); ?> />
								<?php esc_html_e( 'Désactiver les auto-pings (self-pings)', 'beriyack-optimizations' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Empêche WordPress de créer des notifications lorsqu\'un article crée un lien vers un autre article du même site.', 'beriyack-optimizations' ); ?></p>
						</fieldset>
						<fieldset>
							<label for="remove_feed_links">
								<input type="checkbox" id="remove_feed_links" name="beriyack_optimizations_settings[remove_feed_links]" value="1" <?php checked( isset( $options['remove_feed_links'] ), 1 ); ?> />
								<?php esc_html_e( 'Supprimer les liens des flux RSS', 'beriyack-optimizations' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Supprime les liens vers les flux RSS (principal et commentaires) du <head> de votre site.', 'beriyack-optimizations' ); ?></p>
						</fieldset>
						<fieldset>
							<label for="remove_jquery_migrate">
								<input type="checkbox" id="remove_jquery_migrate" name="beriyack_optimizations_settings[remove_jquery_migrate]" value="1" <?php checked( isset( $options['remove_jquery_migrate'] ), 1 ); ?> />
								<?php esc_html_e( 'Supprimer jQuery Migrate', 'beriyack-optimizations' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Supprime le script de compatibilité jQuery Migrate si vos plugins et thèmes sont à jour.', 'beriyack-optimizations' ); ?></p>
						</fieldset>
						<fieldset>
							<label for="disable_embeds">
								<input type="checkbox" id="disable_embeds" name="beriyack_optimizations_settings[disable_embeds]" value="1" <?php checked( isset( $options['disable_embeds'] ), 1 ); ?> />
								<?php esc_html_e( 'Désactiver les Embeds', 'beriyack-optimizations' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Supprime le script wp-embed.min.js et désactive la fonctionnalité d\'intégration automatique des contenus.', 'beriyack-optimizations' ); ?></p>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Optimisations de sécurité', 'beriyack-optimizations' ); ?></th>
					<td>
						<fieldset>
							<label for="disable_xmlrpc">
								<input type="checkbox" id="disable_xmlrpc" name="beriyack_optimizations_settings[disable_xmlrpc]" value="1" <?php checked( isset( $options['disable_xmlrpc'] ), 1 ); ?> />
								<?php esc_html_e( 'Désactiver XML-RPC', 'beriyack-optimizations' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Désactive l\'ancienne API XML-RPC, souvent ciblée par des attaques.', 'beriyack-optimizations' ); ?></p>
						</fieldset>
						<fieldset>
							<label for="remove_wp_version">
								<input type="checkbox" id="remove_wp_version" name="beriyack_optimizations_settings[remove_wp_version]" value="1" <?php checked( isset( $options['remove_wp_version'] ), 1 ); ?> />
								<?php esc_html_e( 'Supprimer la version de WordPress', 'beriyack-optimizations' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Supprime la balise meta "generator" pour masquer la version de WordPress utilisée.', 'beriyack-optimizations' ); ?></p>
						</fieldset>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/**
 * Ajoute un lien vers la page de réglages directement sur la page des plugins.
 */
function beriyack_optimizations_add_settings_link( $links ) {
	$settings_link = '<a href="' . admin_url( 'options-general.php?page=beriyack-optimizations' ) . '">' . __( 'Réglages', 'beriyack-optimizations' ) . '</a>';
	array_unshift( $links, $settings_link );
	return $links;
}
$beriyack_optimizations_plugin_basename = plugin_basename( dirname( __DIR__ ) . '/beriyack-optimizations.php' );
add_filter( "plugin_action_links_{$beriyack_optimizations_plugin_basename}", 'beriyack_optimizations_add_settings_link' );
