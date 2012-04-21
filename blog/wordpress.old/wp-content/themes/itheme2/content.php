<?php
/**
 * @package WordPress
 * @subpackage iTheme2
 * @since iTheme2 1.1-wpcom
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'itheme2' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_post_thumbnail( 'large-feature' ); ?></a>
		<?php endif; ?>

		<div class="post-date">
			<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'itheme2' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
				<span class="month"><?php the_time( 'M' ); ?></span>
				<span class="day"><?php the_time( 'j' ); ?></span>
				<span class="year"><?php the_time( 'Y' ); ?></span>
			</a>
		</div>
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'itheme2' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
	</header><!-- .entry-header -->

	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'itheme2' ) ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><strong>' . __( 'Pages:', 'itheme2' ) . '</strong>', 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
	<?php endif; ?>

	<footer class="entry-meta">
		<?php if ( 'post' == get_post_type() ) : ?>
			<?php
				$author_name = esc_html( get_the_author() );
				if ( is_multi_author() )
					$author_name = get_the_author_link();

				printf( __( 'By %1$s', 'itheme2' ), $author_name );
			?>

			<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ', ', 'itheme2' ) );
				if ( $categories_list && itheme2_categorized_blog() ) :
			?>
			<span class="sep"> &#149; </span>
			<span class="cat-links">
				<?php printf( __( 'Posted in %1$s', 'itheme2' ), $categories_list ); ?>
			</span>
			<?php endif; // End if categories ?>

			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', 'itheme2' ) );
				if ( $tags_list ) :
			?>
			<span class="sep"> &#149; </span>
			<span class="tag-links">
				<?php printf( __( 'Tagged %1$s', 'itheme2' ), $tags_list ); ?>
			</span>
			<?php endif; // End if $tags_list ?>

			<?php edit_post_link( __( 'Edit', 'itheme2' ), '<span class="sep"> &#149; </span><span class="edit-link">', '</span>' ); ?>
		<?php endif; // End if 'post' == get_post_type() ?>

		<?php if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
		<div class="comments-link">
			<?php comments_popup_link( '<span class="no-replies">0</span>', '1', '%' ); ?>
		</div>
		<?php endif; // Comments ?>
	</footer><!-- #entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
