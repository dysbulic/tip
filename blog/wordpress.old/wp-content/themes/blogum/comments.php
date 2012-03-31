<?php
/**
 * @package WordPress
 * @subpackage Blogum
 */
?>
<div id="comments">
	<?php if ( post_password_required() ) : ?>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'blogum' ); ?></p>
	</div><!-- #comments -->
	<?php
		/* Stop the rest of comments.php from being processed,
		 * but don't kill the script entirely -- we still have
		 * to fully load the template.
		 */
		return;
	endif;
	?>

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
		<div class="comments-heading clear">
			<div class="comment-qty">
				<?php
					printf( _n( '%1$s comment', '%1$s comments', get_comments_number(), 'blogum' ),
					number_format_i18n( get_comments_number() ) );
					?>
			</div>
			<nav id="comment-nav-above">
				<span class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'blogum' ) ); ?></span>
				<span class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'blogum' ) ); ?></span>
			</nav>
		</div><!-- .comments-heading -->

		<ol class="commentlist">
			<?php
				wp_list_comments( array( 'callback' => 'blogum_comment' ) );
			?>
		</ol><!-- .commentlist -->

	<?php
		/* If there are no comments and comments are closed, let's leave a little note, shall we?
		 * But we don't want the note on pages or post types that do not support comments.
		 */
		elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="nocomments"><?php _e( 'Comments are closed.', 'blogum' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>

</div><!-- #comments -->