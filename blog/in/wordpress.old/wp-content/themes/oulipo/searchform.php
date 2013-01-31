<?php
/**
 * @package WordPress
 * @subpackage Oulipo
 */
?>
<form method="get" id="searchform" action="<?php bloginfo( 'url' ); ?>/">
	<div>
		<span class="search"><?php _e( 'Search:', 'oulipo' ); ?></span><input type="text" value="<?php the_search_query(); ?>" size="15" name="s" id="s" />
		<input type="submit" id="searchsubmit" value="Go" />
	</div>
</form>