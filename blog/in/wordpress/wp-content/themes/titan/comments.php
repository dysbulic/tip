<?php
// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ( 'Please do not load this page directly. Thanks!' );

	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.', 'titan' ); ?></p>
	<?php
		return;
	} ?>
<!-- You can start editing here. -->
<div id="comments">
<?php if ( have_comments() ) : ?>
	<div class="comment-number">
		<span><?php comments_number( __( 'Leave a Comment', 'titan' ), __( 'One Comment', 'titan' ), __( '% Comments', 'titan' ) ); ?></span>
		<?php if ( 'open' == $post->comment_status) : ?>
			<a id="leavecomment" href="#respond" title="<?php esc_attr_e( 'Leave One &rarr;', 'titan' ); ?>"></a>
		<?php endif; ?>
	</div><!--end comment-number-->
	<ol class="commentlist">
		<?php wp_list_comments( 'type=comment&callback=custom_comment' ); ?>
	</ol>
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link( __( '&laquo; Older Comments', 'titan' ) ); ?></div>
		<div class="alignright"><?php next_comments_link( __( 'Newer Comments &raquo;', 'titan' ) ); ?></div>
	</div>
	<?php if ( !empty($comments_by_type['pings']) ) : ?>
		<h3 class="pinghead"><?php _e( 'Trackbacks &amp; Pingbacks', 'titan' ); ?></h3>
		<ol class="pinglist">
			<?php wp_list_comments( 'type=pings&callback=list_pings' ); ?>
		</ol>
	<?php endif; ?>
	<?php if ( 'closed' == $post->comment_status ) : ?>
		<p class="note"><?php _e( 'Comments are closed.', 'titan' ); ?></p>
	<?php endif; ?>
	<?php else : // this is displayed if there are no comments so far ?>
	<?php if ( 'open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->
		<div class="comment-number">
			<span><?php _e( 'Leave a Comment', 'titan' ); ?></span>
		</div>
	 <?php else : // comments are closed ?>
		<?php if ( !is_page() ) : ?>
			<p class="note"><?php _e( 'Comments are closed.', 'titan' ); ?></p>
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>
</div><!--end comments-->

<?php comment_form(); ?>
