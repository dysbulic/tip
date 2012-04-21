<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) { ?>
<p><?php _e('This post is password protected. Enter the password to view comments.','almost-spring'); ?><p>
<?php 
return;
}
	
if ( have_comments() ) : ?>
<div><h2 id="comments">
<?php comments_number(__('Comments','almost-spring'), __('1 Comment','almost-spring'), __('% Comments','almost-spring'));?>
<?php if ( comments_open() ) : ?>
	<a href="#postcomment" title="<?php esc_attr_e( 'Jump to the comments form', 'almost-spring' ); ?>">&raquo;</a>
<?php endif; ?>
</h2>
 
<ol class="commentlist" id="commentlist">
<?php wp_list_comments(array('callback'=>'almost_spring_callback')); ?>
</ol>
	
	<p class="small">
	<?php post_comments_feed_link( __( '<abbr title="Really Simple Syndication">RSS</abbr> feed for comments on this post','almost-spring' ) ); ?>
	<?php if ( pings_open() ) : ?>
	&#183; <a href="<?php trackback_url() ?>" rel="trackback"><?php _e('TrackBack <abbr title="Uniform Resource Identifier">URI</abbr>','almost-spring'); ?></a>
	<?php endif; ?>
	</p>

<div class="navigation">
<div class="alignleft"><?php previous_comments_link() ?></div>
<div class="alignright"><?php next_comments_link() ?></div>
</div>
</div>
<?php else : // this is displayed if there are no comments so far ?>
	<?php if (comments_open()) :
		// If comments are open, but there are no comments.
	else : // comments are closed
	endif;
?>

<?php
endif;
?>

<?php if ( comments_open() ) : ?>
	<?php comment_form(); ?>
<?php else : ?>
	<?php if ( ! is_page() ) : ?>
	<p><?php _e( 'Comments are closed.', 'almost-spring' ); ?></p>
	<?php endif; ?>
<?php endif; // if you delete this the sky will fall on your head ?>