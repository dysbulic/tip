<?php
/**
 * Hemingway Options
 *
 * @package WordPress
 * @subpackage Hemingway
 **/
class Hemingway {

	var $raw_blocks;
	var $available_blocks;
	var $style;
	var $version;

	function add_available_block( $block_name, $block_ref ) {
		$blocks = $this->available_blocks;

		if ( ! $blocks[$block_ref] ) {
			$blocks[$block_ref] = $block_name;
			update_option( 'hem_available_blocks', $blocks );
		}
	}

	/*
		This function returns an array of available blocks
		in the format of $arr[block_ref] = block_name
	*/
	function get_available_blocks()	{
		$this->available_blocks = get_option( 'hem_available_blocks' );
		return $this->available_blocks;
	}

	/*
		Returns an array of block_refs in specified block
	*/
	function get_block_contents( $block_place ) {
		if ( ! $this->raw_blocks ) {
			$this->raw_blocks = get_option( 'hem_blocks' );
		}

		return $this->raw_blocks[$block_place];
	}

	function add_block_to_place( $block_place, $block_ref ) {
		$block_contents = $this->get_block_contents( $block_place );

		if (in_array( $block_ref, $block_contents ) )
			return true;

		$block_contents[] = $block_ref;
		$this->raw_blocks[$block_place] = $block_contents;
		update_option( 'hem_blocks', $this->raw_blocks );

		return true;
	}

	function remove_block_in_place( $block_place, $block_ref ) {
		$block_contents = $this->get_block_contents( $block_place );

		if ( ! in_array( $block_ref, $block_contents ) )
			return true;

		$key = array_search( $block_ref, $block_contents );
		unset( $block_contents[$key] );
		$this->raw_blocks[$block_place] = $block_contents;
		update_option( 'hem_blocks', $this->raw_blocks );

		return true;
	}

	/*
		Templating functions
	*/
	function get_block_output( $block_place ) {
		$blocks = $this->get_block_contents( $block_place );

		foreach ( $blocks as $key => $block ) {
			include( TEMPLATEPATH . '/blocks/' . $block . '.php' );
		}
	}

	function get_style() {
		$this->style = get_option( 'hem_style' );
	}

	function set_style( $new_style ) {
		update_option( 'hem_style', $new_style );
		$this->style = $new_style;
	}

}

// Set up option object
$hemingway = new Hemingway();
$hemingway->get_available_blocks();
$hemingway->get_style();
$hemingway->version = "0.13";

// Set up form and default options
add_action( 'admin_menu', 'hemingway_add_theme_options_page' );
add_action( 'admin_init', 'hemingway_init_theme_options' );

function hemingway_add_theme_options_page() {
	$page = add_theme_page( __( 'Theme Options', 'hemingway' ), __( 'Theme Options', 'hemingway' ), 'edit_theme_options', 'theme-options', 'hemingway_theme_options' );
	add_action( "admin_print_scripts-$page", 'hemingway_admin_js' );
	add_action( "load-$page", 'hemingway_save_theme_options' );
}

function hemingway_init_theme_options() {

	global $hemingway;

	$default_blocks = array(
		'recent_entries' => __( 'Recent Entries', 'hemingway' ),
		'about_page' => __( 'About Page', 'hemingway' ),
		'category_listing' => __( 'Category Listing', 'hemingway' ),
		'blogroll' => __( 'Blogroll', 'hemingway' ),
		'pages' => __( 'Pages', 'hemingway' ),
		'monthly_archives' => __( 'Monthly Archives', 'hemingway' ),
	);

	$default_block_locations = array(
		'block_1' => array( 'about_page' ),
		'block_2' => array( 'recent_entries' ),
		'block_3' => array( 'category_listing' ),
	);

	// Check for previous options
	if ( !get_option( 'hem_version' ) || get_option( 'hem_version' ) < $hemingway->version ) {
		// Hemingway wasn't installed before, so we'll need to add options
		if ( ! get_option( 'hem_version' ) )
			add_option( 'hem_version', $hemingway->version, __( 'Hemingway Version installed', 'hemingway' ) );
		else
			update_option( 'hem_version', $hemingway->version);

		if ( ! get_option( 'hem_available_blocks' ) )
			add_option( 'hem_available_blocks', $default_blocks, __( 'A list of available blocks for Hemingway', 'hemingway' ) );

		if ( ! get_option( 'hem_blocks' ) )
			add_option( 'hem_blocks', $default_block_locations, __( 'An array of blocks and their contents', 'hemingway' ) );

		if ( ! get_option( 'hem_style' ) )
			add_option( 'hem_style', 'none', __( 'Location of custom style sheet', 'hemingway' ) );
	}

}

function hemingway_admin_js() {
	wp_enqueue_script( 'prototype' );
	wp_enqueue_script( 'scriptaculous-dragdrop' );
	wp_enqueue_script( 'scriptaculous-effects' );
}

