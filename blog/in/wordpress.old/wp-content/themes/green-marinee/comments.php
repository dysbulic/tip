<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.','greenmarinee'); ?></p>
<?php
	return;
}

if (have_comments()) : ?>
	<h3 class="reply"><?php comments_number(__('No Responses Yet','greenmarinee'),__('One Response','greenmarinee'),__('% Responses','greenmarinee'));?> <?php _e('to','greenmarinee'); ?> '<?php the_title(); ?>'</h3> 
<p class="comment_meta"><?php _e('Subscribe to comments with','greenmarinee'); ?> <?php post_comments_feed_link( __( '<abbr title="Really Simple Syndication">RSS</abbr>' ) ); ?>
<?php if ( pings_open() ) : ?>
	<?php _e('or','greenmarinee'); ?> <a href="<?php trackback_url() ?>" rel="trackback"><?php _e('TrackBack','greenmarinee');?></a> <?php _e('to','greenmarinee'); ?> '<?php the_title(); ?>'.
<?php endif; ?>
</p>
	<ol class="commentlist">

	<?php wp_list_comments(array('callback'=>'green_marinee_comment', 'avatar_size'=>48)); ?>
	</ol>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
	<br />
<?php endif; ?>


<?php if (comments_open()) : ?>

<?php comment_form(); ?>

<?php elseif ( have_comments() ) : ?>
	<p class="nocomments"><?php _e('Comments are closed.','greenmarinee'); ?></p>
<?php endif; // if you delete this the sky will fall on your head ?>
