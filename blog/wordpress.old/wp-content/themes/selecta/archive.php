<?php
/**
 * The archive template.
 * @package WordPress
 * @subpackage Selecta
 */
get_header(); ?>

<div id="single-header">
	<div class="single-title-wrap">
		<h1 class="single-title">
			<span><?php
				if ( is_day() ) :
					printf( __( 'Daily Archives: %s', 'selecta' ), '<span>' . get_the_date() . '</span>' );
				elseif ( is_month() ) :
					printf( __( 'Monthly Archives: %s', 'selecta' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );
				elseif ( is_year() ) :
					printf( __( 'Yearly Archives: %s', 'selecta' ), '<span>' . get_the_date( 'Y' ) . '</span>' );
				elseif ( is_category() ) :
					printf( __( 'Category Archives: %s', 'selecta' ), '<span>' . single_cat_title( '', false ) . '</span>' );
				elseif ( is_author() ) :
					printf( __( 'Author Archives: %s', 'selecta' ), '<span><a class="url fn n" href="' . get_author_posts_url( get_the_author_meta( "ID" ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' );
				elseif ( is_tag() ) :
					printf( __( 'Tag Archives: %s', 'selecta' ), '<span>' . single_tag_title( '', false ) . '</span>' );
				else :
					_e( 'Archives', 'selecta' );
				endif;
			?></span>
		</h1>
	</div><!-- .single-title-wrap -->
</div><!-- #single-header -->

<?php rewind_posts(); ?>

<div id="main" class="clearfix">

	<div id="content" role="main">

		<ul class="archive-posts clearfix">
		<?php $count = 0; // Set up a variable to count the number of posts so that we can break them up into rows ?>

		<?php while ( have_posts() ) : the_post(); $count++; ?>

			<li <?php post_class( 'archive-post' ); ?>>
				<?php if( has_post_thumbnail() ) : ?>
					<div class="archive-post-wrapper">
						<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'selecta' ), the_title_attribute( 'echo=0' ) ) ); ?>">
							<?php the_post_thumbnail( 'normal', array( 'class' => 'post-thumbnail', 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?>
						</a>
					</div><!-- .archive-post-wrapper -->
				<?php else : ?>
					<div class="archive-post-wrapper">
						<span><?php echo strip_shortcodes( strip_tags( selecta_short_excerpt( '150' ), '<a>' ) ); ?></span>
					</div><!-- .archive-post-wrapper -->
				<?php endif; ?>
				<h2 class="archive-post-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s', 'selecta' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2>
				<?php selecta_entry_date(); ?>
			</li>
			<?php if( $count % 4 == 0 ) : // After every 4th post, end the row and start a new one. ?>
				</ul><!-- .archive-posts -->
				<ul class="archive-posts clearfix">
			<?php endif; ?>

		<?php endwhile; // end of the loop. ?>

		</ul><!-- .archive-posts -->

		<?php selecta_content_nav( 'nav-below' ); ?>

	</div><!-- #content -->

</div><!-- #main -->

<?php get_footer(); ?>