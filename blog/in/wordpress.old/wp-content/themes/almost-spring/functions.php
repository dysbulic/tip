<?php
/**
 * @package WordPress
 * @subpackage Almost Spring
 */

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '000000',
	'link' => 'E58712',
	'border' => '9BBB38',
	'url' => 'F0B56D'
	);

$content_width = 480;

add_filter( 'body_class', '__return_empty_array', 1 );

function widget_almostspring_search() {
?>
	<li>
		<h2><?php _e('Search'); ?></h2>
		<?php get_search_form(); ?>
	</li>
<?php
}

register_sidebar(array(
	'before_title' => '<div><h2>',
	'after_title' => "</h2></div>\n",
));

function almostspring_widgets_init() {
	unregister_widget('WP_Widget_Search');
	wp_register_sidebar_widget('search', __('Search'), 'widget_almostspring_search');
}
add_action('widgets_init', 'almostspring_widgets_init');

add_theme_support( 'automatic-feed-links' );

// Custom background
add_custom_background();

function almostspring_custom_background() {
	if ( '' != get_background_color() && '' == get_background_image() ) { ?>
	<style type="text/css">
		body { background-image: none; }
	</style>
	<?php }
}
add_action( 'wp_head', 'almostspring_custom_background' );

function almost_spring_callback($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
	<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<div class="comment-author vcard">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
	<h3 class="commenttitle"><cite class="fn"><?php comment_author_link(); ?></cite> <span class="says"><?php _e('said','almost-spring'); ?></span></h3>
	</div>
<?php if ($comment->comment_approved == '0') : ?>
	<em><?php _e('Your comment is awaiting moderation.','almost-spring') ?></em>
	<br />
<?php endif; ?>
	
	<small class="comment-meta commentmetadata commentmeta">
	<?php comment_date(); ?> @ 
	<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>" 
	title="<?php esc_attr_e( 'Permanent link to this comment', 'almost-spring' ); ?>"><?php comment_time(); ?></a>
	<?php edit_comment_link(__('Edit','almost-spring'), ' &#183; ', ''); ?></small>

	<?php comment_text(); ?>

	<div class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => 'comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
<?php 
}