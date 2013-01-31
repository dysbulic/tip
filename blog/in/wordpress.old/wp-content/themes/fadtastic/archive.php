<?php get_header(); ?>

		<div id="content_wrapper">
			<div id="content">

			 <?php if ( have_posts() ) : ?>

			<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
				<?php /* If this is a category archive */ if ( is_category() ) { ?>
					<h1 class="pagetitle"><?php single_cat_title(); ?></h1>

				<?php /* If this is a daily archive */ } elseif ( is_day() ) { ?>
					<h1 class="pagetitle"><?php _e( 'Archive for', 'fadtastic' ); ?> <?php the_time( __( 'F jS, Y' ) ); ?></h1>

				<?php /* If this is a monthly archive */ } elseif ( is_month() ) { ?>
					<h1 class="pagetitle"><?php _e( 'Archive for', 'fadtastic' ); ?> <?php the_time( __( 'F, Y' ) ); ?></h1>

				<?php /* If this is a yearly archive */ } elseif ( is_year() ) { ?>
					<h1 class="pagetitle"><?php _e( 'Archive for', 'fadtastic' ); ?> <?php the_time( __( 'Y' ) ); ?></h1>

				<?php /* If this is a search */ } elseif ( is_search() ) { ?>
					<h1 class="pagetitle"><?php _e( 'Search Results', 'fadtastic' ); ?></h1>

				<?php /* If this is an author archive */ } elseif ( is_author() ) { ?>
					<h1 class="pagetitle"><?php _e( 'Author Archive', 'fadtastic' ); ?></h1>

				<?php /* If this is a paged archive */ } elseif ( isset( $_GET['paged'] ) && !empty( $_GET['paged'] ) ) { ?>
					<h1 class="pagetitle"><?php _e( 'Blog Archives', 'fadtastic' ); ?></h1>

			<?php } ?>

					<?php while ( have_posts() ) : the_post(); ?>
					<div <?php post_class(); ?>>
							<h2 id="post-<?php the_ID(); ?>" class="top_border"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent link to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2>
							<p class="author"><?php _e( 'Posted on' ); ?> <em><?php the_time( get_option( 'date_format' ) ); ?></em>. <?php _e( 'Filed under:' ); ?> <?php the_category( ', ' ) ?> | <?php the_tags( __( 'Tags: ' ), ', ', ' | '); ?> <?php edit_post_link( __( 'Edit This' ) ); ?></p>

							<?php the_excerpt(); ?>

							<strong><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent link to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php _e( 'Read Full Post' ); ?></a></strong> | <strong><a href="<?php the_permalink(); ?>#respond" title="Make a comment"><?php _e( 'Make a Comment' ); ?></a></strong> <small> ( <?php comments_popup_link( __( 'None so far' ), __( '<strong>1</strong> so far' ), ( '<strong>%</strong> so far' ) ); ?> ) </small>

					</div>

					<?php endwhile; ?>

					<br />
					<?php next_posts_link( __( '&laquo; Previous Entries' ) ); ?> <?php previous_posts_link( __( 'Next Entries &raquo;' ) ); ?>

				<?php else : ?>

					<h2 class="center"><?php _e( 'Not Found', 'fadtastic' ); ?></h2>
					<?php get_search_form(); ?>

				<?php endif; ?>

			</div>
		</div>

	<?php get_sidebar(); ?>

<?php get_footer(); ?>
