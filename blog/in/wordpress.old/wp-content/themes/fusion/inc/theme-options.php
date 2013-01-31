<?php

add_action( 'admin_init', 'fusion_theme_options_init' );
add_action( 'admin_menu', 'fusion_theme_options_add_page' );

/**
 * Add theme options page styles
 */
wp_register_style( 'fusion', get_bloginfo( 'template_directory' ) . '/inc/theme-options.css', '', '0.1' );
if ( isset( $_GET['page'] ) && $_GET['page'] == 'theme_options' ) {
	wp_enqueue_style( 'fusion' );
}

/**
 * Init plugin options to white list our options
 */
function fusion_theme_options_init() {
	register_setting( 'fusion_options', 'fusion_theme_options', 'fusion_theme_options_validate' );
}

/**
 * Load up the menu page
 */
function fusion_theme_options_add_page() {
	add_theme_page( __( 'Theme Options' ), __( 'Theme Options' ), 'edit_theme_options', 'theme_options', 'fusion_theme_options_do_page' );
}

/**
 * Return array for our layouts
 */
function fusion_layouts() {
	$theme_layouts = array(
		'content-sidebar' => array(
			'value' => 'content-sidebar',
			'label' => __( 'Right Sidebar' ),
		),
		'sidebar-content' => array(
			'value' => 'sidebar-content',
			'label' => __( 'Left Sidebar' )
		),
		'content-sidebar-sidebar' => array(
			'value' => 'content-sidebar-sidebar',
			'label' => __( 'Two Right Sidebars' )
		),
		'no-sidebar' => array(
			'value' => 'no-sidebar',
			'label' => __( 'No Sidebar <br /><em>Use footer widget area</em>' )
		),
	);

	return $theme_layouts;
}

/**
 * Return array for post length
 */
function fusion_post_length() {
	$post_length = array(
		'full-post' => array(
			'value' =>	'full-post',
			'label' => __( 'Full Post' )
		),
		'excerpt' => array(
			'value' =>	'excerpt',
			'label' => __( 'Excerpt' )
		),
	);

	return $post_length;
}

/**
 * Return array for flexible or fixed layout
 */
function fusion_flexible_layout() {
	$flexible_layout = array(
		'fixed' => array(
			'value' =>	'fixed',
			'label' => __( 'Fixed' )
		),
		'flexible' => array(
			'value' =>	'flexible',
			'label' => __( 'Flexible' )
		),
	);

	return $flexible_layout;
}

/**
 * Create the options page
 */
function fusion_theme_options_do_page() {

	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;

	?>
	<div class="wrap">
		<?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' Theme Options' ) . "</h2>"; ?>

		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'fusion_options' ); ?>
			<?php $options = fusion_get_theme_options(); ?>

			<table class="form-table">

				<?php
				/**
				 * Fusion Layout
				 */
				?>
				<tr valign="top" id="fusion-layouts"><th scope="row"><?php _e( 'Default Layout' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Default Layout' ); ?></span></legend>
						<?php
							if ( ! isset( $checked ) )
								$checked = '';
							foreach ( fusion_layouts() as $option ) {
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
									<input type="radio" name="fusion_theme_options[theme_layout]" value="<?php esc_attr_e( $option['value'] ); ?>" <?php echo $checked; ?> />
									<span>
										<img src="<?php bloginfo( 'template_directory' ); ?>/images/<?php echo $option['value']; ?>.png"/>
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

				<?php
				/**
				 * Flexible Layout
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Layout Width' ); ?></th>
					<td>
						<label class="description" for="fusion_theme_options[flexible_layout]"></label>
						<select name="fusion_theme_options[flexible_layout]">
							<?php
								$selected = $options['flexible_layout'];
								$p = '';
								$r = '';

								foreach ( fusion_flexible_layout() as $option ) {
									$label = $option['label'];

									if ( $selected == $option['value'] ) // Make default first in list
										$p = "\n\t<option selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
									else
										$r .= "\n\t<option value='" . esc_attr( $option['value'] ) . "'>$label</option>";
								}
								echo $p . $r;
							?>
						</select>
					</td>
				</tr>

				<?php
				/**
				 * Fusion Post Length
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Display Full Post or Excerpt' ); ?></th>
					<td>
						<label class="description" for="fusion_theme_options[post_length]"><?php _e( 'Archives show:' ); ?></label>
						<select name="fusion_theme_options[post_length]">
							<?php
								$selected = $options['post_length'];
								$p = '';
								$r = '';

								foreach ( fusion_post_length() as $option ) {
									$label = $option['label'];

									if ( $selected == $option['value'] ) // Make default first in list
										$p = "\n\t<option selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
									else
										$r .= "\n\t<option value='" . esc_attr( $option['value'] ) . "'>$label</option>";
								}
								echo $p . $r;
							?>
						</select>
					</td>
				</tr>
			</table>

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Options' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function fusion_theme_options_validate( $input ) {

	// Our radio option must actually be in our array of radio options
	if ( ! isset( $input['theme_layout'] ) )
		$input['theme_layout'] = null;
	if ( ! array_key_exists( $input['theme_layout'], fusion_layouts() ) )
		$input['theme_layout'] = null;

	if ( ! array_key_exists( $input['post_length'], fusion_post_length() ) )
		$input['post_length'] = null;

	if ( ! array_key_exists( $input['flexible_layout'], fusion_flexible_layout() ) )
		$input['flexible_layout'] = null;

	return $input;
}