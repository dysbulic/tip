<?php get_header(); ?>

<!-- BEGIN HOME.PHP -->
<div id="content">
<!-- Begin conditional statement -->
<?php if ( $paged < 2 ) { // Do stuff specific to first page?>
<?php } else { // Do stuff specific to non-first page ?>
<!-- optional content for /page/2/ and up goes here -->
<?php get_template_part( 'navigation' ); ?>
<?php } ?>
<!-- End conditional statement -->

<?php if ( have_posts() ) : ?>
<?php while ( have_posts() ) : the_post(); ?>

	<div <?php post_class(); ?>>
		<h3 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h3>
		<small><?php the_time( get_option( 'date_format' ) ); ?> <!-- by <?php the_author(); ?> --></small>
		<div class="entry">
			<?php the_content( __( 'Read more &raquo;', 'vermilionchristmas' ) ); ?>
		</div>
		<p class="postmetadata"><?php printf( __( 'Posted in %1$s', 'vermilionchristmas' ), get_the_category_list( ', ' ) ); ?> &nbsp;|&nbsp; <?php the_tags( __( 'Tags: ', 'vermilionchristmas' ), ', ', ' &nbsp;|&nbsp; ' ); ?> <span class="commentlink"><?php comments_popup_link( __( 'Leave a Comment &#187;', 'vermilionchristmas' ), __( '1 Comment &#187;', 'vermilionchristmas' ), __( '% Comments &#187;', 'vermilionchristmas' ) ); ?></span></p>
		<?php edit_post_link( __( 'edit', 'vermilionchristmas' ),'<div class="edit">[',']</div>' ); ?>
	</div>

<?php endwhile; ?>

<?php get_template_part( 'navigation' ); ?>

<?php else : ?>

	<h2 class="center"><?php _e( 'Not Found', 'vermilionchristmas' ); ?></h2>
	<p class="center"><?php _e( "Sorry, but you are looking for something that isn't here.", 'vermilionchristmas' ); ?></p>
	<?php get_search_form(); ?>

<?php endif; ?>

</div>
<!-- END HOME.PHP -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>