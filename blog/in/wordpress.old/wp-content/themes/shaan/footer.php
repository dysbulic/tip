<?php
/**
 * @package Shaan
 */
?>

</div><!--#container-->

<div id="footer">

	<div id="footer-menu">

		<?php wp_nav_menu( array(
			'container'      => 'div',
			'depth'          => '1',
			'theme_location' => 'footer-menu',
			'fallback_cb'    => '__return_false',
		) ); ?>

	</div>

	<div id="footer-credit">
		<a href="http://wordpress.org/" rel="generator">Proudly powered by WordPress</a>
		<?php printf( __( 'Theme: %1$s by %2$s.', 'shaan' ), 'Shaan', '<a href="http://www.speckygeek.com/shaan-free-wordpress-theme/" rel="designer">Specky Geek</a>' ); ?>
	</div>

</div><!--#footer-->

<div class="clear"></div>
</div><!--#wrapper -->

<!-- Do not remove this, it's required for certain plugins which generally use this hook to reference JavaScript files. -->
<?php wp_footer(); ?>
</body>
</html>