<?php
/**
 * @package Adventure_Journal
 */

get_header(); ?>

	<div id="content" class="clearfix">
		<div id="main-content">

			<?php if ( have_posts() ) the_post(); ?>

			<h1 class="entry-title"><?php printf( __( 'Author Archives: %s', 'adventurejournal' ), "<span class='vcard'><a class='url fn n' href='" . get_author_posts_url( get_the_author_meta( 'ID' ) ) . "' title='" . esc_attr( get_the_author() ) . "' rel='me'>" . get_the_author() . "</a></span>" ); ?></h1>

			<?php
			// If a user has filled out their description, show a bio on their entries.
			if ( get_the_author_meta( 'description' ) ) : ?>
	      		<div id="entry-author-info">
					<div id="author-avatar"><?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'adventurejournal_author_bio_avatar_size', 60 ) ); ?></div>
					<div id="author-description">
						<h2><?php printf( __( 'About %s', 'adventurejournal' ), get_the_author() ); ?></h2>
						<?php the_author_meta( 'description' ); ?>
					</div><!-- #author-description -->
				</div><!-- #entry-author-info -->
			<?php endif; ?>

			<?php rewind_posts(); ?>
			<?php get_template_part( 'loop', 'author' ); ?>
		</div><!-- #main-content -->

		<?php get_sidebar(); ?>
	</div><!-- #content -->

<?php get_footer(); ?>