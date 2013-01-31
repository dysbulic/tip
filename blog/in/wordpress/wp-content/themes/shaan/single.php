<?php
/**
 * @package Shaan
 */

	get_header();
?>

<div id="container">

	<div id="content" class="narrow">

	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<?php if ( has_post_thumbnail( get_the_ID() ) ) : ?>
				<div class="post-thumb">
					<?php echo get_the_post_thumbnail( get_the_ID(), 'shaan_featured_image' ); ?>
				</div><!--  #post-thumb -->
			<?php endif; ?>

			<?php the_title( '<h1 class="post-title">', '</h1>' ); ?>

			<p class="post-meta">
				<?php the_author_posts_link(); ?>

				&diams; <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php echo esc_html( get_the_date() ); ?></a>

				<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
					&diams; <?php comments_popup_link(
						__( 'Leave a Comment', 'shaan' ),
						__( '1 Comment',       'shaan' ),
						__( '% Comments',      'shaan' )
					); ?>
				<?php endif; ?>
			</p>

			<?php the_content(); ?>

			<?php wp_link_pages( array(
				'before' => '<div class="page-link">' . __( 'Pages: ', 'shaan' ),
				'after'  => '</div>',
			) ) ; ?>

			<div id="post-info">
				<ul>
					<li><?php
						/* translators: %1$s is a comma-separated list of categories. */
						printf( __( 'Posted in: %1$s', 'shaan' ), get_the_category_list( __( ' &diams; ', 'shaan' ) ) );
					?></li>
					<?php
						/* translators: Both strings end with a space. */
						the_tags( '<li>' . __( 'Tagged: ', 'shaan' ), __( ', ', 'shaan' ), '</li>' );
					?>
					<?php edit_post_link( __( 'Edit this post', 'shaan' ), '<li>', '</li>' ); ?>
				</ul>
			</div>

	</div><!--#posts-->

	<nav id="post-nav" class="paged-navigation">
		<h1 class="assistive-text"><?php _e( 'Post navigation', 'shaan' ); ?></h1>
		<?php previous_post_link( '<div class="nav-older">&larr;&nbsp;%link</div>' ); ?>
		<?php next_post_link( '<div class="nav-newer">%link&nbsp;&rarr;</div>' ); ?>
	</nav>

	<?php comments_template(); ?>

		<?php endwhile; ?>

	<?php else : ?>

		<h2 class="page-title"><?php _e( 'Not Found', 'shaan' ); ?></h2>
		<p><?php _e( 'Sorry, but you are looking for something that is not here.', 'shaan' ); ?></p>
		<?php get_search_form(); ?>

	<?php endif; ?>

	</div><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>