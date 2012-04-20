<?php get_header(); ?>

<?php the_post(); ?>
	
<div <?php post_class(); ?>>
<div class="heading"><span id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'girl' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></span></div>

<div class="entry">
<?php the_content(__('Read the rest of this entry &raquo;','girl')); ?>
<?php wp_link_pages(); ?>
</div>

<div class="footer"><?php edit_post_link(__('Edit?','girl'),' (',')'); ?></div>
</div>

<br />
<br />

			<?php comments_template(); ?>		
		
</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
