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
define( 'HEADER_TEXTCOLOR', '' );
// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
//define( 'HEADER_IMAGE', '%s/images/headers/path.jpg' );

// The height and width of your custom header. 
define( 'HEADER_IMAGE_WIDTH', apply_filters( 'bueno_header_image_width',  930 ) );
define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'bueno_header_image_height',	198 ) );

// Don't support text inside the header image.
define( 'NO_HEADER_TEXT', true );

// Add a way for the custom header to be styled in the admin panel that controls custom headers
add_custom_image_header( '', 'bueno_admin_header_style' );

function bueno_admin_header_style() {
?>
<style type="text/css">
#headimg {
	border: 5px solid #efefef;
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
}
#headimg h1, #headimg #desc {
	display: none;
}
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