<?php get_header(); ?>

	<div id="container">
		<div id="content">

<?php the_post(); ?>

<?php if ( is_day() ) : ?>
			<h2 class="page-title"><?php printf( __( 'Daily Archives: <span>%s</span>', 'notesil' ), get_the_time(get_option( 'date_format' )) ); ?></h2>
<?php elseif ( is_month() ) : ?>
			<h2 class="page-title"><?php printf( __( 'Monthly Archives: <span>%s</span>', 'notesil' ), get_the_time( 'F Y' ) ); ?></h2>
<?php elseif ( is_year() ) : ?>
			<h2 class="page-title"><?php printf( __( 'Yearly Archives: <span>%s</span>', 'notesil' ), get_the_time( 'Y' ) ); ?></h2>
<?php elseif ( isset( $_GET['paged'] ) && !empty( $_GET['paged'] ) ) : ?>
			<h2 class="page-title"><?php _e( 'Blog Archives', 'notesil' ); ?></h2>
<?php endif; ?>

<?php rewind_posts(); ?>

			<div id="nav-above" class="navigation">
				<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&laquo;</span> Older posts', 'notesil' ) ); ?></div>
				<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&raquo;</span>', 'notesil' ) ); ?></div>
			</div>

<?php while ( have_posts() ) : the_post(); ?>

			<div id="post-<?php the_ID(); ?>" class="<?php notesil_post_class(); ?>">
				<h3 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( __( 'Permalink to %s', 'notesil' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
				<div class="entry-date"><a href="<?php the_permalink(); ?>" class="published" title="<?php the_time( 'Y-m-d\TH:i:sO' ); ?>"><?php unset( $previousday ); printf( __( '%1$s &#8211; %2$s', 'notesil' ), the_date( '', '', '', false ), get_the_time() ); ?></a></div>
				<div class="entry-content">
					<?php the_excerpt( __( 'Read More <span class="meta-nav">&raquo;</span>', 'notesil' ) ); ?>
				</div>
				<div class="entry-meta">
					<span class="author vcard"><?php printf( __( 'By %s', 'notesil' ), '<a class="url fn n" href="' . get_author_posts_url( $authordata->ID, $authordata->user_nicename ) . '" title="' . sprintf( __( 'View all posts by %s', 'notesil' ), $authordata->display_name ) . '">' . get_the_author() . '</a>' ); ?></span>
					<span class="meta-sep">|</span>
					<span class="cat-links"><?php printf( __( 'Posted in %s', 'notesil' ), get_the_category_list( ', ' ) ); ?></span>
					<span class="meta-sep">|</span>
					<?php the_tags( __( '<span class="tag-links">Tagged ', 'notesil' ), ", ", "</span>\n\t\t\t\t\t<span class=\"meta-sep\">|</span>\n" ); ?>
<?php edit_post_link( __( 'Edit', 'notesil' ), "\t\t\t\t\t<span class=\"edit-link\">", "</span>\n\t\t\t\t\t<span class=\"meta-sep\">|</span>\n" ); ?>
					<span class="comments-link"><?php comments_popup_link( __( 'Comments (0)', 'notesil' ), __( 'Comments (1)', 'notesil' ), __( 'Comments (%)', 'notesil' ) ); ?></span>
				</div>
			</div><!-- .post -->

<?php endwhile; ?>

			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&laquo;</span> Older posts', 'notesil' ) ); ?></div>
				<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&raquo;</span>', 'notesil' ) ); ?></div>
			</div>

		</div><!-- #content .hfeed -->
		<?php get_sidebar(); ?>
	</div><!-- #container -->

<?php get_footer(); ?>