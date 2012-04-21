<?php
/**
 * @package WordPress
 * @subpackage StrangeLittleTown
 */
?>

<?php get_header(); ?>

<div id="content">

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'contain' ); ?>>

				<header class="contain">
					<?php the_title( '<h1>', '</h1>' ); ?>
					<span class="about-this-post"><?php edit_post_link( __( 'Edit', 'strange-little-town' ) ); ?></span>
				</header>

				<div class="entry-content contain">
					<?php the_content( __( 'Read the rest of this entry &raquo;', 'strange-little-town' ) ); ?>
				</div>

				<?php
					wp_link_pages( array(
						'after'       => '</nav>',
						'before'      => '<nav class="paginated-post contain">',
						'link_after'  => '</span>',
						'link_before' => '<span>',
					) );
				?>

			</article>

			<?php comments_template(); ?>

		<?php endwhile; ?>

	<?php endif; ?>

</div><!-- content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>