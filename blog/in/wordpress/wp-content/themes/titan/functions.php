<?php
/**
 * @package Titan
 */

define( 'TITAN_FUNC_PATH',  get_template_directory() . '/functions' );

$themecolors = array(
	'bg' => 'f9f7f5',
	'border' => 'dfdad5',
	'text' => '444444',
	'link' => '4265a7',
	'url' => 'beaa99',
);
$content_width = 497;

// Add support for post thumbnails (added in 2.9)
if ( function_exists( 'add_theme_support' ) )
	add_theme_support( 'post-thumbnails' );

// Required functions
require_once( TITAN_FUNC_PATH . '/comments.php' );
require_once( TITAN_FUNC_PATH . '/titan-extend.php' );

// Enable widgets
if ( function_exists( 'register_sidebar_widget' ) ) {
	register_sidebar(
		array(
			'name' => __( 'Sidebar' ),
			'id' => 'titan_sidebar'
		)
	);
	register_sidebar(
		array(
			'name' => __( 'Footer Left' ),
			'id' => 'footer_left'
		)
	);
	register_sidebar(
		array(
			'name' => __( 'Footer Center' ),
			'id' => 'footer_center'
		)
	);
	register_sidebar(
		array(
			'name' => __( 'Footer Right' ),
			'id' => 'footer_right'
		)
	);
}

add_theme_support( 'automatic-feed-links' );

register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'titan' ),
) );

function titan_page_menu() {
	global $titan;
// fallback for primary navigation ?>
<ul id="nav" class="wrapper">
	<?php if ( $titan->hideHome() !== 'true' ) : ?>
	<li class="page_item <?php if (is_front_page()) echo ( 'current_page_item' ); ?>"><a href="<?php bloginfo( 'url' ); ?>"><?php _e( 'Home', 'titan' ); ?></a></li>
	<?php endif; ?>
    <?php if ( $titan->hidePages() !== 'true' ) : ?>
    	<?php wp_list_pages( 'title_li=&exclude='. $titan->excludedPages() ); ?>
    <?php endif; ?>
    <?php if ( $titan->hideCategories() != 'true' ) : ?>
    	<?php wp_list_categories( 'title_li=&exclude=' . $titan->excludedCategories() ); ?>
    <?php endif; ?>
</ul>
<?php }

/**
 * Let's start the changeable header business here
 */

define( 'NO_HEADER_TEXT', true );

// The default header text color
define( 'HEADER_TEXTCOLOR', '' );

// By leaving empty, we allow for random image rotation.
define( 'HEADER_IMAGE', '' );

// The height and width of our custom header.
define( 'HEADER_IMAGE_WIDTH', 960 );
define( 'HEADER_IMAGE_HEIGHT', 180 );

// Turn on random header image rotation by default.
add_theme_support( 'custom-header', array( 'random-default' => true ) );

// Add a way for the custom header to be styled in the admin panel that controls custom headers.
add_custom_image_header( 'titan_header_style', 'titan_admin_header_style' );

// Custom styles for our blog header
function titan_header_style() {
	// If no custom options for text are set, let's bail
	$header_image = get_header_image();
	if ( empty( $header_image ) )
		return;
	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	#title {
		padding-bottom: 15px;
	}
	#header-image {
		clear: both;
		display: block;
		margin: 0 0 44px;
	}
	</style>
	<?php
} // titan_header_style()

// Custom styles for the custom header page in the admin
function titan_admin_header_style() {
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		border: none;
	}
	</style>
<?php
} // titan_admin_header_style

if ( ! function_exists( 'titan_posted_by' ) ) :
/**
 * Prints HTML with meta information for the current author on multi-author blogs
 */
function titan_posted_by() {
	if ( is_multi_author() && ! is_author() ) {
		printf( __( '<span class="by-author"><span class="sep">by</span> <span class="titan-author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span> </span>', 'titan' ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'titan' ), get_the_author_meta( 'display_name' ) ) ),
			esc_attr( get_the_author_meta( 'display_name' ) )
		);
	}
}
endif;