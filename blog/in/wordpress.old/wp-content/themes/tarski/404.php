<?php get_header(); ?>
<div id="primary">
	<div class="entry static">
		<div class="post-meta">
			<h1 class="post-title" id="error-404"><?php _e( 'Error 404', 'tarski' ); ?></h1>
		</div>
		
		<div class="post-content">
			<p><?php printf( __( 'The page you are looking for does not exist; it may have been moved, or removed altogether. You might want to try the search function. Alternatively, return to the <a href="%1$s">front page</a>.', 'tarski' ), home_url( '/' ) ); ?></p>
		</div>
	</div>
</div>
<?php get_footer(); ?>