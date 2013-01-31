<?php
/**
 * @package WordPress
 * @subpackage Comet
 */
?>

<?php get_header(); ?>

	<div class="post">
		<h1 class="post-title"><?php _e( 'Page not found', 'comet' ); ?></h1>
		<div class="post-text">
			<p><?php _e( 'The page you were looking for could not be found. If you followed a link on this site to get here, please contact the administrator so it can be corrected.', 'comet' ); ?></p>
			<?php get_search_form(); ?>
		</div>
	</div>

<?php get_footer(); ?>
