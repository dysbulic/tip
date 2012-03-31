<?php
/**
 * @package WordPress
 * @subpackage Clean Home
 */

$content_width = 590;

// Setup Clean Home
add_action( 'after_setup_theme', 'cleanhome_theme_setup' );

function cleanhome_theme_setup() {

	// Theme options
	require( dirname( __FILE__ ) . '/theme-options.php' );

	// Feed
	add_theme_support( 'automatic-feed-links' );

	// Background
	add_custom_background();

	// Header Image
	define( 'HEADER_IMAGE', '' );
	define( 'HEADER_IMAGE_WIDTH', 900 );
	define( 'HEADER_IMAGE_HEIGHT', 200 );
	define( 'HEADER_TEXTCOLOR', ' ' );

	add_custom_image_header( 'cleanhome_header_style', 'cleanhome_admin_header_style', 'cleanhome_admin_header_image' );

	add_action( 'init', 'cleanhome_register_menus' );

	add_action( 'widgets_init', 'cleanhome_widgets_init' );

	add_action( 'wp_enqueue_scripts', 'cleanhome_color_registrar' );

}

function cleanhome_header_style() {
	// If no custom options for text are set, let's bail
	// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
	if ( HEADER_TEXTCOLOR == get_header_textcolor() )
		return;
	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == get_header_textcolor() ) :
	?>
		#logo h1,
		#logo h2 {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		#logo h1 a,
		#logo h2 {
			color: #<?php echo get_header_textcolor(); ?> !important;
		}
	<?php endif; ?>
	</style>
	<?php
}

// Admin Header
function cleanhome_admin_header_style() {
	?><style type="text/css">
		.appearance_page_custom-header #headimg {
			background: rgba(255, 255, 255, 0.88);
			border: none;
			border-radius: 6px;
			padding: 30px;
			max-width: 900px;
		}
		#headimg h1 {
			margin: 0;
		}
		a#name {
			color: #CA1E00;
			font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
		    font-size: 60px;
		    font-weight: 600;
			line-height: 22px;
		    letter-spacing: -2px;
		    text-decoration: none;
		    text-rendering: optimizelegibility;
		}
		#desc {
			color: #000;
			font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
		    font-size: 18px;
		    font-weight: 200;
		    padding: 12px 4px 34px 0;
		}
		<?php
		$options = cleanhome_get_theme_options();
		$color_scheme = $options['color_scheme'];

		if ( 'dark' == $color_scheme ) {
			?>
			.appearance_page_custom-header #headimg {
				background: #161616;
			}
			a#name {
				color: #ad5a00;
			}
			#desc {
				color: #777;
			}
			#headimg img {
				border-top: 6px solid #333;
			}
			<?php
		}
		elseif ( 'snowy' == $color_scheme ) {
			?>
			.appearance_page_custom-header #headimg {
				background: rgba(255, 255, 255, 0.7);
			}
			a#name {
				color: #086581;
			}
			#desc {
				color: #263E46;
			}
			#headimg img {
				border-top: 6px solid #333;
			}
			<?php
		}
		elseif ( 'sunny' == $color_scheme ) {
			?>
			.appearance_page_custom-header #headimg {
				background: rgba(255, 255, 255, 0.7);
			}
			a#name {
				color: #815303;
			}
			#desc {
				color: #4B3E27;
			}
			#headimg img {
				border-top: 6px solid #333;
			}
			<?php
		}
		if ( HEADER_TEXTCOLOR != get_header_textcolor() ) {
			?>
			a#name, desc {
				color: #<?php echo get_header_textcolor(); ?>;
			}
			<?php
		}
		if ( 'blank' == get_header_textcolor() ) {
			?>
			a#name, desc {
				display: none;
			}
			<?php
		}
		?>
	</style><?php
}

function cleanhome_admin_header_image() { ?>
	<div id="headimg">
		<?php
		if ( 'blank' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) || '' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) )
			$style = ' style="display:none;"';
		else
			$style = ' style="color:#' . get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) . ';"';
		?>
		<h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
		<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
		<?php endif; ?>
	</div>
<?php }


// Header navigation menu
function cleanhome_register_menus() {
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'cleanhome' ),
	) );
}

// Menu Fallback
function cleanhome_page_menu() { // fallback for primary navigation ?>
<ul>
	<?php wp_list_pages( 'title_li=&depth=1' ); ?>
</ul>
<?php }

// Widgets
function cleanhome_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar', 'cleanhome' ),
		'id' => 'primary-widget-area',
		'description' => __( 'The primary widget area', 'cleanhome' ),
		'before_widget' => '<div id="%1$s" class="widget block %2$s sidebar-box">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>'
	));
	register_sidebar( array(
		'name' => __( 'Header', 'cleanhome' ),
		'id' => 'header',
		'description' => __( 'Header widget area with big font size.', 'cleanhome' ),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));
}

// Returns the current Clean Home theme options, with default values for fallback
function cleanhome_get_theme_options() {
	$defaults = array(
		'color_scheme' => 'light',
	);
	$options = get_option( 'cleanhome_theme_options', $defaults );

	return $options;
}

// Register our color schemes and add them to the queue
function cleanhome_color_registrar() {
	$options = cleanhome_get_theme_options();
	$color_scheme = $options['color_scheme'];

	if ( 'dark' == $color_scheme ) {
		wp_register_style( 'dark', get_template_directory_uri() . '/colors/dark.css', null, null );
		wp_enqueue_style( 'dark' );
	}
	if ( 'snowy' == $color_scheme ) {
		wp_register_style( 'snowy', get_template_directory_uri() . '/colors/snowy.css', null, null );
		wp_enqueue_style( 'snowy' );
	}
	if ( 'sunny' == $color_scheme ) {
		wp_register_style( 'sunny', get_template_directory_uri() . '/colors/sunny.css', null, null );
		wp_enqueue_style( 'sunny' );
	}
}

$options = cleanhome_get_theme_options();
$color_scheme = $options['color_scheme'];

if ( 'dark' == $color_scheme ) {
	$themecolors = array(
    	'bg' => '161616',
    	'border' => '222222',
    	'text' => 'd5d5d5',
    	'link' => 'd5d5d5',
    	'url' => 'ad5a00',
    );
}
if ( 'snowy' == $color_scheme ) {
    $themecolors = array(
    	'bg' => 'e9f7fb',
    	'border' => '9ccedd',
    	'text' => '000000',
    	'link' => '0092be',
    	'url' => '086581',
    );
}
if ( 'sunny' == $color_scheme ) {
    $themecolors = array(
    	'bg' => 'f9f7e1',
    	'border' => 'd6cd64',
    	'text' => '000000',
    	'link' => '856d0e',
    	'url' => '815303',
    );
}
if ( 'light' == $color_scheme ) {
    $themecolors = array(
    	'bg' => 'fafafa',
    	'border' => 'cccccc',
    	'text' => '000000',
    	'link' => 'ca1e00',
    	'url' => 'e94325',
    );
}