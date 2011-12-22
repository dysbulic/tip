<?php
/**
 * @package WordPress
 * @subpackage Notepad
 */

// Theme colors and content width
$themecolors = array(
	'bg' => 'fafad3',
	'border' => '644527',
	'text' => '6f5e4e',
	'link' => '644527',
	'url' => 'ca6c18',
);
$content_width = 500;

// Automatic feed links
add_theme_support( 'automatic-feed-links' );

// Register support for nav menus
register_nav_menus( array(
	'primary' => __( 'Primary Menu', 'notepad-theme' ),
) );

// Filter the default page menu
function notepad_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'notepad_page_menu_args' );

// Add ID and CLASS attributes to the first <ul> occurence in wp_page_menu
function add_menuclass($ulclass) {
return preg_replace('/<ul>/', '<ul class="menu">', $ulclass, 1);
}
add_filter('wp_page_menu','add_menuclass');

//sidebar widgets
if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => __( 'Sidebar', 'notepad-theme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));
}

// Make [...] link to posts (from search results page)
function new_excerpt_more($more) {
	global $post;
	return ' <a href="' . get_permalink() . '" class="excerpt-more-link">' . '[&hellip;]' . '</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');

function resize_youtube( $content ) {
	if ( strpos( $content, "width='425' height='350'></embed>" ) != -1 )
	return str_replace( "width='425' height='350'></embed>", "width='240' height='197'></embed>", $content );
}
add_filter( 'the_content', 'resize_youtube', 999 );

// Grab our theme options
require_once ( get_template_directory() . '/theme-options.php' );

//custom comment template
function mytheme_comment( $comment, $args, $depth ) {
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
		<p class="comment-author">
			<?php echo get_avatar( $comment, $size='48' ); ?>
			<?php printf(__( '<cite>%s</cite>' ), get_comment_author_link() ) ?><br />
			<small>
				<strong><?php comment_date( 'M d, Y' ); ?></strong> @ <?php comment_time( 'H:i:s' ); ?>
				<?php edit_comment_link( 'Edit',' [',']' ) ?>
			</small>
		</p>

		<?php if ( '' == $comment->comment_type ) : ?>

		<div class="commententry" id="comment-<?php comment_ID() ?>">
			<?php if ( $comment->comment_approved == '0' ) : ?>
			<p>
				<em>
					<?php _e( 'Your comment is awaiting moderation.' ) ?>
				</em>
			</p>
			<?php endif; ?>

			<?php comment_text() ?>
		</div>


		<p class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'add_below' => 'commententry', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ) ?>
		</p>

		<?php endif; ?>

<?php
}

// Custom background
add_custom_background();

function notepad_custom_background() {
	if ( '' != get_background_color() || '' != get_background_image() ) { ?>
		<style type="text/css">
			html { background-image: none; }
		</style>
	<?php }
}
add_action( 'wp_head', 'notepad_custom_background' );

/**
 * Let's start the changeable header business here
 */

// The default header text color
define( 'HEADER_TEXTCOLOR', '74685C' );

// By leaving empty, we allow for random image rotation.
define( 'HEADER_IMAGE', '' );

// The height and width of our custom header.
define( 'HEADER_IMAGE_WIDTH', 974 );
define( 'HEADER_IMAGE_HEIGHT', 240 );

// Turn on random header image rotation by default.
add_theme_support( 'custom-header', array( 'random-default' => true ) );

// Add a way for the custom header to be styled in the admin panel that controls custom headers.
add_custom_image_header( 'notepad_header_style', 'notepad_admin_header_style', 'notepad_admin_header_image' );

// Custom styles for our blog header
function notepad_header_style() {
	// If no custom options for text are set, let's bail
	$header_image = get_header_image();
	if ( empty( $header_image ) )
		return;
	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	#header-image {
		background: url(<?php echo get_template_directory_uri(); ?>/img/wrapper.png) repeat center 5px;
		padding: 0 0 35px;
	}
	#header-image .home-link {
		background: url(<?php echo esc_url( $header_image ); ?>) no-repeat 0 0;
		display: block;
		margin: -45px 0 0 3px;
		width: 974px;
		height: 240px;
		text-indent: -9000px;
		position: relative;
	}
	.rtl #header-image .home-link {
		text-direction: ltr;
	}
	<?php
		// Has the text been hidden? Let's hide it then.
		if ( 'blank' == get_header_textcolor() ) :
	?>
		#logo,
		#header > .description {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		#logo a,
		#header > .description {
			color: #<?php echo get_header_textcolor(); ?> !important;
		}
	<?php endif; ?>
	</style>
	<?php
} // notepad_header_style()

// Custom styles for the custom header page in the admin
function notepad_admin_header_style() {
?>
	<style type="text/css">
	#headimg {
		max-width: 944px;
	}
	.appearance_page_custom-header #headimg {
		border: none;
	}
	#headimg h1 {
		font-size: 47.9833px;
		line-height: 1em;
		margin: 0 0 10px;
	}
	#headimg h1 a {
		text-decoration: none;
	}
	#headimg #desc {
		font-size: 18.15px;
		margin-bottom: 20px;
		line-height: 1em;
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
} // notepad_admin_header_style

// Custom markup for the custom header admin page
function notepad_admin_header_image() { ?>
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
<?php } // notepad_admin_header_image
