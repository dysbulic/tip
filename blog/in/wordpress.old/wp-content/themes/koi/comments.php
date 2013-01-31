<?php
/**
 * @package WordPress
 * @subpackage Koi
 */

// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.', 'ndesignthemes' ); ?></p>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<div id="comments">

<?php if ( have_comments() ) : ?>

	<h3><?php comments_number( __('Leave a Comment', 'ndesignthemes'), __('One Comment', 'ndesignthemes'), __('% Comments', 'ndesignthemes') );?> <em>(<a href="#respond"><?php _e('+add yours?', 'ndesignthemes'); ?></a>)</em></h3>

	<div class="comment-nav">
		<span class="previous"><?php previous_comments_link( __('Older', 'ndesignthemes') ); ?></span>
		<span class="next"><?php next_comments_link( __('Newer', 'ndesignthemes') ); ?></span>
	</div>

	<ol class="commentlist">
	<?php wp_list_comments( 'callback=koi_comment' ); ?>
	</ol>

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ( comments_open() ) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments"><?php _e('Comments are closed', 'ndesignthemes'); ?>.</p>

	<?php endif; ?>
<?php endif; ?>

<?php if ( comments_open() ) : ?>

<?php comment_form(); ?>

<?php endif; // if you delete this the sky will fall on your head ?>

</div><!-- /comments -->