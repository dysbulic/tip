<?php
/**
 * @package WordPress
 * @subpackage Spring Loaded
 */

add_filter( 'body_class', '__return_empty_array', 1 );

function widget_springloaded_search() {
?>
<div class="side-widget"><div class="side-widget-wrap">
  <h3><?php _e('Search');?></h3>  
	<form method="get" action="<?php echo home_url( '/' ); ?>">
<p><input type="text" name="s" onblur="this.value=(this.value=='') ? '<?php esc_attr_e( 'Search this Blog', 'springloaded' ); ?>' : this.value;" onfocus="this.value=(this.value=='<?php esc_attr_e( 'Search this Blog', 'springloaded' ); ?>') ? '' : this.value;" value="<?php esc_attr_e( 'Search this Blog', 'springloaded' ); ?>" id="s" /> <input type="submit" name="submit" value="Search" id="some_name"></p>
	</form>
</div></div>
<?php
}

unregister_widget('WP_Widget_Search');
wp_register_sidebar_widget('search', __('Search'), 'widget_springloaded_search');

register_sidebar( array(
	'before_widget' => '<div class="side-widget %2$s"><div class="side-widget-wrap">',
	'after_widget' => '</div></div>',
	'before_title' => '<h3>',
	'after_title' => '</h3>',
) );

$themecolors = array(
	'bg' => 'ffffff',
	'border' => 'dee0bf',
	'text' => '000000',
	'link' => '9c4617',
	'url' => 'cf542e',
);
$content_width = 570; // pixels

add_theme_support( 'automatic-feed-links' );

register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'springloaded' ),
) );

function springloaded_page_menu() { // fallback for primary navigation ?>
<ul id="navigation">
	<li class="page_item <?php if( is_front_page() ) { echo 'current_page_item'; } ?>"><a href="<?php bloginfo( 'url' ); ?>"><?php _e( 'Home', 'springloaded' ); ?></a></li>
	<?php wp_list_pages( 'title_li=&depth=1' ); ?>
</ul>
<?php }

function springloaded_comment($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
			<div class="comment-gravatar">
				<?php echo get_avatar( $comment, 30 ); ?>
			</div>
			<div class="comment-body">
				<div class="comment-head">
					<p><?php printf(__('Posted by %1$s on <a href="#comment-%2$s">%3$s at %4$s</a>'), get_comment_author_link(), get_comment_ID(), get_comment_date(), get_comment_time()); ?><?php edit_comment_link('edit','&nbsp;&nbsp;',''); ?></p>
					<?php if ($comment->comment_approved == '0') : ?>
						<p><em><?php _e('Your comment is awaiting moderation.'); ?></em></p>
					<?php endif; ?>
				</div>
				<div class="comment-text">
					<?php comment_text() ?>
					<p><?php echo comment_reply_link(array('depth' => $depth, 'max_depth' => $args['max_depth'], 'before' => '')) ?></p>
				</div>
			</div>
<?php }