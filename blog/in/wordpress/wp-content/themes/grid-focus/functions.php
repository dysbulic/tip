<?php
/**
 *	@package WordPress
 *	@subpackage Grid Focus
 */
$content_width = 406;

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '333333',
	'link' => '3c6c92',
	'border' => 'eeeeee',
	'url' => '3c6c92',
);

add_filter( 'body_class', '__return_empty_array', 1 );

// Make theme available for translation
// Translations can be filed in the /languages/ directory
load_theme_textdomain( 'grid-focus', get_template_directory() . '/languages' );

$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable( $locale_file ) )
	require_once( $locale_file );


if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => 'Primary - Index',
		'before_widget' => '<div id="%1$s" class="widgetContainer %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widgetTitle">',
		'after_title' => '</h3>'
	));
	register_sidebar(array(
		'name' => 'Primary - Post',
		'before_widget' => '<div id="%1$s" class="widgetContainer %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widgetTitle">',
		'after_title' => '</h3>'
	));
	register_sidebar(array(
		'name' => 'Secondary - Shared',
		'before_widget' => '<div id="%1$s" class="widgetContainer %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widgetTitle">',
		'after_title' => '</h3>'
	));
}

add_theme_support( 'automatic-feed-links' );

add_custom_background();

/**
 * Let's start the changeable header business here
 */

// The default header text color
define( 'HEADER_TEXTCOLOR', '000' );

// By leaving empty, we allow for random image rotation.
define( 'HEADER_IMAGE', '' );

// The height and width of our custom header.
define( 'HEADER_IMAGE_WIDTH', 970 );
define( 'HEADER_IMAGE_HEIGHT', 175 );

// Turn on random header image rotation by default.
add_theme_support( 'custom-header', array( 'random-default' => true ) );

// Add a way for the custom header to be styled in the admin panel that controls custom headers.
add_custom_image_header( 'grid_focus_header_style', 'grid_focus_admin_header_style', 'grid_focus_admin_header_image' );

// Custom styles for our blog header
function grid_focus_header_style() {
	// If no custom options for text are set, let's bail
	$header_image = get_header_image();
	if ( empty( $header_image ) )
		return;
	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	#header-image {
		clear: both;
		display: block;
	}
	<?php
		// Has the text been hidden? Let's hide it then.
		if ( 'blank' == get_header_textcolor() ) :
	?>
		#masthead h1,
		#authorIntro {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
		/* Move the blog avatar into a different position */
		#masthead {
			padding: 2px 0;
			position: relative;
		}
		#blogLead img { 
			position: absolute;
				top: 10px;
				right: 0;
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		#masthead h1 a,
		#authorIntro {
			color: #<?php echo get_header_textcolor(); ?> !important;
		}
	<?php endif; ?>
	</style>
	<?php
} // grid_focus_header_style()

// Custom styles for the custom header page in the admin
function grid_focus_admin_header_style() {
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		border: none;
		overflow: hidden;
		width: 970px;
	}
	#headimg #masthead {
		min-height: 48px;
		padding: 7px 0;
		overflow: hidden;
	}
	#headimg h1 {
		float: left;
		font-family: arial, verdana, sans-serif;
		font-size: 24px;
		line-height: 16.8px;
		text-transform: uppercase;
		width: 400px;
	    margin: 15px 0 0 7px;
	}
	#headimg h1 a {
		text-decoration: none;
	}
	#headimg #desc {
		float: right;
		font-family: arial, verdana, sans-serif;
		font-size: 12px;
		line-height: 16.8px;
		opacity: .8;
	    margin: 8px 52px 0 0;
	    opacity: 0.8;
	    width: 340px;
	}
	<?php
		// If the user has set a custom color for the text use that
		if ( get_header_textcolor() != HEADER_TEXTCOLOR ) :
	?>
		#headimg h1 a,
		#headimg #desc {
			color: #<?php echo get_header_textcolor(); ?>;
		}
	<?php endif; ?>
	</style>
<?php
} // grid_focus_admin_header_style

// Custom markup for the custom header admin page
function grid_focus_admin_header_image() { ?>
	<div id="headimg">
		<?php
		if ( 'blank' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) || '' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) )
			$style = ' style="display:none;"';
		else
			$style = ' style="color:#' . get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) . ';"';
		?>
		<div id="masthead">
			<h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
			<div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
		</div>
		<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
		<?php endif; ?>
	</div>
<?php } // grid_focus_admin_header_image
