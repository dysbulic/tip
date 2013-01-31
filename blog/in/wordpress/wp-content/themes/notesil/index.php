<?php get_header(); ?>

	<div id="container">
		<div id="content">

<?php while ( have_posts() ) : the_post(); ?>

			<div id="post-<?php the_ID(); ?>" class="<?php notesil_post_class(); ?>">
				<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( __( 'Permalink to %s', 'notesil' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
				<div class="entry-content">
					<?php the_content( __( 'Read More <span class="meta-nav">&raquo;</span>', 'notesil' ) ); ?>
					<?php wp_link_pages( 'before=<div class="page-link">' . __( 'Pages:', 'notesil' ) . '&after=</div>' ); ?>
				</div>
				<div class="entry-meta">
					<span class="entry-date"><a href="<?php the_permalink(); ?>" class="published" title="<?php the_time( 'Y-m-d\TH:i:sO' ); ?>"><?php unset( $previousday ); printf( __( '%1$s &#8211; %2$s', 'notesil' ), the_date( '', '', '', false ), get_the_time() ); ?></a>
					<?php printf( __( 'Categories: %s', 'notesil' ), get_the_category_list( ', ' ) ); ?></span>
					<span class="meta-sep">|</span>
					<span class="comments-link"><?php comments_popup_link( __( 'Post a comment', 'notesil' ), __( 'Comments (1)', 'notesil' ), __( 'Comments (%)', 'notesil' ) ); ?></span>
					<p>	<?php the_tags( __( '<span class="tag-links">Tagged ', 'notesil' ), ", ", "</span>\n\t\t\t\t\t<span class=\"meta-sep\">|</span>\n" ); ?>
					<?php edit_post_link( __( 'Edit', 'notesil' ), "\t\t\t\t\t<span class=\"edit-link\">", "</span>\n" ); ?></p>
				</div>
			</div><!-- .post -->

<?php comments_template(); ?>

<?php endwhile; ?>

			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&laquo;</span> Older posts', 'notesil' ) ); ?></div>
				<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&raquo;</span>', 'notesil' ) ); ?></div>
			</div>

		</div><!-- #content -->
		<?php get_sidebar(); ?>
	</div><!-- #container -->

<?php get_footer(); ?>