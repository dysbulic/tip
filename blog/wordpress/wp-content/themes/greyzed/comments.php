<?php
/**
 * @package WordPress
 * @subpackage Greyzed
 */

// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
	<?php
		return;
	}
?>
<!-- You can start editing here. -->
<?php if ( have_comments() ) : ?>
	<div id="comments"><?php _e( 'Comments', 'greyzed' ); ?></div>
	<ol class="commentlist snap_preview">
	<?php wp_list_comments('avatar_size=40'); ?>
	</ol>
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
<?php else : // this is displayed if there are no comments so far ?>
	<?php if ( comments_open() ) : ?>
		<!-- If comments are open, but there are no comments. -->
	<?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments">Comments are closed.</p>
	<?php endif; ?>
<?php endif; ?>

<?php if ( comments_open() ) : ?>
<div id="respondcon">
	<?php comment_form(); ?>
</div><!-- #respondcon -->
<?php endif; // if you delete this the sky will fall on your head ?>