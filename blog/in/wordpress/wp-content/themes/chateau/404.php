<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Chateau
 */
get_header();
?>
	<div id="primary">
		<div id="error404">
			<h1><?php _e( 'Not Found!', 'chateau' ); ?></h1>
			<p><span><?php _e( "What you were looking for doesn't exist or isn't here anymore. We are sorry :(", 'chateau' ); ?></span></p>
			<p><?php printf( __( 'Please <a href="%s" title="&laquo; Return to homepage">return to the home page</a> or use the search box above', 'chateau' ), get_home_url( '/' ) ); ?></p>
		</div>
	</div><!-- end #primary -->
</div><!-- end #page -->
</body>
</html>