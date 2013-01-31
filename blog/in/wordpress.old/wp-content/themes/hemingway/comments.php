<?php // Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die (__('Please do not load this page directly. Thanks!', 'hemingway'));
	if ( post_password_required() ) {
	?>
	<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'hemingway'); ?></p>
	<?php
		return;
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