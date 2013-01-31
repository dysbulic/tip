<?php
/**
 * @package Imbalance 2
 */
?>
<div id="comments">
<?php if ( post_password_required() ) : ?>
	<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'imbalance2' ); ?></p>
</div><!-- #comments -->

<?php
		return;
	endif;
?>

	<?php if ( have_comments() ) : ?>
		<h3 id="comments-title">
			<?php
				printf( _n( 'One Comment', '%1$s Comments', get_comments_number(), 'imbalance2' ),
					number_format_i18n( get_comments_number() )
				);
			?>
		</h3>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'imbalance2' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'imbalance2' ) ); ?></div>
			</div> <!-- .navigation -->
		<?php endif; // check for comment navigation ?>

		<ol class="commentlist">
			<?php wp_list_comments( array( 'callback' => 'imbalance2_comment' ) ); ?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'imbalance2' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'imbalance2' ) ); ?></div>
			</div><!-- .navigation -->
		<?php endif; // check for comment navigation ?>
	<?php endif; // have_comments() ?>

	<?php if ( ! comments_open() && '0' != get_comments_number() ) : // comments are closed and there is at least one comment ?>
		<p class="nocomments"><?php _e( 'Comments are closed.' , 'imbalance2' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>

</div><!-- #comments -->