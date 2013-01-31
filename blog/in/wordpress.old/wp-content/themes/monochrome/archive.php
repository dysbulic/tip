<?php get_header(); ?>
<?php $options = get_option('mc_options'); ?>
  <div id="contents" class="clearfix">

   <div id="left_col">
    <div class="content_noside">
<?php if (have_posts()) : ?>

<div id="headline">
<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
 <?php if (is_category()) { ?>
 <p><?php printf(__('Archive for the &#8216; %s &#8217; Category', 'monochrome'), single_cat_title('', false)); ?></p>
 
 <?php } elseif( is_tag() ) { ?>
 <p><?php printf(__('Posts Tagged &#8216; %s &#8217;', 'monochrome'), single_tag_title('', false) ); ?></p>

 <?php } elseif (is_day()) { ?>
 <p><?php printf(__('Archive for %s', 'monochrome'), get_the_time(__('F jS, Y', 'monochrome'))); ?></p>

 <?php } elseif (is_month()) { ?>
 <p><?php printf(__('Archive for %s', 'monochrome'), get_the_time(__('F, Y', 'monochrome'))); ?></p>

 <?php } elseif (is_year()) { ?>
 <p><?php printf(__('Archive for %s', 'monochrome'), get_the_time(__('Y', 'monochrome'))); ?></p>

 <?php } elseif (is_author()) { ?>
 <p><?php _e('Author Archive', 'monochrome'); ?></p>

 <?php } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
 <p><?php _e('Blog Archives', 'monochrome'); ?></p>
<?php } ?>
</div>

<?php while ( have_posts() ) : the_post(); ?>

    <div class="archive_contents">
     <h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
     <ul class="archive_meta">
      <li><?php the_time(__('F jS, Y', 'monochrome')) ?></li>
      <li><?php _e('Posted in ','monochrome'); ?><?php the_category(' . '); ?></li>
      <?php if ($options['author']) : ?><li><?php _e('By ','monochrome'); ?><?php the_author_posts_link(); ?></li><?php endif; ?>
      <?php edit_post_link(__('[ EDIT ]', 'monochrome'), '<li class="post-edit">', '</li>' ); ?>
     </ul>
     <p><a href="<?php the_permalink() ?>"><?php the_excerpt_rss(); ?><span class="read-more"><?php _e('[ READ MORE ]', 'monochrome'); ?></span></a></p>
    </div>

<?php endwhile; else: ?>
    <div class="archive_contents">
     <p><?php _e("Sorry, but you are looking for something that isn't here.","monochrome"); ?></p>
    </div>
<?php endif; ?>

    <?php include('navigation.php'); ?>
    </div><!-- #content_noside end -->

   </div><!-- #left_col end -->

   <?php get_sidebar(); ?>

  </div><!-- #contents end -->

  <div id="footer">
<?php get_footer(); ?>