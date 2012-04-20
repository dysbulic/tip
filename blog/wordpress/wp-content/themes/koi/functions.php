<?php
/**
 * @package WordPress
 * @subpackage Koi
 */

$themecolors = array(
	'bg' => 'f4d0a8',
	'border' => 'f4d0a8',
	'text' => '453320',
	'link' => '000000',
	'url' => '000000'
);
$content_width = 535;

//sidebar widgets
if ( function_exists( 'register_sidebar' ) ) {
	register_sidebar( array(
		'name' => 'Sidebar',
		'id' => 'sidebar-1',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	) );
	register_sidebar( array(
		'name' => 'Footer 1',
		'id' => 'footer-1',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	) );
	register_sidebar( array(
		'name' => 'Footer 2',
		'id' => 'footer-2',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	) );
	register_sidebar( array(
		'name' => 'Footer 3',
		'id' => 'footer-3',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	) );
}

//custom comment template
function koi_comment( $comment, $args, $depth ) {
	 $GLOBALS['comment'] = $comment; ?>

	<?php if ( '' == $comment->comment_type ) : ?>
	 <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p class="comment-author">
			<?php echo get_avatar( $comment, $size = '48' ); ?>
			<?php printf( __( '<cite>%s</cite>' ), get_comment_author_link() ); ?><br />
			<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><small><strong><?php comment_date( 'M d, Y' ); ?></strong> @ <?php comment_time( 'H:i:s' ); ?></a> <?php edit_comment_link( 'Edit', ' [', ']' ); ?></small>
		</p>
		<div class="commententry">
			<?php if ( $comment->comment_approved == '0' ) : ?>
			<p><em><?php _e( 'Your comment is awaiting moderation.' ); ?></em></p>
			<?php endif; ?>

			<?php comment_text(); ?>
		</div>

		<p class="reply">
		<?php comment_reply_link( array_merge( $args, array( 'add_below' => 'commententry', 'depth' => $depth, 'max_depth' => $args['max_depth']) ) ); ?>
		</p>

	<?php else : ?>

	<li class="trackback"><?php _e( 'Trackback: ', 'ndesignthemes' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( 'Edit', ' [', ']' ); ?>

	<?php endif; ?>

<?php
}

// Feed links
add_theme_support( 'automatic-feed-links' );

// Header navigation menu
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'ndesignthemes' ),
) );

function koi_page_menu() { // fallback for primary navigation  ?>
<ul class="menu page-menu">
	<li class="page_item <?php if ( is_home() ) { echo ' current_page_item'; }?>"><a href="<?php echo home_url( '/' ); ?>"><?php _e( 'Home', 'ndesignthemes' ); ?></a></li>
	<?php wp_list_pages( array( 'title_li' => '' ) ); ?>
</ul>
<?php }

// Custom background
add_custom_background();

function koi_custom_background() {
	// Clear default styles if background image is present
	if ( '' != get_background_image() ) { ?>
	<style type="text/css">
		html { background: none; }
		#header, #footer { background-image: none; }
		#header #logo, .post-date, .post-title, #comments h3, #sidebar h4, #sidebar .widgettitle { text-shadow: 0 1px 0 #<?php echo get_background_color(); ?>; }
	</style>
	<?php }
}
add_action( 'wp_head', 'koi_custom_background' );

// Custom header image
define( 'HEADER_TEXTCOLOR', '6c5c46' );
define( 'HEADER_IMAGE', '' );
define( 'HEADER_IMAGE_WIDTH', 980 );
define( 'HEADER_IMAGE_HEIGHT', 200 );

add_custom_image_header( 'koi_header_style', 'koi_admin_header_style' );

// Styles for the admin header image
function koi_admin_header_style() {
?>
	<style type="text/css">
		#headimg h1, #headimg h1 a {
			margin: -100px 0 0;
			font: bold 45px/1 Arial, Helvetica, sans-serif;
			letter-spacing: -.05em;
			text-decoration: none;
		}
		#headimg #desc {
			margin: 10px 0 20px;
			font: italic 20px/1 Georgia, "Times New Roman", Times, serif;
		}
	<?php if ( 'blank' != get_header_textcolor() ) { ?>
		#headimg {
			background-position: 0 100%;
			background-repeat: no-repeat;
			padding-top: 100px;
		}
		#headimg h1 {
			margin: -100px 0 0;
		}
 	<?php } ?>
	</style>
<?php
}

// Styles for the header image
function koi_header_style() {
?>
	<style type="text/css">
	<?php if ( '' != get_header_image() ) { ?>
		#header #logo, #header #logo a {
			color: #<?php header_textcolor(); ?>;
		}
		#custom-header-img {
			margin-bottom: 20px;
		}
	<?php } ?>
	<?php if ( 'blank' != get_header_textcolor() ) { ?>
		#header #logo, #header #logo a, #header #description {
			color: #<?php header_textcolor(); ?>;
		}
	<?php } else { ?>
		#header h1, #header #description {
			display: none;
		}
	<?php } ?>
	</style>
<?php
}

// Load theme options
require_once( dirname( __FILE__ ) . '/inc/theme-options.php' );