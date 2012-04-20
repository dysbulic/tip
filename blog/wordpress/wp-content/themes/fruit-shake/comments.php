<?php
/**
 * @package WordPress
 * @subpackage Fruit Shake
 */

if ( ! function_exists( 'fruit_shake_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own fruit_shake_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Fruit Shake 0.4
 */
function fruit_shake_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer>
				<div class="comment-author vcard">
					<?php echo get_avatar( $comment, 40 ); ?>
					<cite class="fn"><?php comment_author_link(); ?></cite>
				</div><!-- .comment-author .vcard -->
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'fruit-shake' ); ?></em>
					<br />
				<?php endif; ?>

				<div class="comment-meta commentmetadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time pubdate datetime="<?php comment_time( 'c' ); ?>">
					<?php
						/* translators: 1: date, 2: time */
						printf( __( '%1$s at %2$s', 'fruit-shake' ), get_comment_date(),  get_comment_time() ); ?>
					</time> <span class="infin">&infin;</span></a>
					<?php edit_comment_link( __( '[Edit]', 'fruit-shake' ), ' <span class="edit-comment-link">', '</span>' );
					?>
				</div><!-- .comment-meta .commentmetadata -->
			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'fruit-shake' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'fruit-shake'), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif; // ends check for fruit_shake_comment()

?>

	<div id="comments">
	<?php if ( post_password_required() ) : ?>
		<div class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'fruit-shake' ); ?></div>
	</div><!-- .comments -->
	<?php return;
		endif;
	?>

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
		<h2 id="comments-title">
			<?php
			    printf( _n( 'One Response', '%1$s Responses', get_comments_number(), 'fruit-shake' ),
			        number_format_i18n( get_comments_number() ) );
			?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-above">
			<h1 class="section-heading"><?php _e( 'Comment navigation', 'fruit-shake' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'fruit-shake' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'fruit-shake' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

		<ol class="commentlist">
			<?php wp_list_comments( array( 'callback' => 'fruit_shake_comment' ) ); ?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below">
			<h1 class="section-heading"><?php _e( 'Comment navigation', 'fruit-shake' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'fruit-shake' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'fruit-shake' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

	<?php else : // this is displayed if there are no comments so far ?>

		<?php if ( comments_open() ) : // If comments are open, but there are no comments ?>

		<?php else : // or, if we don't have comments:

			/* If there are no comments and comments are closed,
			 * let's leave a little note, shall we?
			 * But only on posts! We don't want the note on pages.
			 */
			if ( ! comments_open() && ! is_page() ) :
			?>
			<p class="nocomments"><?php _e( 'Comments are closed.', 'fruit-shake' ); ?></p>
			<?php endif; // end ! comments_open() && ! is_page() ?>


		<?php endif; ?>

	<?php endif; ?>

	<?php comment_form( array( 'cancel_reply_link' => __( '[Cancel reply]' )  ) ); ?>

</div><!-- #comments -->