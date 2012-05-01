<?php
/**
 * @package WordPress
 * @subpackage Rounded
 */

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '4f402a',
	'link' => '97c000',
	'border' => '503f2b',
	'url' => '346ba4',
);

add_filter( 'body_class', '__return_empty_array', 1 );

if ( function_exists('register_sidebar') ) {
	$a = get_bloginfo('template_directory');

	register_sidebar(array(
        'before_widget' => '<!-- //start sideitem //--><br /><div id="%1$s" class="sideitem widget %2$s"><div class="sideitemtop"><img src="'.$a.'/img/stl.gif" alt="" width="15" height="15" class="corner" style="display: none" /></div><div class="sideitemcontent">',
        'after_widget' => '</div><div class="sideitembottom"><img src="'.$a.'/img/sbl.gif" alt="" width="15" height="15" class="corner" style="display: none" /></div></div><!-- //end sideitem //-->',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ));
}

function widget_rounded_links() {
	$a = get_bloginfo('template_directory');
 ?>
	<!-- //start sideitem //--><br /><div class="widget sideitem"><div class="sideitemtop"><img src="<?php echo $a; ?>/img/stl.gif" alt="" width="15" height="15" class="corner" style="display: none" /></div><div class="sideitemcontent">
    	<?php 
	wp_list_bookmarks(array(
	'title_before' => '<h2>', 
	'title_after' => '</h2>', 
   	));
	?>
	</div><div class="sideitembottom"><img src="<?php echo $a; ?>/img/sbl.gif" alt="" width="15" height="15" class="corner" style="display: none" /></div></div><!-- //end sideitem //-->
<?php }

unregister_widget('WP_Widget_Links');
wp_register_sidebar_widget('links', __('Links', 'widgets'), 'widget_rounded_links');

register_nav_menus( array(
	'primary' => __( 'Primary Navigation' ),
) );

function rounded_page_menu() { // fallback for primary navigation ?>
<ul>
	<?php wp_list_pages( 'title_li=&depth=1' ); ?>
</ul>

<?php }

add_theme_support( 'automatic-feed-links' );

// Custom background
add_custom_background();

function rounded_custom_background() {
	if ( '' != get_background_color() && '' == get_background_image() ) { ?>
	<style type="text/css">
		body { background-image: none; }
	</style>
	<?php }
}
add_action( 'wp_head', 'rounded_custom_background' );

function rounded_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>

<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID() ?>">
	<div class="comment-author vcard">
		<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
		<cite class="fn"><?php comment_author_link() ?></cite> <?php _e( 'Says:' ); ?>
	</div>
	<?php if ($comment->comment_approved == '0') : ?>
	<em><?php _e( 'Your comment is awaiting moderation.' ); ?></em>
	<?php endif; ?>

	<span class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>" title=""><?php comment_date() ?> at <?php comment_time() ?></a> <?php edit_comment_link('e','',''); ?></span>

	<?php comment_text() ?>

	<div class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	</div>
<?php
}
