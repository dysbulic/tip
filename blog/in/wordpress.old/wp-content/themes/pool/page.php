<?php get_header(); ?>

	<div id="bloque">

		<div id="noticias">

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<div <?php post_class( 'entrada' ); ?>>
				<h2 id="post-<?php the_ID(); ?>"><?php the_title(); ?> </h2>
				<small style="font-size: 10px; "><?php edit_post_link( __( 'Edit this post', 'pool' ) ); ?></small>

				<?php the_content( __( 'Continue Reading', 'pool' ).' '.the_title('', '', false)."..." ); ?>

				<?php wp_link_pages( array( 'before' => '<p><strong>'.__( "Pages:", "pool" ).' </strong> ', 'after' => '</strong></p>', 'next_or_number' => 'number' ) ); ?>

			</div><!-- .entrada -->

			<?php comments_template(); ?>

		</div><!-- #noticias -->

		<?php endwhile; endif; ?>

<?php get_footer(); ?>