function hemingway_save_theme_options() {

	global $hemingway, $message;

	if ( ! current_user_can( 'edit_theme_options' ) )
		return;

	// Process POST requests
	if ( isset( $_POST['custom_styles'] ) ) {
		check_admin_referer( 'custom_styles' );

		$hemingway->set_style( $_POST['custom_styles'] ); // save new option
		$message = __( 'Color options updated.', 'hemingway' );
	}

	if ( isset ( $_POST['block_ref'] ) ) {
		check_admin_referer( 'block_ref' );

		$hemingway->add_available_block( $_POST['display_name'], $_POST['block_ref'] );
		$hemingway->get_available_blocks();

		$message = __( 'Block added.', 'hemingway' );
	}
	// END Process POST requests

	// Process Ajax requests for adding/removing blocks
	if ( isset( $_GET['hem_action'] ) ) {

		$action = $_GET['hem_action'];

		if ( $action == 'add_block' ) {
			check_ajax_referer( 'add_block' );

			$block_ref = $_GET['block_ref'];
			$block_place = $_GET['block_place'];
			$block_name = __( $hemingway->available_blocks[$block_ref], 'hemingway' );
			$hemingway->add_block_to_place( $block_place, $block_ref );

			$output = '<ul>';
			foreach ( $hemingway->get_block_contents( $block_place ) as $key => $block_ref ) {
					$block_name = __( $hemingway->available_blocks[$block_ref], 'hemingway' );
					$output .= '<li>' . $block_name . ' (<a href="#" onclick="remove_block(\'' . $block_place . '\', \'' . $block_ref . '\' );">' . __( 'remove', 'hemingway' ) . '</a>)</li>';
			}
			$output .= '</ul>';

			echo $output;
			exit(); // Kill any more output
		}

		if ( $action == 'remove_block' ) {
			check_ajax_referer( 'remove_block' );

			$block_ref = $_GET['block_ref'];
			$block_place = $_GET['block_place'];
			$hemingway->remove_block_in_place( $block_place, $block_ref );

			$output = '<ul>';
			foreach ( $hemingway->get_block_contents( $block_place ) as $key => $block_ref ) {
					$block_name = __( $hemingway->available_blocks[$block_ref], 'hemingway' );
					$output .= '<li>' . $block_name . ' (<a href="#" onclick="remove_block(\'' . $block_place . '\', \'' . $block_ref . '\' );">' . __( 'remove', 'hemingway' ) . '</a>)</li>';
			}
			$output .= '</ul>';

			echo $output;
			exit(); // Kill any more output
		}

	} // END Process Ajax requests

}

