<?php
	get_header();
	$pageDisplay = bm_getProperty( 'excerpt' );
	$authorDisplay = bm_getProperty( 'author' );
?>

<div id="content">
<?php is_tag(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div <?php post_class(); ?>>
	<h2>
		<?php if ( ! is_single() ) : ?>
			<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'regulus' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
		<?php else : ?>
			<?php the_title(); ?>
		<?php endif; ?>
		<em><?php the_time(get_option("date_format")); ?></em>
	</h2>
	<em class="info">Posted by <?php the_author(); ?> in <?php the_category( ", " ) ?>.
<br />
<?php the_tags('Tags: ', ', ', '<br />'); ?>
	<?php if( !is_single() ) {

			// comments are open so display a link to them
			if ( comments_open() ) {
				comments_popup_link( "add a comment", "1 comment so far", "% comments", "comments" );

			// otherwise comments are closed
			} else {
					?> <a href="<?php the_permalink() ?>" class="comments">comments closed</a> <?php
			}

	} else {

	        // trackback links
	        ?>
	        <a href="<?php trackback_url(display); ?>" title="trackback url">trackback</a>
			<?php


	}
	edit_post_link( 'edit post', ' , ', ' ' );
	?>
	</em>

	<?php

	// single page
	// -----------
	if( !is_single() ) {

	    if ( $pageDisplay == 0 ) {
			the_content();
		} else {
			the_excerpt();
		}

 	} else {
		the_content();
	}

	wp_link_pages();
?></div><?php
	if( is_single() ) {

		comments_template();

	}

	endwhile; else: ?>

	<p>Sorry, no posts matched your criteria.</p>

<?php endif;

if ( !is_single() ) {

	echo "<div id=\"pageNav\">";
	next_posts_link( '&laquo; older posts');
	previous_posts_link( 'newer posts &raquo;' );
	echo "</div>";

} ?>

</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
