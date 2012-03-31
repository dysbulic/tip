<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage The Morning After
 */
get_header(); ?>

<?php get_template_part( 'top-banner' ); ?>

<div id="arch_content" class="column full-width">

	<div class="column archive-info first">
		<h2 class="archive_name"><?php bloginfo( 'name' ); ?></h2>

		<div class="archive_meta">

			<div class="archive_feed">
				<a href="<?php bloginfo( 'rss_url' ); ?>"><?php _e( 'RSS feed for','woothemes' ); ?> <?php bloginfo( 'name' ); ?></a>
			</div>

		</div>
	</div><!-- end .archive-info -->

	<div class="column mid-column">

		<p><strong><?php _e( 'Oops!','woothemes' );?></strong></p>
		
		<?php 
			printf( __( "<p>Looks like the page you're looking for has been moved or had its name changed. Or maybe it's just fate. You could use the search box in the header to search for what you're looking for, or begin again from the <a href='%s/'>home page</a>.</p>", "woothemes" ),
				get_home_url()
			);
		?>

	</div><!-- end .mid-column -->

	<?php get_sidebar(); ?>

</div><!-- end #arch_content -->

<?php get_footer(); ?>