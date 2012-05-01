<?php get_header(); ?>
<div id="content">
	<?php if ( have_posts() ) : ?>
		<?php $postCount=0; ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php $postCount++;?>
	<div <?php post_class( 'entry entry-' . $postCount); ?> id="post-<?php the_ID(); ?>">
		<div class="entrytitle">
			<h2>
			<?php if ( ! is_single() ) : ?>
				<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'treba' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
			<?php else : ?>
				<?php the_title(); ?>
			<?php endif; ?>
			</h2>
			<?php if ( !is_page() ) { ?>
			<h3><?php the_time( get_option( 'date_format' ) ); ?></h3>
			<?php } ?>
		</div>
		<div class="entrybody">
			<?php the_content( __( 'Read the rest of this entry &raquo;', 'treba' ) ); ?>
			<?php wp_link_pages(); ?>
		</div>

		<div class="entrymeta">
			<p><?php printf( __( 'Posted in %1$s', 'treba' ), get_the_category_list( ', ' ) ); ?> | <?php edit_post_link( __( 'Edit', 'treba' ), '', ' | ' ); ?>  <?php comments_popup_link( __( 'Leave a Comment &#187;','treba' ), __( '1 Comment &#187;', 'treba' ), __( '% Comments &#187;', 'treba' ) ); ?><br /><?php the_tags( __( 'Tags: ', 'treba' ), ', ', '<br />' ); ?></p>
		</div>

	</div>
	<div class="commentsblock">
		<?php comments_template(); ?>
	</div>
		<?php endwhile; ?>
		<div class="navigation">
			<div class="alignleft"><?php next_posts_link( __( '&laquo; Previous Entries', 'treba' ) ); ?></div>
			<div class="alignright"><?php previous_posts_link( __( 'Next Entries &raquo;', 'treba' ) ); ?></div>
		</div>

	<?php else : ?>

		<h2><?php _e( 'Not Found', 'treba' ); ?></h2>
		<div class="entrybody"><?php _e( "Sorry, but you are looking for something that isn't here.", 'treba' ); ?></div>

	<?php endif; ?>
	<?php get_footer(); ?>
	</div>

<?php get_sidebar(); ?>

</div>
<?php wp_footer(); ?>
</body>
</html>