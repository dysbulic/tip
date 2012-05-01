<?php
/**
 * @package WordPress
 * @subpackage Titan
 */
?>
</div><!--end wrapper-->
</div><!--end content-background-->

<div id="footer">
	<div class="wrapper clear">

		<div id="footer-first" class="footer-column">
			<ul>
			<?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'footer_left' ) ) : ?>
				<li class="widget widget_bookmarks">
					<h2 class="widgettitle"><?php _e( 'Links' ); ?></h2>
					<ul>
						<?php wp_list_bookmarks( 'title_li=&categorize=0' ); ?>
					</ul>
				</li>
			<?php endif; ?>
			</ul>
		</div>

		<div id="footer-second" class="footer-column">
			<ul>
			<?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'footer_center' ) ) : ?>
				<li class="widget widget_pages">
					<h2 class="widgettitle"><?php _e( 'Pages' ); ?></h2>
					<ul>
						<?php wp_list_pages( 'title_li=' ); ?>
					</ul>
				</li>
			<?php endif; ?>
			</ul>
		</div>

		<div id="footer-third" class="footer-column">
			<ul>
			<?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'footer_right' ) ) : ?>
				<li class="widget widget_categories">
					<h2 class="widgettitle"><?php _e( 'Search' ); ?></h2>
			 		<?php get_search_form(); ?>
				</li>
			</ul>
			<?php endif; ?>
		</div>

		<div id="copyright">
			<p class="copyright-notice"><a href="http://wordpress.com/" rel="generator">Get a free blog at WordPress.com</a> | <?php printf( __( 'Theme: %1$s by %2$s.', 'titan' ), 'Titan', '<a href="http://thethemefoundry.com/" rel="designer">The Theme Foundry</a>' ); ?>.</p>
		</div>

	</div><!--end wrapper-->
</div><!--end footer-->

<?php wp_footer(); ?>
</body>
</html>