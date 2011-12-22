<?php
/**
 * @package WordPress
 * @subpackage AutoFocus
 */
get_header(); ?>

<div id="content">

	<div id="post-0" class="hentry">
		<h2 class="entry-title">
			<?php _e( 'Not Found', 'autofocus' ); ?>
		</h2><!-- .entry-title -->
		<div id="entry-content">
			<p><?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', 'autofocus' ); ?></p>
			<?php get_search_form(); ?>
		</div><!-- #entry-content -->
	</div><!-- #post-0 -->

</div><!-- #content -->

<?php get_footer(); ?>