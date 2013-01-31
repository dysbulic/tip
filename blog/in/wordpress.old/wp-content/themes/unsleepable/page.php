<?php get_header(); ?>

<div class="content">
	
	<div class="primary">

    <?php if (have_posts()) { while (have_posts()) { the_post(); ?>

		<div class="item2">

			<div class="pagetitle">
				<h2 id="post-<?php the_ID(); ?>"><?php the_title(); ?></h2>
				 <?php edit_post_link('<img src="'.get_bloginfo(template_directory).'/images/pencil.png" alt="' . __( 'Edit Link', 'unsleepable' ) . '" />', '<span class="editlink">', '</span>'); ?>
			</div>
		
			<div class="itemtext">
				<?php the_content('<p class="serif">' . __( 'Read the rest of this page &raquo;', 'unsleepable' ) . '</p>'); ?>
	
				<?php link_pages('<p><strong>' . __( 'Pages:', 'unsleepable' ) . '</strong> ', '</p>', 'number'); ?>
			</div>

		</div>
		
		<?php comments_template(); ?>

		<?php } ?>
		<?php /* If there is nothing to loop */  } else { $notfound = '1'; /* So we can tell the sidebar what to do */ ?>
		
		<div class="center">
			<h2><?php _e( 'Not Found', 'unsleepable' ); ?></h2>
		</div>
		
		<div class="item">
			<div class="itemtext2">
			<p><?php _e( 'Oh no! You&rsquo;re looking for something which just isn&rsquo;t here! Fear not however,
			errors are to be expected, and luckily there are tools on the sidebar for you to
			use in your search for what you need.', 'unsleepable' ); ?></p>
			</div>
		</div>

		<?php /* End Loop Init */ } ?>

	</div>

	<?php get_sidebar(); ?>

</div>

<?php get_footer(); ?>
