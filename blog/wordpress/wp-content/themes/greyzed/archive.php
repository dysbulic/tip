<?php
/**
 * @package WordPress
 * @subpackage Greyzed
 */

get_header(); ?>
<div id="container">
<?php get_sidebar(); ?>
	<div id="content" role="main">
	<div class="column">
		<?php if (have_posts()) : ?>

		<?php the_post(); ?>

		<h2 class="archivetitle">
		<?php
			if ( is_category() ) {
				printf( __( 'Archive for the &#8216;%s&#8217; Category', 'greyzed' ), single_cat_title( '', FALSE ) );
			} elseif ( is_tag() ) {
				printf( __( 'Posts Tagged &#8216;%s&#8217;', 'greyzed' ), single_tag_title( '', FALSE ) );
			} elseif ( is_day() ) {
				printf( __( 'Archive for %s', 'greyzed' ), get_the_date( get_option( 'date_format' ) ) );
			} elseif ( is_month() ) {
				printf( __( 'Archive for %s', 'greyzed' ), get_the_date( 'F, Y' ) );
			} elseif ( is_year() ) {
				printf( __( 'Archive for %s', 'greyzed' ), get_the_date( 'Y' ) );				
			} elseif (is_author()) {
				_e( 'Author Archive', 'greyzed' );
			} elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
				_e( 'Blog Archives', 'greyzed' );
			}
		?>
		</h2>

		<?php rewind_posts();?>

		<?php $postcount = 0; ?>

		<?php while (have_posts()) : the_post(); ?>
		<?php $postcount++; // post counter ?>
		<div <?php post_class() ?>>
				<div class="posttitle">
					<h2 class="pagetitle"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
					<small>
						<?php printf( __( 'Posted: %1$s by <strong>%2$s</strong> in %3$s', 'greyzed' ),
							get_the_date( get_option( 'date_format' ) ),
							get_the_author(),
							get_the_category_list( ', ' )
							); ?>
						<br />
						<?php the_tags( __( 'Tags: ', 'greyzed' ), ', ', ''); ?>
					</small>
				</div>
				<?php if ( ( comments_open() ) && ( ! post_password_required() ) ) : ?>
				<div class="postcomments"><?php comments_popup_link( '0', '1', '%' ); ?></div>
				<?php endif; ?>
				<div class="entry">
					<?php the_excerpt() ?>
					<?php edit_post_link( __( 'Edit this entry.', 'greyzed' ), '<p>', '</p>' ); ?>
				</div>
			</div>
		<?php endwhile; ?>
		<?php
			// Find page with last post
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			$postsppage= get_option('posts_per_page');
			$total = $paged * $postsppage;
			$remainder = $total - $wp_query->found_posts;
			$endvar =  $postsppage - $remainder;
		?>
	<?php else :

		echo '<h2 class="center">';
		if ( is_category() ) { // If this is a category archive
			printf( __( 'Sorry, but there aren&#8217;t any posts in the %s category yet.', 'greyzed' ), single_cat_title( '',false ) );
		} else if ( is_date() ) { // If this is a date archive
			_e( 'Sorry, but there aren&#8217;t any posts with this date.', 'greyzed' );
		} else if ( is_author() ) { // If this is a category archive
			$userdata = get_userdatabylogin( get_query_var( 'author_name' ) );
			printf( 'Sorry, but there aren&#8217;t any posts by %s yet.', $userdata->display_name );
		} else {
			_e( 'No posts found.', 'greyzed' );
		}
		echo '</h2>';
		get_search_form();

	endif;
?>
</div>
<div id="nav-post">
	<div class="navigation-bott">
		<div class="navigation">
			<?php if($endvar == 0 || $postcount == $endvar) { } else { ?>
			<div class="leftnav"><?php next_posts_link( __( 'Older Entries', 'greyzed' ) ) ?></div>
			<?php } if ($paged > 1) { ?>
			<div class="rightnav"><?php previous_posts_link( __( 'Newer Entries', 'greyzed' ) ) ?></div>
			<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>