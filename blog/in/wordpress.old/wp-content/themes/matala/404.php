<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Matala
 */

get_header(); ?>

	<div id="primary">
		<div id="content" role="main">
			<article id="post-0" class="post error404 not-found">
				<header class="entry-header">
					<h1 class="entry-title"><?php _e( 'Whoops &#8212; four oh four', 'matala' ); ?></h1>
				</header>

				<div class="entry-content">
					<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching will help.', 'matala' ); ?></p>

					<?php get_search_form(); ?>

				</div><!-- .entry-content -->
			</article><!-- #post-0 -->

		</div><!-- #content -->

		<?php
			/* Two columns of sidebars.
			 */
			get_sidebar( 'supplementary' );
		?>
		<div id="primary-bottom"></div>
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>