function hemingway_theme_options() {

	global $hemingway, $message;

	if ( ! current_user_can( 'edit_theme_options' ) )
		wp_die( __( 'Cheatin&#8217; uh?' ) );
	?>

	<?php if ( $message ) : ?>
		<div id="message" class="updated fade"><p><?php echo $message; ?></p></div>
	<?php endif; ?>

	<?php // Build options form ?>
	<div class="wrap" style="position:relative;">
		<h2><?php _e( 'Hemingway Options', 'hemingway' ); ?></h2>

		<h3><?php _e( 'Color Options', 'hemingway' ); ?></h3>
		<p><?php _e( 'Choose a primary color for your site:', 'hemingway' ); ?></p>

		<form action="" method="post">
			<?php wp_nonce_field( 'custom_styles' ); ?>
			<p><label><input name="custom_styles" type="radio" value="none" <?php if ( 'none' == $hemingway->style || '' == $hemingway->style ) echo 'checked="checked"'; ?> />
			<?php _e( 'Black', 'hemingway' ); ?></label></p>
			<p><label><input name="custom_styles" type="radio" value="white.css" <?php if ( 'white.css' == $hemingway->style ) echo 'checked="checked"'; ?> /> <?php _e( 'White', 'hemingway' ); ?></label></p>

			<input type="submit" value="<?php esc_attr_e( 'Update Color &raquo;', 'hemingway' ); ?>" />
		</form>

		<h3><?php _e("Hemingway's Bottombar&trade;", 'hemingway' ); ?></h3>
		<p><?php _e( 'Drag and drop the different blocks into their place below. After you drag the block to the area, it will update with the new contents automatically.', 'hemingway' ); ?></p>
		<p><?php _e( '*Note: Widgets take preference over these blocks.', 'hemingway' ); ?></p>
		<ul id="addables">
			<?php foreach ( $hemingway->available_blocks as $ref => $name ) : ?>
			<li id="<?php echo $ref; ?>" class="blocks"><?php _e( $name, 'hemingway' ); ?></li>
			<script type="text/javascript">new Draggable( '<?php echo $ref; ?>', {revert:true})</script>
			<?php endforeach; ?>
		</ul>

	<div class="clear"></div>

		<?php // Ajax functions to add/remove a block ?>
		<?php // Remove a block ?>
		<?php $nonce_remove = wp_create_nonce( 'remove_block' ); ?>
		<script type="text/javascript">
			function remove_block(block_place, block_ref) {
				url = 'themes.php?page=theme-options&_ajax_nonce=<?php echo $nonce_remove; ?>&hem_action=remove_block&block_place=' + block_place + '&block_ref=' + block_ref;
				new Ajax.Updater(block_place, url,
						{
							evalScripts:true, asynchronous:true
						}
				)
			}
		</script>
		<style>
			.block{
				width:200px;
				height:200px;
				border:1px solid #CCC;
				float:left;
				margin:20px 1em 20px 0;
				padding:10px;
				display:inline;
			}
			.block ul{
				padding:0;
				margin:0;
			}
			.block ul li{
				margin:0 0 5px 0;
				list-style-type:none;
			}
			.block-active{
				border:1px solid #333;
				background:#F2F8FF;
			}

			#addables li{
				list-style-type:none;
				margin:1em 1em 1em 0;
				background:#EAEAEA;
				border:1px solid #DDD;
				padding:3px;
				width:215px;
				float:left;
				cursor:move;
			}
			ul#addables{
				margin:0;
				padding:0;
				width:720px;
				position:relative;
			}
		</style>

		<?php // Add a block ?>
		<?php $nonce_add = wp_create_nonce( 'add_block' ); ?>
		<div class="block" id="block_1">
			<ul>
				<?php
				foreach ( $hemingway->get_block_contents( 'block_1' ) as $key => $block_ref ) :
					$block_name = __( $hemingway->available_blocks[$block_ref], 'hemingway' );
				?>
					<li><?php echo $block_name; ?> (<a href="#" onclick="remove_block( 'block_1', '<?php echo $block_ref; ?>' );"><?php _e( 'remove', 'hemingway' ); ?></a>)</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<script type="text/javascript">
		Droppables.add(
			'block_1', {
				accept:'blocks',
				onDrop:function(element) {
					new Ajax.Updater( 'block_1', 'themes.php?page=theme-options&_ajax_nonce=<?php echo $nonce_add; ?>&hem_action=add_block&block_place=block_1&block_ref=' + element.id,
						{
							evalScripts:true, asynchronous:true
						}
					)
				},
				hoverclass:'block-active'
			}
		)
		</script>

		<div class="block" id="block_2">
			<ul>
				<?php
				foreach ( $hemingway->get_block_contents( 'block_2' ) as $key => $block_ref ) :
					$block_name = __( $hemingway->available_blocks[$block_ref], 'hemingway' );
				?>
					<li><?php echo $block_name; ?> (<a href="#" onclick="remove_block( 'block_2', '<?php echo $block_ref; ?>' );"><?php _e( 'remove', 'hemingway' ); ?></a>)</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<script type="text/javascript">
		Droppables.add(
			'block_2', {
				accept:'blocks',
				onDrop:function(element) {
					new Ajax.Updater( 'block_2', 'themes.php?page=theme-options&_ajax_nonce=<?php echo $nonce_add; ?>&hem_action=add_block&block_place=block_2&block_ref=' + element.id,
						{
							evalScripts:true, asynchronous:true
						}
					)
				},
				hoverclass:'block-active'
			}
		)
		</script>

		<div class="block" id="block_3">
			<ul>
				<?php
				foreach ( $hemingway->get_block_contents( 'block_3' ) as $key => $block_ref ) :
					$block_name = __( $hemingway->available_blocks[$block_ref], 'hemingway' );
				?>
					<li><?php echo $block_name; ?> (<a href="#" onclick="remove_block( 'block_3', '<?php echo $block_ref; ?>' );"><?php _e( 'remove', 'hemingway' ); ?></a>)</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<script type="text/javascript">
		Droppables.add(
			'block_3', {
				accept:'blocks',
				onDrop:function(element) {
					new Ajax.Updater( 'block_3', 'themes.php?page=theme-options&_ajax_nonce=<?php echo $nonce_add; ?>&hem_action=add_block&block_place=block_3&block_ref=' + element.id,
						{
							evalScripts:true, asynchronous:true
						}
					)
				},
				hoverclass:'block-active'
			}
		)
		</script>

		<div class="clear"></div>

		<?php
			$blocks_dir = @ dir(ABSPATH . '/wp-content/themes/' . get_template() . '/blocks' );

			if ( $blocks_dir) {
				while ( ( $file = $blocks_dir->read() ) !== false ) {
						if ( !preg_match( '|^\.+$|', $file ) && preg_match( '|\.php$|', $file ) )
						$blocks_files[] = $file;
					}
				}
				if ( $blocks_dir || $blocks_files ) {
					foreach ( $blocks_files as $blocks_file ) {
					$block_ref = preg_replace( '/\.php/', '', $blocks_file );
					if ( ! array_key_exists( $block_ref, $hemingway->available_blocks ) ) {
					?>
					<h3><?php _e( 'You have uninstalled blocks!', 'hemingway' );?></h3>
					<p><?php printf(_( 'Give the block <strong>%s</strong> a display name (such as "About Page")', 'hemingway' ), $block_ref ); ?>
					<form action="" method="post">
						<?php wp_nonce_field( 'block_ref' ); ?>
						<input type="hidden" name="block_ref" value="<?php echo $block_ref; ?>" />
						<?php echo $block_ref; ?> : <input type="text" name="display_name" />
						<input type="submit" value="<?php esc_attr_e( 'Save', 'hemingway' ); ?>" />
					</form>
					<?
					}
				}
			}
		?>
	</div>

<?php }