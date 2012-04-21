<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p><?php _e( 'This post is password protected. Enter the password to view comments.', 'tarski' ); ?></p>
<?php
	return;
}
?>

<div id="comments" class="commentlist">

<?php if ( have_comments() ) : ?>

	<?php if ( comments_open() ) : ?>

		<div id="comments-meta">
			<h2 class="comments-title"><?php comments_number( __( 'Leave a comment', 'tarski' ), __( '1 comment', 'tarski' ), __( '% comments', 'tarski' ) ); ?></h2>
			<p class="comments-feed"><?php post_comments_feed_link( __( 'Comments feed for this article', 'tarski' ) ); ?></p>
		</div>

	<?php else : // comments are closed ?>

		<div id="comments-meta">
			<h2 class="comments-title"><?php comments_number( __( 'Leave a comment', 'tarski' ), __( '1 comment', 'tarski' ), __( '% comments', 'tarski' ) ); ?></h2>
		</div>

	<?php endif; ?>

	<?php wp_list_comments( array( 'callback'=>'tarski_comment', 'end-callback'=>'tarski_comment_end', 'style'=>'li' ) ); ?>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link(); ?></div>
		<div class="alignright"><?php next_comments_link(); ?></div>
	</div>

	<br />

<?php else : // this is displayed if there are no comments so far ?>

	<?php if ( comments_open() ) : ?>

		<div id="comments-meta">
			<h2 class="comments-title"><?php comments_number( __( 'Leave a comment', 'tarski' ), __( '1 comment', 'tarski' ), __( '% comments', 'tarski' ) ); ?></h2>
			<p class="comments-feed"><a title="<?php esc_attr_e( 'Subscribe to this article&#8217;s comments feed', 'tarski' ); ?>" href="<?php the_permalink(); ?>feed/"><?php _e( 'Comments feed for this article', 'tarski' ); ?></a></p>		
		</div>

	<?php else : // comments are closed ?>

	<?php endif; // end comments_open() check ?>

<?php endif; // end have_comments() check ?>

<?php if ( comments_open() ) : ?>
	
	<?php comment_form(); ?>

<?php endif; // if you delete this the sky will fall on your head / O RLY / YA RLY ?>

</div><!-- #comments -->