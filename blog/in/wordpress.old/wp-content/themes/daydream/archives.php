<?php /* Template Name: Archives (Don't use here) 
*/  ?>

<?php get_header(); ?>

<div id="content" class="archives">
	
	<h2><?php _e('Archives by Month', 'daydream'); ?></h2>
	  <ul>
		<?php wp_get_archives('type=monthly'); ?>
	  </ul>
	
	<h2><?php _e('Archives by Subject', 'daydream'); ?></h2>
	  <ul style="margin-bottom: 50px;">
		 <?php wp_list_cats(); ?>
	  </ul>

</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
