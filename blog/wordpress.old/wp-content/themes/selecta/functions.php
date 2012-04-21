<?php
/**
 * @package WordPress
 * @subpackage Selecta
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 560;

// WP.com: Check the current color scheme and set the correct themecolors array
if ( ! isset( $themecolors ) ) {
	$options = get_option( 'selecta_theme_options' );
	$color_scheme = $options['color_scheme'];
	switch ( $color_scheme ) {
		case 'black-blue':
			$themecolors = array(
				'bg' => '333333',
				'border' => '444444',
				'text' => '111111',
				'link' => '00adef',
				'url' => '00adef',
			);
			break;
		case 'black-green':
			$themecolors = array(
				'bg' => '333333',
				'border' => '444444',
				'text' => '111111',
				'link' => '3b96df',
				'url' => '3b96df',
			);
			break;
		case 'black-red':
			$themecolors = array(
				'bg' => '333333',
				'border' => '444444',
				'text' => '111111',
				'link' => 'ff3333',
				'url' => 'ff3333',
			);
			break;
		case 'sea-blue':
			$themecolors = array(
				'bg' => 'bbd5db',
				'border' => 'f0fef5',
				'text' => '111111',
				'link' => '8b9898',
				'url' => '8b9898',
			);
			break;
		case 'white-rose':
			$themecolors = array(
				'bg' => 'ffffff',
				'border' => 'ff0000',
				'text' => '111111',
				'link' => 'ff3333',
				'url' => 'ff3333',
			);
			break;
		default:
			$themecolors = array(
				'bg' => '7dbaea',
				'border' => 'd8eaf9;',
				'text' => '111111',
				'link' => '3c96df',
				'url' => '3c96df',
			);
			break;
	}
}

/**
 * Tell WordPress to run selecta_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'selecta_setup' );

function selecta_setup() {

	// This theme has an options page that lets users pick layout, color scheme, featured post title text and configure a twitter icon
	//require_once( dirname( __FILE__ ) . '/inc/theme-options.php' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// This theme supports post formats.
	add_theme_support( 'post-formats', array( 'aside', 'audio', 'image', 'quote', 'gallery', 'video', 'chat' ) );

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'selecta', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'selecta' ),
	) );

	// This theme allows users to set a custom background.
	add_custom_background();

	// This theme allows users to upload a custom header.
	define( 'HEADER_IMAGE', '' );
	define( 'HEADER_IMAGE_WIDTH', 940 ); // use width and height appropriate for your theme
	define( 'HEADER_IMAGE_HEIGHT', 150 );

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See selecta_admin_header_style(), below.
	add_custom_image_header( '', 'selecta_admin_header_style' );

	// This theme uses post thumbnails.
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'normal', 190, 110, true ); // normal thumbnail size

	// Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
	function selecta_page_menu_args($args) {
		$args['show_home'] = true;
	return $args;
	}
	add_filter( 'wp_page_menu_args', 'selecta_page_menu_args' );
}

// Load up the theme options
require( dirname( __FILE__ ) . '/inc/theme-options.php' );

// Get selecta options
function selecta_get_options() {
	$defaults = array(
		'color_scheme' => 'blue',
		'theme_slider' => 'no',
		'theme_latest_posts' => 'no'
	);
	$options = get_option( 'selecta_theme_options', $defaults );
	return $options;
}

// Register our color schemes and add them to the style queue
function selecta_color_registrar() {
	$options = selecta_get_options();
	$color_scheme = $options['color_scheme'];

	if ( ! empty( $color_scheme ) && $color_scheme != 'blue' ) {
		wp_register_style( $color_scheme, get_template_directory_uri() . '/colors/' . $color_scheme . '.css', null, null );
		wp_enqueue_style( $color_scheme );
	}
}
add_action( 'wp_enqueue_scripts', 'selecta_color_registrar' );

// Load scripts.
function selecta_scripts() {
	if ( ! is_singular() || ( is_singular() && 'audio' == get_post_format() ) ) {
		wp_enqueue_script( 'audio-player', get_template_directory_uri() . '/js/audio-player.js', array( 'jquery' ), '20110829' );
	}
	if ( is_front_page() ) {
		wp_enqueue_script( 'functions-js', get_template_directory_uri().'/js/functions.js', array( 'jquery'), '20110829' );
	}
}
add_action( 'wp_enqueue_scripts', 'selecta_scripts' );

/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 */
 define( 'HEADER_TEXTCOLOR', '' );
define( 'NO_HEADER_TEXT', true );

function selecta_admin_header_style() {
?>
	<style type="text/css">
        #headimg {
            width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
            height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
        }
        #heading {
        	display: none;
        }
    </style>
<?php
}

/**
 * Register widgetized area and update sidebar with default widgets
 */
