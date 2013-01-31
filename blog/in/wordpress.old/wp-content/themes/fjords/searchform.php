<?php
/**
 * @package WordPress
 * @subpackage Fjords
 */
?>
<form method="get" action="/">
<p>
<input type="text" value="<?php the_search_query(); ?>" size="18" name="s" id="s" />
<input type="submit" value="<?php esc_attr_e( 'Search', 'fjords' ); ?>" />
</p>
</form>