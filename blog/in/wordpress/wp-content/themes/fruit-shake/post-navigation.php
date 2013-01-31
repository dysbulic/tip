<?php
/**
 * @package WordPress
 * @subpackage Fruit Shake
 */
?>

<?php if ( is_single() ) : ?>
	<nav id="content-nav">
		<h1 class="section-heading"><?php _e( 'Post navigation', 'fruit-shake' ); ?></h1>
		<div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'fruit-shake' ) . '</span> %title' ); ?></div>
		<div class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'fruit-shake' ) . '</span>' ); ?></div>
	</nav><!-- #content-nav -->

<?php else : ?>
	<?php if (  $wp_query->max_num_pages > 1 ) : /* Display navigation to next/previous pages when applicable */ ?>
		<nav id="content-nav">
			<h1 class="section-heading"><?php _e( 'Post navigation', 'fruit-shake' ); ?></h1>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'fruit-shake' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'fruit-shake' ) ); ?></div>
		</nav><!-- #content-nav -->
	<?php endif; ?>	
<?php endif; ?>
