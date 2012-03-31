<?php get_header(); ?>

	<div id="content" class="narrowcolumn">

	<?php if (have_posts()) : ?>
		
		<?php while (have_posts()) : the_post(); ?>
				
			<div <?php post_class(); ?>>
				<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link: %s', 'whiteasmilk' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2>
				<small><?php the_time(get_option('date_format')) ?> <!-- by <?php the_author() ?> --></small>
				
				<div class="entry">
					<?php the_content(__('Read the rest of this entry &raquo;','whiteasmilk')); ?>
				</div>
		
				<p class="postmetadata"><?php _e('Posted in','whiteasmilk'); ?> <?php the_category(', ') ?> <strong>|</strong> <!--<?php edit_post_link(__('Edit','whiteasmilk'),'','<strong>|</strong>'); ?>-->  <?php comments_popup_link(__('Leave a Comment &#187;','whiteasmilk'),__('1 Comment &#187;','whiteasmilk'),__('% Comments &#187;','whiteasmilk')); ?><br /><?php the_tags('Tags: ', ', ', '<br />'); ?></p> 
				<hr />
			</div>
	
		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link( __( '&laquo; Older posts', 'whiteasmilk' ) ); ?></div>
			<div class="alignright"><?php previous_posts_link( __( 'Newer posts &raquo;', 'whiteasmilk' ) ); ?></div>		
		</div>
		
	<?php else : ?>

		<h2 class="center"><?php _e('Not Found','whiteasmilk'); ?></h2>
		<p class="center"><?php _e("Sorry, but you are looking for something that isn't here.","whiteasmilk"); ?></p>
		<?php get_search_form(); ?>

	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
