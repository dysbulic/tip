<?php get_header(); ?>

		<div id="content_wrapper">
			<div id="content">

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

				<h1 id="post-<?php the_ID(); ?>" ><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent link to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h1>
				<p class="author" ><?php _e( 'Posted on' ); ?> <em><?php the_time( get_option( 'date_format' ) ); ?></em>. <?php _e( 'Filed under:', 'fadtastic' ); ?> <?php the_category( ', ' ); ?> | <?php the_tags( __( 'Tags: ' ), ', ', ' | ' ); ?> <?php edit_post_link( __( 'Edit This' ) ); ?></p>

				<?php the_content(); ?>
				<?php wp_link_pages(); ?>

				<?php if ( 'open' == $post->comment_status ) : ?>
					<p class="clear"><strong><a href="#respond"><?php _e( 'Make a Comment' ); ?></a></strong></p>
				<?php endif; ?>

				<?php endwhile; ?>


				<?php else : ?>

				<h2><?php _e( 'Not Found', 'fadtastic' ); ?></h2>
				<p><?php _e( "Sorry, but you are looking for something that isn't here.", 'fadtastic' ); ?></p>
				<?php get_search_form(); ?>

				<?php endif; ?>

				<!-- Story ends here -->

				<?php comments_template(); ?>

			</div>
		</div>

	<?php get_sidebar(); ?>

<?php get_footer(); ?>
