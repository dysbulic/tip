<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.', 'sunburn' ); ?><p>
<?php
	return;
}
?>

<?php if ( have_comments() || comments_open() ) : ?>
	<h3 id="comments"><?php comments_number( __( 'No Responses', 'sunburn'), __( 'One Response', 'sunburn' ), __( '% Responses', 'sunburn' ) ); ?> <?php printf( __( 'to &#8220;%s&#8221;', 'sunburn' ), the_title( '', '', false ) ); ?></h3>

<?php endif; ?>

<?php if (have_comments()) : ?>
	<ol class="commentlist" id='commentlist'>
	<?php wp_list_comments(array('callback'=>'sunburn_comment', 'avatar_size'=>48)); ?>
	</ol>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
	<br />

	<?php if (!comments_open()) : ?>
	<p><?php _e( 'Comments are closed.', 'sunburn' ); ?></p>
	<?php endif; ?>
<?php endif; ?>

<?php comment_form(); ?>
