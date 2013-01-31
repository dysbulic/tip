<?php get_header(); ?>

	<div id="content">

	<?php if (have_posts()) : ?>
		
		<?php while (have_posts()) : the_post(); ?>
				
			<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
			
				<div class="date">
					<div class="date_month"><?php the_time('M') ?></div>
					<div class="date_day"><?php the_time('d') ?></div>
				</div>
				
				<div class="title_box">
					<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'sunburn' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2> 
					<div class="comment_link"><?php comments_popup_link( __( 'Leave a Comment', 'sunburn' ), __( '1 Comment', 'sunburn' ), __( '% Comments', 'sunburn' ) ); ?></div>
				</div>
				
				<div class="entry">
					<?php the_content( __( 'Read the rest of this entry &raquo;', 'sunburn' ) ); ?>
				</div>
		
				<p class="postmetadata">
					<?php
						_e( 'Categorized in', 'theme-slug' );
						echo ' ';
						the_nice_category(', ', ' ' . __( 'and', 'sunburn' ) . ' ' );
						edit_post_link( __( 'Edit', 'sunburn' ), ' | ', '');
					?>
					<br />
					<?php the_tags( __( 'Tags:', 'sunburn' ) . ' ', ', ', '<br />');
					?>
				</p>
			</div>
	
		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link( __( '&laquo; Previous Entries', 'sunburn' ) ); ?></div>
			<div class="alignright"><?php previous_posts_link( __( 'Next Entries &raquo;', 'sunburn' ) ); ?></div>
		</div>
		
	<?php else : ?>

		<h2 class="center"><?php _e( 'Not Found', 'sunburn' ); ?></h2>
		<p class="center"><?php _e( 'Sorry, but you are looking for something that isn&rsquo;t here.', 'sunburn' ); ?></p>
		<?php get_search_form(); ?>

	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
