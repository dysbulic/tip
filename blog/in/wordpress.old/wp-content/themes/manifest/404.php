<?php
/**
 * @package WordPress
 * @subpackage Manifest
 */

get_header(); ?>

<div id="core-content">

	<div class="post hentry">
		<div class="post-content">
			<h2 class="entry-title"><?php _e( 'Page Not Found', 'manifest' ); ?></h2>
			<div class="entry-content">
				<p><?php _e( "Unfortunately the content you're looking for isn't here. There may be a misspelling in your web address or you may have clicked a link for content that no longer exists. Perhaps you would be interested in our most recent articles.", 'manifest' ); ?></p>
			</div>
		</div>
	</div>

	<h4><?php _e( 'Recent Articles', 'manifest' ); ?></h4>

	<?php query_posts( 'cat=&showposts=5' ); ?>
	<ul id="recent-posts">
		<?php while ( have_posts() ) : the_post(); ?>
		<li>
			<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
			<div class="post-date"><abbr class="published" title="<?php the_time( 'Y-m-d\TH:i:sO' ); ?>"><?php the_date( 'F j, Y' ); ?></abbr></div>
		</li>
		<?php endwhile; ?>
	</ul>

</div><!-- #core-content -->

<?php get_footer(); ?>