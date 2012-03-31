<?php get_header(); ?>
<div id="content">
<?php is_tag(); ?>
	<?php if ( have_posts() ) : ?>
		<?php $postCount=0; ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php $postCount++;?>
	<div <?php post_class( 'entry entry-' . $postCount ); ?> id="post-<?php the_ID(); ?>">
		<div class="entrytitle">
			<h2>
			<?php if ( ! is_single() ) : ?>
				<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'simpla' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
			<?php else : ?>
				<?php the_title(); ?>
			<?php endif; ?>
			</h2>
			<h3><?php the_time( get_option( 'date_format' ) ); ?></h3>
		</div>
		<div class="entrybody">
			<?php the_content( __( 'Read the rest of this entry &raquo;', 'simpla' ) ); ?>
			<?php wp_link_pages(); ?>
		</div>

		<div class="entrymeta">
		<div class="postinfo">
			<span class="postedby"><?php printf( __( 'Posted by %1$s', 'simpla' ), get_the_author() ); ?></span><br />
			<?php if ( !is_page() ) { ?>
				<span class="filedto"><?php printf( __( 'Filed in %1$s', 'simpla' ), get_the_category_list( ', ' ) ); ?></span><br />
				<?php the_tags( '<span class="filedto">' . __( 'Tags: ', 'simpla' ), ', ', '</span><br />' ); ?>
			<?php } ?>
			<?php edit_post_link( __( 'Edit', 'simpla' ), '<span class="filedto">', '</span>' ); ?>
		</div>
		<?php comments_popup_link( __( 'Leave a Comment &#187;', 'simpla' ), __( '1 Comment &#187;', 'simpla' ), __( '% Comments &#187;', 'simpla' ), 'commentslink' ); ?>
		</div>

	</div>
	<div class="commentsblock">
		<?php comments_template(); ?>
	</div>
	<?php endwhile; ?>

	<?php if ( is_home() || is_archive() || is_search() ) : ?>
		<div class="navigation">
			<div class="alignleft"><?php next_posts_link( __( '&laquo; Older Entries', 'simpla' ) ); ?></div>
			<div class="alignright"><?php previous_posts_link( __( 'Newer Entries &raquo;', 'simpla' ) ); ?></div>
		</div>
	<?php elseif ( is_single() ) : ?>
		<div class="navigation">
			<div class="alignleft"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&laquo;', 'Previous post link', 'simpla' ) . '</span> %title' ); ?></div>
			<div class="alignright"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&raquo;', 'Next post link', 'simpla' ) . '</span>' ); ?></div>
		</div>
	<?php endif; ?>

	<?php else : ?>

		<h2><?php _e( 'Not Found', 'simpla' ); ?></h2>
		<div class="entrybody"><?php _e( "Sorry, but you are looking for something that isn't here.", 'simpla' ); ?></div>

	<?php endif; ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>