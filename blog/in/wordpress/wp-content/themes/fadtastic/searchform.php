<form method="get" id="searchform" action="<?php bloginfo( 'url' ); ?>/" class="float_right">
<label for="s"><?php _e( 'Search:', 'fadtastic' ); ?> </label><input value="<?php the_search_query(); ?>" name="s" id="s" type="text" />
<input id="searchsubmit" value="Search" class="button" type="submit" />
<div class="clear"></div>
</form>