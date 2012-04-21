<?php
/**
 * @package WordPress
 * @subpackage StrangeLittleTown
 */
?>

<?php get_header(); ?>

<div id="content">

	<header id="introduction" class="contain">
		<hgroup>
		<?php
			$title = __( 'Archives', 'strange-little-town' );
			$description = '';
			if ( is_tag() ) :
				$title = single_tag_title( '', false );
				$description = sprintf( __( 'All posts tagged %1$s', 'strange-little-town' ), $title );
			elseif ( is_category() ) :
				$title = single_cat_title( '', false );
				$description = sprintf( __( 'All posts in the %1$s category', 'strange-little-town' ), $title );
			elseif ( is_day() ) :
				$description = sprintf( __( 'All posts for the day %1$s', 'strange-little-town' ), esc_html( get_the_date( __( 'F jS, Y', 'strange-little-town' ) ) ) );
			elseif ( is_month() ) :
				$description = sprintf( __( 'All posts for the month %1$s', 'strange-little-town' ), esc_html( get_the_date( __( 'F, Y', 'strange-little-town' ) ) ) );
			elseif ( is_year() ) :
				$description = sprintf( __( 'All posts for the year %1$s', 'strange-little-town' ), esc_html( get_the_date( __( 'Y', 'strange-little-town' ) ) ) );
			elseif ( is_author() ) :
				the_post();
				$description = sprintf( __( 'All posts by %1$s', 'strange-little-town' ), esc_html( get_the_author() ) );
				rewind_posts();
			endif;

			echo '<h1 id="page-title">' . $title . '</h1>';
			echo '<h2 id="page-tagline">' . $description . '</h2>';

		?>
		</hgroup>
	</header>

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', get_post_format() ); ?>

		<?php endwhile; ?>

			<?php get_template_part( 'nav', 'posts' ); ?>

	<?php else : ?>

		<?php get_template_part( 'content', '404' ); ?>

	<?php endif; ?>

</div><!-- content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>