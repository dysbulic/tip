<?php
/**
 * @package WordPress
 * @subpackage Retro MacOS
 */
get_header(); ?>

	<div id="content" class="narrowcolumn">

		<?php if ( have_posts() ) : ?>

		<h2 class="pagetitle">
			<?php
				if ( is_category() )
					printf( __( 'Archive for the &#8216;%s&#8217; Category', 'retro' ), '<span>' . single_cat_title( '', false ) . '</span>' );
				if ( is_tag() )
					printf( __( 'Archive for the &#8216;%s&#8217; Tag', 'retro' ), '<span>' . single_tag_title( '', false ) . '</span>' );
				if ( is_author() )
					printf( __( 'Author Archive: %s', 'retro' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' );
				if ( is_day() )
					printf( __( 'Archive for %s', 'retro' ), get_the_time( 'F jS, Y' ) );
				if ( is_month() )
					printf( __( 'Archive for %s', 'retro' ), get_the_time( 'F, Y' ) );
				if ( is_year() )
					printf( __( 'Archive for %s', 'retro' ), get_the_time( 'Y' ) );
			?>
		</h2>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content' ); ?>

		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link( __( '&laquo; Previous Entries', 'retro' ) ); ?></div>
			<div class="alignright"><?php previous_posts_link( __( 'Next Entries &raquo;', 'retro' ) ); ?></div>
		</div>

	<?php else : ?>

		<h2 class="center"><?php _e( 'Not Found', 'retro' ); ?></h2>
		<?php get_search_form(); ?>

	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>