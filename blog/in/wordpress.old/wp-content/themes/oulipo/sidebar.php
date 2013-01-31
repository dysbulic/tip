<?php
/**
 * @package WordPress
 * @subpackage Oulipo
 */
?>
	<div id="supplementary">
		<div class="meta">

		<?php if ( is_home() || is_page() ) {?>
			<ul>
				<?php if ( !function_exists( 'dynamic_sidebar' )
					  || !dynamic_sidebar() ) : ?>

			<li>
				<h3><?php _e( 'Categories', 'oulipo' ); ?></h3>
				<ul id="categories">
					<?php wp_list_categories( 'orderby=name&title_li=' ); ?>
				</ul>
			</li>

			<li>
				<h3><?php _e( 'Archives', 'oulipo' ); ?></h3>
				<ul id="archives">
					<?php wp_get_archives( 'format=html' ); ?>
				</ul>
			</li>

				<?php endif; ?>
			</ul>

		<?php } elseif ( is_single() and !wp_attachment_is_image() ) { ?>
			<div class="post-nav">
				<h3><?php _e( 'What&rsquo;s this?', 'oulipo' ); ?></h3>
				<p><?php printf(__( 'You are currently reading <strong>%1$s</strong> at <a href="%2$s">%3$s</a>.', 'oulipo' ), the_title( '', '', false ), get_bloginfo( 'url' ), get_bloginfo( 'name' ) ); ?></p>

				<h3><?php _e( 'meta', 'oulipo' ); ?></h3>
				<ul class="single-post-meta">
					<li><strong><?php _e( 'Author:', 'oulipo' ); ?></strong> <?php the_author(); ?></li>
					<li><strong><?php _e( 'Comments:', 'oulipo' ); ?></strong> <?php comments_popup_link( __( 'Leave a Comment', 'oulipo' ), __( '1 Comment', 'oulipo' ), __( '% Comments', 'oulipo' ) ); ?></li>
					<li><strong><?php _e( 'Categories:', 'oulipo' ); ?></strong> <?php the_category( ', ' ); ?></li>
				</ul>
				<p class="edit"><?php edit_post_link( 'Edit', '', '' ); ?></p>
			</div>

		<?php } elseif ( is_category() ) { ?>
				<div class="post-nav">
				<h3><?php _e( 'Where Am I?', 'oulipo' ); ?></h3>
					<p><?php printf(__( 'You are currently browsing the <strong>%1$s</strong> category at <a href="%2$s">%3$s</a>.', 'oulipo' ), single_cat_title( '', false ), get_bloginfo( 'url' ), get_bloginfo( 'name' ) ); ?></p>
					<div class="spacer"></div>
				</div>

		<?php } elseif ( is_tag() ) { ?>
				<div class="post-nav">
				<h3><?php _e( 'Where Am I?', 'oulipo' ); ?></h3>
					<p><?php printf(__( 'You are currently browsing entries tagged with <strong>%1$s</strong> at <a href="%2$s">%3$s</a>.', 'oulipo' ), single_tag_title( '', false ), get_bloginfo( 'url' ), get_bloginfo( 'name' ) ); ?></p>
					<div class="spacer"></div>
				</div>

		<?php } elseif ( is_month() ) { ?>
				<div class="post-nav">
				<h3><?php _e( 'Where Am I?', 'oulipo' ); ?></h3>
					<p><?php printf(__( 'You are currently viewing the archives for <strong>%1$s</strong> at <a href="%2$s">%3$s</a>.', 'oulipo' ), get_the_date( 'F, Y' ), get_bloginfo( 'url' ), get_bloginfo( 'name' ) ); ?></p>
					<div class="spacer"></div>
				</div>

		<?php } elseif ( is_year () ) { ?>
				<div class="post-nav">
				<h3><?php _e( 'Where Am I?', 'oulipo' ); ?></h3>
					<p><?php printf(__( 'You are currently viewing the archives for <strong>%1$s</strong> at <a href="%2$s">%3$s</a>.', 'oulipo' ), get_the_date( 'Y' ), get_bloginfo( 'url' ), get_bloginfo( 'name' ) ); ?></p>
					<div class="spacer"></div>
				</div>

		<?php } elseif ( is_day() ) { ?>
				<div class="post-nav">
				<h3><?php _e( 'Where Am I?', 'oulipo' ); ?></h3>
					<p><?php printf(__( 'You are currently viewing the archives for <strong>%1$s</strong> at <a href="%2$s">%3$s</a>.', 'oulipo' ), get_the_date( 'l, F jS, Y' ), get_bloginfo( 'url' ), get_bloginfo( 'name' ) ); ?></p>
					<div class="spacer"></div>
				</div>

		<?php } elseif ( is_search() ) {?>
				<div class="post-nav">
				<h3><?php _e( 'Search Results', 'oulipo' ); ?></h3>
					<p><?php printf(__( 'You are currently viewing the search results for <strong>%1$s</strong>.', 'oulipo' ), get_search_query() ); ?></p>
					<div class="spacer"></div>
				</div>

		<?php } elseif ( wp_attachment_is_image() ) {?>
				<div class="post-nav">
					<h3><?php _e( 'Image', 'oulipo' ); ?></h3>
					<p><?php printf(__( 'You are currently viewing <strong>%1$s</strong> from <a href="%2$s" title="Return to %3$s" rel="gallery">%3$s</a> at <a href="%4$s">%5$s</a>.', 'oulipo' ), get_the_title(), get_permalink( $post->post_parent ), esc_attr( get_the_title( $post->post_parent ) ), get_bloginfo( 'url' ), get_bloginfo( 'name' ) ); ?></p>
					<p class="edit"><?php edit_post_link( 'Edit', '', '' ); ?></p>
				</div>

		<?php } elseif ( is_page( 'about' ) ) {?>
				<div class="post-nav">
				<h3><?php _e( 'Blogroll', 'oulipo' ); ?></h3>
					<ul>
						<?php wp_list_bookmarks( 'categorize=0&title_li=0&title_after=&title_before=' );?>
					</ul>
				</div>

		<?php } elseif ( is_page( 'contact' ) ) {?>

		<?php } ?>

	</div> <!-- close meta -->
	</div> <!-- close supplementary -->

</div> <!-- close content -->