<?php
/**
 * @package WordPress
 * @subpackage Comet
 */
if ( ! isset( $content_width ) )
	$content_width = 600;

if ( ! function_exists( 'comet_setup' ) ) {
	/**
	 * Setup for Comet Theme.
	 */
	function comet_setup() {
		if ( is_admin() )
			require( get_template_directory() . '/inc/theme-options.php' );

		add_theme_support( 'menus' );
		add_theme_support( 'automatic-feed-links' );

		add_custom_background();

		define( 'HEADER_TEXTCOLOR', '000' );
		define( 'HEADER_IMAGE', '' ); // Defaults to no header image.
		define( 'HEADER_IMAGE_WIDTH', apply_filters( 'comet_header_image_width', 960 ) );
		define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'comet_header_image_height', 200 ) );
		add_custom_image_header( 'comet_header_style', 'comet_admin_header_style', 'comet_admin_header_image' );

		load_theme_textdomain( 'comet', get_template_directory() . '/languages' );

		register_nav_menus( array(
			'primary-menu'   => __( 'Above Title', 'comet' ),
			'secondary-menu' => __( 'Below Title', 'comet' ),
		) );

		add_action( 'init',               'comet_register_styles' );
		add_action( 'widgets_init',       'comet_register_sidebars' );
		add_action( 'wp_enqueue_scripts', 'comet_enqueue_color_style' );
		add_filter( 'body_class',         'comet_body_class' );
		add_filter( 'widget_title',       'comet_calendar_widget_title', 10, 3 );
	}
}
add_action( 'after_setup_theme', 'comet_setup' );

if ( ! function_exists( 'comet_header_style' ) ) {
	/**
	 * Public styles the header image and text.
	 */
	function comet_header_style() {
		if ( HEADER_TEXTCOLOR == get_header_textcolor() && '' == get_header_image() )
			return;
		?>
		<style type="text/css">
		<?php
			// Has the text been hidden?
			if ( 'blank' == get_header_textcolor() ) :
		?>
			#site-title,
			#site-description {
				position: absolute !important;
				clip: rect( 1px 1px 1px 1px ); /* IE6, IE7 */
				clip: rect( 1px, 1px, 1px, 1px );
			}
		<?php
			endif;
		?>
		<?php if ( '' != get_header_image() ) : ?>
			#header {
				height: <?php echo HEADER_IMAGE_HEIGHT ?>px;
				padding: 0;
				position: relative;
			}
			#header-image {
				position: absolute;
				top: 0;
				left: 0;
				z-index: 2;
			}
			#site-title,
			#site-description {
				position: relative;
				z-index: 2;
				margin-left: 40px;
				margin-right: 40px;
			}
		<?php endif; ?>
		<?php if ( 'blank' != get_header_textcolor() ) : ?>
			#site-title a,
			#site-description {
				color: #<?php echo get_header_textcolor(); ?> !important;
			}
		<?php endif; ?>
		</style>
		<?php
	}
}

if ( ! function_exists( 'comet_admin_header_style' ) ) {
	/**
	 * Styles the header image displayed on the Appearance > Header admin panel.
	 *
	 * Referenced via add_custom_image_header() in comet_setup().
	 */
	function comet_admin_header_style() {
	?>
		<style type="text/css">
		.appearance_page_custom-header #headimg {
			<?php
				$color = comet_get_background_color();
				if ( '' != get_background_image() )
					echo 'background: #' . $color . ' url( ' . esc_url( get_background_image() ) . ' ) repeat fixed !important;';
				else
					echo 'background: #' . $color . ' !important;';
			?>
			border: 1px solid #eee;
			overflow: hidden;
			position: relative;
			text-align: left;
			width: 960px;
			height: 200px;
		}
		#headimg h1,
		#desc {
			clear: both;
			line-height: 150%;
			margin: 10px;
		}
		#headimg h1 {
			font: bold 38px/100% 'Droid Sans', Helvetica, Arial, sans-serif;
			letter-spacing: -1px;
			margin: 60px 0 0 40px;
			padding: 0;
			position: relative;
			text-shadow: 1px 1px 1px rgba( 255, 255, 255, 0.5 );
			z-index: 2;
		}
		#headimg h1 a {
			text-decoration: none;
		}
		#desc {
			font: normal bold 145%/170% 'Droid Serif', Georgia, serif;
			margin: 10px 0 0 40px;
			padding: 0;
			position: relative;
			text-shadow: 1px 1px 1px rgba( 255, 255, 255, 0.5 );
			z-index: 2;
		}
		#comet-header-image {
			position: absolute;
			top: 0;
			left: 0;
			z-index: 1;
		}
		</style>
	<?php
	}
}

