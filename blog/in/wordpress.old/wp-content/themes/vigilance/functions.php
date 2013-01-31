<?php
/**
 * @package Vigilance
 */

define( 'VIGILANCE_FUNC_PATH',  get_template_directory() . '/functions' );

// Required functions
require_once( VIGILANCE_FUNC_PATH . '/sidebars.php');
require_once( VIGILANCE_FUNC_PATH . '/comments.php');
require_once( VIGILANCE_FUNC_PATH . '/vigilance-extend.php');

//Add support for post thumbnails
if ( function_exists( 'add_theme_support' ) )
	add_theme_support( 'post-thumbnails' );

function vigilance_dark_color_scheme() {
	global $vigilance;

	if ( 'Dark' == $vigilance->baseColorScheme() )
		wp_enqueue_style( 'dark', get_template_directory_uri() . '/stylesheets/dark.css', array(), null );
}
add_action( 'wp_enqueue_scripts', 'vigilance_dark_color_scheme' );

function vigilance_custom_css() {
	global $vigilance;

	if ( 'Enabled' == $vigilance->backgroundCss() ) { ?>
		<style type="text/css" media="screen">
			body { background-color: <?php echo $vigilance->backgroundColor(); ?>; }
			#wrapper{
				padding: 0 20px 10px 20px;
				border-left: 4px solid <?php echo $vigilance->borderColor(); ?>;
				border-right: 4px solid <?php echo $vigilance->borderColor(); ?>;
			}
			.sticky .entry {
				background-color: #<?php echo $vigilance->borderColor(); ?>;
				padding: 10px;
			}
			.alert-box, .highlight-box { border: 1px solid <?php echo $vigilance->borderColor(); ?>; }
			#content a:link, #content a:visited { color: <?php echo $vigilance->linkColor(); ?>; }
			#sidebar a:link, #sidebar a:visited { color: <?php echo $vigilance->linkColor(); ?>; }
			#title a:hover { color: <?php echo $vigilance->linkColor(); ?>; }
			#nav ul li a:hover, #nav ul li:hover > a, #nav ul li.current_page_item > a, #nav ul li.current_page_parent > a, #nav ul li.current_page_ancestor > a, #nav ul li.current-cat > a, #nav ul li.current-menu-ancestor > a, #nav ul li.current-menu-item > a, #nav ul li.current-menu-parent a {
				color: <?php echo $vigilance->linkColor(); ?>;
				border-top: 4px solid <?php echo $vigilance->linkColor(); ?>;
			}
			.post-header h1 a:hover, .post-header h2 a:hover { color: <?php echo $vigilance->linkColor(); ?>; }
			.comments a:hover { color: <?php echo $vigilance->linkColor(); ?>; }
			.meta a:hover { color: <?php echo $vigilance->linkColor(); ?>; }
			.post-footer a:hover { color: <?php echo $vigilance->linkColor(); ?>; }
			#footer a:hover { color: <?php echo $vigilance->linkColor(); ?>; }
			#content .entry a:hover { color: <?php echo $vigilance->hoverColor(); ?>; }
			#wrapper #sidebar a:hover { color: <?php echo $vigilance->hoverColor(); ?>; }
			#content .post-header h1 a:hover, #content .post-header h2 a:hover { color: <?php echo $vigilance->linkColor(); ?>; }
			#content .comments a:hover { color: <?php echo $vigilance->linkColor(); ?>; }
			#content .meta a:hover { color: <?php echo $vigilance->linkColor(); ?>; }
			#content .reply a:hover { color: <?php echo $vigilance->linkColor(); ?>; }
			#content .reply a:link, #reply .c-permalink a:visited { color: #aaa; }
			#content .c-permalink a:link, #content .c-permalink a:visited { color: #ccc; }
			#content .post-footer a:link, #content .post-footer a:visited { color: #333; }
			#content .comments a { color: #757575;  }
			#footer a:hover { color: <?php echo $vigilance->linkColor(); ?>; }
			#footer a:link, #footer a:visited { color: #666; }
		</style>

		<?php if ( 'Dark' == $vigilance->baseColorScheme() ) { // if Base Color scheme is dark ?>

		<style type="text/css" media="screen">
			#wrapper{ background: #000; }
			#content .post-header h1 a:link, #content .post-header h1 a:visited, #content .post-header h2 a:link, #content .post-header h2 a:visited  { color: #fff; }
			#content .meta a:link, #content .meta a:visited { color: #fff; }
		</style>

		<?php } else { // if Base Color scheme is Light, the default -- or if option is empty ?>

		<style type="text/css" media="screen">
			#wrapper{ background: #fff; }
			#content .post-header h1 a:link, #content .post-header h1 a:visited, #content .post-header h2 a:link, #content .post-header h2 a:visited  { color: #444; }
			#content .meta a:link, #content .meta a:visited { color: #666; }
		</style>

		<?php }
	} ?>

	<?php if ($vigilance->imageHover() == 'true') { ?>
	<style type="text/css" media="screen">
		/*Hide hover colors on comment images and sidebar menu images
		------------------------------------------------------------ */
		.comments a:hover { background-position: 0 4px; }
		ul li.widget ul li a:hover { background-position: 0 6px; }
	</style>
	<?php
	}
} // END vigilance_custom_css
add_action( 'wp_head', 'vigilance_custom_css' );

