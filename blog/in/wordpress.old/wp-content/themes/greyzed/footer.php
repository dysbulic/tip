<?php
/**
 * @package Greyzed
 */
?>
			<hr />

			<div id="footer" role="contentinfo">

				<?php if ( is_active_sidebar( 2 ) || is_active_sidebar( 3 ) || is_active_sidebar( 4 ) ) : ?>

					<div id="footer-left" class="widget-area">
						<ul>
						<?php dynamic_sidebar( 2 ); ?>
						</ul>
					</div>

					<div id="footer-middle" class="widget-area">
						<ul>
						<?php dynamic_sidebar( 3 ); ?>
						</ul>
					</div>

					<div id="footer-right" class="widget-area">
						<ul>
						<?php dynamic_sidebar( 4 ); ?>
						</ul>
					</div>

				<?php endif; ?>

			</div>

		</div><!-- #container -->
	</div><!-- #page -->

	<div id="footer-bott">
		<a href="http://wordpress.com/" rel="generator">Get a free blog at WordPress.com</a>. | <?php printf( __( 'Theme: %1$s by %2$s.' ), 'Greyzed', '<a href="http://theforge.co.za/" rel="designer">The Forge Web Creations</a>' ); ?>
	</div>

	<div class="footerbar"></div>
</div><!-- #wrapper -->

<?php wp_footer(); ?>
</body>
</html>