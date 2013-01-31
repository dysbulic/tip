<?php
/**
 * The template for displaying content in list format
 *
 * @package WordPress
 * @subpackage Chateau
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header>
		<h1><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'chateau' ), the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a></h1>
	</header>
	<span><?php the_time( get_option( 'date_format' ) ); ?></span>
	<?php the_excerpt(); ?>
</article><!-- #post-<?php the_ID(); ?> -->