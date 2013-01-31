<?php header("HTTP/1.1 404 Not Found"); ?>
<?php get_header(); ?>

<div id="content">
	<h2><?php _e( 'Hi there! You seem to be lost...', 'vermilionchristmas' ); ?></h2>
	<p><?php _e( "The address you tried going to doesn't exist on our blog. Don't worry. It's possible that the page you're looking for has been moved to a different address or you may have mis-typed the address.", 'vermilionchristmas' ); ?></p>
	<p><?php _e( "You may want to search for what you're looking for:", 'vermilionchristmas' ); ?></p>
	<?php get_search_form(); ?>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>