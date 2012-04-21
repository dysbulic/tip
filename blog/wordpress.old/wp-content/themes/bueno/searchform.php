<?php
/**
 * @package WordPress
 * @subpackage Bueno
 */
?>
<div id="search_main" class="widget">

	<h3><?php _e('Search', 'woothemes'); ?></h3>

    <form method="get" id="searchform" action="<?php bloginfo('url'); ?>">
        <input type="text" class="field" name="s" id="s"  value="<?php esc_attr_e( 'Search...', 'woothemes' ); ?>" onfocus="if (this.value == '<?php esc_attr_e( 'Search...', 'woothemes'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php esc_attr_e( 'Search...', 'woothemes'); ?>';}" />
        <input type="submit" class="submit" name="submit" value="<?php esc_attr_e( 'Search', 'woothemes' ); ?>" />
    </form>
    
    <div class="fix"></div>
    
</div>
