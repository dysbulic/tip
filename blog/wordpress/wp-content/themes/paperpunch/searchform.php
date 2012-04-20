<?php
/**
 * @package WordPress
 * @subpackage Paperpunch
 */
?><h2 class="widgettitle"><?php _e( 'Search', 'paperpunch' ); ?></h2>
<form method="get" id="search_form" action="<?php bloginfo( 'url' ); ?>/">
	<div>
		<input type="text" value="<?php _e( 'Type and press enter', 'paperpunch' ); ?>" name="s" id="s" onfocus="if (this.value == '<?php _e( 'Type and press enter', 'paperpunch' ); ?>' ) {this.value = '';}" onblur="if (this.value == '' ) {this.value = '<?php _e( 'Type and press enter', 'paperpunch' ); ?>';}" />
		<input type="hidden" value="<?php _e( 'Search', 'paperpunch' ); ?>" />
	</div>
</form>
