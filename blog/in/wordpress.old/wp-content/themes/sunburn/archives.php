<?php
/*
Template Name: Archives
*/
?>

<?php get_header(); ?>

<div id="content" class="widecolumn">

<?php get_search_form(); ?>

<h2><?php _e( 'Archives by Month:', 'sunburn' ); ?></h2>
  <ul>
    <?php wp_get_archives('type=monthly'); ?>
  </ul>

<h2><?php _e( 'Archives by Subject:', 'sunburn' ); ?></h2>
  <ul>
     <?php wp_list_cats(); ?>
  </ul>

</div>	

<?php get_footer(); ?>
