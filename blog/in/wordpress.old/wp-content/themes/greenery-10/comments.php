<?php // Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');
	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e('This post is password protected.'); ?></p>
	<?php
		return;
	}

if (have_comments()) : ?>

	<h2 id="comments">
		<?php comments_number(__('Comments'), __('1 Response'), __('% Responses')); ?> <?php _e('so far'); ?>
		<?php if ( comments_open() ) : ?>
			<a href="#respond" title="<?php esc_attr_e( 'Jump to the comments form' ); ?>">&raquo;</a>
		<?php endif; ?>
	</h2>
	
	<ol id='commentlist' class="commentlist">
	<!-- Comment Counter -->
	<?php 
	global $relax_comment_count;
	$relax_comment_count=isset($commentcount)? $commentcount+0 : 1; 
	
	wp_list_comments(array('callback'=>'greenery_10_comment', 'avatar_size'=>48)); 
	?>
	</ol>
	
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
	<br />
	
	<p class="small">
		<?php post_comments_feed_link( __( 'Comment <abbr title="Really Simple Syndication">RSS</abbr>' ) ); ?>
		<?php if ( pings_open() ) : ?>
			&#183; <a href="<?php trackback_url() ?>" rel="trackback"><?php _e('TrackBack <abbr title="Uniform Resource Identifier">URI</abbr>'); ?></a>
		<?php endif; ?>
	</p>
<?php endif; ?>

<?php if (comments_open()) : ?>

<?php comment_form(); ?>

<?php elseif ( have_comments() ) : ?>
	<p><?php _e('Comments are closed.'); ?></p>
<?php endif; // if you delete this the sky will fall on your head ?>
