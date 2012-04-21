<?php
/**
 * @package WordPress
 * @subpackage Greyzed
 */

get_header(); ?>

<div id="container">	
<?php get_sidebar(); ?>	
	<div id="content" role="main">
		<div class="column">
		
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
				<div class="posttitle">
					<h2 class="pagetitle"><?php the_title(); ?></h2>
					<small>
						<?php printf( __( 'Posted: %1$s by <strong>%2$s</strong> in %3$s', 'greyzed' ),
							get_the_date( get_option( 'date_format' ) ),
							get_the_author(),
							get_the_category_list( ', ' )
							); ?>
						<br />
						<?php the_tags( __( 'Tags: ', 'greyzed' ), ', ', ''); ?>
					</small>
				</div>
				<?php if ( ( comments_open() ) && ( ! post_password_required() ) ) : ?>
					<div class="postcomments"><?php comments_popup_link( '0', '1', '%' ); ?></div>
				<?php endif; ?>
				<div class="entry">
					<?php the_content(); ?>
					<?php wp_link_pages(array('before' => '<p><strong>'. __( 'Pages:', 'greyzed') . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
					<?php edit_post_link( __( 'Edit this entry.', 'greyzed' ), '<p>', '</p>' ); ?>
				</div>

		<?php comments_template(); ?>
	
		<?php endwhile; else: ?>
		
			<p><?php _e( 'Sorry, no posts matched your criteria.', 'greyzed' ); ?></p>
		
		<?php endif; ?>
	
		</div>
	</div>
	
	<div id="nav-post">
		<div class="navigation-bott">
			<?php previous_post_link( '<div class="leftnav">%link</div>' ); ?>
			<?php next_post_link( '<div class="rightnav">%link</div>' ); ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>