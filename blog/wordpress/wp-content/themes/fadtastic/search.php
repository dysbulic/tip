<?php get_header(); ?>

		<div id="content_wrapper">
			<div id="content">

			<?php if ( have_posts() ) : ?>

		<h1><?php _e( 'Search Results', 'fadtastic' ); ?></h1>

		<?php while ( have_posts() ) : the_post(); ?>

			<div <?php post_class(); ?>>
				<h2 id="post-<?php the_ID(); ?>" ><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent link to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2>
					<p class="author" ><?php _e( 'Posted on' ); ?> <em><?php the_time( get_option( 'date_format' ) ); ?></em>. <?php _e( 'Filed under:', 'fadtastic' ); ?> <?php the_category( ', ' ); ?> | <?php the_tags( __( 'Tags: ' ), ', ', ' | ' ); ?> <?php edit_post_link( __( 'Edit This' ) ); ?></p>

							<?php the_excerpt(); ?>

							<strong><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent link to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php _e( 'Read Full Post' ); ?></a></strong> | <strong><a href="<?php the_permalink(); ?>#respond" title="Make a comment"><?php _e( 'Make a Comment' ); ?></a></strong> <small> ( <?php comments_popup_link( __( 'None so far' ), __( '<strong>1</strong> so far' ), ( '<strong>%</strong> so far' ) ); ?> ) </small>



			</div>

				<?php endwhile; ?>

				<br />
				<?php next_posts_link( __( '&laquo; Previous Entries' ) ); ?> | <?php previous_posts_link( __( 'Next Entries &raquo;' ) ); ?>


			<?php else : ?>

				<h2 class="center"><?php _e( 'No posts found. Try a different search?', 'fadtastic' ); ?></h2>
				<?php get_search_form(); ?>

			<?php endif; ?>

			</div>
		</div>

	<?php get_sidebar(); ?>

<?php get_footer(); ?>
