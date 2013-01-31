<?php
// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ( 'Please do not load this page directly. Thanks!' );

	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.', 'paperpunch' ); ?></p>
	<?php
		return;
	}
?>
<!-- You can start editing here. -->
<div id="comments">
<?php if ( have_comments() ) : ?>
	<div class="comment-number">
		<h4><?php comments_number( __( 'Leave a Comment', 'paperpunch' ), __( 'One Comment', 'paperpunch' ), __( '% Comments', 'paperpunch' )); ?></h4>
		<?php if ( 'open' == $post->comment_status ) : ?>
			<span><a href="#respond" title="<?php esc_attr_e( 'Leave a Comment', 'paperpunch' ); ?>"><?php _e( 'Leave a Comment', 'paperpunch' ); ?></a></span>
		<?php endif; ?>
	</div><!--end comment-number-->
	<?php if ( ! empty($comments_by_type['comment']) ) : ?>
		<ol class="commentlist">
			<?php wp_list_comments( 'type=comment&callback=custom_comment' ); ?>
		</ol>
	<?php endif; ?>
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link(__( '&laquo; Older Comments', 'paperpunch' )); ?></div>
		<div class="alignright"><?php next_comments_link(__( 'Newer Comments &raquo;', 'paperpunch' )); ?></div>
	</div>
	<?php if ( ! empty($comments_by_type['pings']) ) : ?>
		<h3 class="pinghead"><?php _e( 'Trackbacks', 'paperpunch' ); ?></h3>
		<ol class="pinglist">
			<?php wp_list_comments( 'type=pings&callback=list_pings' ); ?>
		</ol>
	<?php endif; ?>
	<?php if ( 'closed' == $post->comment_status ) : ?>
		<p class="note"><?php _e( 'Comments are closed.', 'paperpunch' ); ?></p>
	<?php endif; ?>
<?php endif; ?>
</div><!--end comments-->

<?php if ( 'open' == $post->comment_status ) : ?>

<?php comment_form(); ?>

<?php endif; // if you delete this the sky will fall on your head ?>
