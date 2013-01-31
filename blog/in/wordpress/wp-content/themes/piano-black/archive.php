<?php
/**
 * @package WordPress
 * @subpackage Piano Black
 */

get_header(); ?>

		<section id="primary">
			<div id="content" role="main">

				<header class="page-header">
					<h1 class="page-title">
						<?php
							if ( is_day() ) :
								printf( __( 'Daily Archives: %s', 'piano-black' ), '<span>' . get_the_date() . '</span>' );
							elseif ( is_month() ) :
								printf( __( 'Monthly Archives: %s', 'piano-black' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );
							elseif ( is_year() ) :
								printf( __( 'Yearly Archives: %s', 'piano-black' ), '<span>' . get_the_date( 'Y' ) . '</span>' );
							elseif ( is_tag() ) :
								printf( __( 'Tag Archives: %s', 'piano-black' ), '<span>' . single_tag_title( '', false ) . '</span>' );
							elseif ( is_author() ) :
								printf( __( 'Author Archives: %s', 'piano-black' ), '<span class="vcard"><a class="url fn n" href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' );
							else :
								_e( 'Archives', 'piano-black' );
							endif;
						?>
					</h1>
				</header>

				<?php rewind_posts(); ?>

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', get_post_format() ); ?>

				<?php endwhile; ?>

				<?php /* Display navigation to next/previous pages when applicable */ ?>
				<?php if ( $wp_query->max_num_pages > 1 ) : ?>
					<nav id="nav-below" class="post-nav">
						<h1 class="section-heading"><?php _e( 'Post navigation', 'piano-black' ); ?></h1>
						<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'piano-black' ) ); ?></div>
						<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'piano-black' ) ); ?></div>
					</nav><!-- #nav-below -->
				<?php endif; ?>

			</div><!-- #content -->
		</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>