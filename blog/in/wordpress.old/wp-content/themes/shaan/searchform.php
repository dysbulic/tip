<?php
/**
 * @package Shaan
 */
?>

<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>/">
	<label for="s" class="assistive-text"><?php _e( 'Search', 'shaan' ); ?></label>
	<input id="s" name="s" class="search-input" type="text" value="<?php echo get_search_query(); ?>" placeholder="<?php esc_attr_e( 'Type keywords and press Enter', 'shaan' ); ?>" />
	<input type="submit" class="submit assistive-text" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'shaan' ); ?>" />
</form>