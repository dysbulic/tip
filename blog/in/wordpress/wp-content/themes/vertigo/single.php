<?php
/**
 * @package Vertigo
 */

get_header(); ?>

<div id="content" role="main">

	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

		<div class="pagetype">
			<?php
				printf( __( '<p><span>Posted on <a href="%1$s" rel="bookmark"><time class="entry-date" datetime="%2$s" pubdate>%3$s</time></a></span></p>', 'vertigo' ),
					get_permalink(),
					get_the_date( 'c' ),
					get_the_date()
				);
			?>
		</div>

		<?php get_template_part( 'content', get_post_format() ); ?>

		<?php comments_template( '', true ); ?>

	<?php endwhile; // end of the loop. ?>

</div><!-- #content -->

<?php get_footer(); ?>