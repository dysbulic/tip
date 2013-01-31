<?php
/**
 * The template for Pages.
 * @package WordPress
 * @subpackage Selecta
 */
get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<div id="single-header">
	<div class="single-title-wrap">
		<h1 class="single-title"><?php the_title(); ?></h1>
	</div><!-- .single-title-wrap" -->
</div><!-- #single-header-->

<?php endwhile; // end of the loop. ?>

<?php rewind_posts(); ?>

<div id="main" class="clearfix">
	<div id="content" role="main">

		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

			<div <?php post_class( 'post-wrapper clearfix' ); ?>>

				<div class="entry-wrapper clearfix">
					<div class="entry">
						<?php the_content( __( 'Continue Reading <span class="meta-nav">&rarr;</span>', 'selecta' ) ); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link"><p><strong>'.__( "Pages:", "selecta" ).' </strong> ', 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
					</div><!-- .entry -->

					<div class="post-info clearfix">
						<p class="post-meta"><?php edit_post_link( __( 'Edit this Entry', 'selecta' ), '<span class="edit-link">', '</span>' ); ?></p>
					</div><!-- .post-info -->

				</div><!-- .entry-wrapper -->

			</div><!-- .post-wrapper -->

		<?php comments_template( '', true ); ?>

		<?php endwhile; // end of the loop. ?>

	</div><!-- #content -->

	<?php get_sidebar(); ?>

</div><!-- #main -->

<?php get_footer(); ?>