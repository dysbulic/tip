<?php get_header(); ?>
		<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="post-header">
				<h1><?php the_title(); ?></h1>
				<div id="single-date" class="date"><?php the_time(get_option("date_format")); ?></div>
			</div><!--end post header-->
			<div class="meta clear">
				<div class="tags"><?php the_tags('tags: ', ', ', ''); ?></div>
				<div class="author">
					<?php vigilance_posted_by(); ?>
				</div>
			</div><!--end meta-->
			<div class="entry clear">
				<?php if ( function_exists( 'add_theme_support' ) ) the_post_thumbnail( array(250,9999), array('class' => 'alignleft') ); ?>
				<?php the_content(); ?>
				<?php edit_post_link(__('Edit', 'vigilance'),'<p>','</p>'); ?>
				<?php wp_link_pages(); ?>
			</div><!--end entry-->
			<div class="post-footer">
				<div class="categories"><?php _e('from &rarr;', 'vigilance'); ?> <?php the_category(', '); ?></div>
			</div><!--end post footer-->
		</div><!--end post-->
		<?php endwhile; /* rewind or continue if all posts have been fetched */ ?>
		<div class="navigation post single clear">
			<div class="alignleft"><?php previous_post_link( '%link', _x( '&larr;', 'Previous post', 'vigilance' ) . ' %title' ); ?></div>
			<div class="alignright" ><?php next_post_link( '%link', _x( '%title ', 'Next post', 'vigilance' ) . '&rarr;' ); ?></div>
		</div><!--end pagination-->
		<?php comments_template('', true); ?>
		<?php else : ?>
		<?php endif; ?>
	</div><!--end content-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>