<?php
/**
 * @package WordPress
 * @subpackage zbench
 *
 * Large chunk of code borrowed from Toolbox. http://wordpress.org/extend/themes/toolbox
 */

/**
 * Set theme colours and width
 */
$themecolors = array(
	'bg' => 'f7f7f7',
	'border' => 'cccccc',
	'text' => '242424',
	'link' => '333',
	'url' => '333'
);

// Calculate content_width based on layout option
$content_width = 630;
if ( 'three-column sidebar-content-sidebar' == zbench_current_layout() )
	$content_width = 472;

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_editor_style() To style the visual editor.
 * @uses add_custom_image_header() To add support for a custom header.
 *
 * @since zBench 1.0
 */
function zbench_setup() {
	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'primary-menu' => __( 'Primary Menu' ),
		)
	);

	// This theme has some pretty cool theme options
	require ( dirname( __FILE__ ) . '/theme-options.php' );

	// This theme allows users to set a custom background
	add_custom_background();

	// This theme allows users to add a custom image header
	add_custom_image_header( 'zbench_header_style', 'zbench_admin_header_style', 'zbench_admin_header_image' );

	// Add feed links to the head section
	add_theme_support( 'automatic-feed-links' );

	// Your changeable header business starts here
	define( 'HEADER_TEXTCOLOR', '222' ); // Text colour of custom header
	define( 'HEADER_IMAGE_WIDTH', 960 ); // Width of custom header
	define( 'HEADER_IMAGE_HEIGHT', 200 ); // Height of custom header

}
add_action( 'after_setup_theme', 'zbench_setup' );

/**
 * Included in the site head for custom headers
 *
 * @since zBench 1.0
 */
function zbench_header_style() { ?>
	<style type="text/css">
	<?php if ( get_header_image() ) : ?>
	#header-background {
		width: 100%;
		height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
		-moz-border-radius: 6px;
		-khtml-border-radius: 6px;
		-webkit-border-radius: 6px;
		border-radius: 6px;
		background: #454546 url(<?php header_image(); ?>) 50% 0;
		margin: 0 0 20px 0;
	}
	#header-background a {
		display: block;
		text-decoration: none;
		width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
		height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	}
	#header-background a span {
		visibility: hidden;
	}	
	<?php endif;
	if ( get_header_textcolor() ) : ?>
	#title h1,
	#title h1 a,
	#title p {
		color: #<?php header_textcolor(); ?> !important;
	}
	<?php endif;

	/* If text has been hidden */
	if ( 'blank' == get_header_textcolor() ) : ?>
	/* Remove title text */
	#title h1,
	#title p {
		display: none;
	}
	<?php
	endif;
	?>
</style><?php
}

/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 * Referenced via add_custom_image_header() in zbench_setup().
 *
 * @since zBench 1.0
 */
function zbench_admin_header_image() {
	$header = get_header_image();
	?>
	<div id="zbench_title">
		<h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php bloginfo( 'url' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<p id="desc" <?php echo $style; ?>><?php bloginfo( 'description' ); ?></P>
		<?php if ( '' != $header ) : ?>
		<div id="header-background"></div>
		<?php endif; ?>
	</div>
<?php }

/**
 * Included in the admin head for custom headers
 *
 * @since zBench 1.0
 */
function zbench_admin_header_style() { ?>
<style type="text/css">
	#zbench_title h1 {
		float: left;
		font-family: Georgia, "Times New Roman", Times,serif;
		font-weight: bold;
		color: #555;
		margin:0 10px 18px;
		padding: 0;
		text-shadow: 1px 1px 1px #999;
		font-size: 28px;
	}
	#zbench_title, #zbench_title h1 a {
		color: #<?php header_textcolor(); ?>;
		text-decoration: none;
	}
	#zbench_title a:hover {
		text-decoration: none;
	}
	#zbench_title p {
		float: left;
		line-height: 18px;
		margin: 5px 0 0 20px;
		padding: 0;
		color: #<?php header_textcolor(); ?>;
		font-family: Georgia, 'Times New Roman', sans-serif;
		font-size: 12px;
		font-weight: normal;
		text-shadow: 0 1px 0 #fff;
	}
	#zbench_title #header-background {
		-moz-border-radius: 6px;
		-khtml-border-radius: 6px;
		-webkit-border-radius: 6px;
		border-radius: 6px;
		float: left;
		width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
		height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
		background: url(<?php header_image(); ?>) no-repeat 0 0;
		clear: left;
	}
</style><?php
}

/**
 * Register widget areas
 *
 * @since zBench 1.0
 */
