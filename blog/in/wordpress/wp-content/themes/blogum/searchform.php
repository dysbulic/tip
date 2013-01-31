<?php
/**
 * @package WordPress
 * @subpackage Blogum
 */
?>
<div class="search-form-holder">
	<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<fieldset>
			<input name="s" type="text" onfocus="if ( this.value=='<?php esc_attr_e( 'Search', 'blogum' ); ?>' ) this.value='';" onblur="if ( this.value=='' ) this.value='<?php esc_attr_e( 'Search', 'blogum' ); ?>';" value="<?php esc_attr_e( 'Search', 'blogum' ); ?>" />
			<button type="submit"></button>
		</fieldset>
	</form>
</div><!-- .search-form-holder -->