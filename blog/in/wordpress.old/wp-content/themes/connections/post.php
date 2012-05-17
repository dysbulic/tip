<p class="post-date"><?php the_time(get_option('date_format')); ?></p>
<div class="post-info">
	<h2 class="post-title">
	<?php if ( ! is_single() ) : ?>
		<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link: %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a>
	<?php else : ?>
		<?php the_title(); ?>
	<?php endif; ?>
	</h2>
Posted by <?php the_author(); ?><?php if ( !is_attachment() ) { ?> under <?php the_category(', '); ?> <?php the_tags( ' | ' . __( 'Tags' ) . ': ', ', ', ' | '); ?><?php } ?><?php edit_post_link('(edit this)'); ?><br/><?php comments_popup_link('Leave a Comment', '1 Comment', '[%] Comments'); ?>&nbsp;</div>
<div class="post-content">
	<?php the_content(); ?>
	<div class="post-info">
		<?php wp_link_pages(); ?>
	</div>
	<div class="post-footer">&nbsp;</div>
</div>
