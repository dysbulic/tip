<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ( 'Please do not load this page directly. Thanks!' );
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.' ); ?></p>
<?php
	return;
}

if (have_comments()) : ?>
	<h3 id="comments"><?php comments_number( __( 'No Responses Yet', 'treba' ), __( 'One Response', 'treba' ), __( '% Responses', 'treba' ) );?> <?php printf( __( 'to &#8220;%s&#8221;', 'treba' ), the_title( '', '', false ) ); ?></h3> 

	<ol class="commentlist">
	<?php wp_list_comments( array( 'callback' => 'treba_comment' ) ); ?>
	</ol>
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link(); ?></div>
		<div class="alignright"><?php next_comments_link(); ?></div>
	</div>
	<br />

	<?php if ( !comments_open() ) : ?> 
		<p class="nocomments"><?php _e( 'Comments are closed.', 'treba' ); ?></p>
	<?php endif; ?>
<?php endif; ?>

<?php comment_form(); ?>