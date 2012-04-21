<?php
/**
 * @package Imbalance 2
 */
?>
<?php get_header(); ?>

<div id="container">
	<div id="content" role="main">

		<div id="post-0" class="post error404 not-found">
			<h1 class="entry-title"><?php _e( "404! We couldn't find the page!", 'imbalance2' ); ?></h1>
			<div class="entry-content">
				<p><?php _e( "The page you've requested can not be displayed. It appears you've missed your intended destination, either through an outdated link, or a typo in the page you were hoping to reach.", 'imbalance2' ); ?></p>
				<p><?php _e( 'If you were looking for specific content, please try searching for it in the search box below.', 'imbalance2' ); ?></p>
				<div id="page_search">
					<?php get_search_form(); ?>
				</div>
				<p><a href="<?php echo home_url( '/' ); ?>"><?php _e( 'Back to Homepage', 'imbalance2' ); ?></a><br /></p>
			</div><!-- .entry-content -->
		</div><!-- #post-0 -->

	</div><!-- #content -->
</div><!-- #container -->

<?php get_footer(); ?>