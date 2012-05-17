<?php /* Initialize The Loop */ if (have_posts()) { $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>

<?php is_tag(); ?>
	<?php // Headlines for archives
	if ( !is_single() && !is_home() or is_paged() ) { ?>
	<div class="pagetitle">
		<h2>
		<?php /* If this is a category archive */ if ( is_category() ) { ?>
		<?php printf( __( 'Archive for the &#8216;%s&#8217; Category', 'twenty-eight' ), single_cat_title( '', false ) ); ?>

		<?php /* If this is a tag archive */ } elseif ( is_tag() ) { ?>
        <h2 class="pagetitle"><?php printf( __( 'Posts Tagged &#8216;%s&#8217;', 'twenty-eight' ), single_tag_title( '', false ) ); ?></h2>

		<?php /* If this is a daily archive */ } elseif ( is_day() ) { ?>
		<?php printf( __( 'Archive for %s', 'twenty-eight' ), get_the_time( 'F jS, Y' ) ); ?>

		<?php /* If this is a monthly archive */ } elseif ( is_month() ) { ?>
		<?php printf( __( 'Archive for %s', 'twenty-eight' ), get_the_time( 'F, Y' ) ); ?>

		<?php /* If this is a yearly archive */ } elseif ( is_year() ) { ?>
		<?php printf( __( 'Archive for %s', 'twenty-eight' ), get_the_time( 'Y' ) ); ?>

		<?php /* If this is a search */ } elseif ( is_search() ) { ?>
		<?php printf( __( 'Search Results for &#8216;%s&#8217;', 'twenty-eight' ), get_search_query() ); ?>

		<?php /* If this is an author archive */ } elseif ( is_author() ) { ?>
		<?php _e( 'Author Archive for:', 'twenty-eight' ); ?> <?php $curauth = get_userdata( intval( $author ) ); echo $curauth->first_name . ' ' . $curauth->last_name; ?>

		<?php /* If this is a paged archive */ } elseif ( is_paged() ) { ?>
		<?php _e( 'Archive Page', 'twenty-eight' ); ?> <?php echo $paged; ?>

		<?php } ?>
		</h2>
	</div>

	<?php } ?>

	<?php while ( have_posts() ) { the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class( 'item entry' ); ?>>
				<div class="itemhead">
					<h3>
					<?php if ( ! is_single() ) : ?>
						<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twenty-eight' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
					<?php else : ?>
						<?php the_title(); ?>
					<?php endif; ?>
					</h3>
					<?php edit_post_link( '<img src="' . get_bloginfo( 'template_directory' ) . '/images/pencil.png" alt="Edit Link" />','<span class="editlink">','</span>' ); ?>
					<p class="metadata">
						<?php printf( __( '%1$s in %2$s', 'twenty-eight' ), get_the_time( get_option( 'date_format' ) ), get_the_category_list( ', ' ) ); ?><?php if ( (is_home()) && !(is_page()) && !(is_single()) && !(is_search()) && !(is_archive()) && !(is_author()) && !(is_category()) && !(is_paged()) OR is_search() ) { ?>. <?php comments_popup_link( __( 'Leave a <span>Comment</span>', 'twenty-eight' ), __( '1 <span>Comment</span>', 'twenty-eight' ), __( '% <span>Comments</span>', 'twenty-eight' ), 'commentslink', '<span class="commentslink">' . __( 'Closed', 'twenty-eight' ) . '</span>'); ?><?php } ?><br />
						<?php the_tags( __( 'Tags: ', 'twenty-eight' ), ', ', '<br />' ); ?>
					</p>
				</div>
				<div class="itemtext">
					<?php if ( is_archive() or is_search() ) {
						the_excerpt();
					} else {
						the_content( __( 'Continue Reading &raquo;', 'twenty-eight' ) );
					} ?>
					<?php link_pages( '<p><strong>' . __( 'Pages:', 'twenty-eight' ) . '</strong> ', '</p>', 'number' ); ?>
				</div>
			</div>
<?php /* End The Loop */ }

	/* Insert Paged Navigation */ if ( !is_single() ) { include (TEMPLATEPATH . '/navigation.php'); } ?>

<?php /* If there is nothing to loop */  } else { $notfound = '1'; /* So we can tell the sidebar what to do */ ?>

<h2><?php _e( 'Oops, I Did Not Find Anything', 'twenty-eight' ); ?></h2>
<div class="item">
	<div class="itemtext">
		<p><?php _e( 'What you were searching for could not be found. Maybe what you are looking for can be found by trying an alternate search query or browsing through the archives.', 'twenty-eight' ); ?></p>
	</div>
</div>

<?php /* End Loop Init */ } ?>