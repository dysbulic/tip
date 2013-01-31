<?php
/**
 * @package WordPress
 * @subpackage Manifest
 */
?>

<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
    <div class="post-content">
    	<h3 class="entry-title"><?php the_title(); ?></h3>
    	<h4 class="vcard author"><?php printf( __( 'by <span class="fn">%s</span>', 'manifest' ), get_the_author() ); ?></h4>
    <?php if ( 'image' == get_post_format() ) : ?>
	<?php
		$attachments = get_children(
			array(
				'post_parent' => get_the_ID(),
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'orderby' => 'menu_order'
			)
		);
		if ( ! is_array($attachments) )
			continue;
		
		$count = count($attachments);
	
		$first_attachment = array_shift($attachments);
	?>
		<img class="entry-image" src="<?php echo $first_attachment->guid; ?>?w=500" alt="<?php esc_attr_e( $first_attachment->post_title ); ?>" />
	<?php endif; ?>
    	<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<p>' . __( 'Pages:', 'manifest' ), 'after' => '</p>' ) ); ?>
    	</div>
    </div>
    <div class="post-meta">
    	<?php
    		$arc_year = get_the_time( 'Y' );
    		$arc_month = get_the_time( 'm' );
    		$arc_day = get_the_time( 'd' );
    	?>

    	<div class="post-date"><span><?php _e( 'Published:', 'manifest' ); ?></span> <abbr class="published" title="<?php the_time( 'Y-m-d\TH:i:sO' ); ?>"><a href="<?php echo get_day_link( "$arc_year", "$arc_month", "$arc_day" ); ?>"><?php the_time( get_option( 'date_format' ) ); ?></a></abbr></div>
    	<div class="categories"><span><?php _e( 'Filed Under:', 'manifest' ); ?></span> <?php the_category( ', ' ); ?></div>
    	<?php the_tags( '<span>' . __( 'Tags:', 'manifest' ) . '</span> ', ' : ', '' ); ?>
    </div>
</div>