<?php
/**
 * @package Under_the_Influence
 * @subpackage Template
 */
?>
<form class="searchform" method="get" action="<?php bloginfo( 'url' ); ?>">
	<input onfocus="if(this.value==this.defaultValue) this.value='';" class="search" name="s"
		type="text" value="<?php _e('Search...', 'uti_theme')?>" tabindex="1" />
</form><!--END #searchform-->