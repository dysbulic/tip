<?php
/**
 * @package WordPress
 * @subpackage Comet
 */
?>
		</div><!-- /c2 -->

		<?php $comet_options = comet_get_theme_options(); ?>
		<?php if ( in_array( $comet_options['theme_layout'], array( 'content-sidebar', 'sidebar-content-sidebar' ) ) ) : ?>
			<div id="c3">
			<?php
				if ( 'content-sidebar' == $comet_options['theme_layout'] )
					get_sidebar( 'primary' );
				else if ( 'sidebar-content-sidebar' == $comet_options['theme_layout'] )
					get_sidebar( 'secondary' );
			?>
			</div><!-- /c3 -->
		<?php endif; ?>
	</div><!-- /content -->

	<div id="footer">
		<a href="http://wordpress.org/" rel="generator">Proudly powered by WordPress</a>
		<?php printf( __( 'Theme: %1$s by %2$s.', 'theme-name' ), 'Comet', '<a href="http://frostpress.com/" rel="designer">Frostpress</a>' ); ?>
	</div><!-- /footer -->

</div><!-- /wrap -->

<?php wp_footer(); ?>

</body>
</html>
