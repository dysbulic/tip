<?php
/**
 * @package WordPress
 * @subpackage Paperpunch
 */
?><?php get_header(); ?>
	<div class="post-box page-box">
		<div class="post-header">
			<h1 class="pagetitle"><?php _e( '404: Page Not Found', 'paperpunch' ); ?></h1>
		</div><!--end post-header-->
		<div class="entry page clear">
			<p><?php _e( 'We are terribly sorry, but the URL you typed no longer exists. It might have been moved or deleted, or perhaps you mistyped it. We suggest searching the site:', 'paperpunch' ); ?></p>
			<?php get_search_form(); ?>
		</div><!--end entry-->
	</div><!--end post-box-->
</div><!--end content-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>