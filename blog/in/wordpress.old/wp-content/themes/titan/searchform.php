<?php
/**
 * @package Titan
 */
?>
<form method="get" id="search_form" action="<?php echo home_url( '/' ); ?>">
	<div>
		<input type="text" name="s" id="s" class="search"/>
		<input type="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'titan' ); ?>" />
	</div>
</form>
