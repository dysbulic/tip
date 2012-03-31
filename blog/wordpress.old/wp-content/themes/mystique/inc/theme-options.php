<?php

add_action( 'admin_init', 'mystique_theme_options_init' );
add_action( 'admin_menu', 'mystique_theme_options_add_page' );

/**
 * Add theme options page styles
 */
wp_register_style( 'mystique', get_template_directory_uri() . '/inc/theme-options.css', '', '0.1' );
if ( isset( $_GET['page'] ) && $_GET['page'] == 'theme_options' ) {
	wp_enqueue_style( 'mystique' );
}

/**
 * Init plugin options to white list our options
 */
function mystique_theme_options_init(){
	register_setting( 'mystique_options', 'mystique_theme_options', 'mystique_theme_options_validate' );
}

/**
 * Load up the menu page
 */
function mystique_theme_options_add_page() {
	add_theme_page( __( 'Theme Options' ), __( 'Theme Options' ), 'edit_theme_options', 'theme_options', 'mystique_theme_options_do_page' );
}

/**
 * Return array for our color schemes
 */
function mystique_color_schemes() {
	$color_schemes = array(
		'green' => array(
			'value' =>	'green',
			'label' => __( 'Green' )
		),
		'red' => array(
			'value' =>	'red',
			'label' => __( 'Red' )
		),
		'blue' => array(
			'value' =>	'blue',
			'label' => __( 'Blue' )
		),
		'grey' => array(
			'value' =>	'grey',
			'label' => __( 'Grey' )
		),
		'pink' => array(
			'value' =>	'pink',
			'label' => __( 'Pink' )
		),
		'purple' => array(
			'value' =>	'purple',
			'label' => __( 'Purple' )
		),
	);

	return $color_schemes;
}

/**
 * Return array for our layouts
 */
function mystique_layouts() {
	$theme_layouts = array(
		'content-sidebar' => array(
			'value' => 'content-sidebar',
			'label' => __( 'Content-Sidebar' ),
		),
		'sidebar-content' => array(
			'value' => 'sidebar-content',
			'label' => __( 'Sidebar-Content' )
		),
		'content-sidebar-sidebar' => array(
			'value' => 'content-sidebar-sidebar',
			'label' => __( 'Content-Sidebar-Sidebar' )
		),
		'sidebar-sidebar-content' => array(
			'value' => 'sidebar-sidebar-content',
			'label' => __( 'Sidebar-Sidebar-Content' )
		),
		'sidebar-content-sidebar' => array(
			'value' => 'sidebar-content-sidebar',
			'label' => __( 'Sidebar-Content-Sidebar' )
		),
		'no-sidebar' => array(
			'value' => 'no-sidebar',
			'label' => __( 'Full-Width, No Sidebar' )
		),
	);

	return $theme_layouts;
}

/**
 * Create the options page
 */
