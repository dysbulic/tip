<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.', 'jentri' ); ?></p>
<?php
	return;
}

if (have_comments()) : ?>
	<h3 id="comments"><?php comments_popup_link( __( 'Leave a Reply to', 'jentri' ), __( 'One Response to', 'jentri' ), __( '% Responses to', 'jentri') ); ?> &#8220;<?php the_title(); ?>&#8221;</h3> 

	<ol class="commentlist">
	<?php wp_list_comments(array('callback'=>'jentri_comment')); ?>
	</ol>
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link(); ?></div>
		<div class="alignright"><?php next_comments_link(); ?></div>
	</div>

	<?php if ( !comments_open() ) : ?> 
	<p class="nocomments"><?php _e( 'Comments are closed.', 'jentri' ); ?></p>
	<?php endif; ?>
<?php endif; ?>


<?php if ( comments_open() ) : ?>

<?php comment_form(); ?>

<?php endif; // if you delete this the sky will fall on your head ?>
