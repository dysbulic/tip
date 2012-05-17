<div <?php post_class(); ?>>
	<h2 class="post-title">
		<em><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link: %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></em>
		<?php if ( ! is_page()) { the_time('l, M j Y'); } ?>&nbsp;</h2>
		
	<p class="post-info">
	<?php if ( ! is_page() ) : ?>	
		<?php if ( !is_attachment() ) : ?>
			<span class="pcat"><?php the_category(' and ') ?></span>
		<?php 
			the_tags( '<span class="pcat">', ', ', '</span>');
			endif;
		?>
		<span class="pauthor"><?php the_author() ?></span>
		<span class="ptime"><?php the_time();?></span><?php edit_post_link(); ?>
	<?php endif; ?>
	</p>
	
	<div class="post-content">
		<?php the_content(); ?>
		<?php wp_link_pages(); ?>
		<p class="post-info-co">	
			<?php if ( ! is_page() ) : ?>			
			<span class="feedback"><?php comments_popup_link(__('Leave a Response &#187;'), __('One Response &#187;'), __('% Responses &#187;')); ?></span>
			<?php endif; ?>											
		</p>
		<div class="post-footer">&nbsp;</div>
	</div>
	<?php comments_template(); ?>
</div>
