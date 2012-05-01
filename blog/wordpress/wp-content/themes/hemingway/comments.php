<?php // Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die (__('Please do not load this page directly. Thanks!', 'hemingway'));
	if ( post_password_required() ) {
	?>
	<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'hemingway'); ?></p>
	<?php
		return;
	}

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

if ( comments_open() ) {
	// Comments are open ?>
	<div class="comment-head">
		<h2><?php comments_number(__('No comments yet', 'hemingway'), __('1 Comment', 'hemingway'), __('% Comments', 'hemingway')); ?></h2>
		<span class="details"><a href="#comment-form"><?php _e('Jump to comment form', 'hemingway'); ?></a> | <a href="<?php echo get_post_comments_feed_link(); ?>"><?php _e('comment rss', 'hemingway'); ?> <?php _e('[?]', 'hemingway'); ?></a> <?php if ( pings_open() ): ?>| <a href="<?php trackback_url(true); ?>"><?php _e('trackback uri', 'hemingway'); ?></a> <a href="#what-is-trackback" class="help"><?php _e('[?]', 'hemingway'); ?></a><?php endif; ?></span>
	</div>
<?php } elseif ( have_comments() && !comments_open() ) {
	// Neither Comments, nor Pings are open ?>
	<div class="comment-head">
		<h2><?php _e('Comments are closed','hemingway'); ?></h2>
		<span class="details"><?php _e('Comments are currently closed on this entry.', 'hemingway'); ?></span>
	</div>
<?php }

if ( have_comments() ) : ?>
	<ol id="comments" class="commentlist">
	<?php wp_list_comments(array('callback'=>'hemingway_comment', 'style'=>'ol')); ?>
	</ol>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
<?php endif; ?>

<?php comment_form(); ?>