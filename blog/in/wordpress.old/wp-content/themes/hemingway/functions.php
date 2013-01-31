<?php
/**
 * @package WordPress
 * @subpackage Hemingway
 */

load_theme_textdomain( 'hemingway' );

$themecolors = array(
	'bg' => '000000',
	'text' => '808080',
	'link' => 'ffffff',
	'border' => '272727',
	'url' => 'bfbfbf',
);

// this varies but the single page content width seems to be 607px max
$content_width = 600;

add_filter( 'body_class', '__return_empty_array', 1 );

// Sidebars
register_sidebar( array(
	'name'          => __('Bottom 1', 'hemingway'),
	'id'            => 'bottom-1',
	'before_widget' => '<div class="widget">',
	'after_widget'  => '</div>',
	'before_title'  => '<h2>',
	'after_title'   => '</h2>' ) );

register_sidebar( array(
	'name'          => __('Bottom 2', 'hemingway'),
	'id'            => 'bottom-2',
	'before_widget' => '<div class="widget">',
	'after_widget'  => '</div>',
	'before_title'  => '<h2>',
	'after_title'   => '</h2>' ) );

register_sidebar( array(
	'name'          => __('Bottom 3', 'hemingway'),
	'id'            => 'bottom-3',
	'before_widget' => '<div class="widget">',
	'after_widget'  => '</div>',
	'before_title'  => '<h2>',
	'after_title'   => '</h2>' ) );

// Load theme options page
require_once( get_stylesheet_directory() . '/theme-options.php' );

function hem_about_block( $content ) {
	// resize about page images to fit "about page" block size
	// adapted from wp-content/admin-plugins/readomattic.php image_resize()
	if ( preg_match_all( '|<img[^>]+>|i', $content, $images ) ) {
		foreach ( $images[0] as $image ) {

			if ( preg_match( "|width=['\"]?(\d+)['\" ]|i", $image, $width ) )
				$w = $width[1];
			if ( preg_match( "|height=['\"]?(\d+)['\" ]|i", $image, $height ) )
				$h = $height[1];

			if ( $w ) {
				list ( $new_w, $new_h ) = wp_constrain_dimensions( $w, $h, 300 ); // fix the max width possible
				if ( $new_w != $w ) {
					$new_image = str_replace( $width[0], 'width="'.$new_w.'" ', $image);
					$new_image = str_replace( $height[0], 'height="'.$new_h.'" ', $new_image);
					$content = str_replace( $image, $new_image, $content);
				}
			}
		}
	}
	return $content;
}

add_theme_support( 'automatic-feed-links' );

// Custom background
add_custom_background();

function hem_custom_background() {
	if ( '' != get_background_color() || '' != get_background_image() ) { ?>
	<style type="text/css">
		#header, #primary, #secondary { background: none; }
	</style>
	<?php }
}
add_action( 'wp_head', 'hem_custom_background' );

function hemingway_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID() ?>">
		<cite class="comment-author vcard comment-meta commentmetadata">
              		<span class="avatarspan"><?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?></span>
			<span class="author fn"><?php comment_author_link() ?></span>
			<span class="date"><?php comment_date('n.j.y') ?> / <?php comment_date('ga') ?></span>
		</cite>
		<div class="content">
			<?php if ($comment->comment_approved == '0') : ?>
			<em><?php _e('Your comment is awaiting moderation.', 'hemingway'); ?></em>
			<?php endif; ?>
			<?php comment_text() ?>
		</div>
		<div class="reply">
			<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
		</div>
		<div class="clear"></div>
	</div>
<?php
}