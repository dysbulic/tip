<?php
/**
 * @package Imbalance 2
 */
?>
<?php $options = imbalance2_get_theme_options(); ?>

<?php get_header(); ?>

<div id="container">
	<div id="content">

	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', 'single' ); ?>

		<?php
			if ( comments_open() || '0' != get_comments_number() )
				comments_template( '', true );
		?>

	<?php endwhile; ?>

	<?php
		if ( 'yes' == $options['sticky'] )
			get_template_part( 'featured' );
	?>

	</div><!-- #content -->
</div><!-- #container -->

<?php get_footer(); ?>