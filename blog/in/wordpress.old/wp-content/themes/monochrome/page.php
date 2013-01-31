<?php get_header(); ?>
  <div id="contents" class="clearfix">

   <div id="left_col">
<?php $options = get_option('mc_options'); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <div class="post clearfix" id="single_post">
     <div class="post_content_wrapper">
      <h2><span><?php the_title(); ?></span></h2>
      <?php edit_post_link(__('[ EDIT ]', 'monochrome'), '<p>', '</p>' ); ?>      
      <div class="post_content">
       <?php the_content(__('Read more', 'monochrome')); ?>
        <?php wp_link_pages('before=<div class="page-link">' . __('Pages:') . '&after=</div>'); ?>
      </div>
     </div>
    </div>

<?php endwhile; else: ?>
    <div class="post_odd">
     <div class="post clearfix">
      <div class="post_content_wrapper">
       <?php _e("Sorry, but you are looking for something that isn't here.","monochrome"); ?>
      </div>
      <div class="post_meta">
      </div>
     </div>
    </div>
<?php endif; ?>

<div id="comments_wrapper">
<?php comments_template('', true); ?>
</div>

   </div><!-- #left_col end -->

   <?php get_sidebar(); ?>

  </div><!-- #contents end -->

  <div id="footer">
<?php get_footer(); ?>