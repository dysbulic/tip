<?php
/**
 * The template for displaying comments.
 *
 * @package WordPress
 * @subpackage Mystique
 */
?>

<div id="post-extra-content" class="clear-block">
	<?php if ( post_password_required() ) : ?>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'mystique' ); ?></p>
</div><!-- #post-extra-content -->
	<?php
		/* Stop the rest of comments.php from being processed,
		 * but don't kill the script entirely -- we still have
		 * to fully load the template.
		 */
		return;
	endif;
	?>

	<?php
		/* Count the totals */
		$numPingBacks	= 0;
		$numComments	= 0;

		/* Loop throught comments to count these totals */
		foreach ( $comments as $comment )
			if ( get_comment_type() != "comment" ) $numPingBacks++; else $numComments++;
	?>
	<div id="secondary-tabs">
		<ul class="comment-tabs">
			<?php if ( comments_open() ) : ?>
			<li class="leave-a-comment">
				<h3 class="comment-tab-title">
					<a href="#respond" title="<?php esc_attr_e( 'Leave a reply', 'mystique' ); ?>"> <?php _e( 'Leave a Comment', 'mystique' ); ?></a>
				</h3>
			</li>
			<?php endif; ?>
			<?php if ( $numPingBacks > 0 ) : ?>
			<li>
				<h3 class="comment-tab-title">
					<a href="#trackbacks"><?php printf( _n( 'Trackbacks(1)', 'Trackbacks (%1$s)', $numPingBacks, 'mystique' ), number_format_i18n( $numPingBacks ) ); ?></a>
				</h3>
			</li>
			<?php endif; ?>
			<?php if ( comments_open() ) : ?>
			<li>
				<h3 class="comment-tab-title">
					<a href="#comments"><?php printf( _n( 'Comments (1)', 'Comments (%1$s)', $numComments, 'mystique' ), number_format_i18n( $numComments ) ); ?></a>
				</h3>
			</li>
			<?php endif; ?>
		</ul><!-- .comment-tabs -->
	</div><!-- #secondary-tabs -->


	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<div class="navigation">
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'mystique' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'mystique' ) ); ?></div>
		</div> <!-- .navigation -->
		<?php endif; // check for comment navigation ?>


		<div id="commentlist">

			<ol id="comments">
			<?php if ( have_comments() ) : ?>
				<?php wp_list_comments( 'type=comment&callback=mystique_comment' ); ?>
			<?php else : // this is displayed if there are no comments so far ?>
				<?php if ( 'open' == $post->comment_status ) : ?>
					<li><?php _e( 'Leave a Comment', 'mystique' ); ?></li>
				<?php else : // comments are closed ?>
					<li><?php _e( 'Comments are closed.', 'mystique' ); ?></li>
				<?php endif; ?>
			<?php endif; ?>
			</ol><!-- #comments -->

			<?php if ( ( $numPingBacks > 0 ) ) : ?>
			<ol id="trackbacks">
				<?php wp_list_comments( 'type=pings&callback=mystique_pings' ); ?>
			</ol><!-- #trackbacks -->
			<?php endif; ?>

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'mystique' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'mystique' ) ); ?></div>
			</div><!-- .navigation -->
			<?php endif; // check for comment navigation ?>

		</div><!-- #comment-list -->

	<?php else : // or, if we don't have comments:

		/* If there are no comments and comments are closed,
		 * let's leave a little note, shall we?
		 * But only on posts! We don't really need the note on pages.
		 */
		if ( ! comments_open() && ! is_page() ) :
		?>
		<p class="nocomments"><?php _e( 'Comments are closed.', 'mystique' ); ?></p>
		<?php endif; // end ! comments_open() ?>

	<?php endif; // end have_comments() ?>

	<?php comment_form(); ?>

</div><!-- #post-extra-content -->