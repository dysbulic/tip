<?php
/**
 * @package WordPress
 * @subpackage ChaosTheory
 */

$themecolors = array(
	'bg' => '1B1B1B',
	'border' => '0A0A0A',
	'text' => 'DDDDDD',
	'link' => '6DCFF6'
);

$content_width = 510;

add_theme_support( 'automatic-feed-links' );

add_custom_background();

register_nav_menus( array(
	'primary' => __( 'Primary Navigation' ),
) );

// Make theme available for translation
// Translations can be filed in the /languages/ directory
load_theme_textdomain( 'chaostheory', TEMPLATEPATH . '/languages' );

$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable( $locale_file ) )
	require_once( $locale_file );

// Widgets
require_once( get_template_directory() . '/inc/widgets.php' );

function chaostheory_widgets_init() {
	register_sidebars( 2, array(
		'before_title' => '<h3 class="widgettitle">',
		'after_title' => '</h3>',
	) );

	unregister_widget( 'WP_Widget_Search' );
	unregister_widget( 'WP_Widget_Links' );
	unregister_widget( 'WP_Widget_Meta' );

	wp_register_sidebar_widget( 'search', __( 'Search', 'chaostheory' ), 'widget_chaostheory_search' );
	wp_register_sidebar_widget( 'meta', __( 'Meta', 'chaostheory' ), 'widget_chaostheory_meta' );
	wp_register_sidebar_widget( 'links', __( 'Links', 'chaostheory' ), 'widget_chaostheory_links' );
	wp_register_sidebar_widget( 'home-link', __( 'Home Link', 'chaostheory' ), 'widget_sandbox_homelink' );
	wp_register_sidebar_widget( 'rss-links', __( 'RSS Links', 'chaostheory' ), 'widget_sandbox_rsslinks' );
}
add_action( 'widgets_init', 'chaostheory_widgets_init' );

// Nav fallback
function chaostheory_globalnav() {
?>
<div id="globalnav">
	<ul id="menu">
		<?php wp_list_pages( 'title_li=&sort_column=menu_order&depth=1' ); ?>
	</ul>
</div>
<?php }