<div id="comments">
	<?php if ( post_password_required() ) : ?>
		<div class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'vertigo' ); ?></div>
	</div><!-- .comments -->
	<?php return;
		endif;
	?>

	<?php if ( have_comments() ) : ?>
		<h3 id="comments"><?php comments_number( __( 'Leave a Reply', 'manifest' ), __( 'One Response', 'manifest' ), __( '% Responses', 'manifest' ) );?> to &#8220;<?php the_title(); ?>&#8221;</h3>

		<div class="navigation">
			<div class="alignleft"><?php previous_comments_link(); ?></div>
			<div class="alignright"><?php next_comments_link(); ?></div>
		</div>

		<ol class="commentlist">
		<?php wp_list_comments( 'avatar_size=48' ); ?>
		</ol>

		<div class="navigation">
			<div class="alignleft"><?php previous_comments_link(); ?></div>
			<div class="alignright"><?php next_comments_link(); ?></div>
		</div>
 	<?php else : // this is displayed if there are no comments so far ?>

		<?php if ( comments_open() ) : ?>
			<!-- If comments are open, but there are no comments. -->

		 <?php else : // comments are closed ?>
			<!-- If comments are closed. -->

		<?php endif; ?>
	<?php endif; ?>

	<?php comment_form(); ?>
</div>