<?php
/**
 * @package WordPress
 * @subpackage Enterprise
 */

$themecolors = array(
	'bg' => 'f0f0f0',
	'border' => 'cccccc',
	'text' => '555555',
	'link' => '008DCF',
	'url' => '008DCF'
);
$content_width = 630; // pixels

// Register Enterprise nav menus
register_nav_menus( array(
	'primary'	=> __( 'Primary Menu', 'enterprise' ),
	'secondary'	=> __( 'Secondary Menu', 'enterprise' ),
) );

// Get our fallback menu to show a home link
function enterprise_page_menu_args($args) {
	$args['show_home'] = true;
	return $args;
}
add_filter('wp_page_menu_args', 'enterprise_page_menu_args');

// A custom fallback for the Secondary nav menu
// that display the 10 most popular categories
function enterprise_secondary_cat_menu() { ?>
	<div class="menu">
		<ul>
			<?php wp_list_categories( 'title_li=&orderby=count&number=10&order=DESC' ); ?>
		</ul>
	</div>
<?php }

load_theme_textdomain('enterprise', TEMPLATEPATH.'/languages/');

// Add default posts and comments RSS feed links to head
add_theme_support( 'automatic-feed-links' );

// Define widget areas
register_sidebar(array(
	'name'=> __( 'Sidebar', 'enterprise' ),
	'description' => __( 'This is the main sidebar.', 'enterprise' ),
	'before_title'=>'<h4>',
	'after_title'=>'</h4>',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => "</div>"
));
register_sidebar(array(
	'name'=> __( 'Footer #1', 'enterprise' ),
	'description' => __( 'This is the left column in the footer.', 'enterprise'),
	'before_title'=>'<h4>',
	'after_title'=>'</h4>',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => "</div>"
));
register_sidebar(array(
	'name'=> __( 'Footer #2', 'enterprise' ),
	'description' => __( 'This is the middle column in the footer.', ''),
	'before_title'=>'<h4>',
	'after_title'=>'</h4>',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => "</div>"
));
register_sidebar(array(
	'name'=> __( 'Footer #3', 'enterprise' ),
	'description' => __( 'This is the right column in the footer.', 'enterprise' ),
	'before_title'=>'<h4>',
	'after_title'=>'</h4>',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => "</div>"
));

// Template for comments and pingbacks
if ( ! function_exists( 'studiopress_comment' ) ) :
function studiopress_comment( $comment, $args, $depth ) {
	$GLOBALS ['comment'] = $comment; ?>
	<?php if ( '' == $comment->comment_type ) : ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 40 ); ?>
			<?php printf( __( '<cite class="fn">%s</cite> <span class="says">says:</span>', 'enterprise' ), get_comment_author_link() ); ?>
		</div>
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em><?php _e( 'Your comment is awaiting moderation.', 'enterprise' ); ?></em>
			<br />
		<?php endif; ?>

		<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><?php printf( __( '%1$s at %2$s', 'enterprise' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'enterprise' ),'  ','' ); ?></div>

		<div class="comment-body"><?php comment_text(); ?></div>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div>
	</div>

	<?php else : ?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'enterprise' ); ?> <?php comment_author_link(); ?><?php edit_comment_link ( __('edit', 'enterprise'), '&nbsp;&nbsp;', '' ); ?></p>
	<?php endif;
}
endif;

// Add support for WP 3.0 custom background
add_custom_background();

// Provide custom header

// Set some default values
define('HEADER_TEXTCOLOR', '333333'); // Default text color
define('HEADER_IMAGE_WIDTH', 960); // Default image width is actually the div's height
define('HEADER_IMAGE_HEIGHT', 80); // Same for height

function header_style() {
// This function defines the style for the theme
// You can change these selectors to match your theme
?>
<style type="text/css">
#header {
	background: #FFFFFF url(<?php header_image() ?>) no-repeat;
}
<?php
// Has the text been hidden?
// If so, set display to equal none
if ( 'blank' == get_header_textcolor() ) { ?>
.header-left {
	padding: 0;
	width: auto;
}
#header h1,
#header h4 {
	text-indent: -9000px;
	margin: 0;
	padding: 0;
}
#header h1 a,
#header h4 a {
	display: block;
	margin: 0;
	width: 960px;
	height: 80px;
}
p#description {
	display: none;
}
<?php } else {
// Otherwise, set the color to be the user selected one
?>
#header h1, #header h1 a, #header h1 a:visited, #header h4, #header h4 a, #header h4 a:visited, .header-left {
	color: #<?php header_textcolor(); ?>;
}
<?php } ?>
</style>
<?php
}

function enterprise_admin_header_style() {
?>
<style type="text/css">
#headimg {
	background: #FFFFFF url(<?php header_image() ?>) no-repeat;
	border: 1px solid #E4E4E4;
	-moz-border-radius: 0 0 10px 10px;
	-khtml-border-radius: 0 0 10px 10px;
	-webkit-border-radius: 0 0 10px 10px;
	border-radius: 0 0 10px 10px;
	overflow: hidden;
	padding: 0;
	width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
}
#headimg h1 {
	font: 24px/20px Tahoma,Arial,Verdana;
	font-weight: normal;
	margin: 0 0 7px;
	font-weight: normal;
	padding: 15px 0 0 20px;
	width: 690px;
}
#headimg, #headimg h1 a {
	color: #<?php header_textcolor(); ?>;
	text-decoration: none;
}
#headimg #desc {
	font-family:Tahoma,Arial,Verdana;
	font-size:14px;
	font-style:italic;
	margin: 0 0 0 20px;
}
<?php if ( 'blank' == get_header_textcolor() ) { ?>
#header h1 a {
	display: none;
}
#header, #header h1 a {
	color: <?php echo HEADER_TEXTCOLOR ?>;
}
<?php } ?>
</style>
<?php
}

add_custom_image_header('header_style', 'enterprise_admin_header_style');

/**
 * Get the URL of the next image in a gallery for attachment pages
 */
function theme_get_next_attachment_url() {
	global $post;
	$post = get_post($post);
	$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );

	foreach ( $attachments as $k => $attachment ) {
		if ( $attachment->ID == $post->ID )
			break;
	}
	$k++;
	if ( isset( $attachments[ $k ] ) )
		return get_attachment_link( $attachments[ $k ]->ID );
	else
		return get_permalink( $post->post_parent );
}