if ( ! function_exists( 'comet_admin_header_image' ) ) {
	/**
	 * Custom header image markup displayed on the Appearance > Header admin panel.
	 *
	 * Referenced via add_custom_image_header() in comet_setup().
	 */
	function comet_admin_header_image() { ?>
		<div id="headimg">
			<?php
			if ( 'blank' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) || '' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) )
				$style = ' style="display:none;"';
			else
				$style = ' style="color:#' . get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) . ';"';
			?>
			<h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo home_url( '/' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
			<div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
			<?php $header_image = get_header_image();
			if ( ! empty( $header_image ) ) : ?>
				<img id="comet-header-image" src="<?php echo esc_url( $header_image ); ?>" alt="" />
			<?php endif; ?>
		</div>
	<?php
	}
}

/**
 * Get Background Color.
 *
 * @return string The current background color of the theme.
 */
function comet_get_background_color() {
	/* Use the "Color Scheme" option as a base. */
	$options = comet_get_theme_options();
	switch ( $options['color_scheme'] ) {
		case 'blue' :
			$color = 'f2f3f4';
			break;
		case 'green' :
			$color = 'f2f4f2';
			break;
		case 'purple' :
			$color = 'f3f2f4';
			break;
		case 'red' :
			$color = 'e7e4e4';
			break;
		case 'teal' :
			$color = 'f2f4f4';
			break;
		case 'yellow' :
			$color = 'f4f3f2';
			break;
		case 'grey' :
		default :
			$color = 'f3f3f3';
			break;
	}

	/* Override $color with user-defined value. */
	if ( '' != get_background_color() ) {
		$color = get_background_color();
	}

	return $color;
}

/**
 * Enqueue stylesheets.
 */
function comet_enqueue_color_style() {
	$options = comet_get_theme_options();
	wp_enqueue_style( 'comet_' . $options['color_scheme'] );
	wp_enqueue_style( 'comet_print' );
}

/**
 * Register all stylesheets.
 */
function comet_register_styles() {
	wp_register_style( 'comet_print',  get_stylesheet_directory_uri() . '/css/print.css',  array(), '20110826', 'print' );
	wp_register_style( 'comet_blue',   get_stylesheet_directory_uri() . '/css/blue.css',   array(), '20110826', 'all' );
	wp_register_style( 'comet_green',  get_stylesheet_directory_uri() . '/css/green.css',  array(), '20110826', 'all' );
	wp_register_style( 'comet_grey',   get_stylesheet_directory_uri() . '/css/grey.css',   array(), '20110826', 'all' );
	wp_register_style( 'comet_purple', get_stylesheet_directory_uri() . '/css/purple.css', array(), '20110826', 'all' );
	wp_register_style( 'comet_red',    get_stylesheet_directory_uri() . '/css/red.css',    array(), '20110826', 'all' );
	wp_register_style( 'comet_teal',   get_stylesheet_directory_uri() . '/css/teal.css',   array(), '20110826', 'all' );
	wp_register_style( 'comet_yellow', get_stylesheet_directory_uri() . '/css/yellow.css', array(), '20110826', 'all' );
}

/**
 * Register Sidebars.
 */
