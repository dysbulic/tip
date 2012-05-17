<?php
/**
 * @package WordPress
 * @subpackage Fusion
 */
?>
<?php get_header(); ?>

	<div id="mid-content">

	<?php if ( have_posts() ) : ?>

		<?php $post = $posts[ 0 ]; // Set $post so that the_date() works. ?>
		
		<?php /* If this is a category archive */ if ( is_category() ) { ?>
		<h1 class="pagetitle"><?php printf( __( 'Archive for category %s', 'fusion' ), single_cat_title( '', false) ); ?></h1>
		
		<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h1 class="pagetitle"><?php printf( __( 'Posts Tagged %s', 'fusion' ), single_cat_title( '', false ) ); ?></h1>
		
		<?php /* If this is a daily archive */ } elseif ( is_day() ) { ?>
		<h1 class="pagetitle"><?php	printf( __( 'Archive for %s', 'fusion' ), get_the_time( __( 'F jS, Y', 'fusion' ) ) );	?></h1>
		
		<?php /* If this is a monthly archive */ } elseif ( is_month() ) { ?>
		<h1 class="pagetitle"><?php	printf( __( 'Archive for %s', 'fusion' ), get_the_time( __( 'F, Y', 'fusion' ) ) );	?></h1>
		
		<?php /* If this is a yearly archive */ } elseif ( is_year() ) { ?>
		<h1 class="pagetitle"><?php	printf( __( 'Archive for %s', 'fusion' ), get_the_time( __( 'Y', 'fusion' ) ) );	?></h1>
		
		<?php /* If this is an author archive */ } elseif ( is_author() ) { ?>
		<h1 class="pagetitle"><?php _e( 'Author Archive', 'fusion' ); ?></h1>
		
		<?php /* If this is a paged archive */ } elseif ( isset( $_GET[ 'paged' ] ) && !empty( $_GET[ 'paged' ] ) ) { ?>
		<h1 class="pagetitle"><?php _e( 'Blog Archives', 'fusion' ); ?></h1>
		
		<?php } ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h3 class="title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'fusion' ), the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a></h3>

				<div class="postheader">
						<div class="postinfo">
						<p><?php printf( __( 'Posted by %s in %s on %s', 'fusion' ),'<a href="'. get_author_posts_url( get_the_author_meta( 'ID' ) ) .'" title="'. sprintf( __( 'Posts by %s', 'fusion' ), esc_attr( get_the_author() ) ).' ">'. get_the_author() .'</a>', get_the_category_list( ', ' ), get_the_time( get_option( 'date_format' ) ) ); ?> <?php edit_post_link( __( 'Edit', 'fusion' ), '| ', '' ); ?></p>
						</div>
				</div>

			<div class="postbody entry clearfix">
				<?php fusion_current_post_length(); ?>
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
						<a class="<?php if( $number<1 ) { echo 'no '; } ?>comments" href="<?php comments_link(); ?>"><?php comments_number( __( 'Leave a Comment', 'fusion' ), __( '1 Comment', 'fusion' ), __( '% Comments', 'fusion' ) ); ?></a>
					</p>
					<div class="clear"></div>
			</div>
			<!-- /post -->
			
		<?php endwhile; ?>

		<div class="navigation" id="pagenavi">

			<div class="alignleft"><?php next_posts_link( __( '&laquo; Older Entries', 'fusion' ) ); ?></div>
			<div class="alignright"><?php previous_posts_link( __( 'Newer Entries &raquo;', 'fusion' ) ); ?></div>

			<div class="clear"></div>

		</div>

	<?php else :
		if ( is_category() ) { // If this is a category archive
				?> <h2> <?php printf( __( "Sorry, but there aren't any posts in the %s category yet.", "fusion" ), single_cat_title( '', false ) ); ?> </h2> <?php
		} else if ( is_date() ) { // If this is a date archive
			?> <h2> <?php _e( "Sorry, but there aren't any posts with this date." ); ?> </h2> <?php
		} else if ( is_author() ) { // If this is a category archive
			$userdata = get_userdatabylogin( get_query_var( 'author_name' ) );
			?> <h2> <?php printf( __( "Sorry, but there aren't any posts by %s yet.", "fusion" ), $userdata->display_name ); ?> </h2> <?php
		} else {
			?> <h2> <?php _e( 'No posts found.' ); ?> </h2> <?php
		}
		get_search_form();

		endif;
?>
	</div>
	<!-- mid content -->
		
</div>
<!-- /mid -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>