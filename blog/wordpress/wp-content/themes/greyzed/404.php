<?php
/**
 * @package WordPress
 * @subpackage Greyzed
 */

get_header(); ?>
<div id="container">	
<?php get_sidebar(); ?>	
	<div id="content" role="main">
	<div class="column">
		<div class="fourofour"><?php _e( '404', 'greyzed' )?></div>
		<h2 class="archivetitle"><?php _e( 'Error 404 - Page Not Found', 'greyzed' ); ?></h2>		
		<div class="entry">
			<p><?php _e( 'The page you requested could not be found. Perhaps searching will help.', 'greyzed' ); ?></p>
			<?php get_search_form(); ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>