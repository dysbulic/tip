<?php get_header(); ?>

<div id="content">

<?php if ( have_posts() ) : ?>

<?php while ( have_posts() ) : the_post(); ?>

	<div id="post-<?php the_ID(); ?>" <?php post_class( 'entry single' ); ?>>

		<h2><?php the_title(); ?></h2>

		<p class="info">
   		<?php blix_posted_on(); ?>
   		<?php blix_posted_by(); ?>
		<?php if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
			<em class="comments-popup">
			<?php comments_popup_link( __( 'Leave a comment' ), __( '1 comment' ), __( '% comments' ), __( 'commentlink', 'blix' ), '' ); ?>
			</em>
		<?php endif; ?>
   		<?php edit_post_link( __( 'Edit', 'blix' ), '<span class="editlink">', '</span>' ); ?>
   		</p>

		<?php the_content(); ?>
		<?php wp_link_pages(); ?>

		<p id="filedunder">
			<?php
				$tag_list = get_the_tag_list( '', ', ' );
				printf( __( 'Entry filed under: %1$s. Tags: %2$s.' ),
					get_the_category_list( ', ' ),
					$tag_list
				 );
			?>
		</p>

   </div>

	<p id="entrynavigation">
		<?php previous_post( '<span class="previous">%</span>','','yes' ); ?>
		<?php next_post( '<span class="next">%</span>','','yes' ); ?>
	</p>

<?php endwhile; ?>

<?php else : ?>

	<h2><?php _e( 'Not Found', 'blix' ); ?></h2>
	<p><?php _e( 'Sorry, but you are looking for something that isn&rsquo;t here.', 'blix' ); ?></p>

<?php endif; ?>

<?php comments_template(); ?>

</div> <!-- /content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
