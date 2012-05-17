<?php
/**
 * @package WordPress
 * @subpackage Monotone
 */
get_header(); rewind_posts(); ?>

<div class="archive">
	<ul id="filters">
		<li><?php _e( 'Filter by:', 'monotone' ); ?></li>
		<li>
			<select name="archive-dropdown" onChange='document.location.href=this.options[this.selectedIndex].value;'>
				<option value=""><?php _e( 'Select Month', 'monotone' ); ?></option>
				<?php wp_get_archives( 'type=monthly&format=option&show_post_count=1' ); ?>
			</select>
		</li>
		<li>
			<?php wp_dropdown_categories( 'show_option_none=Select Category' ); ?>
			<script type="text/javascript">
			/* <![CDATA[ */
				var dropdown = document.getElementById( "cat" );
				function onCatChange() {
					if ( dropdown.options[dropdown.selectedIndex].value > 0 ) {
						location.href = "<?php echo home_url(); ?>/?cat=" + dropdown.options[dropdown.selectedIndex].value;
					}
				}
				dropdown.onchange = onCatChange;
			/* ]]> */
			</script>
		</li>
	</ul>

	<?php if ( have_posts() ) : ?>

	<?php if ( is_category() ) { /* If this is a category archive */ ?>
		<h2>
		<?php
			printf( __( 'Archive for the &#8216;%1$s&#8217; Category', 'monotone' ),
				single_cat_title( '', false )
			);
		?>
		</h2>
	<?php } elseif ( is_tag() ) { /* If this is a tag archive */ ?>
		<h2>
		<?php
			printf( __( 'Posts Tagged &#8216;%1$s&#8217;', 'monotone' ),
				single_tag_title( '', false )
			);
		?>
		</h2>
 	<?php } elseif ( is_day() ) { /* If this is a daily archive */ ?>
		<h2>
		<?php
			printf( __( 'Archive for %1$s', 'monotone' ),
				get_the_date()
			);
		?>
		</h2>
	<?php } elseif ( is_month() ) { /* If this is a monthly archive */ ?>
		<h2>
		<?php
			printf( __( 'Archive for %1$s', 'monotone' ),
				get_the_date( 'F, Y' )
			);
		?>
		</h2>
	<?php } elseif ( is_year() ) { /* If this is a yearly archive */ ?>
		<h2>
		<?php
			printf( __( 'Archive for %1$s', 'monotone' ),
				get_the_date( 'Y' )
			);
		?>
		</h2>
	<?php } elseif ( is_author() ) { /* If this is an author archive */ ?>
		<h2><?php _e( 'Author Archive', 'monotone' ); ?></h2>
 	<?php } elseif ( isset( $_GET['paged'] ) && !empty( $_GET['paged'] ) ) { /* If this is a paged archive */ ?>
		<h2><?php _e( 'Blog Archives', 'monotone' ); ?></h2>
 	<?php } ?>

	<div class="nav clearfix">
		<div class="prev"><?php next_posts_link( __( '&laquo; Older Entries', 'monotone' ) ); ?></div>
		<div class="next"><?php previous_posts_link( __( 'Newer Entries &raquo;', 'montone' ) ); ?></div>
	</div>

	<ul class="thumbnails clearfix">
		<?php while ( have_posts() ) : the_post(); ?>
			<li id="post-<?php the_ID(); ?>">
				<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'monotone' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php monotone_the_thumbnail(); ?></a>
			</li>
		<?php endwhile; ?>
	</ul>

	<div class="nav clearfix">
		<div class="prev"><?php next_posts_link( __( '&laquo; Older Entries', 'monotone' ) ); ?></div>
		<div class="next"><?php previous_posts_link( __( 'Newer Entries &raquo;', 'monotone' ) ); ?></div>
	</div>

	<?php else : ?>

		<h2 class="center"><?php _e( 'Not Found', 'monotone' ); ?></h2>
		<?php get_search_form(); ?>

	<?php endif; ?>
</div>

<?php get_footer(); ?>