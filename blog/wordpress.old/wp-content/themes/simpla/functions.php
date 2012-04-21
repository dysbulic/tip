<?php
/**
 * @package WordPress
 * @subpackage Simpla
 */

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '555555',
	'link' => '557799',
	'border' => 'dddddd',
	'url' => 'b4c24b',
);

$content_width = 480;

add_filter( 'body_class', '__return_empty_array', 1 );

if ( function_exists( 'register_sidebars' ) )
	register_sidebars(1);

add_theme_support( 'automatic-feed-links' );

// Custom background
add_custom_background();

function simpla_custom_background() {
	if ( '' != get_background_color() && '' == get_background_image() ) { ?>
	<style type="text/css">
		body { background-image: none; }
	</style>
	<?php }
}
add_action( 'wp_head', 'simpla_custom_background' );

// Navigation menus
register_nav_menus( array(
	'primary' => __( 'Header Navigation', 'simpla' ),
	'secondary' => __( 'Footer Navigation', 'simpla' ),
) );


/**
 * Adds support for Custom Header Image.
 */

define( 'NO_HEADER_TEXT', true );

// The default header text color
define( 'HEADER_TEXTCOLOR', '' );

// By leaving empty, we default to random image rotation
define( 'HEADER_IMAGE', '' );

// The height and width of your custom header.
define( 'HEADER_IMAGE_WIDTH', 750 );
define( 'HEADER_IMAGE_HEIGHT', 120 );

// Add support for Manifest header image.
add_custom_image_header( '', 'simpla_admin_header_style' );

add_theme_support( 'custom-header', array( 'random-default' => true ) );

/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 */
function simpla_admin_header_style() {
?>
	<style type="text/css">
        #headimg {
            width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
            height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
        }
        #heading {
        	display: none;
        }
    </style>
<?php
}

function simpla_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent'); ?> id="comment-<?php comment_ID(); ?>">
	<div id="div-comment-<?php comment_ID(); ?>">
	<div class="comment-author vcard">
		<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
		<cite class="fn"><?php comment_author_link(); ?></cite> Says:
	</div>
	<?php if ($comment->comment_approved == '0') : ?>
		<em><?php _e( 'Your comment is awaiting moderation.', 'simpla' ); ?></em>
	<?php endif; ?>
	<br />

	<small class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>" title=""><?php comment_date(); ?> at <?php comment_time(); ?></a> <?php edit_comment_link( 'e','','' ); ?></small>

	<?php comment_text(); ?>

	<div class="reply">
		<?php comment_reply_link( array_merge( $args, array( 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
	</div>
	</div>
<?php
}
