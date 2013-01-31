<?php
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p><?php _e('Enter your password to view comments.','benevolence'); ?></p>
<?php
	return;
}

if ( comments_open() || have_comments() ) : ?>
<b><?php comments_number(__('Leave a Comment','benevolence'), __('1 Comment','benevolence'), __('% Comments','benevolence')); ?> <?php if ( comments_open() ) _e('so far','benevolence'); ?></b>
<?php else : // If there are no comments yet ?>
<?php endif; ?>
<?php if ( comments_open() ) : ?><br />
<a href="#postcomment" title="<?php esc_attr_e( 'Leave a comment', 'benevolence' ); ?>"><?php _e('Leave a comment','benevolence'); ?></a>
<?php endif; ?>
<br /><br />
<a name="comments"></a>
<?php if ( $comments ) : ?>

<div class="commentlist">
<?php wp_list_comments(array('callback'=>'benevolence_callback', 'style'=>'div')); ?>
</div>
<div class="navigation">
	<div class="alignleft"><?php previous_comments_link() ?></div>
	<div class="alignright"><?php next_comments_link() ?></div>
</div>
<br />

<?php else : // If there are no comments yet ?>

<?php endif; ?>

<div class="right"><?php post_comments_feed_link( __( '<abbr title="Really Simple Syndication">RSS</abbr> feed for comments on this post.', 'benevolence' ) ); ?>
<?php if ( pings_open() ) : ?>
	<a href="<?php trackback_url() ?>" rel="trackback"><?php _e('TrackBack <abbr title="Uniform Resource Identifier">URI</abbr>','benevolence'); ?></a>
<?php endif; ?>
</div>

<br /><br />

<a name="postcomment"></a>

<?php if ( comments_open() ) : ?>

	<?php comment_form(); ?>

<?php else : // Comments are closed ?>

	<?php if ( ! is_page() ) : ?>
		<p class="nocomments"><?php _e( 'Comments are closed.', 'benevolence' ); ?></p>
	<?php endif; ?>

<?php endif; ?>
