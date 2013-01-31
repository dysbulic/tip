<?php
/**
 * @package WordPress
 * @subpackage The Journalist
 */

$content_width = 700;

if ( function_exists('register_sidebar') )
		register_sidebar(array(
				'before_widget' => '',
				'after_widget' => '',
				'before_title' => '<h3>',
				'after_title' => '</h3>',
		));

$themecolors = array(
	'bg' => 'ffffff',
	'border' => 'f3f3f3',
	'text' => '1c1c1c',
	'link' => '004276',
	'url' => 'cc0000',
);

add_theme_support( 'automatic-feed-links' );

function tj_comment_class( $classname='' ) {
	global $comment, $post;

	$c = array();
	if ($classname)
		$c[] = $classname;

	// Collects the comment type (comment, trackback),
	$c[] = $comment->comment_type;

	// If the comment author has an id (registered), then print the log in name
	if ( $comment->user_id > 0 ) {
		$user = get_userdata($comment->user_id);

		// For all registered users, 'byuser'; to specificy the registered user, 'commentauthor+[log in name]'
		$c[] = "byuser comment-author-" . sanitize_title_with_dashes(strtolower($user->user_login));
		// For comment authors who are the author of the post
		if ( $comment->user_id === $post->post_author )
			$c[] = 'bypostauthor';
	}

	// Separates classes with a single space, collates classes for comment LI
	return join(' ', apply_filters('comment_class', $c));
}

function journalist_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<li id="comment-<?php comment_ID() ?>" <?php comment_class(tj_comment_class()); ?>>
	<div id="div-comment-<?php comment_ID() ?>">
	<div class="comment_mod">
	<?php if ($comment->comment_approved == '0') : ?>
	<em><?php _e('Your comment is awaiting moderation.', 'journalist'); ?></em>
	<?php endif; ?>
	</div>
	
	<div class="comment_text">
	<?php comment_text() ?>
	</div>
	
	<div class="comment_author vcard">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
	<p><strong class="fn"><?php comment_author_link() ?></strong></p>
	<p><small>
		<?php printf(__('%s at %s','journalist'), get_comment_date(),
		    '<a href="#comment-'.get_comment_ID().'">'.get_comment_time().'</a>'); ?>
		<?php edit_comment_link(__('Edit','journalist'), ' ', ''); ?>
	</small></p>
	</div>
	<div class="clear"></div>
	
	<div class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	</div>
<?php
}