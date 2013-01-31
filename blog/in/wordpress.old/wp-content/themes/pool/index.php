<?php get_header(); ?>

	<div id="bloque">

		<div id="noticias">

		<?php is_tag(); ?>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<div <?php post_class( 'entrada' ); ?>>
				<h2 id="post-<?php the_ID(); ?>">
					<?php if ( ! is_single() ) : ?>
						<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'pool' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
					<?php else : ?>
						<?php the_title(); ?>
					<?php endif; ?>				
				</h2>
				<small><?php printf( __( '%1$s at %2$s', 'pool' ), get_the_time( get_option( 'date_format' ) ), get_the_time() ); ?> | <?php printf( __( 'Posted in %s', 'pool' ), get_the_category_list(', ') ); ?> <?php _e( ' | ', 'pool' ); ?> <?php comments_popup_link( __( 'Leave a comment', 'pool' ), __( '1 Comment', 'pool' ), __( '% Comments', 'pool' ) ); ?> <?php edit_post_link( __( 'Edit this post', 'pool' ) ); ?><br /><?php the_tags( __( 'Tags:' , 'pool' ).' ', ', ', '<br />'); ?></small>

				<?php the_content( __( 'Continue Reading', 'pool' ).' '.the_title( '', '', false)."..." ); ?>

				<div class="feedback"><?php wp_link_pages( array( 'before' => '<p><strong>'.__( "Pages:", "pool" ).' </strong> ', 'after' => '</strong></p>', 'next_or_number' => 'number' ) ); ?></div>
			</div><!-- .entrada -->

			<?php comments_template(); // Get wp-comments.php template ?>

		<?php endwhile; else: ?>
			<h2 class="center"><?php _e( 'Not Found', 'pool'); ?></h2>
			<p><?php _e( 'Sorry, no posts matched your criteria.', 'pool' ); ?></p>
		<?php endif; ?>

		<?php posts_nav_link(' &#8212; ', __( '&laquo; Previous Page', 'pool' ), __( 'Next Page &raquo;', 'pool' ) ); ?>

		</div><!-- #noticias -->

<?php get_footer(); ?>