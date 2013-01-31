<?php get_header(); ?>

<!-- BEGIN search.php -->
<div id="content">
<?php if ( have_posts() ) : ?>

	<h2 class="search-title"><?php printf( __( 'Search Results for &quot;%1$s&quot;...', 'vermilionchristmas' ), get_search_query() ); ?></h2>
	
	<?php while ( have_posts() ) : the_post(); ?>
	
	<div <?php post_class(); ?>>
		<h3 class="post-title" id="post-<?php the_ID(); ?>"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h3>
		<small><?php the_time( get_option( 'date_format' ) ); ?> <!-- by <?php the_author(); ?> --></small>
		<div class="entry">
			<?php the_excerpt(); ?>
		</div>
		<p class="postmetadata"><?php printf( __( 'Posted in %1$s', 'vermilionchristmas' ), get_the_category_list( ', ' ) ); ?> &nbsp;|&nbsp; <span class="commentlink"><?php comments_popup_link( __( 'Leave a Comment &#187;', 'vermilionchristmas' ), __( '1 Comment &#187;', 'vermilionchristmas' ), __( '% Comments &#187;', 'vermilionchristmas' ) ); ?></span><br /><?php the_tags( __( 'Tags: ', 'vermilionchristmas' ), ', ', '<br />' ); ?></p>
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
<!-- END search.php -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>