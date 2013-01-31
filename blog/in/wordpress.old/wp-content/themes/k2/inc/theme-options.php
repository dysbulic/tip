<?php

add_action( 'admin_init', 'k2_theme_options_init' );
add_action( 'admin_menu', 'k2_theme_options_add_page' );

/**
 * Add theme options page styles
 */
wp_register_style( 'k2_domain', get_template_directory_uri() . '/inc/theme-options.css', '', '0.1' );
if ( isset( $_GET['page'] ) && $_GET['page'] == 'theme_options' ) {
	wp_enqueue_style( 'k2_domain' );
}

/**
 * Init plugin options to white list our options
 */
function k2_theme_options_init(){
	register_setting( 'k2_options', 'k2_theme_options', 'k2_theme_options_validate' );
}

/**
 * Load up the menu page
 */
function k2_theme_options_add_page() {
	add_theme_page( __( 'Theme Options', 'k2_domain' ), __( 'Theme Options', 'k2_domain' ), 'edit_theme_options', 'theme_options', 'k2_theme_options_do_page' );
}

/**
 * Return array for our layouts
 */
function k2_layouts() {
	$theme_layouts = array(
		'content-sidebar' => array(
			'value' => 'content-sidebar',
			'label' => __( 'Content-Sidebar (Default)', 'k2_domain' ),
		),
		'sidebar-content' => array(
			'value' => 'sidebar-content',
			'label' => __( 'Sidebar-Content', 'k2_domain' ),
		),
		'full-width' => array(
			'value' => 'full-width',
			'label' => __( 'Single column, with sidebar in the footer)', 'k2_domain' )
		),
	);

	return $theme_layouts;
}

/**
 * Create the options page
 */
function k2_theme_options_do_page() {

	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;

	?>
	<div class="wrap">
		<?php screen_icon(); echo "<h2>" . sprintf( __( '%1$s Theme Options', 'k2_domain' ), get_current_theme() )
		 . "</h2>"; ?>

		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved', 'k2_domain' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'k2_options' ); ?>
			<?php $options = k2_get_theme_options(); ?>

			<table class="form-table">

					<?php
				/**
				 * K2 Layout
				 */
				?>
				<tr valign="top" id="k2-layouts"><th scope="row"><?php _e( 'Default Layout', 'k2_domain' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Default Layout', 'k2_domain' ); ?></span></legend>
						<?php
							if ( ! isset( $checked ) )
								$checked = '';
							foreach ( k2_layouts() as $option ) {
								$radio_setting = $options['theme_layout'];

								if ( '' != $radio_setting ) {
									if ( $options['theme_layout'] == $option['value'] ) {
										$checked = "checked=\"checked\"";
									} else {
										$checked = '';
									}
								}
								?>
								<div class="layout">
								<label class="description">
									<input type="radio" name="k2_theme_options[theme_layout]" value="<?php esc_attr_e( $option['value'], 'k2_domain' ); ?>" <?php echo $checked; ?> />
									<span>
										<img src="<?php echo get_template_directory_uri(); ?>/images/layouts/<?php echo $option['value']; ?>.png"/>
										<?php echo $option['label']; ?>
									</span>
								</label>
								</div>
								<?php
							}
						?>
						</fieldset>
					</td>
				</tr>

			</table>

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Options', 'k2_domain' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function k2_theme_options_validate( $input ) {

	// Our radio option must actually be in our array of radio options
	if ( ! isset( $input['theme_layout'] ) )
		$input['theme_layout'] = null;
	if ( ! array_key_exists( $input['theme_layout'], k2_layouts() ) )
		$input['theme_layout'] = null;

	return $input;
}

// adapted from http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/