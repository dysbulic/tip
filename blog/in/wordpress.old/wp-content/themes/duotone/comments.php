<?php // Do not delete these lines

if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.' ); ?></p>
<?php
	return;
}

if (have_comments()) : ?>
	<h3 id="comments"><?php comments_number( __( 'Leave a Reply' ), __( 'One Response' ), __( '% Responses' ) ); ?> to &#8220;<?php the_title(); ?>&#8221;</h3>

	<ol class="commentlist">
	<?php wp_list_comments( array( 'callback'=>'duotone_comment' ) ); ?>
	</ol>
	<div class="comment-navigation">
		<div class="alignleft"><?php previous_comments_link(); ?></div>
		<div class="alignright"><?php next_comments_link(); ?></div>
	</div>
	
	<br />

	<?php if ( !comments_open() ) : ?>
		<p class="nocomments"><?php _e( 'Comments are closed.' ); ?></p>
	<?php endif; ?>
<?php endif; ?>


<?php if ( comments_open() ) : ?>

<?php comment_form(); ?>

<?php endif; // if you delete this the sky will fall on your head ?>