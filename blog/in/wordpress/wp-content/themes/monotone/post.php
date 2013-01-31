<?php
/**
 * @package WordPress
 * @subpackage Monotone
 */
?>
<div id="container" <?php post_class(); ?>>
<?php if ( is_single() ) : ?>
	<h2><?php the_title(); ?></h2>
<?php else : ?>
	<h2><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'monotone' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
<?php endif; ?>

	<div id="postmetadata">
		<div class="sleeve">
			<?php if ( ! is_page() ) { ?>
				<p><?php printf( __( 'By %1$s', 'monotone' ), '<cite>' . get_the_author() . '</cite>' ); ?></p>

				<p><small><a href="<?php echo get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ); ?>"><?php the_time( get_option( 'date_format' ) ); ?></a></small></p>

				<?php
					/* translators: used between list items, there is a space after the comma */
					$tags_list = get_the_tag_list( '', __( ', ', 'monotone' ) );
					if ( $tags_list ) :
				?>
				<p><?php printf( __( 'Tags: %1$s', 'monotone' ), $tags_list ); ?></p>
				<?php endif; // End if $tags_list ?>

				<p><?php _e( 'Category: ', 'monotone' ); the_category( ', ' ); ?></p>
				<?php edit_post_link( __( 'Edit This Post', 'monotone' ), '<p>', '</p>' ); ?>
				<p><?php comments_popup_link( __( 'Leave a Comment &#187;', 'monotone' ), __( '1 Comment &#187;', 'monotone' ), __( '% Comments &#187;', 'monotone' ) ); ?></p>
			<?php } else { ?>
				<?php edit_post_link( __( 'Edit This Page', 'monotone' ), '<p>', '</p>' ); ?>
			<?php } ?>
		</div><!-- .sleeve -->
	</div><!-- #postmetadata -->

	<div id="post">
		<div class="sleeve">
			<?php the_content( __( 'Read the rest of this entry &raquo;', 'monotone' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'monotone' ), 'after' => '</div>' ) ); ?>
		</div><!-- .sleeve -->
	</div><!-- #post -->

<?php if ( is_single() ) : ?>
	<div class="navigation">
		<div class="prev"><?php next_post_link( '%link', '&lsaquo;' ); ?></div>
		<div class="next"><?php previous_post_link( '%link', '&rsaquo;' ); ?></div>
	</div><!-- #navigation -->
	<?php comments_template(); ?>
<?php elseif ( is_page() ) :
	comments_template();
endif; ?>
</div><!-- #container -->