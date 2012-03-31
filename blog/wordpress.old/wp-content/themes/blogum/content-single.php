<?php
/**
 * @package WordPress
 * @subpackage Blogum
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'clear' ); ?>>
	<div class="post-meta">
		<?php blogum_post_data(); ?>

		<?php blogum_comments_popup_link(); ?>

		<?php edit_post_link( __( 'Edit', 'blogum' ), '<div class="post-edit">', '</div>' ); ?>
	</div><!-- .post-meta -->

	<div class="post-content">
		<header>
			<h1 class="post-title"><?php the_title(); ?></h1>
		</header>
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'blogum' ) . '</span>', 'after' => '</div>' ) ); ?>

		<?php if ( get_the_tag_list() ) :
			echo get_the_tag_list( '<div class="post-tags clear"><ul><li>','</li><li>','</li></ul></div><!-- .post-tags -->' );
		endif; ?>
	</div><!-- .post-content -->

</article><!-- #post-<?php the_ID(); ?> -->