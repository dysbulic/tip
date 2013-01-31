<?php get_header();?>	
	<div id="main">
	<div id="content">
		<?php if ($posts) { ?>	
		<?php $post = $posts[0]; /* Hack. Set $post so that the_date() works. */ ?>
		<?php if (is_day()) { ?>
			<h3><?php echo date_i18n(__('l, F jS, Y', 'connections'), get_the_time('U')); ?></h3>
			<div class="post-info"><?php _e('Daily Archive') ?></div>
		<?php } elseif (is_month()) { ?>
			<h3><?php echo date_i18n(__('F Y', 'connections'), get_the_time('U')); ?></h3>
			<div class="post-info"><?php _e('Monthly Archive') ?></div>
		
		<?php } elseif (is_year()) { ?>
			<h3><?php the_time('Y'); ?></h3>
			<div class="post-info"><?php _e('Yearly Archive') ?></div>
		
		<?php } ?>				
		<br/>				
		<?php foreach ($posts as $post) : the_post(); ?>				
			<?php require('post.php'); ?>
		<?php endforeach; ?>
		<p align="center"><?php posts_nav_link() ?></p>		
		<?php } else { ?>
			<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>			
		<?php } ?>			
	</div>
	<div id="sidebar">
	
	<?php get_sidebar(); ?>
	</div>

<?php get_footer();?>
</div>
</div>
</body>
</html>
