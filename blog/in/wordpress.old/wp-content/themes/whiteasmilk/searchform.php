<form method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
<div><input type="text" value="<?php the_search_query(); ?>" name="s" id="s" /><br />
<input type="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search','whiteasmilk' ); ?>" />
</div>
</form>