<?php
/**
 * The template for displaying comments.
 *
 * @package WordPress
 * @subpackage Liquorice
 */
?>

<div id="comments">
	<?php if ( post_password_required() ) : ?>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'liquorice' ); ?></p>
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
		<h3 id="comments-title"><?php printf( _n( 'One Response', '%1$s Responses', get_comments_number(), 'liquorice' ), number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' ); ?>
			<?php if ( comments_open() ) : ?>
				<a href="#postcomment" title="<?php _e( 'Leave a comment', 'liquorice' ); ?>"><?php _e( '&raquo;', 'liquorice' ); ?></a>
			<?php endif; ?>
		</h3>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<div class="navigation">
			<div class="nav-previous"><span class="meta-nav"><?php _e( '&larr;', 'liquorice' );?></span> <?php previous_comments_link( __( 'Older Comments', 'liquorice' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments', 'liquorice' ) ); ?> <span class="meta-nav"><?php _e( '&rarr;', 'liquorice' );?></span></div>
		</div> <!-- .navigation -->
		<?php endif; // check for comment navigation ?>

		<ol class="commentlist">
			<?php wp_list_comments( array( 'callback' => 'liquorice_comment' ) ); ?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<div class="navigation">
			<div class="nav-previous"><span class="meta-nav"><?php _e( '&larr;', 'liquorice' );?></span> <?php previous_comments_link( __( 'Older Comments', 'liquorice' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments', 'liquorice' ) ); ?> <span class="meta-nav"><?php _e( '&rarr;', 'liquorice' );?></span></div>
		</div><!-- .navigation -->
		<?php endif; // check for comment navigation ?>

	<?php else : // or, if we don't have comments:

		/* If there are no comments and comments are closed,
		 * let's leave a little note, shall we?
		 * But only on posts! We don't really need the note on pages.
		 */
		if ( ! comments_open() && ! is_page() ) :
		?>
		<p class="nocomments"><?php _e( 'Comments are closed.', 'liquorice' ); ?></p>
		<?php endif; // end ! comments_open() ?>

	<?php endif; // end have_comments() ?>

	<?php comment_form(); ?>

</div><!-- #comments -->