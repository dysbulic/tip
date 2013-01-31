<?php
/**
 * @package WordPress
 * @subpackage Structure
 */
 
$content_width = 640; 


// This theme uses wp_nav_menu()
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'structuretheme' ),
) );


// This theme enables the choice of a custom background
add_custom_background();

// Add ID and CLASS attributes to the first <ul> occurence in wp_page_menu
// so we can get it to match up to wp_nav_menu() when we fall back to it
function add_menuclass($ulclass) {
	return preg_replace('/<ul>/', '<ul class="ot-menu">', $ulclass, 1);
}
add_filter('wp_page_menu','add_menuclass');

// Get our fallback, wp_page_menu(), to look even more like wp_nav_menu()
function new_menu_args($args) {
	$args = array(
		'sort_column' => 'menu_order, post_title',
		'menu_class' => 'navbar',
		'echo' => true,
		'link_before' => '',
		'link_after' => '',
		'show_home' => true,		
	);	
	
	return $args;
}
add_filter('wp_page_menu_args', 'new_menu_args');

// Filter the excerpt […]
function structure_excerpt_more( $more ) {
	return '&nbsp;&hellip; <a href="'. get_permalink() . '">' . __('Read&nbsp;more', 'structuretheme') . '</a>';
}
add_filter( 'excerpt_more', 'structure_excerpt_more' );

// Grab the custom header bits
require_once ('includes/wpcom-header.php');

// Make theme available for translation
// Translations can be filed in the /languages/ directory
load_theme_textdomain( 'structuretheme', TEMPLATEPATH . '/languages' );

$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable( $locale_file ) )
	require_once( $locale_file );


// Add theme scripts
function structure_scripts() {
	wp_enqueue_script("jquery");

	wp_register_script('superfish', get_bloginfo('template_url') . '/js/superfish/superfish.js');
	wp_register_script('hoverintent', get_bloginfo('template_url') . '/js/superfish/hoverIntent.js');
	wp_register_script('iepngfix', get_bloginfo('template_url') . '/js/iepngfix_tilebg.js');
	
	wp_enqueue_script('superfish');	
	wp_enqueue_script('hoverintent');	
	wp_enqueue_script('iepngfix');		
}
add_action( 'init', 'structure_scripts' );

// enable threaded comments
function enable_threaded_comments(){
	if (!is_admin()) {
		if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1))
			wp_enqueue_script('comment-reply');
		}
}
add_action('get_header', 'enable_threaded_comments');

// Add default posts and comments RSS feed links to head
add_theme_support( 'automatic-feed-links' );

// The post thumbnail business
add_theme_support( 'post-thumbnails', array( 'post' ) );
add_image_size( 'structure-small', 440, 240, true ); // Used on the home page for featured posts
add_image_size( 'structure-medium', 620, 380, true ); // Used on the home page for latest feature post
add_image_size( 'structure-large', 640, 392, true ); // Used on single pages and posts

//Turn a category ID to a Name
function cat_id_to_name($id) {
	foreach((array)(get_categories()) as $category) {
    	if ($id == $category->cat_ID) { return $category->cat_name; break; }
	}
}

//	Pull theme options from database
function st_option($key) {
	global $settings;
	$option = get_option($settings);
	if(isset($option[$key])) return $option[$key];
	else return FALSE;
}

// include the theme options
include(TEMPLATEPATH."/includes/theme-options.php");

if ( function_exists('register_sidebars') )
	register_sidebar(array('name'=>'Right Sidebar','before_title'=>'<h4>','after_title'=>'</h4>'));
	register_sidebar(array('name'=>'Left Sidebar','before_title'=>'<h4>','after_title'=>'</h4>'));
	register_sidebar(array('name'=>'Homepage Top Right','before_title'=>'<h4>','after_title'=>'</h4>'));
	register_sidebar(array('name'=>'Footer Left','before_title'=>'<h4>','after_title'=>'</h4>'));
	register_sidebar(array('name'=>'Footer Mid Left','before_title'=>'<h4>','after_title'=>'</h4>'));
	register_sidebar(array('name'=>'Footer Mid Right','before_title'=>'<h4>','after_title'=>'</h4>'));
	register_sidebar(array('name'=>'Footer Right','before_title'=>'<h4>','after_title'=>'</h4>'));

//	Use a div ID, not CLASS, for wp_page_menu
add_filter('wp_page_menu', 'menu_class_to_div');
function menu_class_to_div($menu) {
	$menu = str_replace('<div class', '<div id', $menu);
	
	return $menu;
}

add_filter('wp_list_pages', 'structuretheme_list_pages');
function structuretheme_list_pages($output) {
	$include_pages = st_option('include_pages');
	if(in_array('feed', (array)$include_pages))
		$output .= '<li class="feed"><a href="'.get_bloginfo('rss2_url').'">RSS Feed</a></li>';
	
	return $output;
}

// Get the URL of the next image in the gallery
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

/**
 * WP.com: Check the current color scheme and set the correct themecolors array
 */
if ( ! isset( $themecolors ) ) {
	if ( st_option( 'dark_scheme' ) ) {
		$themecolors = array(
			'bg' => '000000',
			'border' => '222222',
			'text' => '999999',
			'link' => 'ffffff',
			'url' => 'ffffff',
		);
	} else {
		$themecolors = array(
			'bg' => 'ffffff',
			'border' => '222222',
			'text' => '666666',
			'link' => '000000',
			'url' => '999999',
		);	
	}
}
?>