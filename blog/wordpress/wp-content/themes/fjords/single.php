<?php
/**
 * @package WordPress
 * @subpackage Fjords
 */
?>
<?php get_header(); ?>

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<div id="pagina">

			<h2 class="post-titulo" id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent link to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h2>

			<p class="postmeta">
			<?php printf( __( '%1$s at %2$s', 'fjords' ), get_the_time( get_option( 'date_format' ) ), get_the_time() ); ?>
			&#183; <?php _e( 'Filed under' ); ?> <?php the_category( ', ' ); ?>
			<?php the_tags( __( 'and tagged: ', 'fjords' ), ', ', '' ); ?>
			<?php edit_post_link( __( 'Edit', 'fjords' ), ' &#183; ', '' ); ?>
			</p>

			<div class="postentry">
			<?php the_content( __( 'Read the rest of this entry &raquo;', 'fjords' ) ); ?>
			<?php wp_link_pages(); ?>
			</div>

			<p class="linkpermanente">
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent link to %s', 'fjords' ), the_title_attribute( 'echo=0' ) ) ); ?>" class="permalink"><?php _e( 'Permalink', 'fjords' ); ?></a>
			</p>

		</div>

		<?php comments_template(); ?>

	<?php endwhile; else : ?>

		<h2><?php _e( 'Not Found', 'fjords' ); ?></h2>

		<p><?php _e( 'Sorry, but the page you requested cannot be found.', 'fjords' ); ?></p>

		<h3><?php _e( 'Search', 'fjords' ); ?></h3>

		<?php get_search_form(); ?>

	<?php endif; ?>

<?php get_sidebar(); ?>

<?php get_footer(); ?>