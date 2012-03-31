<?php

add_action( 'admin_init', 'selecta_theme_options_init' );
add_action( 'admin_menu', 'selecta_theme_options_add_page' );

/**
 * Properly enqueue styles and scripts for our theme options page.
 *
 * This function is attached to the admin_enqueue_scripts action hook.
 */
function selecta_admin_enqueue_scripts( $hook_suffix ) {
	wp_enqueue_style( 'selecta-theme-options', get_template_directory_uri() . '/inc/theme-options.css', false, '2011-09-30' );
	if ( is_rtl() ) {
		wp_enqueue_style( 'selecta-theme-options-rtl', get_template_directory_uri() . '/inc/theme-options-rtl.css', false, '2011-09-30' );
	}
}
add_action( 'admin_print_styles-appearance_page_theme_options', 'selecta_admin_enqueue_scripts' );

// Init plugin options to white list our options
function selecta_theme_options_init() {
	register_setting( 'selecta_options', 'selecta_theme_options', 'selecta_theme_options_validate' );
}

// Load up the menu page
function selecta_theme_options_add_page() {
	add_theme_page( __( 'Theme Options', 'selecta' ), __( 'Theme Options', 'selecta' ), 'edit_theme_options', 'theme_options', 'selecta_theme_options_do_page' );
}

// Return array for our color schemes
function selecta_color_schemes() {
	$color_schemes = array(
		'blue' => array(
			'value' =>	'blue',
			'label' => __( 'Blue (Default)', 'selecta' )
		),
		'black-blue' => array(
			'value' =>	'black-blue',
			'label' => __( 'Blue &amp; Black', 'selecta' )
		),
		'black-green' => array(
			'value' =>	'black-green',
			'label' => __( 'Green &amp; Black', 'selecta' )
		),
		'black-red' => array(
			'value' =>	'black-red',
			'label' => __( 'Red &amp; Black', 'selecta' )
		),
		'sea-blue' => array(
			'value' =>	'sea-blue',
			'label' => __( 'Sea Blue', 'selecta' )
		),
		'white-rose' => array(
			'value' =>	'white-rose',
			'label' => __( 'White Rose', 'selecta' )
		),
	);

	return $color_schemes;
}

// Return arrays for our Featured Slider and Latest Posts settings
function selecta_featured_slider() {
	$theme_slider = array(
		'yes' => array(
			'value' => 'yes',
			'label' => __( 'Yes', 'selecta' ),
		),
		'no' => array(
			'value' => 'no',
			'label' => __( 'No', 'selecta' )
		),
	);
	return $theme_slider;
}
function selecta_latest_posts() {
	$theme_latest_posts = array(
		'yes' => array(
			'value' => 'yes',
			'label' => __( 'Yes', 'selecta' ),
		),
		'no' => array(
			'value' => 'no',
			'label' => __( 'No', 'selecta' )
		),
	);
	return $theme_latest_posts;
}

// Create the options page
function selecta_theme_options_do_page() {

	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;

	?>
	<div class="wrap">
		<?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' Theme Options', 'selecta' ) . "</h2>"; ?>

		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved', 'selecta' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'selecta_options' ); ?>
			<?php $options = selecta_get_options(); ?>

			<table class="form-table">

				<?php // Selecta Color Scheme ?>
				<tr valign="top" id="selecta-colors"><th scope="row"><?php _e( 'Color Scheme', 'selecta' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Color Scheme', 'selecta' ); ?></span></legend>
						<?php
							if ( ! isset( $checked ) )
								$checked = '';
							foreach ( selecta_color_schemes() as $option ) {
								$radio_setting = $options['color_scheme'];

								if ( '' != $radio_setting ) {
									if ( $options['color_scheme'] == $option['value'] ) {
										$checked = "checked=\"checked\"";
									} else {
										$checked = '';
									}
								}
								?>
								<div class="layout">
								<label class="description">
									<input type="radio" name="selecta_theme_options[color_scheme]" value="<?php echo esc_attr( $option['value'] ); ?>" <?php echo $checked; ?> />
									<span>
										<img src="<?php echo get_template_directory_uri(); ?>/colors/<?php echo $option['value']; ?>.png"/>
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

				<?php // Selecta Featured Slider ?>
				<tr valign="top" id="selecta-featured-slider"><th scope="row"><?php _e( 'Show sticky posts in a featured slider on the front page', 'selecta' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Show sticky posts in a featured slider on the front page', 'selecta' ); ?></span></legend>
						<?php
							if ( ! isset( $checked ) )
								$checked = '';
							foreach ( selecta_featured_slider() as $option ) {
								$radio_setting = $options['theme_slider'];

								if ( '' != $radio_setting ) {
									if ( $options['theme_slider'] == $option['value'] ) {
										$checked = "checked=\"checked\"";
									} else {
										$checked = '';
									}
								}
								?>
								<div class="layout">
								<label class="description">
									<input type="radio" name="selecta_theme_options[theme_slider]" value="<?php esc_attr_e( $option['value'], 'selecta' ); ?>" <?php echo $checked; ?> />
									<span>
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
				<?php // Selecta Latest Posts ?>
				<tr valign="top" id="selecta-latest-posts"><th scope="row"><?php _e( 'Show Latest Posts row on the front fage', 'selecta' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Show Latest Posts row on the front fage', 'selecta' ); ?></span></legend>
						<?php
							if ( ! isset( $checked ) )
								$checked = '';
							foreach ( selecta_latest_posts() as $option ) {
								$radio_setting = $options['theme_latest_posts'];

								if ( '' != $radio_setting ) {
									if ( $options['theme_latest_posts'] == $option['value'] ) {
										$checked = "checked=\"checked\"";
									} else {
										$checked = '';
									}
								}
								?>
								<div class="layout">
								<label class="description">
									<input type="radio" name="selecta_theme_options[theme_latest_posts]" value="<?php esc_attr_e( $option['value'], 'selecta' ); ?>" <?php echo $checked; ?> />
									<span>
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
				<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Options', 'selecta' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function selecta_theme_options_validate( $input ) {
	if ( ! array_key_exists( $input['color_scheme'], selecta_color_schemes() ) )
		$input['color_scheme'] = 'blue';

	if ( ! array_key_exists( $input['theme_slider'], selecta_featured_slider() ) )
		$input['theme_slider'] = 'yes';

	if ( ! array_key_exists( $input['theme_latest_posts'], selecta_latest_posts() ) )
	$input['theme_latest_posts'] = 'yes';

	return $input;
}
// adapted from http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/