<?php if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p><?php _e('Enter your password to view comments.'); ?></p>
<?php
	return;
}
?>
<?php if ( have_comments() || comments_open() ) : ?>
	<h2 id="comments"><?php comments_number(__('Leave a Comment'), __('1 Comment'), __('% Comments')); ?>
<?php endif; ?>
<?php if ( comments_open() ) : ?>
	<a href="#postcomment" title="<?php _e("Leave a comment"); ?>">&raquo;</a>
<?php endif; ?>
</h2>

<p>
<?php if ( comments_open() ) : ?>
<?php post_comments_feed_link( __( '<abbr title="Really Simple Syndication">RSS</abbr> feed for comments on this post.' ) ); ?>
<?php endif; ?>

<?php if ( pings_open() ) : ?>
<a href="<?php trackback_url() ?>" rel="trackback"><?php _e('TrackBack <abbr title="Uniform Resource Identifier">URI</abbr>'); ?></a>
<?php endif; ?>
</p>

<?php if ( have_comments() ) : ?>
<ol id="commentlist">
<?php wp_list_comments(array('callback'=>'pool_comment', 'avatar_size'=>48)); ?>
</ol>
<div class="navigation">
	<div class="alignleft"><?php previous_comments_link() ?></div>
	<div class="alignright"><?php next_comments_link() ?></div>
</div>
<br />

<?php if ( !comments_open() ) : ?>
<p><?php _e('Sorry, the comment form is closed at this time.'); ?></p>
<?php endif; ?>
<?php endif; ?>

<?php comment_form(); ?>