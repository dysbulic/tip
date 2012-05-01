<?php
/**
 * @package WordPress
 * @subpackage Fruit Shake
 */

/**
 * Make theme available for translation
 * Translations can be filed in the /languages/ directory
 * If you're building a theme based on toolbox, use a find and replace
 * to change 'fruit-shake' to the name of your theme in all the template files
 */
load_theme_textdomain( 'fruit-shake', TEMPLATEPATH . '/languages' );

$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable( $locale_file ) )
	require_once( $locale_file );

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

/**
 * This theme uses wp_nav_menu() in one location.
 */
register_nav_menus( array(
	'primary' => __( 'Primary Menu', 'fruit-shake' ),
) );

/**
 * Add default posts and comments RSS feed links to head
 */
add_theme_support( 'automatic-feed-links' );

/**
 * Add support for the Aside and Gallery Post Formats
 */
add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' ) );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function fruit_shake_page_menu_args($args) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'fruit_shake_page_menu_args' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function fruit_shake_widgets_init() {
	register_sidebar( array (
		'name' => __( 'Sidebar 1', 'fruit-shake' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
}
add_action( 'init', 'fruit_shake_widgets_init' );

/**
 * Grab the first URL from a Link post
 */
function fruit_shake_url_grabber() {
	global $post, $posts;

	$first_url = '';

	ob_start();
	ob_end_clean();

	$output = preg_match_all('/<a.+href=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);

	$first_url = $matches [1] [0];

	if ( empty( $first_url ) )
		return false;

	return $first_url;
}

/**
 *
 */
function fruit_shake_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script(
		'audio-player',
		get_template_directory_uri() . '/js/audio-player.js',
		array( 'jquery'),
		'0.8.01'
	);
}
add_action( 'init', 'fruit_shake_scripts' );

/**
 * Grab the URL the first audio attachment in a post
 */
function fruit_shake_get_audio_file() {
	global $wpdb, $post;

	$audio = '';

	$query = "SELECT guid FROM $wpdb->posts WHERE post_parent = '$post->ID' "."AND post_type = 'attachment' AND post_mime_type = 'audio/mpeg' ORDER BY menu_order ASC LIMIT 0,1";

	$first_audio = $wpdb->get_results( $query );

	if ( $first_audio )
		$audio = $first_audio[0]->guid;

	return $audio;
}

/**
 * Add scripts for setting up the Audio Player
 */
function fruit_shake_audio_player_setup () {
	echo "\n" . '<script type="text/javascript">
	//<![CDATA[
	AudioPlayer.setup( "' . get_template_directory_uri() . '/swf/player.swf", {
		bg: "222222",
		leftbg: "444444",
		rightbg: "444444",
		track: "222222",
		text: "ffffff",
		lefticon: "eeeeee",
		righticon: "eeeeee",
		border: "222222",
		tracker: "3d87cb",
		loader: "666666"
	});
	//]]>
	</script>' . "\n";
}
add_action( 'wp_head', 'fruit_shake_audio_player_setup', 10 );

/**
 * This theme was built with PHP, Semantic HTML, CSS, love, and a Toolbox.
 */