function comet_register_sidebars() {
	$options = comet_get_theme_options();

	$primary_description = '';
	if ( 'content' == $options['theme_layout'] ) {
		$primary_description = __( 'The Primary Sidebar is currently disabled. Please visit the Theme Options page and choose a new layout to enable', 'comet' );
	}

	$secondary_description = '';
	if ( 'sidebar-content-sidebar' != $options['theme_layout'] ) {
		$secondary_description = __( 'The Secondary Sidebar is currently disabled. Please visit the Theme Options page and choose the "Content Sidebar Content" layout to enable.', 'comet' );
	}

	register_sidebar( array(
		'name'          => __( 'Primary Sideber', 'comet' ),
		'id'            => 'sidebar-1',
		'description'   => $primary_description,
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => '</li>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Secondary Sidebar', 'comet' ),
		'id'            => 'sidebar-2',
		'description'   => $secondary_description,
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => '</li>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}

if ( ! function_exists( 'comet_comments' ) ) {
	/**
	 * Display a Comment.
	 *
	 * Used as callback to wp_list_comments() in comments.php
	 */
	function comet_comments( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<div class="comment-body" id="div-comment-<?php comment_ID(); ?>">
			<?php echo get_avatar( $comment, 50 ); ?>
			<div class="comment-wrap">
				<div class="comment-meta">
					<h4><?php echo get_comment_author_link(); ?></h4> &nbsp;/&nbsp;
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>" class="comment-date"><?php echo get_comment_date(); ?></a>
					<?php edit_comment_link( __( 'Edit', 'comet' ), ' &nbsp;/&nbsp; ', '' ); ?>
				</div><!-- /comment-meta -->
				<div class="comment-text">
					<?php if ( 0 == $comment->comment_approved ) : ?>
						<p class="info"><em><?php _e( 'Your comment is awaiting moderation.', 'comet' ); ?></em></p>
					<?php endif; ?>
					<?php comment_text(); ?>
				</div><!-- /comment-text -->
			</div><!-- /comment-wrap -->
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div><!-- /comment-body -->
	<?php
	}
}

if ( ! function_exists( 'comet_trackbacks' ) ) {
	/**
	 * Display a Trackback.
	 *
	 * Used as callback to wp_list_comments() in comments.php
	 */
	function comet_trackbacks( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
	?>
		<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
			<?php echo get_comment_author_link(); ?>
	<?php
	}
}

/**
 * Get Theme Options.
 *
 * @return array The current user defined options.
 */
function comet_get_theme_options() {
	return get_option( 'comet_theme_options', comet_get_theme_option_defaults() );
}

/**
 * Get Default Options.
 *
 * @return array A list of default options.
 */
function comet_get_theme_option_defaults() {
	$defaults = array(
		'color_scheme' => 'grey',
		'theme_layout' => 'content-sidebar',
	);

	return apply_filters( 'comet_default_theme_options', $defaults );
}

/**
 * Get Recognized Color Schemes.
 *
 * @return array A list of recognized color schemes.
 */
function comet_color_schemes() {
	$color_schemes = array(
		'grey' => array(
			'value' =>	'grey',
			'label' => __( 'Grey', 'comet' )
		),
		'red' => array(
			'value' =>	'red',
			'label' => __( 'Red', 'comet' )
		),
		'yellow' => array(
			'value' =>	'yellow',
			'label' => __( 'Yellow', 'comet' )
		),
		'green' => array(
			'value' =>	'green',
			'label' => __( 'Green', 'comet' )
		),
		'teal' => array(
			'value' =>	'teal',
			'label' => __( 'Teal', 'comet' )
		),
		'blue' => array(
			'value' =>	'blue',
			'label' => __( 'Blue', 'comet' )
		),
		'purple' => array(
			'value' =>	'purple',
			'label' => __( 'Purple', 'comet' )
		),
	);

	return $color_schemes;
}

/**
 * Get Recognized Layouts.
 *
 * @return array A list of recognized layouts.
 */
function comet_theme_layouts() {
	$theme_layouts = array(
		'content-sidebar' => array(
			'value'     => 'content-sidebar',
			'label'     => __( 'Content-Sidebar', 'comet' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/content-sidebar.png',
		),
		'sidebar-content' => array(
			'value'     => 'sidebar-content',
			'label'     => __( 'Sidebar-Content', 'comet' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/sidebar-content.png',
		),
		'sidebar-content-sidebar' => array(
			'value'     => 'sidebar-content-sidebar',
			'label'     => __( 'Sidebar-Content-Sidebar', 'comet' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/sidebar-content-sidebar.png',
		),
		'content' => array(
			'value'     => 'content',
			'label'     => __( 'Content', 'comet' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/content.png',
		),
	);

	return $theme_layouts;
}

/**
 * Allow WordPress "Background" UI to recognize the background color set in Comet's "Color Scheme" option.
 */
function comet_admin_background_style() {
?>
	<style type="text/css">
	#custom-background-image {
		background-color: #<?php echo comet_get_background_color(); ?>;
	}
	</style>
<?php
}
add_action( 'admin_head-appearance_page_custom-background', 'comet_admin_background_style' );

/**
 * Add custom layout option to the body class.
 *
 * Enables easy switching of different layouts as defined by the user via the "Layout" option.
 *
 * @param array $classes A list of classes to be applied to the body tag.
 * @return array Modified list of classes to be applied to the body tag.
 */
function comet_body_class( $classes ) {
	$options = comet_get_theme_options();
	$class = 'comet-' . $options['theme_layout'];
	if ( ! in_array( $class, $classes ) )
		$classes[] = $class;
	return $classes;
}

/**
 * Calendar Widget Title
 *
 * For some reason, WordPress will print a non-breaking space
 * entity wrapped in the appropriate tags for the calendar
 * widget even if the title's value is left empty by the user.
 * This function will remove the empty heading tag.
 *
 * Hooked into "widget_title".
 *
 * It is possible that this filter may not be needed in the future:
 *
 * @see http://core.trac.wordpress.org/ticket/17837
 *
 * @param string $title The value of the calendar widget's title for this instance.
 * @param unknown $instance
 * @param string $id_base
 * @return string Calendar widget title.
 */
function comet_calendar_widget_title( $title = '', $instance = '', $id_base = '' ) {
	if ( 'calendar' == $id_base && '&nbsp;' == $title )
		$title = '';

	return $title;
}

/*
 * WP.com: Set value of $themecolors.
 */
$comet_options = comet_get_theme_options();

switch ( $comet_options['color_scheme'] ) {
	case 'blue' :
		$themecolors = array(
			'bg'     => 'f2f3f4',
			'text'   => '888888',
			'link'   => 'b91313',
			'border' => 'c9d1dc',
			'url'    => 'b91313',
		);
		break;
	case 'green' :
		$themecolors = array(
			'bg'     => 'f2f4f2',
			'text'   => '888888',
			'link'   => 'b91313',
			'border' => 'cdd8cd',
			'url'    => 'b91313',
		);
		break;
	case 'purple' :
		$themecolors = array(
			'bg'     => 'f3f2f4',
			'text'   => '888888',
			'link'   => 'b91313',
			'border' => 'd3c8dd',
			'url'    => 'b91313',
		);
		break;
	case 'red' :
		$themecolors = array(
			'bg'     => 'e7e4e4',
			'text'   => '888888',
			'link'   => '2464b3',
			'border' => 'e6bfbf',
			'url'    => '2464b3',
		);
		break;
	case 'teal' :
		$themecolors = array(
			'bg'     => 'f2f4f4',
			'text'   => '888888',
			'link'   => 'b91313',
			'border' => 'c8dddd',
			'url'    => 'b91313',
		);
		break;
	case 'yellow' :
		$themecolors = array(
			'bg'     => 'f4f3f2',
			'text'   => '888888',
			'link'   => 'b91313',
			'border' => 'e7dbbe',
			'url'    => 'b91313',
		);
		break;
	case 'grey' :
	default :
		$themecolors = array(
			'bg'     => 'f3f3f3',
			'text'   => '888888',
			'link'   => 'b91313',
			'border' => 'd2d2d2',
			'url'    => 'b91313',
		);
		break;
}
