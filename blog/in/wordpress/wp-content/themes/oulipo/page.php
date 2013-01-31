<?php
/**
 * @package WordPress
 * @subpackage Oulipo
 */
?>
<?php get_header(); ?>

<div id="content">

	<div id="entry-content">

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>
			<div id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h2><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'oulipo' ), the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a></h2>

				<div class="entry">
					<?php the_content( __( 'Read the rest of this entry &raquo;', 'oulipo' ) ); ?>
					<?php wp_link_pages( array( 'before' => '<p>' . __( 'Page: ', 'oulipo' ), 'after' => '</p>', 'next_or_number' => 'number' ) ); ?>

					<?php comments_template(); ?>
				</div>
			</div>

		<?php endwhile; ?>

		<div class="navigation">
			<p class="alignleft"><?php next_posts_link( __( '&laquo; Older Entries', 'oulipo' ) ); ?></p>
			<p class="alignright"><?php previous_posts_link( __( 'Newer Entries &raquo;', 'oulipo' ) ); ?></p>
		</div>

	<?php else : ?>

		<div class="entry">
			<p><?php _e( 'Looks like what you were looking for isn&rsquo;t here. You might want to give it another try, perhaps the server hiccuped, or perhaps you spelled something wrong (or maybe I did).', 'oulipo' ); ?></p>
		</div>

	<?php endif; ?>

</div> <!-- close entry-content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>