<?php
/**
 * The template that shows single posts
 * @package WordPress
 * @subpackage Selecta
 */
get_header();
?>

<?php
	// Access global variable directly to set content_width
	if ( isset( $GLOBALS['content_width'] ) ) :
		if ( 'video' == get_post_format() || 'image' == get_post_format() ) :
			$GLOBALS['content_width'] = 940;
		endif;
	endif;
?>
<?php while ( have_posts() ) : the_post(); ?>

<div id="single-header">
	<div class="single-title-wrap">
		<h1 class="single-title"><?php the_title(); ?></h1>
	</div><!-- .single-title-wrap" -->
</div><!-- #single-header-->

<?php endwhile; // end of the loop. ?>

<?php rewind_posts(); ?>

<div id="main" class="clearfix">

	<div id="content" role="main">

		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', get_post_format() ); ?>

			<?php comments_template( '', true ); ?>

			<nav id="nav-below">
				<h3 class="assistive-text"><?php _e( 'Post navigation', 'selecta' ); ?></h3>
				<span class="nav-previous"><?php previous_post_link( '%link', __( '&larr; Previous Post', 'selecta' ) ); ?></span>
				<span class="nav-next"><?php next_post_link( '%link', __( 'Next Post &rarr;', 'selecta' ) ); ?></span>
			</nav><!-- #nav-below -->

		<?php endwhile; // end of the loop. ?>

	</div><!-- #content -->

	<?php get_sidebar(); ?>

</div><!-- #main -->

<?php get_footer(); ?>