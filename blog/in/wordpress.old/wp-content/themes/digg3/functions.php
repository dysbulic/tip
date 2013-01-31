<?php
/**
 * @package WordPress
 * @subpackage Digg3
 */

$themecolors = array(
	'bg' => 'ffffff',
	'border' => '666666',
	'text' => '333333',
	'link' => '105CB6',
	'url' => '8DAB3B',
);

$content_width = 468; // pixels

add_filter( 'body_class', '__return_empty_array', 1 );

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
<?php 
}

function digg3_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>

<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
<div id="div-comment-<?php comment_ID() ?>">
	<div class="comment-meta commentmetadata">
		<?php if ($args['avatar_size'] != 0) { ?><div class="avatar"><?php echo get_avatar( $comment, $args['avatar_size'] ); ?></div><?php } ?>
		<?php
		printf( __('%1$s, on %2$s at %3$s said:', 'digg3'),
		'<span class="comment-author vcard"><strong class="fn">' . get_comment_author_link() . '</strong>',
		'<a href="#comment-' . get_comment_ID() . '" title="">'. get_comment_date(),
		get_comment_time() . '</a>' ); ?>
		<?php edit_comment_link( __('Edit Comment', 'digg3'), ' ' ); ?></span>
		<?php if ($comment->comment_approved == '0') : ?>
		<em><?php _e('Your comment is awaiting moderation.', 'digg3'); ?></em>
		<?php endif; ?>
	</div>

	<?php comment_text() ?>

	<div class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
</div>
<?php
}