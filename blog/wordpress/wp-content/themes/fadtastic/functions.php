<?php

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '000000',
	'link' => '80ae14'
);

$content_width = 544;

function widget_fadtastic_links() {
	wp_list_bookmarks(array('title_before'=>'<h3>', 'title_after'=>'</h3>', 'show_images'=>true));
}

function fadtastic_widget_init() {
	register_sidebar(array('before_title' => "<h3 class='widgettitle'>", 'after_title' => "</h3>", 'name' => 'Sidebar 1', 'id' => 'main-sidebar'));
	register_sidebar(array('before_title' => "<h3 class='widgettitle'>", 'after_title' => "</h3>", 'name' => 'Sidebar 2', 'id' => 'bottom-bar'));
	unregister_widget('WP_Widget_Links');
	wp_register_sidebar_widget('links', __('Links', 'sandbox'), 'widget_fadtastic_links');
}
add_action('widgets_init', 'fadtastic_widget_init');

add_theme_support( 'automatic-feed-links' );

// Custom background
add_custom_background();

function fadtastic_custom_background() {
	if ( '' != get_background_color() || '' != get_background_image() ) { ?>
		<style type="text/css">
			#wrapper { border: none; }
		<?php if ( '' != get_background_color() && '' == get_background_image() ) { ?>
			body { background-image: none; }
		<?php } ?>
		</style>
	<?php }
}
add_action( 'wp_head', 'fadtastic_custom_background' );

// Navigation menu
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'fadtastic' )
) );

// Fallback for primary navigation
function fadtastic_page_menu() { ?>
	<ul id="navlist" class="menu">
		<?php wp_list_pages( 'title_li=&depth=1' ); ?>
	</ul>
<?php }