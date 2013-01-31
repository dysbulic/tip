<?php
/**
 * @package WordPress
 * @subpackage Monotone
 */
if ( post_password_required() ) : ?>
	<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.', 'monotone' ); ?></p>
<?php
	return;
endif;
?>

<?php // You can start editing here -- including this comment! ?>

<?php if ( have_comments() ) : ?>
	<h3 id="comments">
		<?php
			printf( _n( 'One Response to &#8220;%2$s&#8221;', '%1$s Responses to &#8220;%2$s&#8221', get_comments_number(), 'monotone' ),
				number_format_i18n( get_comments_number() ),
				get_the_title()
			);
		?>
	</h3>

	<ol class="commentlist">
		<?php wp_list_comments( array( 'callback' => 'monotone_comment' ) ); ?>
	</ol>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<div class="comment-navigation clearfix">
			<div class="alignleft"><?php previous_comments_link( __( '&laquo; Older Comments', 'monotone' ) ); ?></div>
			<div class="alignright"><?php next_comments_link( __( 'Newer Comments &raquo;', 'monotone' ) ); ?></div>
		</div>
	<?php endif; // check for comment navigation ?>

<?php
	/* If comments are closed, let's leave a little note, shall we?
	 * But we don't want the note on pages or no comments at all.
	 */
	elseif ( ! comments_open() && ! is_page() && '0' != get_comments_number() ) :
?>
	<p class="nocomments"><?php _e( 'Comments are closed.', 'monotone' ); ?></p>
<?php endif; ?>

<?php comment_form(); ?>