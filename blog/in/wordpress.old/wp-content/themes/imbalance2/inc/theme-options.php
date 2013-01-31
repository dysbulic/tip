<?php
/**
 * @package Imbalance 2
 */
?>
<?php
// Properly enqueue styles and scripts for our theme options page.
function imbalance2_admin_enqueue_scripts( $hook_suffix ) {
	wp_enqueue_script( 'farbtastic' );
	wp_enqueue_style( 'farbtastic' );
}
add_action( 'admin_print_styles-appearance_page_theme_options', 'imbalance2_admin_enqueue_scripts' );

// Register the form setting for our imbalance2_options array.
function imbalance2_theme_options_init() {

	// If we have no options in the database, let's add them now.
	if ( false === imbalance2_get_theme_options() )
		add_option( 'imbalance2_theme_options', imbalance2_get_default_theme_options() );

	register_setting(
		'imbalance2_options',
		'imbalance2_theme_options',
		'imbalance2_theme_options_validate'
	);
}
add_action( 'admin_init', 'imbalance2_theme_options_init' );

// Change the capability required to save the 'imbalance2_theme_options' group.
function imbalance2_option_page_capability( $capability ) {
	return 'edit_theme_options';
}
add_filter( 'option_page_capability_imbalance2_options', 'imbalance2_option_page_capability' );

// Add our theme options page to the admin menu, including some help documentation.
function imbalance2_theme_options_add_page() {
	add_theme_page( __( 'Theme Options', 'imbalance2' ), __( 'Theme Options', 'imbalance2' ), 'edit_theme_options', 'theme_options', 'theme_options_do_page' );
}
add_action( 'admin_menu', 'imbalance2_theme_options_add_page' );

// Returns an array of sticky options registered for Imbalance 2.
function imbalance2_sticky_options() {
	$sticky_options = array(
		'yes' => array(
			'value' => 'yes',
			'label' => __( 'Yes', 'imbalance2' ),
		),
		'no' => array(
			'value' => 'no',
			'label' => __( 'No', 'imbalance2' )
		)
	);
	return apply_filters( 'imbalance2_sticky_options', $sticky_options );
}

// Returns an array of sticky options registered for Imbalance 2.
function imbalance2_fluid_options() {
	$fluid_options = array(
		'yes' => array(
			'value' => 'yes',
			'label' => __( 'Yes', 'imbalance2' ),
		),
		'no' => array(
			'value' => 'no',
			'label' => __( 'No', 'imbalance2' )
		)
	);
	return apply_filters( 'imbalance2_fluid_options', $fluid_options );
}

// Returns the default options for Imbalance 2.
function imbalance2_get_default_theme_options() {
	$default_theme_options = array(
		'color' => '#f05133',
		'sticky' => 'no',
		'fluid' => 'no'
	);

	return apply_filters( 'imbalance2_default_theme_options', $default_theme_options );
}

// Returns the options array for Imbalance 2.
function imbalance2_get_theme_options() {
	return get_option( 'imbalance2_theme_options', imbalance2_get_default_theme_options() );
}

