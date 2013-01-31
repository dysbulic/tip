<div id="right_col">
  <?php $options = get_option('mc_options'); ?>

  <?php if ($options['show_information']) : ?>
  <div id="information_area" class="clearfix">
   <div class="side_box" id="information">
    <h3><?php if($options['information_title']) { echo ($options['information_title']); } else { _e('Information','monochrome'); } ?></h3>
    <div id="information_contents"><?php if($options['information_contents']) { echo ($options['information_contents']); } else { _e('Change this sentence and title from admin Theme option page.','monochrome'); } ?></div>
   </div>
   <?php if ($options['rss_feed']) : ?>
   <div id="entries_rss">
    <a href="<?php bloginfo('rss2_url'); ?>" title="<?php esc_attr_e( 'Entries RSS', 'monochrome' ); ?>" ><?php _e('RSS FEED','monochrome'); ?></a>
   </div>
   <?php endif; ?>
  </div>
  <?php endif; ?>

  <?php if($options['search_position'] == 'top') { ?>
  <div class="side_box" id="search_area_top">
   <div id="search_area" class="clearfix">
    <form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
     <div><input type="text" value="<?php esc_attr_e( 'Search', 'monochrome' ); ?>" name="s" id="search_input" onfocus="this.value=''; changefc('white');" /></div>
     <div><input type="image" src="<?php bloginfo('template_url'); ?>/img/search_button_n<?php if ( get_bloginfo('text_direction') == 'rtl' ) echo '-rtl';?>.gif" alt="<?php esc_attr_e( 'Search from this blog.', 'monochrome' ); ?>" title="<?php esc_attr_e(  'Search from this blog.', 'monochrome' ); ?>" id="search_button" /></div>
    </form>
   </div>
   <?php if ($options['tag_list']) : ?>
   <div id="tag_list" class="clearfix">
    <a href="javascript:void(0);" class="search_tag"><?php _e('TAG LIST','monochrome'); ?></a>
    <?php wp_tag_cloud('smallest=11&largest=11&number=30&orderby=count&unit=px&format=list'); ?>
   </div>
   <?php endif; ?>
  </div>
  <?php } ?>

  <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>

   <div class="side_box">
   <h3><?php _e('Recent entry','monochrome'); ?></h3>
    <ul>
<?php $myposts = get_posts('numberposts=5'); foreach($myposts as $post) : ?>
     <li class="side_date"><?php the_time('Y-m-d') ?></li>
     <li><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></li>
<?php endforeach; ?>
   </ul>
  </div>

   <div class="side_box">
   <h3><?php _e('Archive','monochrome'); ?></h3>
   <ul>
    <?php wp_get_archives('type=monthly'); ?>
   </ul>
   </div>

   <div class="side_box">
   <h3><?php _e('Category','monochrome'); ?></h3>
   <ul>
    <?php wp_list_cats('sort_column=name'); ?>
   </ul>
   </div>

<?php endif; ?>

  <?php if($options['search_position'] == 'bottom') { ?>
  <div class="side_box" id="search_area_bottom">
  <h3><?php _e('SEARCH','monochrome'); ?></h3>
   <div id="search_area" class="clearfix">
    <form method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
     <div><input type="text" value="<?php esc_attr_e(  'Search', 'monochrome' ); ?>" name="s" id="search_input" onfocus="this.value=''; changefc('white');" /></div>
     <div><input type="image" src="<?php bloginfo('template_url'); ?>/img/search_button_n.gif" alt="<?php esc_attr_e( 'Search from this blog.', 'monochrome' ); ?>" title="<?php esc_attr_e( 'Search from this blog.', 'monochrome' ); ?>" id="search_button" /></div>
    </form>
   </div>
   <?php if ($options['tag_list']) : ?>
   <div id="tag_list" class="clearfix">
    <a href="javascript:void(0);" class="search_tag"><?php _e('TAG LIST','monochrome'); ?></a>
    <?php wp_tag_cloud('smallest=11&largest=11&number=30&orderby=count&unit=px&format=list'); ?>
   </div>
   <?php endif; ?>
  </div>
  <?php } ?>

</div><!-- #right_col end -->