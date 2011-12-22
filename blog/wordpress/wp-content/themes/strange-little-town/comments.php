<?php
/**
 * @package WordPress
 * @subpackage StrangeLittleTown
 */
?>

	<div id="comments">
	<?php if ( have_comments() ) : ?>
		<h2 id="comments-title"><?php
			printf( _n( 'One comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', get_comments_number(), 'strange-little-town' ), number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
		?></h2>
	<?php elseif ( post_password_required() ) :
		/*
		 * Stop the rest of comments.php from being processed,
		 * but don't kill the script entirely -- we still have
		 * to fully load the template.
		 */
		echo '</div><!-- #comments -->';
		return;
	endif; ?>

	<?php if ( have_comments() ) : ?>
		<section class="commentlist">
			<?php wp_list_comments( array( 'callback' => 'strange_little_town_comment' ) ); ?>
		</section>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
		<nav id="comment-nav-below" class="paged-navigation contain">
			<h1 class="assistive-text"><?php _e( 'Comment navigation', 'strange-little-town' ); ?></h1>
			<div class="nav-older"><?php previous_comments_link( __( '&larr; Older Comments', 'strange-little-town' ) ); ?></div>
			<div class="nav-newer"><?php next_comments_link( __( 'Newer Comments &rarr;', 'strange-little-town' ) ); ?></div>
		</nav>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( comments_open() ) : ?>
		<?php comment_form(); ?>
	<?php elseif ( have_comments() ) : ?>
		<p class="comments-closed"><?php _e( 'Comments are closed.', 'strange-little-town' ); ?></p>
	<?php endif; ?>

</div><!-- #comments -->