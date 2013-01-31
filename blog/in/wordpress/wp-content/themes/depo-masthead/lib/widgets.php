<?php
/**
 * DePo Masthead Custom Widgets.
 */
function depo_search_widget() { ?>
	<li id="search_widget" class="widget widget_search">
		<?php if ( !is_dynamic_sidebar() ) { ?><h2><?php _e( 'Etc', 'depo-masthead' ); ?></h2><?php } ?>
		<form method="get" id="searchform" action="<?php echo home_url(); ?>/">
			<label class="hidden" for="s"><?php _e( 'Search for:', 'depo-masthead' ); ?></label>
			<div>
				<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" />
				<input type="submit" id="searchsubmit" value="Search" />
			</div>
		</form>
	</li>
<?php }

function depo_about_widget() { ?>
	<li id="depo_about">
		<?php query_posts( 'pagename=about' ); ?>
		<?php while ( have_posts() ) : the_post(); ?>
				<h2><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( __( 'Permanent Link to %s', 'depo-masthead' ), the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a></h2>
				<div class="entry">
					<?php depomasthead_continue_reading_excerpt(); ?>
				</div>
		<?php endwhile; ?>
	</li>
<?php }

function depo_archives_widget() { ?>
<li id="depo_archives">
	<h2><?php _e( 'Archives', 'depo-masthead' ); ?></h2>
	<ul>
		<?php wp_get_archives( 'type=monthly&number=7' ); ?>
	</ul>

	<ul>
		<?php wp_list_categories( 'number=7&title_li=&orderby=count&order=DESC' ); // show the 7 most-used categories ?>
	</ul>
	<br class="clear" />
</li>
<?php }

function depo_rss_widget() { ?>
	<li id="rss_link" class="widget widget_rss"><a href="<?php bloginfo( 'rss2_url' ); ?>"><?php _e( 'RSS Feed', 'depo-masthead' ); ?></a></li>
<?php }