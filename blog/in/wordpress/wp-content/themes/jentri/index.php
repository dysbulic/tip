<?php get_header(); ?>
<?php is_tag(); ?>
<div id="content">
	<?php if ( have_posts() ) :?>
		<?php $postCount=0; ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php $postCount++; ?>
	<div id="post-<?php the_ID(); ?>" <?php post_class( "entry entry-$postCount" ); ?>>
		<div class="entrytitle">
			<h2>
				<?php if ( ! is_single() ) : ?>
					<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'jentri' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
				<?php else : ?>
					<?php the_title(); ?>
				<?php endif; ?>
			</h2>
			<?php if ( ! is_page() ) : ?>
			<h3><?php the_time( get_option( 'date_format' ) ); ?></h3>
			<?php endif; ?>
		</div>
		<div class="entrybody">
			<?php the_content( __( 'Read the rest of this entry &raquo;', 'jentri' ) ); ?>
			<?php wp_link_pages(); ?>
		</div>

		<div class="entrymeta">
			<?php if ( ! is_page() ) : ?>
			<?php _e( 'Posted in', 'jentri' ); ?> <?php the_category( ', ' ); ?> | <?php the_tags( __( 'Tagged ', 'jentri') , ', ', ' | ' ); ?> <?php edit_post_link( __( 'Edit', 'jentri'), '', ' | ' ); ?>  <?php comments_popup_link( __( 'Leave a Comment &#187;', 'jentri' ), __( '1 Comment &#187;', 'jentri' ), __( '% Comments &#187;', 'jentri' ) ); ?>
			<?php else : ?>
			<?php edit_post_link( __( 'Edit', 'jentri' ) ); ?>
			<?php endif; ?>
		</div>

	</div>
	<div class="commentsblock">
		<?php comments_template(); ?>
	</div>
		<?php endwhile; ?>
		<div class="navigation">
			<div class="alignleft"><?php next_posts_link( __( '&laquo; Previous Entries', 'jentri' ) ); ?></div>
			<div class="alignright"><?php previous_posts_link( __( 'Next Entries &raquo;', 'jentri' ) ); ?></div>
		</div>

	<?php else : ?>

		<h2><?php _e( 'Not found', 'jentri' ); ?></h2>
		<div class="entrybody"><?php _e( "Sorry, but you are looking for something that isn't here.", 'jentri' ); ?></div>

	<?php endif; ?>

	</div>
</div>
<?php get_sidebar(); ?>

</body>
</html>
