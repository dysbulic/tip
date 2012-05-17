<?php
/**
 * @package Vertigo
 */

get_header(); ?>

<div id="content" role="main">

	<header class="page-header">
		<h1 class="pagetype">
			<span><?php
				if ( is_day() ) :
					printf( __( 'Daily Archives: %s', 'vertigo' ), '<span>' . get_the_date() . '</span>' );
				elseif ( is_month() ) :
					printf( __( 'Monthly Archives: %s', 'vertigo' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );
				elseif ( is_year() ) :
					printf( __( 'Yearly Archives: %s', 'vertigo' ), '<span>' . get_the_date( 'Y' ) . '</span>' );
				elseif ( is_category() ) :
					printf( __( 'Category Archives: %s', 'vertigo' ), '<span>' . single_cat_title( '', false ) . '</span>' );
				elseif ( is_author() ) :
					printf( __( 'Author Archives: %s', 'vertigo' ), '<span><a class="url fn n" href="' . get_author_posts_url( get_the_author_meta( "ID" ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' );
				elseif ( is_tag() ) :
					printf( __( 'Tag Archives: %s', 'vertigo' ), '<span>' . single_tag_title( '', false ) . '</span>' );
				else :
					_e( 'Archives', 'vertigo' );
				endif;
			?></span>
		</h1>
	</header>

	<?php rewind_posts(); ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', get_post_format() ); ?>

	<?php endwhile; ?>

</div><!-- #content -->

<?php get_footer(); ?>