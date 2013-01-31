<?php
/**
 * @package WordPress
 * @subpackage Notepad
 */
// Do not delete these lines
	if ( !empty( $_SERVER['SCRIPT_FILENAME'] ) && 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) )
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<?php if ( have_comments() ) : ?>
	<h4 id="comments">
		<?php comments_number(__( 'No Comments','notepad-theme' ), __( 'One Comment','notepad-theme' ), __( '% Comments','notepad-theme' ) );?>
		<em>(<a href="#respond"><?php _e( '+add yours?','notepad-theme' ) ?></a>)</em>
	</h4>

	<div class="comment-nav">
		<span class="previous"><?php previous_comments_link(__( 'Older','notepad-theme' )) ?></span>
		<span class="next"><?php next_comments_link(__( 'Newer','notepad-theme' )) ?></span>
	</div>

	<ol class="commentlist snap_preview">
		<?php wp_list_comments( 'callback=mytheme_comment' ); ?>
	</ol>

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ( comments_open() ) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
	<?php endif; ?>
<?php endif; ?>


<?php if ( comments_open() ) : ?>

<?php comment_form(); ?>

<?php else : // if comments are closed ?>

		<p class="nocomments"><?php _e( 'Comments are closed.', 'notepad-theme' ); ?></p>

<?php endif; // if you delete this the sky will fall on your head ?>