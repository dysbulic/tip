<?php
/**
 * @package WordPress
 * @subpackage StrangeLittleTown
 */
?>

<form method="get" id="searchform" action="<?php echo esc_url( home_url() ); ?>">
	<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" size="21">
	<input type="image" id="searchsubmit" src="<?php echo esc_url( get_template_directory_uri() . '/img/search.png' ); ?>">
</form>