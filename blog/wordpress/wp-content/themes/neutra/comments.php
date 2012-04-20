<div id="comment-template">

<?php if ( ! empty( $post->post_password ) && $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password ) : ?>
	<p><?php _e( 'Enter your password to view comments.', 'neutra' ); ?></p>
<?php return; endif; ?>

	<?php if ( $comments ) : ?>

		<h2><span><?php comments_number( __( 'No comments', 'neutra' ), __( 'One comment', 'neutra' ), __( '% comments', 'neutra' ) ); ?></span></h2>
		<p class="do-you-comment"><a href="#respond" title="Comment"><?php _e( 'Do you want to comment?', 'neutra' ); ?></a></p>
		<p class="trackback">
			<?php post_comments_feed_link( __( 'Comments RSS', 'neutra' ) ); ?>
			<?php
				printf( __( 'and <a href="%1$s" rel="trackback">TrackBack URI</a>', 'neutra' ),
					get_trackback_url()
				);
			?>
		</p><!-- /trackback -->

		<ol id="commentlist">
			<?php wp_list_comments( 'type=comment&callback=neutra_list_comments' ); ?>
		</ol>

		<div class="navigation">
			<div class="alignleft"><?php next_comments_link( __( '&laquo; Older Comments', 'neutra' ) ); ?></div>
			<div class="alignright"><?php previous_comments_link( __( 'Newer Comments &raquo;', 'neutra' ) ); ?></div>
		</div>

	<?php else : ?>

	<?php endif; ?>

	<?php if ( comments_open() ) : ?>

		<?php comment_form( array( 'title_reply' => '<span>' . __( 'Leave a Reply', 'neutra' ) . '</span>' ) ); ?>

		<?php if ( !empty( $comments_by_type['pings'] ) ) : ?>
			<div id="trackbacks">
				<h2><span><?php _e( 'Trackbacks', 'neutra' ); ?></span></h2>
				<ol>
					<?php wp_list_comments( 'type=pings&callback=neutra_list_pings' ); ?>
				</ol>
			</div><!-- /trackbacks -->
		<?php endif; ?>

	<?php else : // Comments are closed ?>
			<p class="comments-closed"><?php _e( 'Sorry, the comment form is closed at this time.', 'neutra' ); ?></p>
	<?php endif; ?>

</div><!-- /comment-template -->