<?php get_header(); ?>

<!-- BEGIN INDEX.PHP -->
<div id="content">

<?php if ( have_posts() ) : ?>
<?php while ( have_posts() ) : the_post(); ?>

	<h2 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2>
	<small><?php the_time( get_option( 'date_format') ); ?> <!-- by <?php the_author(); ?> --></small>

	<div class="entry">
		<?php the_content( __( 'Read the rest of this entry &raquo;', 'vermilionchristmas' ) ); ?>
	</div>
	<p class="postmetadata"><?php printf( __( 'Posted in %1$s', 'vermilionchristmas' ), get_the_category_list( ', ' ) ); ?> &nbsp;|&nbsp; <?php the_tags( __( 'Tags: ', 'vermilionchristmas' ), ', ', ' &nbsp;|&nbsp; ' ); ?> <?php edit_post_link( __( 'Edit', 'vermilionchristmas' ), '', ' | ' ); ?>  <?php comments_popup_link( __( 'Leave a Comment &#187;', 'vermilionchristmas' ), __( '1 Comment &#187;', 'vermilionchristmas' ), __( '% Comments &#187;', 'vermilionchristmas' ) ); ?></p>

<?php endwhile; ?>

	<div class="navigation">
		<div class="alignleft"><?php next_posts_link( '&laquo; Previous Entries' ); ?></div>
		<div class="alignright"><?php previous_posts_link( 'Next Entries &raquo;' ); ?></div>
	</div>

<?php else : ?>

	<h2 class="center"><?php _e( 'Not Found', 'vermilionchristmas' ); ?></h2>
	<p class="center"><?php _e( "Sorry, but you are looking for something that isn't here.", 'vermilionchristmas' ); ?></p>
	<?php get_search_form(); ?>

<?php endif; ?>
</div>
<!-- END INDEX.PHP -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>