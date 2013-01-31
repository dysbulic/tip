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
     <?php if ( 'page' != get_post_type() ) { ?>
		 (
			<?php if ( is_multi_author() ) {
				printf( __( '<span class="by-author-search"><span class="author-sep">By: </span> <a href="%1$s" title="%2$s">%3$s</a></span>', 'benevolence' ),
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					esc_attr( sprintf( __( 'View all posts by %s', 'benevolence' ), get_the_author_meta( 'display_name' ) ) ),
					esc_attr( get_the_author_meta( 'display_name' ) )
				);
				echo ' | ';
			} ?>
		 <?php _e('Filed under:', 'benevolence'); ?> <?php the_category(', ') ?> <?php the_tags( ' | ' . __( 'Tags' ) . ': ', ', ', ''); ?> )
     <?php } ?>
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