<?php
/**
 * @package WordPress
 * @subpackage Titan
 */
?>
<form method="get" id="search_form" action="<?php bloginfo( 'url' ); ?>/">
	<div>
		<input type="text" name="s" id="s" class="search"/>
		<input type="submit" id="searchsubmit" value="<?php _e( 'Search', 'titan' ); ?>" />
	</div>
</form>
