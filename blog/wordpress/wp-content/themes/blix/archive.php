<?php get_header(); ?>

<div id="content" class="archive">

<?php if ( have_posts() ) : ?>

	<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
	<?php /* If this is a category archive */ if ( is_category() ) { ?>
	<h2><?php printf( __( 'Posts filed under &lsquo;%1$s&rsquo;', 'blix' ), single_cat_title( '', false ) ); ?></h2>

	<?php } elseif (is_tag() ) { ?>
	<h2><?php printf( __( 'Posts tagged &lsquo;%s&rsquo;', 'blix' ), single_tag_title( '', false ) ); ?></h2>

	<?php /* If this is a daily archive */ } elseif ( is_day() ) { ?>
	<h2><?php printf( __( 'Archive for <span>%s</span>', 'blix' ), get_the_date() ); ?></h2>

	<?php /* If this is a monthly archive */ } elseif ( is_month() ) { ?>
	<h2><?php printf( __( 'Archive for <span>%s</span>', 'blix' ), get_the_date( 'F, Y' ) ); ?></h2>

	<?php /* If this is a yearly archive */ } elseif ( is_year() ) { ?>
	<h2><?php printf( __( 'Archive for <span>%s</span>', 'blix' ), get_the_date( 'Y' ) ); ?></h2>

	<?php /* If this is a search */ } elseif ( is_search() ) { ?>
	<h2><?php printf( __( 'Search Results for: &lsquo;%s&rsquo;', 'blix' ), '<span>' . get_search_query() . '</span>' ); ?></h2>

	<?php /* If this is an author archive */ } elseif ( is_author() ) { ?>
	<h2><?php _e( 'Author Archive', 'blix' ); ?></h2>

	<?php /* If this is a paged archive */ } elseif ( isset($_GET['paged'] ) && !empty( $_GET['paged'] ) ) { ?>
	<h2><?php _e( "Blog Archive" ); ?></h2>

<?php } ?>

<?php while ( have_posts() ) : the_post(); ?>

	<div id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>

		<h3><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>

		<?php ($post->post_excerpt != "")? the_excerpt() : BX_shift_down_headlines( the_content() ); ?>

		<p class="info"><?php if ($post->post_excerpt != "") { ?><a href="<?php the_permalink(); ?>" class="more"><?php _e( 'Continue Reading', 'blix' ); ?></a><?php } ?>
   		<?php comments_popup_link( __( 'Add comment', 'blix' ), __( '1 comment', 'blix' ), __( '% comments', 'blix' ), __( 'commentlink', 'blix' ), '' ); ?>
   		<em class="date"><?php the_time( get_option( 'date_format' ) ); ?><!-- at <?php the_time(); ?>--></em>
   		<!--<em class="author"><?php the_author(); ?></em>-->
   		<?php edit_post_link( __( 'Edit', 'blix' ), '<span class="editlink">', '</span>' ); ?>
   		</p>

	</div>


<?php endwhile; ?>

	<p>
		<span class="previous"><?php next_posts_link( __( 'Older Posts', 'blix' ) ); ?></span>
		<span class="next"><?php previous_posts_link( __( 'Newer Posts', 'blix' ) ); ?></span>
	</p>

<?php else : ?>

	<h2><?php _e( 'Not Found', 'blix' ); ?></h2>
	<p><?php _e( 'Sorry, but you are looking for something that isn&rsquo;t here.' ); ?></p>

<?php endif; ?>

</div> <!-- /content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
