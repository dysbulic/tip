<?php
/**
 * @package WordPress
 * @subpackage Neutra
 */
get_header(); ?>

<div id="page">

	<div id="left">
		<?php if ( have_posts() ) : ?>

		<h2 class="title">
			<?php
				if ( is_tag() ) :
					printf( __( 'Tag browsing: <strong>%1$s</strong>', 'neutra' ), single_tag_title( '', false ) );
				elseif ( is_day() ) :
					printf( __( 'Archive for <strong>%s</strong>', 'neutra' ), get_the_date() );
				elseif ( is_month() ) :
					printf( __( 'Archive for <strong>%s</strong>', 'neutra' ), get_the_date( 'F Y' ) );
				elseif ( is_year() ) :
					printf( __( 'Archive for <strong>%s</strong>', 'neutra' ), get_the_date( 'Y' ) );
				else :
					_e( 'Archive', 'neutra' );
				endif;
			?>
		</h2>

		<?php while ( have_posts() ) : the_post(); ?>
			<div class="results">
				<h4><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
			</div>

			<?php endwhile; ?>

			<div class="navigation">
				<div class="old-posts alignleft"><?php next_posts_link( __( '&laquo; Older posts', 'neutra' ) ); ?></div>
				<div class="new-posts alignright"><?php previous_posts_link( __( 'Newer posts &laquo;', 'neutra' ) ); ?></div>
			</div><!-- /navigation -->

			<?php else : ?>

			<div class="post">
				<h2 class="title"><?php _e( 'I&rsquo;m sorry, there&rsquo;s no archive!', 'neutra' ); ?></h2>
				<div class="postcontent">
					<p><?php _e( 'Don&rsquo;t worry, you can always <strong>search the blog</strong> or browse the <strong>categories</strong>.', 'neutra' ); ?></p>
				</div>
			</div><!-- /post -->

		<?php endif; ?>

	</div><!-- /left -->

	<div id="right">
		<?php get_sidebar(); ?>
	</div><!-- /right -->

</div><!-- /page -->

<?php get_footer(); ?>