function zbench_widgets_init() {
	// Registers Primary Widget Area
	register_sidebar(
		array (
			'name' => __( 'Primary Widget Area', 'zbench' ),
			'id' => 'primary-widget-area',
			'description' => __( 'The primary widget area', 'zbench' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		)
	);

	// Registers Secondary Widget Area
	register_sidebar(
		array (
			'name' => __( 'Secondary Widget Area', 'zbench' ),
			'id' => 'secondary-widget-area',
			'description' => __( 'The primary widget area', 'zbench' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		)
	);

	// Registers Featured Widget Area
	register_sidebar(
		array (
			'name' => __( 'Featured Widget Area', 'zbench' ),
			'id' => 'featured-widget-area',
			'description' => __( 'The featured widget area', 'zbench' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		)
	);

	// Registers First Footer Widget Area
	register_sidebar(
		array (
			'name' => __( 'First Footer Widget Area', 'zbench' ),
			'id' => 'first-footer-widget-area',
			'description' => __( 'The first footer widget area', 'zbench' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		)
	);

	// Registers Second Footer Widget Area
	register_sidebar(
		array (
			'name' => __( 'Second Footer Widget Area', 'zbench' ),
			'id' => 'second-footer-widget-area',
			'description' => __( 'The second footer widget area', 'zbench' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		)
	);

	// Registers Third Footer Widget Area
	register_sidebar(
		array (
			'name' => __( 'Third Footer Widget Area', 'zbench' ),
			'id' => 'third-footer-widget-area',
			'description' => __( 'The third footer widget area', 'zbench' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		)
	);

	// Registers Fourth Footer Widget Area
	register_sidebar(
		array (
			'name' => __( 'Fourth Footer Widget Area', 'zbench' ),
			'id' => 'fourth-footer-widget-area',
			'description' => __( 'The fourth footer widget area', 'zbench' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'zbench_widgets_init' );

/**
 * Make theme available for translation
 * Translations can be filed in the /languages/ directory
 *
 * @since zBench 1.0
 */
load_theme_textdomain( 'zbench', dirname( __FILE__ ) . '/languages' );
$locale = get_locale();
$locale_file = get_template_directory() . '/languages/$locale.php';
if ( is_readable( $locale_file ) )
	require_once( $locale_file );

/**
 * Returns the current zBench layout as selected in the theme options
 *
 * @since zBench 1.0
 */
function zbench_current_layout() {
	$options = get_option( 'zbench_theme_options' );
	$current_layout = $options['theme_layout'];

	$two_columns = array( 'content-sidebar', 'sidebar-content' );

	if ( in_array( $current_layout, $two_columns ) )
		return 'two-column ' . $current_layout;
	else
		return 'three-column ' . $current_layout;
}

/**
 *  Adds zbench_current_layout() to the array of body classes
 *
 * @since zBench 1.0
 */
function zbench_body_class($classes) {
	$classes[] = zbench_current_layout();

	return $classes;
}
add_filter( 'body_class', 'zbench_body_class' );

/**
 * Add class attributes to the first <ul> occurence in wp_page_menu and strip <div> tags and strip title attributes
 * Needed for suckerfish script to function correctly
 * Code courtesy of Ian Stewart ... http://themeshaper.com/adding-class-wordpress-page-menu/
 *
 * @since zBench 1.0
 */
function zbench_add_menuclass( $menu ) {
	$menu = preg_replace( '/<ul>/', '<ul class="nav sf-menu">', $menu, 1 ); // Add classes
	$menu = preg_replace( '/<div class="nav sf-menu">/', '', $menu, 1 ); // Remove opening DIV
	$menu = preg_replace( '/<\/div>/', '', $menu, 1 ); // Remove closing DIV
	$menu = preg_replace( '/title=\"(.*?)\"/','',$menu ); // Strip title attributes
	return $menu;
}
add_filter( 'wp_page_menu','zbench_add_menuclass' );

/**
 * Adding home link to wp_nav_menu() fallback
 *
 * @since zBench 1.0
 */
function zbench_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'zbench_page_menu_args' );

/**
 * Loading scripts
 * Animations script from PixoPoint Animations plugin ... http://pixopoint.com/2010/03/03/pixopoint-menu-animations-beta/
 * Suckerfish script for IE6 support combined into animations script (already loading JS file so may as well include it there rather than use conditionals)
 *
 * @since zBench 1.0
 */
function zbench_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script(
		'menu',
		get_bloginfo( 'template_url' ) . '/scripts/menu.js',
		array( 'jquery' ),
		1.0,
		true
	);
	if ( is_singular() )
		wp_enqueue_script( 'comment-reply' );
}
add_action(
	'wp_print_scripts',
	'zbench_scripts'
);

/**
 * Comments callback function
 *
 * @since zBench 1.0
 */
function zbench_comment( $comment, $args, $depth ) {
	$GLOBALS ['comment'] = $comment; ?>
	<?php

	/* Display Comments */
	if ( '' == $comment->comment_type ) :  ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
			<?php

			/* Display commenters gravatar */
			echo get_avatar( $comment, 40 );

			/* Display authors name */
			printf( __( '<cite class="fn">%s</cite>', 'zbench' ), get_comment_author_link() );

			/* Display comment link, date and time */ ?>
			<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><?php printf( __( '%1$s at %2$s', 'zbench' ), get_comment_date(),  get_comment_time() ); ?></a>
			<?php

			/* Edit comment link */
			edit_comment_link( __( '(Edit)', 'zbench' ),'  ','' );

			/* Message for when comment not approved yet */
			if ( $comment->comment_approved == '0' )
				echo '<em>' . __( 'Your comment is awaiting moderation.', 'zbench' ) . '</em><br />';

			/* Display the comment itself */ ?>
			<div class="comment-body"><?php comment_text(); ?></div>

			<div class="reply">
				<?php
				/* Comment reply link */
				comment_reply_link(
					array_merge(
						$args, array(
							'depth'     => $depth,
							'max_depth' => $args['max_depth']
						)
					)
				);
				?>
			</div>
		</div>
	<?php

	/* Display pingbacks */
	else : ?>
	<li class="post pingback">
		<p><?php _e( 'Pingback: ', 'zbench' ); ?><?php comment_author_link(); ?><?php edit_comment_link ( __( 'edit', 'zbench' ), '&nbsp;&nbsp;', '' ); ?></p>
	<?php endif;
}

/*
 * Register with hook 'wp_print_styles'
 * Enqueue style sheet
 *
 * @since zBench 1.0
 */
add_action( 'wp_print_styles', 'zbench_stylesheet' );
function zbench_stylesheet() {
	$options = get_option( 'zbench_theme_options' );

	// Register stylesheets
	wp_register_style( 'zbench_stylesheet', get_bloginfo( 'stylesheet_url' ) );

	// Load stylesheets
	wp_enqueue_style( 'zbench_stylesheet' );
}
