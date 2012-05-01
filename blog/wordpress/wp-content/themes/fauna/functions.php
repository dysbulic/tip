<?php

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '000000',
	'link' => '1177aa'
);

$content_width = 500;

register_sidebar( array(
	'name'          => __('Sidebar'),
	'id'            => 'sidebar',
	'before_widget' => '',
	'after_widget'  => '',
	'before_title'  => '<h4>',
	'after_title'   => '</h4>' )
);

add_theme_support( 'automatic-feed-links' );

// Custom background
add_custom_background();

function fauna_custom_background() {
	if ( '' != get_background_color() && '' == get_background_image() ) { ?>
	<style type="text/css">
		body { background-image: none; background-color: #<?php echo get_background_color(); ?>; }
	</style>
	<?php }
}
add_action( 'wp_head', 'fauna_custom_background' );

// Navigation menu
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'fauna' )
) );

// Fallback for primary navigation
function fauna_page_menu() { ?>
	<ul class="menu">
		<li id="current-index"><a href="<?php echo home_url( '/' ); ?>" title="<?php bloginfo( 'name' ); ?>" accesskey="1"><?php _e( 'Blog' ) ?></a></li>
		<?php wp_list_pages( 'sort_column=menu_order&depth=1&title_li=' ); ?>
	</ul>
<?php }