<?php
/*
 * Template Name: Full width, no sidebar Template
 *
 * @package WordPress
 * @subpackage Skeptical
*/
?>

<?php get_header(); ?>

<?php $content_width = 899; ?>

	<div id="content" class="page col-full">
		<div id="main" class="fullwidth">
			<?php while ( have_posts() ) : the_post(); ?>

				<div class="post page">
					<h1 class="title"><?php the_title(); ?></h1>
					<div class="entry">
						<?php the_content(); ?>
						<?php edit_post_link( __( '{ Edit }', 'woothemes' ), '<p>', '</p>' ); ?>
					</div>
				</div><!-- /.post -->
	
				<?php comments_template( '', true ); ?>
			
			<?php endwhile; // end of the loop. ?>
		</div><!-- /#main -->
	</div><!-- /#content -->

<?php get_footer(); ?>