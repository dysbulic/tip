<?php
/**
 * @package WordPress
 * @subpackage zBench
 *
 * Theme options
 * Code adapted from Coraline
 */


/**
 * Add theme options page styles
 *
 * @since zBench 1.0
 */
wp_register_style( 'zbench', get_bloginfo( 'template_directory' ) . '/inc/theme-options.css', '', '0.1' );
if ( isset( $_GET['page'] ) && $_GET['page'] == 'theme_options' ) {
	wp_enqueue_style( 'zbench' );
}

/**
 * Init plugin options to white list our options
 *
 * @since zBench 1.0
 */
function zbench_theme_options_init(){
	register_setting( 'zbench_options', 'zbench_theme_options', 'zbench_theme_options_validate' );
}
add_action( 'admin_init', 'zbench_theme_options_init' );

/**
 * Load up the menu page
 *
 * @since zBench 1.0
 */
function zbench_theme_options_add_page() {
	add_theme_page( __( 'Theme Options' ), __( 'Theme Options' ), 'edit_theme_options', 'theme_options', 'zbench_theme_options_do_page' );
}
add_action( 'admin_menu', 'zbench_theme_options_add_page' );

/**
 * Return array for our layouts
 *
 * @since zBench 1.0
 */
function zbench_layouts() {
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
	);

	return $theme_layouts;
}

/**
 * Create the options page
 *
 * @since zBench 1.0
 */
function zbench_theme_options_do_page() {

	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;

	?>
	<div class="wrap">
		<?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' Theme Options' ) . "</h2>"; ?>

		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'zbench_options' ); ?>
			<?php $options = zbench_get_theme_options(); ?>

			<table class="form-table">

				<?php
				/**
				 * zbench Layout
				 */
				?>
				<tr valign="top" id="zbench-layouts"><th scope="row"><?php _e( 'Default Layout' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Default Layout' ); ?></span></legend>
						<?php
							if ( ! isset( $checked ) )
								$checked = '';
							foreach ( zbench_layouts() as $option ) {
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
									<input type="radio" name="zbench_theme_options[theme_layout]" value="<?php esc_attr_e( $option['value'] ); ?>" <?php echo $checked; ?> />
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
 *
 * @since zBench 1.0
 */
function zbench_theme_options_validate( $input ) {

	// Our radio option must actually be in our array of radio options
	if ( ! isset( $input['theme_layout'] ) )
		$input['theme_layout'] = 'content-sidebar';
	if ( ! array_key_exists( $input['theme_layout'], zbench_layouts() ) )
		$input['theme_layout'] = 'content-sidebar';

	return $input;
}