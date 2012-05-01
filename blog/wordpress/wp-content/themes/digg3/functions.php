<?php
$themecolors = array(
	'bg' => 'ffffff',
	'border' => '666666',
	'text' => '333333',
	'link' => '105CB6'
);

$content_width = 468; // pixels

add_theme_support( 'automatic-feed-links' );

// Custom background
add_custom_background();

function digg3_custom_background() {
	if ( '' != get_background_color() && '' == get_background_image() ) { ?>
	<style type="text/css">
		body { background-image: none; }
	</style>
	<?php }
}
add_action( 'wp_head', 'digg3_custom_background' );

if ( function_exists('register_sidebar') )
    register_sidebars(2);

function digg3_admin_image_header() {
?>
<style>

#headimg {
	margin: 0 0 10px;
	width: 904px;
	height: 160px;
	color: #333;
}

#headimg h1{
	padding: 32px 28px 0;
	font-size: 24px;
	font-weight: bold;
	letter-spacing: 1px;
	text-transform: uppercase;
	color: #<?php header_textcolor() ?>;
}

#headimg h1 a{
	text-decoration: none;
	color: #<?php header_textcolor() ?>;
	border: none;
}
#desc {
	display: none;
}


</style>
<?php
}

function digg3_header_style() {
?>
<style type="text/css">
<?php if ( 'blank' == get_header_textcolor() ) { ?>
#header h1 a, #header .description {
display: none;
}
<?php } else { ?>
#header h1 a, #header h1 a:hover, #header .description {
color: #<?php header_textcolor() ?>;
}
<?php } ?>
</style>
<?php
}


add_custom_image_header('digg3_header_style', 'digg3_admin_image_header');

define('HEADER_TEXTCOLOR', 'ffffff');
define('HEADER_IMAGE', '%s/images/bg_header_img.png'); // %s is theme dir uri
define('HEADER_IMAGE_WIDTH', 904);
define('HEADER_IMAGE_HEIGHT', 160);

// Header navigation menu
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'digg3' ),
) );

function digg3_page_menu() { // fallback for primary navigation ?>
<ul class="menu">
	<li class="page_item<?php if ( is_home() ) { echo ' current_page_item'; } ?>"><a href="<?php bloginfo( 'url' ); ?>/"><?php _e( 'Home', 'digg3' ); ?></a></li>
	<?php wp_list_pages( 'depth=1&title_li=' ); ?>
</ul>
<?php } ?>