<?php // Do not delete these lines
if ( 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) )
die ( 'Please do not load this page directly. Thanks!' );

if ( !empty( $post->post_password ) ) { // if there's a password
	if ( $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password ) {  // and it doesn't match the cookie
		?>

		<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.' ); ?><p>

		<?php
		return;
	}
}
?>

<div id="comments">

<?php if ( $comments ) : ?>
	<h3 class="reply"><?php printf( _n( 'One response to %2$s', '%1$s responses to %2$s', get_comments_number(), 'andrea' ), number_format_i18n( get_comments_number() ), '<em>' . get_the_title() . '</em>' ); ?></h3>

	<p class="comment-meta"><?php printf( __( 'Subscribe to comments with <a href="%s">RSS</a>.', 'andrea' ), get_post_comments_feed_link() ); ?></p>

	<ol class="commentlist">
		<?php wp_list_comments( array( 'callback' => 'andrea_comment' ) ); ?>
	</ol>

<?php else : // this is displayed if there are no comments so far ?>

	<?php if ( comments_open() ) : ?>
	<!-- If comments are open, but there are no comments. -->

	<?php else : // comments are closed ?>
	<!-- If comments are closed. -->
	<p class="nocomments"><?php _e( 'Comments are closed.', 'andrea' ); ?></p>

	<?php endif; ?>

<?php endif; ?>

</div><!-- /#comments -->

<?php if ( comments_open() ) : ?>

<?php comment_form(); ?>

<?php endif; // if you delete this the sky will fall on your head ?>