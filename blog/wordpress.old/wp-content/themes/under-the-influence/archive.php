<?php
/**
 * @package WordPress
 * @subpackage Under_the_Influence
 */

	get_header();
	
	global $options;
	foreach ($options as $value) {
		if (array_key_exists('id',$value)) {
			if (get_option( $value['id'] ) === FALSE) {
				$$value['id'] = $value['std'];
			} else {
				$$value['id'] = get_option( $value['id'] );
			}
		}
	}
	$column = $uti_column;
	$columnwidth = $uti_column_width;
	if ( "on" == $column )
		$content_width = 370; 
	else
		$content_width = $columnwidth;	
?>
<div id="content_container">
	<?php get_sidebar(); ?>
	<?php if (have_posts()) : ?>
		<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
		<?php
			/* A Category */
			if (is_category()) {
		?>
			<h2 class="pagetitle">
				<?php _e('Archive for', 'uti_theme')?> &#8216;<?php single_cat_title(); ?>&#8217;
			</h2>
 		<?php
			/* A Tag */
			} elseif( is_tag() ) {
		?>
			<h2 class="pagetitle">
				<?php _e('Posts tagged', 'uti_theme')?> &#8216;<?php single_tag_title(); ?>&#8217;
			</h2>
 		<?php
			/* A Day */
			} elseif (is_day()) {
		?>
			<h2 class="pagetitle">
				<?php _e('Archive for', 'uti_theme')?> <?php the_time('F jS, Y'); ?>
			</h2>
 		<?php
			/* A Month */
			} elseif (is_month()) {
		?>
			<h2 class="pagetitle">
				<?php _e('Archive for', 'uti_theme')?> <?php the_time('F, Y'); ?>
			</h2>
 		<?php
			/* A Year */
			} elseif (is_year()) {
		?>
			<h2 class="pagetitle">
				<?php _e('Archive for', 'uti_theme')?> <?php the_time('Y'); ?>
			</h2>
		<?php
			/* An Author */
			} elseif (is_author()) {
		?>
			<h2 class="pagetitle">
				<?php _e('Author Archive', 'uti_theme')?>
			</h2>
 		<?php
			/* If this is a paged archive */
			} elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
		?>
		<h2 class="pagetitle">
			<?php _e('Blog Archives', 'uti_theme')?>
		</h2>
 		<?php
			}
		?>

		<div class="navigation top">
			<?php next_posts_link( __( '&laquo; Older Entries', 'uti_theme' ) ); ?>&nbsp;&nbsp;&#124;&nbsp;&nbsp;
			<?php previous_posts_link( __( 'Newer Entries &raquo;', 'uti_theme' ) ); ?>
		</div><!--.navigation-->

		<div id="content" class="mainpage">
			<?php while (have_posts()) : the_post(); ?>
				<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
					<div class="date"><?php the_time(get_option('date_format')) ?></div>
					<h2>
						<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php esc_attr_e( 'Permalink to', 'uti_theme' ); echo ' '; the_title_attribute(); ?>">
							<?php the_title(); ?>
						</a>
					</h2>
					<?php
						/* If author is shown */
						if ($author == "on"){
					?>
					<span class="author">
						<?php _e( 'by', 'uti_theme' ); echo ' '; the_author(); ?>
					</span>
				<?php } ?>
					<div class="entry">
						<?php the_content(__('<div class="read_more">read more &raquo;</div>', 'uti_theme')); ?>
					</div>
					<p class="postmetadata">
						<?php _e( 'Posted in', 'uti_theme' ); echo ' '; the_category( ', ' ); ?> |
						<?php edit_post_link(__('Edit', 'uti_theme'), '', ' | '); ?>
						<?php comments_popup_link(__('Leave a Comment &#187;', 'uti_theme'), __('1 Comment &#187;', 'uti_theme'), __('% Comments &#187;', 'uti_theme')); ?>
					</p>
					<div class="tags">
						<?php the_tags( __( 'Tags:', 'uti_theme' ) . ' ', ', ', '<br />' ); ?>
					</div>
				</div><!--.post-->
			<?php endwhile; ?>
		</div><!--#content-->

		<div class="navigation_box">
	  		<div class="navigation bottom">
				<?php next_posts_link( __( '&laquo; Older Entries', 'uti_theme' ) ); ?>&nbsp;&nbsp;&#124;&nbsp;&nbsp;
				<?php previous_posts_link( __( 'Newer Entries &raquo;', 'uti_theme' ) ); ?>
			</div><!--.navigation-->
		</div><!--.navigation_box-->

	<?php else :
		// no posts
		if ( is_category() ) {
			// If this is a category archive
			printf(__('
			<div class="center">
				<h2 class="center">
					Sorry, but there aren&rsquo;t any posts in the %s category yet.
				</h2>
			', 'uti_theme'), single_cat_title('',false));
		} else if ( is_date() ) {
			// If this is a date archive
			_e('
			<div class="center">
				<h2>
					Sorry, but there aren&rsquo;t any posts with this date.</h2>
			', 'uti_theme');
		} else if ( is_author() ) {
			// If this is a category archive
			$userdata = get_userdatabylogin(get_query_var('author_name'));
			printf(__('
			<div class="center">
				<h2 class="center">
					Sorry, but there aren&rsquo;t any posts by %s yet.
				</h2>
			', 'uti_theme'), $userdata->display_name);
		} else {
			_e('
			<div class="center">
				<h2 class="center">
					No posts found.
				</h2>
			', 'uti_theme');
		}
		get_search_form();
		echo("</div>");

	endif; ?>	
</div><!--#content_container-->

<?php get_footer(); ?>