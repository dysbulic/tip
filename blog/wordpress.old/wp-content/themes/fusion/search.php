<?php
/**
 * @package WordPress
 * @subpackage Fusion
 */
?>
<?php get_header(); ?>

	<div id="mid-content">

	<?php if ( have_posts() ) : ?>

		<h1><?php printf( __( 'Search Results for: %s', 'fusion' ), '<span>' . get_search_query() . '</span>'); ?></h1>

		<?php while ( have_posts() ) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" class="post">
			
			<h3 class="title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'fusion' ), the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a></h3>
			
			<div class="postheader">
				<div class="postinfo">
				<p><?php printf( __( 'Posted by %s in %s on %s', 'fusion' ),'<a href="'. get_author_posts_url( get_the_author_meta('ID') ) .'" title="'. sprintf( __( 'Posts by %s', 'fusion'), esc_attr( get_the_author() ) ).' ">'. get_the_author() .'</a>', get_the_category_list( ', ' ), get_the_time( get_option( 'date_format' ) ) ); ?> <?php edit_post_link( __( 'Edit', 'fusion' ), '| ', '' ); ?></p>
				</div>
			</div>
			
			<div class="postbody entry clearfix">
				<?php fusion_current_post_length(); ?>
				<?php wp_link_pages( array( 'before' => '<p class="postpages"><strong>Pages: </strong> ', 'after' => '</p>', 'next_or_number' => 'number' ) ); ?>
			</div>
		
	<?php
		$posttags = get_the_tags();
		if ($posttags) { ?>
		<p class="tags"><?php the_tags( '' ); ?></p>
	<?php } ?>
		<p class="postcontrols">
	<?php
		global $id, $comment;
		$number = get_comments_number( $id );
	?>

			<a class="<?php if ( $number < 1 ) { echo 'no '; }?>comments" href="<?php comments_link(); ?>"><?php comments_number( __( 'Leave a Comment', 'fusion' ), __( '1 Comment', 'fusion' ), __( '% Comments', 'fusion' ) ); ?></a>
		</p>

		<div class="clear"></div>
				
		</div>

		<?php endwhile; ?>

		<div class="navigation" id="pagenavi">

			<div class="alignleft"><?php next_posts_link( __( '&laquo; Older Entries', 'fusion' ) ); ?></div>
			<div class="alignright"><?php previous_posts_link( __( 'Newer Entries &raquo;', 'fusion' ) ); ?></div>

			<div class="clear"></div>

		</div>

	<?php else : ?>

		<h1><?php _e( 'No posts found. Try a different search?', 'fusion' ); ?></h1>
		<?php get_search_form(); ?>

	<?php endif; ?>

	</div>
	<!-- mid content -->

</div>
<!-- /mid -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>