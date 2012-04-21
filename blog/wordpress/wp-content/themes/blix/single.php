<?php get_header(); ?>

<div id="content">

<?php if ( have_posts() ) : ?>

<?php while ( have_posts() ) : the_post(); ?>

	<div id="post-<?php the_ID(); ?>" <?php post_class( 'entry single' ); ?>>

		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

		<p class="info">
		<?php if ( $post->comment_status == "open" ) ?>
   		<em class="date"><?php the_time( get_option( 'date_format' ) ); ?><!-- at <?php the_time(); ?>--></em>
   		<!--<em class="author"><?php the_author(); ?></em>-->
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
