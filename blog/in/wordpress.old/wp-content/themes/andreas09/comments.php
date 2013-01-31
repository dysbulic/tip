<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) { ?>
	<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.','andreas09'); ?><p>
<?php
	return;
}

if ( have_comments() ) : ?>
<h3 id="comments"><?php comments_number(__('No Responses Yet','andreas09'), __('One Response','andreas09'), __('% Responses','andreas09') );?> <?php _e('to','andreas09'); ?> &#8220;<?php the_title(); ?>&#8221;</h3> 
 
<ol class="commentlist">
<?php wp_list_comments(array('callback'=>'andreas09_callback')); ?>
</ol>
 
<div class="navigation">
<div class="alignleft"><?php previous_comments_link() ?></div>
<div class="alignright"><?php next_comments_link() ?></div>
</div>
<?php else : // this is displayed if there are no comments so far ?>
	<?php if (comments_open()) :
		// If comments are open, but there are no comments.
	else : // comments are closed
	endif;
endif;
?>

<?php if ( comments_open() ) : ?>
	<?php comment_form(); ?>
<?php else: ?>
	<?php if ( ! is_page() ) : ?>
	<p><?php _e( 'Sorry, the comment form is closed at this time.', 'andreas09' ); ?></p>
	<?php endif; ?>
<?php endif; ?>
