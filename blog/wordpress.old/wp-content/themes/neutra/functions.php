<?php
/**
 * @package WordPress
 * @subpackage Neutra
 */

$themecolors = array(
	'bg' => 'ffffff',
	'border' => 'f1f1f1',
	'text' => '555555',
	'link' => '059bff',
	'url' => 'e5f2bf',
);

$content_width = 575; // pixels

register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'neutra' ),
) );

add_theme_support( 'automatic-feed-links' );

add_custom_background();

function neutra_page_menu() { ?>
	<ul>
		<?php wp_list_pages( 'title_li=' ); ?>
	</ul>
<?php }

if ( function_exists( 'register_sidebar' ) ) {
	register_sidebar(
		array(
			'name' => __( 'Sidebar' ),
			'id' => 'neutra_sidebar'
		)
	);
}

// Comments
function neutra_list_comments( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<div class="comment-avatar">
			<div class="avatar-wrap"><?php echo get_avatar( $comment, 80 ); ?></div><!-- /avatar -->
		</div>
		<div class="comment-body">
			<div class="author"><?php comment_author_link(); ?></div>
			<div class="comment-text"><?php comment_text(); ?></div>
			<div class="comment-meta"><a href="<?php echo get_permalink(); ?>#comment-<?php comment_ID(); ?>"><?php _e( 'Permalink', 'neutra' ); ?></a><?php echo comment_reply_link( array( 'depth' => $depth, 'max_depth' => $args['max_depth'], 'before' => ', ' ) ); ?><?php edit_comment_link( __( 'Edit' ), ', ' ); ?></div>
			<div class="date"><span><?php comment_date(); ?> <a href="#comment-<?php comment_ID(); ?>"><?php comment_time(); ?></a></span></div>
		</div>
<?php }

// Pingbacks/trackbacks
function neutra_list_pings( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
?>
	<li id="comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?>
<?php }

function neutra_comment_form_defaults( $args ) {
	$args['title_reply'] = '<span>' . $args['title_reply'] . '</span>';
	return $args;
}
add_filter( 'comment_form_defaults', 'neutra_comment_form_defaults', 30 );
