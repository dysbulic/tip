<?php
/**
 * @package WordPress
 * @subpackage DePo Masthead
 */
?>
<form id="searchform" name="searchform" method="get" action="/?s=">
<input type="text" name="s" value=""  />
<input type="submit" id="searchsubmit" style="display: none;" value="<?php esc_attr_e( 'Search', 'depo-masthead' ); ?>" />
</form>