// Create the options page
function theme_options_do_page() {

	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;
	?>

	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php printf( __( '%s Theme Options', 'imbalance2' ), get_current_theme() ); ?></h2>

		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved', 'imbalance2' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'imbalance2_options' ); ?>
			<?php $options = imbalance2_get_theme_options(); ?>
			<?php $default_options = imbalance2_get_default_theme_options(); ?>

			<table class="form-table">

				<tr valign="top"><th scope="row"><?php _e( 'Select theme color', 'imbalance2' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Select theme color', 'imbalance2' ); ?></span></legend>
						<input type="text" id="imbalance2_theme_options_color" name="imbalance2_theme_options[color]" value="<?php esc_attr_e( $options['color'] ); ?>" />
						<div id="colorpicker"></div>

						<script type="text/javascript">
						jQuery(document).ready(function() {
							jQuery( '#colorpicker' ).farbtastic( '#imbalance2_theme_options_color' );
						});
						</script>
						</fieldset>
					</td>
				</tr>

				<tr valign="top"><th scope="row"><?php _e( 'Show sticky posts below a single post', 'imbalance2' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Show sticky posts below a single post', 'imbalance2' ); ?></span></legend>
						<?php
							foreach ( imbalance2_sticky_options() as $option ) {
								?>
								<div class="layout">
								<label class="description">
									<input type="radio" name="imbalance2_theme_options[sticky]" value="<?php echo esc_attr( $option['value'] ); ?>" <?php checked( $options['sticky'], $option['value'] ); ?> />

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

				<tr valign="top"><th scope="row"><?php _e( 'Enable fluid grid layout', 'imbalance2' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Enable fluid grid layout', 'imbalance2' ); ?></span></legend>
						<?php
							foreach ( imbalance2_fluid_options() as $option ) {
								?>
								<div class="layout">
								<label class="description">
									<input type="radio" name="imbalance2_theme_options[fluid]" value="<?php echo esc_attr( $option['value'] ); ?>" <?php checked( $options['fluid'], $option['value'] ); ?> />

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
				<?php submit_button( __( 'Save Options', 'imbalance2' ), 'button-primary', 'submit', false ); ?>
				<?php submit_button( __( 'Reset Options', 'imbalance2' ), 'button-secondary', 'imbalance2_theme_options[reset]', false, array( 'id' => 'reset' ) ); ?>
			</p>
		</form>
	</div>
	<?php
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function imbalance2_theme_options_validate( $input ) {

	$output = $defaults = imbalance2_get_default_theme_options();

	if ( isset( $input['color'] ) && preg_match( '/^#?([a-f0-9]{3}){1,2}$/i', $input['color'] ) )
		$output['color'] = '#' . strtolower( ltrim( $input['color'], '#' ) );

	if ( isset( $input['sticky'] ) && array_key_exists( $input['sticky'], imbalance2_sticky_options() ) )
		$output['sticky'] = $input['sticky'];

	if ( isset( $input['fluid'] ) && array_key_exists( $input['fluid'], imbalance2_fluid_options() ) )
		$output['fluid'] = $input['fluid'];

	// Reset to default options
	if ( ! empty( $input['reset'] ) ) {
		$output = $defaults = imbalance2_get_default_theme_options();
		foreach ( $ouput as $field => $value ) {
			if ( isset( $defaults[$field] ) )
				$output[$field] = $defaults[$field];
			else
				unset( $output[$field] );
		}
	}

	return apply_filters( 'imbalance2_theme_options_validate', $output, $input, $defaults );
}

function imbalance2_print_color_style() {
	$options = imbalance2_get_theme_options();
	$color = $options['color'];
?>
	<style type="text/css">
	/* <![CDATA[ */
		a,
		.menu a:hover,
		.menu ul .current_page_item > a,
		.menu ul .current_page_ancestor > a,
		.menu ul .current-menu-item > a,
		.menu ul .current-menu-ancestor > a,
		#nav-above a:hover,
		#footer a:hover,
		.entry-meta a:hover,
		.widget_flickr #flickr_badge_uber_wrapper a:hover,
		.widget_flickr #flickr_badge_uber_wrapper a:link,
		.widget_flickr #flickr_badge_uber_wrapper a:active,
		.widget_flickr #flickr_badge_uber_wrapper a:visited {
			color: <?php echo $color ?>;
		}
		.fetch:hover {
			background: <?php echo $color ?>;
		}
		blockquote {
			border-color: <?php echo $color ?>;
		}
		.box .texts {
			border: 20px solid <?php echo $color ?>;
			background: <?php echo $color ?>;
		}
	/* ]]> */
	</style>
<?php
}
add_action( 'wp_head', 'imbalance2_print_color_style' );

function imbalance2_print_wrapper_style() {
	$options = imbalance2_get_theme_options();
	if ( 'no' == $options['fluid'] ) :
		$wrapper_style = 'width: 960px; margin: 0 auto;';
	elseif ( 'yes' == $options['fluid'] ) :
		$wrapper_style = 'margin: 0 40px;';
	endif;
?>
	<style type="text/css">
	/* <![CDATA[ */
		#wrapper {
			<?php echo $wrapper_style; ?>
		}
	/* ]]> */
	</style>
<?php
}
add_action( 'wp_head', 'imbalance2_print_wrapper_style' );