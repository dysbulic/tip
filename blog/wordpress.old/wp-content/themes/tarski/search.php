<?php get_header(); ?>
	<?php if (have_posts()) : ?>
		<div id="intro">
			<h1><?php _e( 'Search results', 'tarski' ); ?></h1>
		</div>

		<div id="primary">
		<?php while (have_posts()) : the_post(); ?>
			<div class="post-brief">
				<h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent link to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h3>
				<p class="post-metadata">
					<?php printf( __( '%1$s in %2$s | ', 'tarski' ), get_the_time( get_option( 'date_format' ) ), get_the_category_list( ', ' ) ); ?>
					<?php comments_popup_link(__('Leave a comment', 'tarski'), __('1 comment', 'tarski'), __('% comments', 'tarski'),'',__('Comments closed','tarski')); ?><br />
					<?php the_tags( __( 'Tags: ', 'tarski' ), ', ', '<br />'); ?>
				</p>
				<p class="excerpt"><?php the_excerpt() ?></p>
			</div>
		<?php endwhile; ?>
		</div>

		<div id="secondary">
			<?php get_search_form(); ?>
		</div>
<?php else : ?>
		<div id="intro">
			<h1><?php _e( 'Not found', 'tarski' ); ?></h1>
		</div>

		<div id="primary">
			<p><?php _e( 'Sorry, there were no results returned for the terms you searched for. Please try a new search.', 'tarski' ); ?></p>
		</div>

		<div id="secondary">
			<?php get_search_form(); ?>
		</div>
<?php endif; ?>
<?php get_footer(); ?>
