<?php
/**
 * @package WordPress
 * @subpackage Dark Wood
 */
?>
<?php get_header(); ?>

<div id="container">

	<div id="content">

		<ul class="posts">
			<li>
				<h2><?php _e( '404 - Page not found', 'darkwood' ); ?></h2>
				<p><?php _e( 'Oops! I cannot find what you are looking for. Please try again with a different keyword.', 'darkwood' ); ?></p>

				<h3><?php _e( 'Search the Site', 'darkwood' ); ?></h3>
				<?php get_search_form(); ?>
			</li>
		</ul>

	</div><!-- /content -->

	<?php get_sidebar(); ?>

</div><!-- /container -->

<?php get_footer(); ?>