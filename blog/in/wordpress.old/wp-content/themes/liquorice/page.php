<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Liquorice
 */

get_header(); ?>

	<div id="primary-content">
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

		<div class="post-wrapper">
			<?php if ( is_front_page() ) : ?>
				<h2 class="page-title"><?php the_title(); ?></h2>
			<?php else : ?>
				<h1 class="page-title"><?php the_title(); ?></h1>
			<?php endif; ?>

			<div class="entry">
				<?php if( has_post_thumbnail() ) : ?>
					<a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
						<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'post-thumbnail', 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?>
					</a>
				<?php endif; ?>
				<?php the_content(); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link"><p><strong>'.__( "Pages:", "liquorice" ).' </strong> ', 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
				<?php edit_post_link( __( '(Edit)', 'liquorice' ), '<span class="edit-link">', '</span>' ); ?>
			</div><!-- .entry -->

		</div><!-- .post-wrapper -->

		<?php comments_template( '', true ); ?>

	<?php endwhile; // end of the loop. ?>
	</div><!-- #primary-content-->

<?php get_sidebar(); ?>
<?php get_footer(); ?>