<?php
/**
 * @package Imbalance 2
 */
?>

<?php $options = imbalance2_get_theme_options(); ?>

<div class="box">
	<div class="rel">

	<?php if ( has_post_thumbnail() ) : //if there is a featured image ?>
		<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'imbalance2' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_post_thumbnail( 'homepage-thumb' ); ?></a>
	<?php endif; ?>

	<?php if ( imbalance2_categorized_blog() ) : ?>
		<div class="categories"><?php imbalance2_posted_in(); ?></div>
	<?php endif; ?>

		<h1><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %', 'imbalance2' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

		<?php the_excerpt(); ?>

		<div class="posted">
		<?php if ( is_sticky() ) : ?>
			<?php _e( 'Featured', 'imbalance2' ); ?>
		<?php else : ?>
			<?php if ( 'post' == get_post_type() ) : ?>
				<?php imbalance2_posted_on(); ?>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
			<span class="main-separator"> / </span><?php echo comments_popup_link( __( 'Leave a comment', 'imbalance2' ), __( 'One Comment', 'imbalance2' ), __( '% Comments', 'imbalance2' ) ); ?>
		<?php endif; ?>
		</div><!-- .posted -->

		<div class="texts">
			<div class="abs">
			<?php if ( has_post_thumbnail() ): //default and if there is a featured image ?>
				<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %', 'imbalance2' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_post_thumbnail( 'homepage-thumb' ); ?></a>
			<?php endif ?>

			<?php if ( imbalance2_categorized_blog() ) : ?>
				<div class="categories"><?php imbalance2_posted_in(); ?></div>
			<?php endif ?>

				<h1><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'imbalance2' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

				<?php the_excerpt(); ?>

				<div class="posted">
					<?php if ( is_sticky() ) : ?>
						<?php _e( 'Featured', 'imbalance2' ); ?>
					<?php else : ?>
						<?php imbalance2_posted_on(); ?>
					<?php endif; ?>

					<?php if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
						<span class="main-separator"> / </span><?php echo comments_popup_link( __( 'Leave a comment', 'imbalance2' ), __( 'One Comment', 'imbalance2' ), __( '% Comments', 'imbalance2' ) ); ?>
					<?php endif; ?>
				</div><!-- .posted -->
			</div><!-- .abs -->
		</div><!-- .texts -->
	</div><!-- .rel -->
</div><!-- .box -->