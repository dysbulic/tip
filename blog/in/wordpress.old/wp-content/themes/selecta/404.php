<?php
/**
 * The 404 template
 * @package WordPress
 * @subpackage Selecta
 */
get_header(); ?>

<div id="single-header">
	<div class="single-title-wrap">
		<h1 class="single-title"><?php _e( 'Not found', 'selecta' ); ?></h1>
	</div><!-- .single-title-wrap" -->
</div><!-- #single-header-->

<div id="main" class="clearfix">
	<div id="content" role="main">
		<div id="post-0" class="post-wrapper clearfix error404 not-found">

			<div class="entry-wrapper clearfix">
				<div class="entry">
					<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'selecta' ); ?></p>
					<?php get_search_form(); ?>
				</div><!-- .entry -->
			</div><!-- .entry-wrapper -->

		</div><!-- #post-0 -->
	</div><!-- #content -->

	<?php get_sidebar(); ?>

</div><!-- #main -->

<?php get_footer(); ?>