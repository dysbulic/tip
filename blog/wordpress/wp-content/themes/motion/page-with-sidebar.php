<?php
/**
 * @package WordPress
 * @subpackage Motion
 */
/*
Template Name: Page with sidebar
*/
?>

<?php get_header(); ?>

<div id="main">

	<div id="content">

		<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>

		<?php if ( function_exists( 'wp_list_comments' ) ) : ?>
		<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
		<?php else : ?>
		<div class="post" id="post-<?php the_ID(); ?>">
		<?php endif; ?>

			<div class="posttop">
				<h2 class="posttitle"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s' ), the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a></h2>
			</div>

			<div class="postcontent">
				<?php the_content( __( 'Read more &raquo;' ) ); ?>
				<div class="linkpages"><?php wp_link_pages( 'link_before=<span>&link_after=</span>' ); ?></div>
			</div>
			<small><?php edit_post_link( __( 'Admin: Edit this entry' ) , '' , '' ); ?></small>

		</div><!-- /post -->

		<div id="comments">
		<?php comments_template( '', true ); ?>
		</div><!-- /comments -->

		<?php endwhile; ?>

		<?php else : ?>
		<div class="post">
			<div class="posttop">
				<h2 class="posttitle"><a href="#"><?php _e( 'Oops!' ); ?></a></h2>
			</div>
			<div class="postcontent">
				<p><?php _e( 'What you are looking for doesn&rsquo;t seem to be on this page...' ); ?></p>
			</div>
		</div><!-- /post -->
		<?php endif; ?>

	</div><!-- /content -->

	<?php get_sidebar(); ?>

</div><!-- /main -->

<?php get_footer(); ?>