function mystique_theme_options_do_page() {

	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;

	?>
	<div class="wrap">
		<?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' Theme Options' ) . "</h2>"; ?>

		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved', 'mystique' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'mystique_options' ); ?>
			<?php $options = mystique_get_theme_options(); ?>

			<table class="form-table">

				<?php
				/**
				 * Mystique Color Scheme
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Color Scheme', 'mystique' ); ?></th>
					<td>
						<select name="mystique_theme_options[color_scheme]">
							<?php
								$selected = $options['color_scheme'];
								$p = '';
								$r = '';

								foreach ( mystique_color_schemes() as $option ) {
									$label = $option['label'];

									if ( $selected == $option['value'] ) // Make default first in list
										$p = "\n\t<option selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
									else
										$r .= "\n\t<option value='" . esc_attr( $option['value'] ) . "'>$label</option>";
								}
								echo $p . $r;
							?>
						</select>
						<label class="description" for="mystique_theme_options[color_scheme]"><?php _e( 'Select a default color scheme', 'mystique' ); ?></label>
					</td>
				</tr>

				<?php
				/**
				 * Mystique Layout
				 */
				?>
				<tr valign="top" id="mystique-layouts"><th scope="row"><?php _e( 'Default Layout', 'mystique' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Default Layout', 'mystique' ); ?></span></legend>
						<?php
							if ( ! isset( $checked ) )
								$checked = '';
							foreach ( mystique_layouts() as $option ) {
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
									<input type="radio" name="mystique_theme_options[theme_layout]" value="<?php esc_attr_e( $option['value'] ); ?>" <?php echo $checked; ?> />
									<span>
										<img src="<?php echo get_template_directory_uri(); ?>/inc/images/<?php echo $option['value']; ?>.png"/>
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
				 * Social Icons
				 */
				?>
				<tr valign="top" id="mystique-social-icons"><th scope="row"><?php _e( 'Social Icons', 'mystique' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Social Icons', 'mystique' ); ?></span></legend>

						<div><?php _e( 'Leave any URL field blank to hide its icon.', 'mystique' ); ?></div>

						<div>
							<input id="mystique_theme_options[show_rss_link]" name="mystique_theme_options[show_rss_link]" type="checkbox" value="1" <?php checked( '1', $options['show_rss_link'] ); ?> />
							<label class="description" for="mystique_theme_options[show_rss_link]"><?php _e( 'Show the RSS feed icon?', 'mystique' ); ?></label>
						</div>

						<div>
							<input id="mystique_theme_options[facebook_link]" class="regular-text" type="text" name="mystique_theme_options[facebook_link]" value="<?php esc_attr_e( $options['facebook_link'] ); ?>" />
							<label class="description" for="mystique_theme_options[facebook_link]"><?php _e( 'Enter your Facebook URL.', 'mystique' ); ?></label>
						</div>

						<div>
							<input id="mystique_theme_options[twitter_link]" class="regular-text" type="text" name="mystique_theme_options[twitter_link]" value="<?php esc_attr_e( $options['twitter_link'] ); ?>" />
							<label class="description" for="mystique_theme_options[twitter_link]"><?php _e( 'Enter your Twitter URL.', 'mystique' ); ?></label>
						</div>

						<div>
							<input id="mystique_theme_options[flickr_link]" class="regular-text" type="text" name="mystique_theme_options[flickr_link]" value="<?php esc_attr_e( $options['flickr_link'] ); ?>" />
							<label class="description" for="mystique_theme_options[flickr_link]"><?php _e( 'Enter your Flickr URL.', 'mystique' ); ?></label>
						</div>

						<div>
							<input id="mystique_theme_options[youtube_link]" class="regular-text" type="text" name="mystique_theme_options[youtube_link]" value="<?php esc_attr_e( $options['youtube_link'] ); ?>" />
							<label class="description" for="mystique_theme_options[youtube_link]"><?php _e( 'Enter your YouTube URL.', 'mystique' ); ?></label>
						</div>

						</fieldset>
					</td>
				</tr>

				<?php
				/**
				 * Featured Posts Label
				 */
				?>
				<tr valign="top" id="mystique-featured-post-label"><th scope="row"><?php _e( 'Featured Post Label', 'mystique' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Featured Post Label', 'mystique' ); ?></span></legend>

						<div>
							<input id="mystique_theme_options[featured_post_label]" class="regular-text" type="text" name="mystique_theme_options[featured_post_label]" value="<?php esc_attr_e( $options['featured_post_label'] ); ?>" />
							<label class="description" for="mystique_theme_options[featured_post_label]"><?php _e( 'Type a custom label for your featured sticky post (leave blank for no label).', 'mystique' ); ?></label>
						</div>

						</fieldset>
					</td>
				</tr>

				<?php
				/**
				 * Featured Posts on home page only?
				 */
				?>
				<tr valign="top" id="mystique-featured-post-home-only"><th scope="row"><?php _e( 'Featured Post Visibility', 'mystique' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Featured Post Visibility', 'mystique' ); ?></span></legend>

						<div>
							<input id="mystique_theme_options[featured_post_home_only]" name="mystique_theme_options[featured_post_home_only]" type="checkbox" value="1" <?php checked( '1', $options['featured_post_home_only'] ); ?> />
							<label class="description" for="mystique_theme_options[featured_post_home_only]"><?php _e( 'Show the featured post only on the home page?', 'mystique' ); ?></label>
						</div>

						</fieldset>
					</td>
				</tr>
			</table>

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Options', 'mystique' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function mystique_theme_options_validate( $input ) {

	// Our color scheme option must actually be in our array of color scheme options
	if ( ! array_key_exists( $input['color_scheme'], mystique_color_schemes() ) )
		$input['color_scheme'] = null;

	// Our radio option must actually be in our array of radio options
	if ( ! isset( $input['theme_layout'] ) )
		$input['theme_layout'] = null;
	if ( ! array_key_exists( $input['theme_layout'], mystique_layouts() ) )
		$input['theme_layout'] = null;

	// Our checkbox values should be either 0 or 1
	if ( ! isset( $input['show_rss_link'] ) )
		$input['show_rss_link'] = null;
	$input['show_rss_link'] = ( $input['show_rss_link'] == 1 ? 1 : 0 );
	if ( ! isset( $input['featured_post_home_only'] ) )
		$input['featured_post_home_only'] = null;
	$input['featured_post_home_only'] = ( $input['featured_post_home_only'] == 1 ? 1 : 0 );

	// Our text option must be safe text with no HTML tags
	$input['twitter_link'] = wp_filter_nohtml_kses( $input['twitter_link'] );
	$input['facebook_link'] = wp_filter_nohtml_kses( $input['facebook_link'] );
	$input['flickr_link'] = wp_filter_nohtml_kses( $input['flickr_link'] );
	$input['youtube_link'] = wp_filter_nohtml_kses( $input['youtube_link'] );
	$input['featured_post_text'] = wp_filter_nohtml_kses( $input['featured_post_label'] );

	// Encode URLs
	$input['twitter_link'] = esc_url_raw( $input['twitter_link'] );
	$input['facebook_link'] = esc_url_raw( $input['facebook_link'] );
	$input['flickr_link'] = esc_url_raw( $input['flickr_link'] );
	$input['youtube_link'] = esc_url_raw( $input['youtube_link'] );

	return $input;
}

// adapted from http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/