</div><!-- / #wrap -->

<?php wp_nav_menu( array( 'container_id' => 'secondary', 'container_class' => 'nav', 'theme_location' => 'secondary', 'fallback_cb' => false ) ); ?>

<div id="footer">
	<?php printf( __( 'Theme: %1$s by %2$s.' ), 'Simpla', '<a href="http://ifelse.co.uk" rel="designer">Phu</a>' ); ?> <a href="http://wordpress.com/" rel="generator">Get a free blog at WordPress.com</a>
</div><!-- / #footer -->

<?php wp_footer(); ?>
</body>
</html>