<?php
/**
 * @package WordPress
 * @subpackage Under_the_Influence
 */
	get_header();
?>
<div id="content_container">
	<?php get_sidebar(); ?>
	<div class="navigation"></div>

	<div class="center">
		<h2 class="center">
			<?php _e('Error 404 - Not Found', 'uti_theme')?>
		</h2>
	</div>
</div><!--#content_container-->


<?php get_footer(); ?>