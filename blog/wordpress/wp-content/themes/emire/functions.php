<?php

$themecolors = array(
	'bg' => '3e3e3e',
	'text' => '000000',
	'link' => 'bbbbbb',
	'border' => '3e3e3e'
);

$content_width = 418;

if ( function_exists('register_sidebars') )
	register_sidebars(1);

add_theme_support( 'automatic-feed-links' );

// Custom background
add_custom_background();

function emire_custom_background() {
	if ( '' != get_background_color() || '' != get_background_image() ) { ?>
		<style type="text/css">
			#header { background: none; }
		<?php if ( '' != get_background_color() && '' == get_background_image() ) { ?>
			body { background-image: none; }
		<?php } ?>
		</style>
	<?php }
}
add_action( 'wp_head', 'emire_custom_background' );

// Navigation menu
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'emire' )
) );

// Fallback for primary navigation
function emire_page_menu() { ?>
	<ul>
		<li><a href="<?php echo home_url( '/' ); ?>"><?php _e( 'Home', 'emire' ); ?></a></li>
		<?php wp_list_pages( 'title_li=&depth=1' ); ?>
	</ul>
<?php }