function selecta_widgets_init() {
	register_sidebar( array (
		'name' => __( 'Default Sidebar', 'selecta' ),
		'id' => 'sidebar-1',
		'description' => __( 'The primary widget area.', 'selecta' ),
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>'
	) );

	register_sidebar( array (
		'name' => __( 'First Footer Widget Area', 'selecta' ),
		'id' => 'sidebar-2',
		'description' => __( 'The first footer widget area.', 'selecta' ),
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>'
	) );

	register_sidebar( array (
		'name' => __( 'Second Footer Widget Area', 'selecta' ),
		'id' => 'sidebar-3',
		'description' => __( 'The second footer widget area.', 'selecta' ),
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	) );

	register_sidebar( array (
		'name' => __( 'Third Footer Widget Area', 'selecta' ),
		'id' => 'sidebar-4',
		'description' => __( 'The third footer widget area.', 'selecta' ),
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	) );
}
add_action( 'init', 'selecta_widgets_init' );

/**
 * Removes the default styles that are packaged with the Recent Comments widget.
 */
function selecta_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'selecta_remove_recent_comments_style' );

/**
 * Prints HTML with meta information for the current post (date, category, tags and permalink).
 */
function selecta_post_meta() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'Posted<span class="byline"> by <a href="%1$s" title="%2$s" rel="author">%3$s</a></span> on <a href="%4$s" title="%5$s" rel="bookmark">%5$s</a> in %6$s and tagged %7$s.', 'selecta' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'Posted<span class="byline"> by <a href="%1$s" title="%2$s" rel="author">%3$s</a></span> on <a href="%4$s" title="%5$s" rel="bookmark">%5$s</a> in %6$s.', 'selecta' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'selecta' ), get_the_author() ) ),
		esc_html( get_the_author() ),
		esc_url( get_permalink() ),
		esc_attr( get_the_date() ),
		get_the_category_list( ', ' ),
		$tag_list
	);
}

/**
 * Prints the post date.
 */
function selecta_entry_date() {
	printf( __( '<span class="entry-date"><a href="%1$s" class="entry-date-link" title="%2$s" rel="bookmark">%3$s</a></span>', 'selecta' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date() )
	);
}

/**
 * Display navigation to next/previous pages when applicable
 */
function selecta_content_nav( $nav_id ) {
	global $wp_query;

	if (  $wp_query->max_num_pages > 1 ) : ?>
	<nav id="<?php echo $nav_id; ?>" class="clearfix">
		<h3 class="assistive-text"><?php _e( 'Post navigation', 'selecta' ); ?></h3>
		<span class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older <span>posts</span>', 'selecta' ) ); ?></span>
		<span class="nav-next"><?php previous_posts_link( __( 'Newer <span>posts</span> <span class="meta-nav">&rarr;</span>', 'selecta' ) ); ?></span>
	</nav><!-- #<?php echo $nav_id; ?> -->
	<?php endif;
}

/**
 * Limit Excerpt Length for Latest Posts and Archive Posts
 */
 function selecta_short_excerpt( $charlength ) {
   $excerpt = get_the_excerpt();
   $charlength++;
   if( strlen( $excerpt ) > $charlength ) {
       $subex = substr( $excerpt, 0, $charlength-5 );
       $exwords = explode( " ", $subex );
       $excut = -( strlen( $exwords[ count( $exwords ) -1 ] ) );
       if( $excut < 0 ) {
            echo substr( $subex, 0, $excut );
       } else {
       	    echo $subex;
       }
       echo "[...]";
   } else {
	   echo $excerpt;
   }
}

// Add in-head JS block for audio post format.
function selecta_add_audio_support() {
	if ( ! is_singular() || ( is_singular() && 'audio' == get_post_format() ) ) {
?>
		<script type="text/javascript">
			AudioPlayer.setup( "<?php echo get_template_directory_uri(); ?>/swf/player.swf", {
				bg: "111111",
				leftbg: "111111",
				rightbg: "111111",
				track: "222222",
				text: "ffffff",
				lefticon: "eeeeee",
				righticon: "eeeeee",
				border: "222222",
				tracker: "eb374b",
				loader: "666666"
			});
		</script>
<?php }
}
add_action( 'wp_head', 'selecta_add_audio_support' );

function selecta_header_css() {
	// Hide the theme-provided background colors if the user adds a custom background image or color
	if ( '' != get_background_image() || '' != get_background_color() || '' != get_header_image() ) : ?>
	<style type="text/css">
	<?php if ( '' != get_background_image() || '' != get_background_color() ) : ?>
		#featured-posts-container,
		#single-header,
		#footer {
			background: none;
		}
	<?php endif; ?>
	</style>
	<?php endif;
}
add_action( 'wp_head', 'selecta_header_css' );