<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
	<?php if (is_sticky()) { $sticky++; } else { $regular++; ?>
	<div class="image">
		<div class="nav prev"><?php next_post_link('%link','&lsaquo;') ?></div>
		<?php the_image(); ?>
		<div class="nav next"><?php previous_post_link('%link','&rsaquo;'); ?></div>
	</div>
	<?php partial('post'); ?>
	<?php } ?>
<?php endwhile; else : ?>

	<h2>Not Found</h2>
	<p>Sorry, but you are looking for something that isn't here.</p>
	<?php include (TEMPLATEPATH . "/searchform.php"); ?>
<?php endif; ?>

<div class="navigation">
	<div class="prev"><?php next_post_link('%link', '&lsaquo' ) ?></div>
	<div class="next"><?php previous_post_link('%link','&rsaquo;') ?></div>
</div>

<?php get_footer(); ?>