// START WordPress.com
function vigilance_get_color_scheme() {
	global $vigilance;
	if ( is_object( $vigilance ) )
		return $vigilance->baseColorScheme();

	return false;
}

$color_scheme = vigilance_get_color_scheme();
switch ( $color_scheme ) {
	case 'Dark' :
		$themecolors = array(
			'bg' => '000000',
			'text' => 'bbbbbb',
			'link' => 'e4741f',
			'border' => '454545',
			'url' => 'e4741f',
		);
		break;
	default:
		$themecolors = array(
			'bg' => 'ffffff',
			'text' => '333333',
			'link' => '772124',
			'border' => 'eeeeee',
			'url' => '58181b',
		);
		break;
}

$content_width = 600;

// Feed links
add_theme_support( 'automatic-feed-links' );

// Enable primary menu
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'vigilance' ),
) );

function vigilance_page_menu() { // fallback for primary navigation ?>
	<ul class="menu">
		<li class="page_item <?php if (is_front_page()) echo('current_page_item'); ?>"><a href="<?php bloginfo('url'); ?>"><?php _e('Home', 'vigilance'); ?></a></li>
		<?php $exclude_pages = get_option('V_pages_to_exclude'); ?>
		<?php wp_list_pages('depth=1&title_li=&exclude=' . $exclude_pages); ?>
	</ul>
<?php }

// Headers R Us
define('HEADER_TEXTCOLOR', '444444');
define('HEADER_IMAGE', '');
define('HEADER_IMAGE_WIDTH', 920);
define('HEADER_IMAGE_HEIGHT', 180);

function vigilance_admin_header_style() {
?>
<style type="text/css">
	#headimg {
		height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
		width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
		overflow: hidden;
	}
	#headimg h1, #headimg #desc {
		font-size: 48px;
		font-weight: bold;
		line-height: 180px;
		text-align: center;
		font-family: Georgia, "Times New Roman", Times, Serif;
		margin: 0;
	}
	#headimg h1 a {
		text-decoration: none;
	}
</style>
<?php
}
add_custom_image_header('header_style', 'vigilance_admin_header_style');

function header_style() { ?>
	<style type="text/css">
	<?php if ( '' != get_header_image() ) { ?>
		#title {
			background: url(<?php header_image(); ?>) no-repeat;
			height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
		}
	<?php } ?>
	<?php if ( 'blank' != get_header_textcolor() ) { ?>
		#title a { color: #<?php header_textcolor(); ?> }
	<?php } else { ?>
		#title { text-indent: -999em !important; }
		#title a { height: <?php echo HEADER_IMAGE_HEIGHT; ?>px; }
	<?php } ?>
	</style>
<?php }
// End WordPress.com

if ( ! function_exists( 'vigilance_posted_by' ) ) :
/**
 * Prints HTML with meta information for the current author on multi-author blogs
 */
function vigilance_posted_by() {
	if ( is_multi_author() && ! is_author() ) {
		printf( __( '<span class="by-author"><span class="sep">by</span> <span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span> </span>', 'vigilance' ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'vigilance' ), get_the_author_meta( 'display_name' ) ) ),
			esc_attr( get_the_author_meta( 'display_name' ) )
		);
	}
}
endif;