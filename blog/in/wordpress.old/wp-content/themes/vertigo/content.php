<?php
/**
 * @package Vertigo
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="container">

		<header class="entry-header">
			<h1 class="entry-title hitchcock"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'vertigo' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
		</header><!-- .entry-header -->

		<div class="entry-content clear-fix">
			<?php
				if ( is_search() ) :
					the_excerpt();
				else :
					the_content( __( 'Read more', 'vertigo' ) );
				endif;
			?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages:', 'vertigo' ), 'after' => '</p></div>' ) ); ?>
		</div><!-- .entry-content -->

		<?php vertigo_entry_meta(); ?>

		<?php if ( get_the_author_meta( 'description' ) && is_single() && get_post_format() == '' ) : // If a user has filled out their description, show a bio on their entries ?>
			<div id="author-info-box">
				<div id="author-avatar">
					<?php echo get_avatar( get_the_author_meta( 'user_email' ), 24 ); ?>
				</div><!-- #author-avatar -->
				<div id="author-description">
					<h2 id="author-info-title"><?php esc_html( printf( __( 'About %s', 'vertigo' ), get_the_author() ) ); ?></h2>
					<?php the_author_meta( 'description' ); ?>
				</div><!-- #author-description -->
				<div id="author-link">
					<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
						<?php printf( __( 'View all posts by %s', 'vertigo' ), get_the_author() ); ?>
					</a>
				</div><!-- #author-link -->
			</div><!-- # author-info-box -->
		<?php endif; ?>

		<?php vertigo_entry_info(); ?>

	</div><!-- .container -->
</article><!-- #post-## -->