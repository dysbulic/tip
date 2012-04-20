<?php

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '000000',
	'link' => '003366'
	);

$content_width = 565;

// Make theme available for translation
// Translations can be filed in the /languages/ directory
load_theme_textdomain( 'iceburgg', get_template_directory() . '/languages' );

$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable( $locale_file ) )
	require_once( $locale_file );


add_theme_support( 'automatic-feed-links' );

if ( function_exists('register_sidebars') )
	register_sidebars(3);

// No CSS, just IMG call

define('HEADER_TEXTCOLOR', '');
define('HEADER_IMAGE', '%s/imgs/freehead.gif'); // %s is theme dir uri
define('HEADER_IMAGE_WIDTH', 790);
define('HEADER_IMAGE_HEIGHT', 150);
define( 'NO_HEADER_TEXT', true );

function iceburgg_admin_header_style() {
?>
<style type="text/css">
#headimg {
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
}

#headimg h1, #headimg #desc {
	display: none;
}

</style>
<?php
}

function header_style() {
?>
<style type="text/css">
#header{
	background: url(<?php header_image() ?>) no-repeat;
}
</style>
<?php
}

add_custom_image_header('header_style', 'iceburgg_admin_header_style');

function iceburgg_custom_background() {
	if ( '' != get_background_color() && '' == get_background_image() ) { ?>
	<style type="text/css">
		body { background: #<?php echo get_background_color(); ?>; }
	</style>
	<?php }
}
add_action( 'wp_head', 'iceburgg_custom_background' );

// Navigation menu
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'iceburgg' )
) );

// Fallback for primary navigation
function iceburgg_page_menu() { ?>
	<ul class="menu">
		<li><a href="<?php echo home_url( '/' ); ?>">Home</a></li>
		<?php wp_list_pages( 'depth=1&title_li=' ); ?>
	</ul>
<?php }