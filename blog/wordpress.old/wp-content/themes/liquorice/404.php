<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Liquorice
 */

get_header(); ?>

	<div id="primary-content">
		<div class="error-page">
			<h1><span><?php _e( 'Area 404', 'liquorice' ); ?></span><br /> <?php _e( 'Nonexistent Area', 'liquorice' ); ?></h1>
			<div class="entry">
				<p><strong><?php _e( 'This is  a restricted area. No trespassing beyond this point. This place does not exist. You were never here.', 'liquorice' ); ?></strong><br /></p>
				<dl>
					<dt><?php _e( 'Leave now by either:', 'liquorice' ); ?></dt>
 					<dd><?php printf( __( 'Going <a href="%s">HOME</a> or try doing a Search.', 'liquorice' ), home_url( '/' ) ); ?></dd>
 				</dl>
				<?php get_search_form(); ?>
			</div><!-- .entry -->
		</div><!-- .error-page -->
	</div><!-- #primary-content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>