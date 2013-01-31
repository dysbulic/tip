<?php
/**
 * @package WordPress
 * @subpackage Fjords
 */
?>
<?php get_header(); ?>

	<?php if ( have_posts() ) : ?>
	
		<?php $post = $posts[ 0 ]; // Thanks Kubrick for this code ?>
		
		<?php if ( is_category() ) { ?>				
		<h2><?php printf( __( 'Archive for %s', 'fjords' ), single_cat_title( '', false ) ); ?></h2>
		
 	  	<?php } elseif ( is_tag() ) { ?>
		<h2><?php printf( __( 'Archive for %s', 'fjords' ), single_tag_title( '', false ) ); ?></h2>
		
 	  	<?php } elseif ( is_day() ) { ?>
		<h2><?php printf( __( 'Archive for %s', 'fjords' ), get_the_time( 'F j, Y' ) ); ?></h2>
		
	 	<?php } elseif ( is_month() ) { ?>
		<h2><?php printf( __( 'Archive for %s', 'fjords' ), get_the_time( 'F, Y' ) ); ?></h2>

		<?php } elseif ( is_year() ) { ?>
		<h2><?php printf( __( 'Archive for %s', 'fjords' ), get_the_time( 'Y' ) ); ?></h2>

		<?php } elseif ( is_author() ) { ?>
		<h2><?php _e( 'Author Archive', 'fjords' ); ?></h2>

		<?php } elseif ( is_search() ) { ?>
		<h2><?php _e( 'Search Results', 'fjords' ); ?></h2>

		<?php } ?>
				
		<?php while ( have_posts() ) : the_post(); ?>

			<div <?php post_class(); ?>>
				<h2 class="post-titulo" id="post-<?php the_ID(); ?>"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent link to %s', 'fjords' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2>
				<p class="postmeta">
				<?php printf( __( '%1$s at %2$s', 'fjords' ), get_the_time( get_option( 'date_format' ) ), get_the_time() ); ?> &#183; <?php _e( 'Filed under' ); ?> <?php the_category( ', ' ); ?> <?php the_tags( __( 'and tagged: ', 'fjords' ), ', ', '' ); ?> <?php edit_post_link( __( 'Edit', 'fjords' ), ' &#183; ', '' ); ?></p>
				<?php if ( is_search() ) { ?>
					<?php the_excerpt(); ?>
				<?php } else { ?>
					<?php the_content( __( 'Read the rest of this entry &raquo;', 'fjords' ) ); ?>
				<?php } ?>

				<p class="comentarios-link"><?php comments_popup_link( __( 'Comments', 'fjords' ), __( 'Comments (1)', 'fjords' ), __( 'Comments (%)', 'fjords' ), 'commentslink', __( 'Comments off', 'fjords' ) ); ?>
</p>
			</div>
				
		<?php endwhile; ?>

<?php posts_nav_link( ' &#183; ',  __('&laquo; Newer entries', 'fjords' ), __( 'Older entries &raquo;', 'fjords' ), '' ); ?>
		
	<?php else : ?>

		<h2><?php _e( 'Not Found', 'fjords' ); ?></h2>

		<p><?php _e( 'Sorry, but no posts matched your criteria.', 'fjords' ); ?></p>
		
		<h3><?php _e( 'Search', 'fjords' ); ?></h3>
		
		<?php get_search_form(); ?>

	<?php endif; ?>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
