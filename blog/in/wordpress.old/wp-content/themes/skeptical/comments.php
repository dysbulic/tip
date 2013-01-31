<?php
/**
 * @package WordPress
 * @subpackage Skeptical
 */
?>
	<div id="comments">
	<?php if ( post_password_required() ) : ?>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'woothemes' ); ?></p>
	</div><!-- #comments -->
	<?php return; endif; ?>

	<?php if ( have_comments() ) : ?>

		<?php if ( ! empty( $comments_by_type['comment'] ) ) : ?>
			<h3>
				<?php
					printf( _n( 'One response to &ldquo;%2$s&rdquo;', '%1$s Responses to &ldquo;%2$s&rdquo;', get_comments_number(), 'woothemes' ),
						number_format_i18n( get_comments_number() ), get_the_title() );
				?>
			</h3>

			<ol class="commentlist">
				<?php wp_list_comments( array( 'callback' => 'skeptical_comment', 'type' => 'comment' ) ); ?>
			</ol>

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
			<div class="navigation">
				<div class="fl"><?php previous_comments_link( __( '&laquo; Older Comments', 'woothemes' ) ); ?></div>
				<div class="fr"><?php next_comments_link( __( 'Newer Comments &raquo;', 'woothemes' ) ); ?></div>
				<div class="fix"></div>
			</div><!-- /.navigation -->
			<?php endif; // check for comment navigation ?>
		<?php endif; ?>

		<?php if ( ! empty( $comments_by_type['pings'] ) ) : ?>
			<div id="pings">
				<h3><?php _e( 'Trackbacks / Pingbacks', 'woothemes' ); ?></h3>
				<ol class="pinglist">
					<?php wp_list_comments( array( 'callback' => 'skeptical_comment', 'type' => 'pings' ) ); ?>
				</ol>
			</div><!-- /#pings -->
		<?php endif; ?>

	<?php elseif ( ! comments_open() && '0' != get_comments_number() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="nocomments"><?php _e( 'Comments are closed.', 'woothemes' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>

</div><!-- #comments -->