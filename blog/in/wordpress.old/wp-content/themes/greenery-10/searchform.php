<form method="get" action="/">
<p>
<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" />&nbsp;
<input class="button" type="submit" value="<?php esc_attr_e( 'Search' ); ?>" />
</p>
</form>
