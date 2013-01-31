<?php get_header(); ?>

<div class="content">
	<div class="primary">
		<?php get_template_part( 'theloop' ); ?>
		<?php comments_template(); ?>
	</div>
	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>