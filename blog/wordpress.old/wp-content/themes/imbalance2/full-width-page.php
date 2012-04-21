<?php
/**
 * Template Name: Full-width
 * @package Imbalance 2
 */
?>
<?php get_header(); ?>

<div id="container" class="one-column">

	<div id="content" role="main">

	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', 'page' ); ?>

		<?php
			if ( comments_open() || '0' != get_comments_number() )
				comments_template( '', true );
		?>

	<?php endwhile; ?>

	</div><!-- #content -->

</div><!-- #container -->

<?php get_footer(); ?>