<?php
/**
 * @package WordPress
 * @subpackage Bueno
 */

// Set the content width based on the Theme CSS
$content_width = 490;

// Automatic feed links
add_theme_support( 'automatic-feed-links' );

// Menus
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'woothemes' ),
) );


// Thumbnails
add_theme_support( 'post-thumbnails', array( 'post' ) );
set_post_thumbnail_size( 490, 200, true );

// Add support for the aside post format
add_theme_support( 'post-formats', array( 'aside' ) );

// This theme allows users to set a custom background
add_custom_background();

// Allow custom colors to clear the background image
function bueno_custom_background_color() {
	if ( get_background_image() == '' && get_background_color() != '' ) { ?>
		<style type="text/css">
		body {
			background-image: none;
		}
		</style>
	<?php }
}
add_action( 'wp_head', 'bueno_custom_background_color' );

// Your changeable header business starts here
define( 'HEADER_TEXTCOLOR', '000000' );
// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
//define( 'HEADER_IMAGE', '%s/images/headers/path.jpg' );

// The height and width of your custom header.
define( 'HEADER_IMAGE_WIDTH', apply_filters( 'bueno_header_image_width',  930 ) );
define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'bueno_header_image_height',	198 ) );

/**
* Add custom header support
*/
if ( ! function_exists( 'bueno_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 */
function bueno_header_style() {
	// If no custom options for text are set, let's bail
	if ( HEADER_TEXTCOLOR == get_header_textcolor() )
		return;
	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php // Has the text been hidden?
		if ( 'blank' == get_header_textcolor() ) :
	?>
		.site-title {
 	 		position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		.site-title a {
			color: #<?php echo get_header_textcolor(); ?> !important;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif;

// Add a way for the custom header to be styled in the admin panel that controls custom headers
add_custom_image_header( 'bueno_header_style', 'bueno_admin_header_style' );

function bueno_admin_header_style() {
?>
<style type="text/css">
#headimg {
	border: 5px solid #efefef;
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	margin-top: 50px;
	width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
}
#headimg #desc {
	display: none !important;
}
#headimg h1 {
	font: 72px/58px Impact, Helvetica, Arial, sans-serif;
	text-transform: uppercase;
}
#headimg h1 a {
	display: block;
	margin-top: -110px;
	text-decoration: none;
}
<?php
	// If the user has set a custom color for the text use that
	if ( HEADER_TEXTCOLOR != get_header_textcolor() ) :
?>
	#site-title a {
		color: #<?php echo get_header_textcolor(); ?>;
	}
<?php endif; ?>
</style>
<?php
}
// ... and thus ends the changeable header business.

// Goodbye Search Widget, Bueno has search in the header and we don't need you.
function bueno_unregister_widgets() {
	unregister_widget('WP_Widget_Search');
}
add_action('widgets_init', 'bueno_unregister_widgets');

// Get the URL of the next image in the gallery
if ( ! function_exists( 'theme_get_next_attachment_url' ) ) :
function theme_get_next_attachment_url() {
	global $post;
	$post = get_post($post);
	$attachments = array_values(get_children( array('post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') ));

	foreach ( $attachments as $k => $attachment )
		if ( $attachment->ID == $post->ID )
			break;

		$k = $k + 1;

		if ( isset($attachments[$k]) ) {
			return get_attachment_link($attachments[$k]->ID);
		} else {
			return get_permalink($post->post_parent);
		}
}
endif;

// Load the theme options page
require_once ( get_template_directory() . '/includes/options-page.php' );

function theme_alt_styles() {
	$options = get_option('bueno_theme_options');

	if ( ! isset( $options['colorscheme'] ) ) {
		echo '<link href="'. get_bloginfo('template_directory') .'/styles/default.css" rel="stylesheet" type="text/css" />'."\n";
	} else {
		echo '<link href="'. get_bloginfo('template_directory') .'/styles/' . strtolower( $options['colorscheme'] ) . '.css" rel="stylesheet" type="text/css" />'."\n";
	}
}
add_action('wp_head', 'theme_alt_styles', 1);

