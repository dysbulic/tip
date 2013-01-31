<?php 
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p><?php _e('Enter your password to view comments.'); ?></p>
<?php
	return;
}
?>

<?php if ( have_comments() || comments_open() ) : ?>
<p>
<strong>
<?php comments_number(__('Leave a Comment'), __('1 Comment(s)'), __('% Comments')); ?>
</strong>
</p>
<?php endif; ?>

<?php if ( have_comments() ) : ?>

<div class="commentlist">
<ol>
<?php wp_list_comments(array('callback'=>'neo_sapien_05_comment', 'avatar_size'=>48)); ?>
</ol>
	<div class="comment-navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
</div>
<br />
	<?php if ( !comments_open() ) { ?>
		<p><?php _e('Sorry, the comment form is closed at this time.'); ?></p>
	<?php } ?>
<?php elseif ( comments_open() ) : ?>
<p><?php _e('No comments yet.'); ?></p>
<?php endif; ?>

<p>
<?php if ( have_comments() || comments_open() ) :
	post_comments_feed_link( __( 'Comments RSS' ) );
endif;
if ( pings_open() ) : ?>

<a href="<?php trackback_url() ?>" rel="trackback"><?php _e('TrackBack Identifier URI'); ?></a>

<?php endif; ?>
</p>

<?php if ( comments_open() ) : ?>

<?php comment_form(); ?>

<?php endif; ?>