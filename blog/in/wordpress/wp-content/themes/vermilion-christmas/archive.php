<?php get_header(); ?>

<!-- BEGIN ARCHIVE.PHP -->
<div id="content">
<?php is_tag(); ?>
<?php if ( have_posts() ) : ?>

<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
<?php /* If this is a category archive */ if ( is_category() ) { ?>
<h2 class="archive-title"><?php printf( __( 'Archive for the &#8216;%s&#8217; Category', 'vermilionchristmas' ), single_cat_title( '', false ) ); ?></h2>

<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
<h2 class="pagetitle"><?php printf( __( 'Posts Tagged &#8216;%s&#8217;', 'vermilionchristmas' ), single_tag_title( '', false ) ); ?></h2>

<?php /* If this is a daily archive */ } elseif ( is_day() ) { ?>
<h2 class="archive-title"><?php printf( __( 'Archive for %s', 'vermilionchristmas' ), get_the_time( 'F jS, Y' ) ); ?></h2>

<?php /* If this is a monthly archive */ } elseif ( is_month() ) { ?>
<h2 class="archive-title"><?php printf( __( 'Archive for %s', 'vermilionchristmas' ), get_the_time( 'F Y' ) ); ?></h2>

<?php /* If this is a yearly archive */ } elseif ( is_year() ) { ?>
<h2 class="archive-title"><?php printf( __( 'Archive for %s', 'vermilionchristmas' ), get_the_time( 'Y' ) ); ?></h2>

<?php /* If this is a search */ } elseif ( is_search() ) { ?>
<h2 class="archive-title"><?php _e( 'Search Results', 'vermilionchristmas' ); ?></h2>

<?php /* If this is an author archive */ } elseif ( is_author() ) { ?>
<h2 class="archive-title"><?php _e( 'Author Archive', 'vermilionchristmas' ); ?></h2>

<?php /* If this is a paged archive */ } elseif ( isset( $_GET['paged'] ) && !empty( $_GET['paged'] ) ) { ?>
<h2 class="archive-title"><?php _e( 'Blog Archives', 'vermilionchristmas' ); ?></h2>

<?php } ?>
<?php while (have_posts()) : the_post(); ?>

<div <?php post_class(); ?>>
	<h3 class="post-title" id="post-<?php the_ID(); ?>"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h3>
	<small><?php the_time( get_option( 'date_format' ) ); ?> <!-- by <?php the_author(); ?> --></small>
	<div class="entry">
	<?php the_excerpt(); ?>
	</div>
	<p class="postmetadata"><?php printf( __( 'Posted in %1$s', 'vermilionchristmas' ), get_the_category_list( ', ' ) ); ?> &nbsp;|&nbsp; <?php the_tags( __( 'Tags: ', 'vermilionchristmas' ), ', ', ' &nbsp;|&nbsp; ' ); ?> <span class="commentlink"><?php comments_popup_link( __( 'Leave a Comment &#187;', 'vermilionchristmas' ), __( '1 Comment &#187;', 'vermilionchristmas' ), __( '% Comments &#187;', 'vermilionchristmas' ) ); ?></span></p>
	<?php edit_post_link( __( 'edit', 'vermilionchristmas' ),'<div class="edit">[',']</div>' ); ?>
</div>

<?php endwhile; ?>

<?php get_template_part( 'navigation' ); ?>

<?php else : ?>

<h2 class="center"><?php _e( 'Not Found', 'vermilionchristmas' ); ?></h2>
<p class="center"><?php _e( "Sorry, but you are looking for something that isn't here.", 'vermilionchristmas' ); ?></p>
<?php get_search_form(); ?>

<?php endif; ?>

</div>
<!-- END ARCHIVE.PHP -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>