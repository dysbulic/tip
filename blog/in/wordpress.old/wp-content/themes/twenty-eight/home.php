<?php get_header(); ?>
<div class="content">
	<div class="primary">
		<?php while ( have_posts() ) { the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class( 'item entry' ); ?>>
			<div class="itemhead">
				<h3><a href="<?php the_permalink(); ?>" rel="bookmark" title='<?php the_title_attribute(); ?>'><?php the_title(); ?></a></h3>
				<?php edit_post_link( '<img src="' . get_bloginfo(template_directory) . '/images/pencil.png" alt="Edit Link" />','<span class="editlink">','</span>' ); ?>
				<p class="metadata">
					<?php printf( __( '%1$s in %2$s', 'twenty-eight' ), get_the_time( get_option( 'date_format' ) ), get_the_category_list( ', ' ) ); ?><?php if ( (is_home()) && !(is_page()) && !(is_single()) && !(is_search()) && !(is_archive()) && !(is_author()) && !(is_category()) && !(is_paged()) OR is_search() ) { ?>. <?php comments_popup_link( __( 'Leave a <span>Comment</span>', 'twenty-eight' ), __( '1 <span>Comment</span>', 'twenty-eight' ), __( '% <span>Comments</span>', 'twenty-eight' ), 'commentslink', '<span class="commentslink">' . __( 'Closed', 'twenty-eight' ) . '</span>'); ?><?php } ?><?php the_tags('<br />' . __( 'Tags: ', 'twenty-eight' ), ', ', '<br />' ); ?></p>
			</div>
			<div class="itemtext">
				<?php if ( is_archive() or is_search() ) { 
					the_excerpt();
					} else {
					the_content( __( 'Continue Reading &raquo;', 'twenty-eight' ) );
				} ?>
				<?php link_pages( '<p><strong>' . __( 'Pages:', 'twenty-eight' ) . '</strong> ', '</p>', 'number' ); ?>
			</div>
		</div>
		<?php } ?>
		<p align="center"><?php posts_nav_link(); ?></p>	
	</div> <!-- close primary -->
	<?php get_sidebar(); ?>
</div> <!-- close content -->

<?php get_footer(); ?>