<?php
/**
 * @package WordPress
 * @subpackage Neo Sapien 05
 */

$themecolors = array(
	'bg' => 'eeeeee',
	'text' => '000000',
	'link' => 'cc0000',
	'border' => '000000',
	'url' => '5e0000',
);

$content_width = 460;

add_filter( 'body_class', '__return_empty_array', 1 );

add_theme_support( 'automatic-feed-links' );

register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'neosapien' ),
) );

function neosapien_page_menu() { // fallback for primary navigation ?>
<ul>
<li <?php if( is_home() ) { echo 'class="current_page_item"'; } ?>><a href="<?php bloginfo( 'url' ); ?>/" title="Home">Home</a></li>
		<?php wp_list_pages( 'title_li=&depth=1' ); ?>
</ul>
<?php }

// Custom background
add_custom_background();

function neosapien_custom_background() {
	if ( '' != get_background_color() && '' == get_background_image() ) { ?>
	<style type="text/css">
		body, .header { background-image: none; }
	</style>
	<?php }
}
add_action( 'wp_head', 'neosapien_custom_background' );

// No CSS, just IMG call

define('HEADER_TEXTCOLOR', '');
define('HEADER_IMAGE', '%s/images/main.jpg'); // %s is theme dir uri
define('HEADER_IMAGE_WIDTH', 480);
define('HEADER_IMAGE_HEIGHT', 250);
define( 'NO_HEADER_TEXT', true );

function neosapien_admin_header_style() {
?>
<style type="text/css">
#headimg {
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
}

#headimg h1, #headimg #desc {
	display: none;
}

</style>
<?php
}

add_custom_image_header('', 'neosapien_admin_header_style');

if ( function_exists('register_sidebar') ) {
	register_sidebar(1);
	register_sidebar(2);
	register_sidebar(3);
}

function neo_sapien_05_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID() ?>">
	<div class="comment-author vcard comment-meta commentmetadata">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
	<cite><?php comment_type(__('Comment'), __('Trackback'), __('Pingback')); ?> <?php _e('by'); ?> <span class="fn"><?php comment_author_link() ?></span> on <?php comment_date() ?> <a href="#comment-<?php comment_ID() ?>"><?php comment_time() ?></a></cite>
	</div>
	<?php comment_text() ?>
	<div class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	</div>
<?php
}
