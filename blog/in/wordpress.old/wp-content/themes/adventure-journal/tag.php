<?php
/**
 * @package Adventure_Journal
 */

get_header(); ?>

	<div id="content" class="clearfix">
		<div id="main-content">
			<h1><?php printf( __( 'Posts Tagged With: %s', 'adventurejournal' ), '<span>' . single_tag_title( '', false ) . '</span>' ); ?></h1>
	
			<?php get_template_part( 'loop', 'tag' ); ?>
		</div><!-- #main-content -->

		<?php get_sidebar(); ?>
	</div><!-- #content -->

<?php get_footer(); ?>