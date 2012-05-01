<?php
/**
 * @package WordPress
 * @subpackage Neutra
 */
get_header(); ?>

<div id="page">

	<div id="left">

		<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<div class="post" id="post-<?php the_ID(); ?>">
				<h2 class="title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
				<div class="postcontent">
					<?php the_content( 'Read more&#8230;' ); ?>
					<?php wp_link_pages( 'before=<p class="link-pages">Pages: &after=</p>' ); ?>
				</div>
			</div><!-- /post -->

			<?php comments_template( '', true ); ?>

			<?php endwhile; ?>
			<?php else : ?>

			<div class="post">
				<h2 class="title">I'm sorry, I couldn't find the page!</h2>
				<div class="postcontent">
					<p>Don't worry, you can always search the <strong>archives</strong> or browse the <strong>categories</strong>.</p>
				</div>
			</div><!-- /post -->

	<?php endif; ?>

	</div><!-- /left -->

	<div id="right">
		<?php get_sidebar(); ?>
	</div><!-- /right -->

</div><!-- /page -->

<?php get_footer(); ?>
