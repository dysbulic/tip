<?php // Do not delete these lines - borrowed directly from Kubrick
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
	?>
	<p class="nocomments"><?php _e("This post is password protected. Enter the password to view comments.", 'black-letterhead'); ?><p>
	<?php
	return;
}

if (have_comments()) :
?>
	<h3 id="comments"><?php comments_number(__('No Responses Yet'), __('One Response'), __('% Responses') );?> <?php _e('to'); ?> &#8220;<?php the_title(); ?>&#8221;</h3> 

	<ol class="commentlist">
	<?php wp_list_comments(array('callback'=>'black_letterhead_comments')); ?>
	</ol>

	<div class="navigation">
	<div class="alignleft"><?php previous_comments_link() ?></div>
	<div class="alignright"><?php next_comments_link() ?></div>
	</div>
<?php endif; ?>

<?php if ( comments_open() ) : ?>

	<?php comment_form(); ?>

<?php else: ?>

	<?php if ( ! is_page() ) : ?>
		<p class="nocomments"><?php _e( 'Comments are closed.', 'black-letterhead' ); ?></p>
	<?php endif; ?>

<?php endif; ?>