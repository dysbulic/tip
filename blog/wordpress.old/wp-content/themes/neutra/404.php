<?php
/**
 * @package WordPress
 * @subpackage Neutra
 */
get_header(); ?>

<div id="page">

	<div id="left">
		<div class="post">
			<h2 class="title"><?php _e( 'Nothing found here: An error occurred (404)', 'neutra' ); ?></h2>
			<div class="postcontent">
				<p><?php _e( 'Either we&rsquo;ve changed a lot of things here or you <strong>mistyped</strong> the URL.', 'neutra' ); ?></p>
				<p><?php _e( 'You can <strong>search</strong>, see the <strong>archives</strong> and browse the <strong>categories</strong>.', 'neutra' ); ?></p>
				<h3><?php _e( 'Archives', 'neutra' ); ?></h3>
				<ul class="browse">
					<?php wp_get_archives( 'type=monthly' ); ?>
				</ul>
				<h3><?php _e( 'Categories', 'neutra' ); ?></h3>
				<ul class="browse">
					<?php wp_list_categories( 'title_li=' ); ?>
				</ul>
			</div>
		</div><!-- /post -->
	</div><!-- /left -->

	<div id="right">
		<?php get_sidebar(); ?>
	</div><!-- /right -->

</div><!-- /page -->

<?php get_footer(); ?>