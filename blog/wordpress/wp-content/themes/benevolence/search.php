<?php get_header(); ?>
<?php get_sidebar(); ?>
<div id="content">

 <div <?php post_class(); ?>>
  <?php if (have_posts()) : ?>
	<br />
   <div class="title"><?php _e('Search Results', 'benevolence'); ?></div>
    <div class="searchdetails"> <?php _e('Search results for', 'benevolence'); ?> "<?php the_search_query(); ?>" </div><br />
     <?php while (have_posts()) : the_post(); ?>
     <a class="title" href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'benevolence' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a>
     ( <?php _e('Filed under:', 'benevolence'); ?> <?php the_category(', ') ?> <?php the_tags( ' | ' . __( 'Tags' ) . ': ', ', ', ''); ?> )
      <?php the_excerpt() ?>
       <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'benevolence' ), the_title_attribute( 'echo=0' ) ) ); ?>">( more )</a>
<br /><br /><br />
  <?php endwhile; ?>
<?php else : ?>
 <?php _e('Nothing Found', 'benevolence'); ?>
<?php endif; ?>
</div>

<div class="right"><?php posts_nav_link('','',__('previous &raquo;', 'benevolence')) ?></div>
 <div class="left"><?php posts_nav_link('',__('&laquo; newer ', 'benevolence'),'') ?></div>
</div>
<br /><br />

<?php get_footer(); ?>