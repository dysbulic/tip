<?php
/**
 * @package Shaan
 */
?>

<div id="comments">
<?php if ( post_password_required() ) : ?>
				<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'shaan' ); ?></p>
			</div><!-- #comments -->
<?php
		/*
		 * Stop the rest of comments.php from being processed,
		 * but don't kill the script entirely -- we still have
		 * to fully load the template.
		 */
		return;
	endif;
?>

<?php if ( have_comments() ) : ?>
	<h3 id="comments-title"><?php comments_number(
		__( 'No Comments', 'shaan' ),
		__( '1 Comment',   'shaan' ),
		__( '% Comments',  'shaan' )
	); ?></h3>

	<ol class="commentlist"><?php
		wp_list_comments( array(
			'callback' => 'shaan_comment',
			'type'     => 'comment'
	) ); ?></ol>

	<?php $comments_by_type = &separate_comments( $comments ); ?>
	<?php if ( ! empty( $comments_by_type['pings'] ) ) : ?>
		<h4><?php _e( 'Trackbacks', 'shaan' ); ?></h4>
		<ol class="pingslist">
			<?php wp_list_comments( array( 'callback' => 'shaan_comment', 'type' => 'pings' ) ); ?>
		</ol>
	<?php endif; ?>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
		<nav id="comment-nav-below" class="paged-navigation">
			<h1 class="assistive-text"><?php _e( 'Comment navigation', 'shaan' ); ?></h1>
			<div class="nav-older"><?php previous_comments_link( __( '&larr; Older Comments', 'shaan' ) ); ?></div>
			<div class="nav-newer"><?php next_comments_link( __( 'Newer Comments &rarr;', 'shaan' ) ); ?></div>
		</nav>
	<?php endif; ?>


<?php else : // or, if we don't have comments:

	/* If there are no comments and comments are closed,
	 * let's leave a little note, shall we?
	 */
	if ( ! comments_open() ) :
?>
	<p class="nocomments"><?php _e( 'Comments are closed.', 'shaan' ); ?></p>
<?php endif; ?>

<?php endif; ?>

<?php comment_form(); ?>

</div><!-- #comments -->