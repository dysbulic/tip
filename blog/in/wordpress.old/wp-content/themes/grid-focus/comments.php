<?php
/**
 * @package WordPress
 * @subpackage Grid_Focus
 */

// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.', 'grid-focus' ); ?></p>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->
<?php if ( have_comments() ) : ?>
	
	<h3 id="comments"><?php comments_number( 'No Responses Yet', 'One Response', '% Responses' ); ?> <?php if ('open' != $post->comment_status) : ?>
	<?php _e( '- Comments are closed.', 'grid-focus' ); ?>
<?php endif; ?></h3>
	<ol class="commentlist">
	<?php wp_list_comments(); ?>
	</ol>
	<div class="navigation">
		<div class="alignleft"><span><?php previous_comments_link() ?></span></div>
		<div class="alignright"><span><?php next_comments_link() ?></span></div>
	</div>
<?php endif; ?>
	
<?php if ('open' == $post->comment_status) : ?>

<?php comment_form(); ?>

<?php endif; // if you delete this the sky will fall on your head ?>
