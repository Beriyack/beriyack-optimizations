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
	// Initialise un tableau pour les nouvelles options nettoyées.
	$new_input = array();

	// Liste de toutes les cases à cocher attendues.
	$checkboxes = array(
		'disable_emojis',
		'remove_feed_links',
		'disable_xmlrpc',
		'remove_wp_version',
	);

	// Pour chaque case à cocher, on vérifie si elle a été envoyée. Si oui, on la met à 1, sinon à 0.
	foreach ( $checkboxes as $key ) {
		$new_input[ $key ] = ! empty( $input[ $key ] ) ? 1 : 0;
	}

	// Champ numérique pour le nombre de révisions.
	$new_input['revisions_count'] = isset( $input['revisions_count'] ) ? intval( $input['revisions_count'] ) : -1;

	return $new_input;
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
				<tr valign="top" id="revisions-setting">
					<th scope="row"><label for="revisions_count"><?php esc_html_e( 'Nombre de révisions', 'beriyack-optimizations' ); ?></label></th>
					<td>
						<input type="number" id="revisions_count" name="beriyack_optimizations_settings[revisions_count]" value="<?php echo isset( $options['revisions_count'] ) ? esc_attr( $options['revisions_count'] ) : '-1'; ?>" min="-1" step="1" class="small-text" />
						<p class="description"><?php esc_html_e( 'Nombre de révisions à conserver par article/page. Mettez -1 pour illimité (comportement par défaut de WordPress).', 'beriyack-optimizations' ); ?></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Optimisations de performance', 'beriyack-optimizations' ); ?></th>
					<td>
						<fieldset>
							<label for="disable_emojis">
								<input type="checkbox" id="disable_emojis" name="beriyack_optimizations_settings[disable_emojis]" value="1" <?php checked( ! empty( $options['disable_emojis'] ), 1 ); ?> />
								<?php esc_html_e( 'Désactiver les Emojis', 'beriyack-optimizations' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Supprime le JavaScript et le CSS liés aux emojis pour améliorer la vitesse.', 'beriyack-optimizations' ); ?></p>
						</fieldset>
						<fieldset>
							<label for="remove_feed_links">
								<input type="checkbox" id="remove_feed_links" name="beriyack_optimizations_settings[remove_feed_links]" value="1" <?php checked( ! empty( $options['remove_feed_links'] ), 1 ); ?> />
								<?php esc_html_e( 'Supprimer les liens des flux RSS', 'beriyack-optimizations' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Supprime les liens vers les flux RSS (principal et commentaires) du <head> de votre site.', 'beriyack-optimizations' ); ?></p>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Optimisations de sécurité', 'beriyack-optimizations' ); ?></th>
					<td>
						<fieldset>
							<label for="disable_xmlrpc">
								<input type="checkbox" id="disable_xmlrpc" name="beriyack_optimizations_settings[disable_xmlrpc]" value="1" <?php checked( ! empty( $options['disable_xmlrpc'] ), 1 ); ?> />
								<?php esc_html_e( 'Désactiver XML-RPC', 'beriyack-optimizations' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Désactive l\'ancienne API XML-RPC, souvent ciblée par des attaques.', 'beriyack-optimizations' ); ?></p>
						</fieldset>
						<fieldset>
							<label for="remove_wp_version">
								<input type="checkbox" id="remove_wp_version" name="beriyack_optimizations_settings[remove_wp_version]" value="1" <?php checked( ! empty( $options['remove_wp_version'] ), 1 ); ?> />
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
