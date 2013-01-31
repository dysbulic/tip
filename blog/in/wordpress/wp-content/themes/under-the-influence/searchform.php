<?php
/**
 * @package Under_the_Influence
 * @subpackage Template
 */
?>
<form class="searchform" method="get" action="<?php echo home_url( '/' ); ?>">
	<input onfocus="if(this.value==this.defaultValue) this.value='';" class="search" name="s"
		type="text" value="<?php esc_attr_e( 'Search...', 'uti_theme' ); ?>" tabindex="1" />
</form><!--END #searchform-->