// Filter wp_nav_menu() to add homelink and rsslink
function bueno_nav_menu_items($items) {
	$options = get_option('bueno_theme_options');

	if ( ! is_front_page()) { $highlight = "page_item"; } else {$highlight = "page_item current_page_item"; }

	$homelink = '<li class="b ' . $highlight . '"><a href="' . get_bloginfo('url') . '">' . __('Home', 'woothemes') . '</a></li>';
	$feedlink = '<li class="rss"><a href="' . get_bloginfo_rss('rss2_url') . '">' . __('RSS', 'woothemes') . '</a></li>';


	if ( $options['homelink'] == 1 ) {
		$items = $homelink . $items;
	}

	if ( $options['feedlink'] == 1 ) {
		$items = $items . $feedlink;
	}

	return $items;
}
add_filter( 'wp_nav_menu_items', 'bueno_nav_menu_items' );

// Fallback for the nav menu
function bueno_page_menu() {
	$options = get_option('bueno_theme_options');
	?>
   		<ul>
   			<?php if (is_page()) { $highlight = "page_item"; } else {$highlight = "page_item current_page_item"; } ?>

   			<?php if ( $options['homelink'] == 1 ) : ?>
            <li class="b <?php echo $highlight; ?>"><a href="<?php bloginfo('url'); ?>"><?php _e('Home', 'woothemes') ?></a></li>
            <?php endif; ?>

	    	<?php wp_list_pages('sort_column=menu_order&depth=3&title_li='); ?>

   			<?php if ( $options['feedlink'] == 1 ) : ?>
	    	<li class="rss"><a href="<?php echo get_bloginfo_rss('rss2_url'); ?>"><?php _e('RSS', 'woothemes') ?></a></li>
            <?php endif; ?>
    	</ul>
	<?php
} // end bueno_page_menu()

// Set path to WooFramework and theme specific functions
$functions_path = get_template_directory() . '/functions/';
$includes_path = get_template_directory() . '/includes/';

// Theme specific functionality
//require_once ($includes_path . 'theme-functions.php'); 		// Custom theme functions
require_once ($includes_path . 'theme-comments.php'); 		// Custom comments/pingback loop
require_once ($includes_path . 'theme-js.php');				// Load javascript in wp_head
require_once ($includes_path . 'sidebar-init.php');			// Initialize widgetized areas
require_once ($includes_path . 'theme-widgets.php');		// Theme widgets

/**
 * Set theme colors array based on the current color scheme.
 */
$theme_options = get_option( 'bueno_theme_options' );
$color_scheme = $theme_options['colorscheme'];

switch ( $color_scheme ) {
	case 'Blue':
		$themecolors = array(
			'bg' => 'ffffff',
			'border' => '9fcef1',
			'text' => '7a7a7a',
			'link' => '17517b',
			'url' => '7ebdec',
		);
		break;

	case 'Brown':
		$themecolors = array(
			'bg' => 'ffffff',
			'border' => 'd3975d',
			'text' => '7a7a7a',
			'link' => '472300',
			'url' => '824b15',
		);
		break;

	case 'Default':
		$themecolors = array(
			'bg' => 'ffffff',
			'border' => 'fbdddf',
			'text' => '7a7a7a',
			'link' => 'f3686d',
			'url' => 'fbdddf',
		);
		break;

	case 'Green':
		$themecolors = array(
			'bg' => 'ffffff',
			'border' => '9bd28e',
			'text' => '7a7a7a',
			'link' => '115900',
			'url' => '585858',
		);
		break;

	case 'Grey':
		$themecolors = array(
			'bg' => 'ffffff',
			'border' => 'b0b0b0',
			'text' => '7a7a7a',
			'link' => '333333',
			'url' => '585858',
		);
		break;

	case 'Purple':
		$themecolors = array(
			'bg' => 'ffffff',
			'border' => 'd7bced',
			'text' => '7a7a7a',
			'link' => '3b0466',
			'url' => '585858',
		);
		break;

	case 'Red':
		$themecolors = array(
			'bg' => 'ffffff',
			'border' => 'ffc1c1',
			'text' => '7a7a7a',
			'link' => 'c40000',
			'url' => 'bd2828',
		);
		break;
}