<?php
/**
 * @package Imbalance 2
 */
?>
<?php get_header(); ?>

<?php $options = imbalance2_get_theme_options(); ?>

<div id="container">
	<div id="content" role="main">

	<?php if ( have_posts() ) : ?>

		<?php if ( is_day() ) : ?>
			<h1 class="page-title"><?php printf( __( 'Daily Archives: <span>%s</span>', 'imbalance2' ), get_the_date() ); ?></h1>
		<?php elseif ( is_month() ) : ?>
			<h1 class="page-title"><?php printf( __( 'Monthly Archives: <span>%s</span>', 'imbalance2' ), get_the_date( 'F Y' ) ); ?></h1>
		<?php elseif ( is_year() ) : ?>
			<h1 class="page-title"><?php printf( __( 'Yearly Archives: <span>%s</span>', 'imbalance2' ), get_the_date( 'Y' ) ); ?></h1>
		<?php elseif ( is_author() ) : the_post(); ?>
			<h1 class="page-title author"><?php printf( __( 'Author Archives: <span>%s</span>', 'imbalance2' ), '<span class="vcard">' . get_the_author() . '</span>' ); ?></h1>
			<?php if ( get_the_author_meta( 'description' ) ) : ?>
				<div id="entry-author-info">
					<div id="author-avatar">
						<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'imbalance2_author_bio_avatar_size', 60 ) ); ?>
					</div><!-- #author-avatar -->
					<div id="author-description">
						<h2><?php printf( __( 'About %s', 'imbalance2' ), get_the_author() ); ?></h2>
						<?php the_author_meta( 'description' ); ?>
					</div><!-- #author-description	-->
				</div><!-- #entry-author-info -->
			<?php endif; ?>
			<?php rewind_posts(); ?>
		<?php elseif ( is_category() ) : ?>
			<h1 class="page-title"><?php printf( __( 'Category Archives: <span>%s</span>', 'imbalance2' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?></h1>
			<?php
				$category_description = category_description();
				if ( ! empty( $category_description ) )
					echo apply_filters( 'category_archive_meta', '<div class="archive-meta">' . $category_description . '</div>' );
			?>
		<?php elseif ( is_tag() ) : ?>
			<h1 class="page-title"><?php printf( __( 'Posts Tagged: <span>%s</span>', 'imbalance2' ), '<span>' . single_tag_title( '', false ) . '</span>' ); ?></h1>
			<?php
				$tag_description = tag_description();
				if ( ! empty( $tag_description ) )
					echo apply_filters( 'tag_archive_meta', '<div class="archive-meta">' . $tag_description . '</div>' );
			?>
		<?php else : ?>
			<h1 class="page-title"><?php _e( 'Blog Archives', 'imbalance2' ); ?></h1>
		<?php endif; ?>

		<div id="boxes">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content' ); ?>
			<?php endwhile; ?>
		</div>

		<?php if ( $wp_query->max_num_pages > 1 ) : ?>
			<div class="fetch">
				<?php next_posts_link( __( 'Load more posts', 'imbalance2' ) ); ?>
			</div>

			<script type="text/javascript">
			/* <![CDATA[ */
			// Ajax-fetching "Load more posts"
			jQuery( document ).ready( function( $ ) {
				$( '.fetch a' ).live( 'click', function( e ) {
					e.preventDefault();
					$( this ).addClass( 'loading' ).text( 'Loading...' );
					$.ajax( {
						type: "GET",
						url: $( this ).attr( 'href' ) + '#boxes',
						dataType: "html",
						success: function( out ) {
							result = $( out ).find( '#boxes .box' );
							nextlink = $( out ).find( '.fetch a' ).attr( 'href' );
							$( '#boxes' ).append( result )
								result.css( { opacity: 0 } );
								result.imagesLoaded( function() {
								result.animate( { opacity: 1 } );
								$( '#boxes' ).masonry( 'appended', result );
								$( '.fetch a' ).removeClass( 'loading' ).text( 'Load more posts' );
								if ( nextlink != undefined ) {
									$( '.fetch a' ).attr( 'href', nextlink );
								} else {
									$( '.fetch' ).remove();
								}
							} );
						}
					} );
				} );
			} );
			/* ]]> */
			</script>
		<?php endif; ?>

	<?php else : ?>

		<div id="post-0" class="post no-results not-found">
			<h1 class="entry-title"><?php _e( 'Nothing Found', 'imbalance2' ); ?></h1>
			<div class="entry-content">
				<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'imbalance2' ); ?></p>
				<div id="page_search">
				<?php get_search_form(); ?>
				</div>
			</div><!-- .entry-content -->
		</div><!-- #post-0 -->

	<?php endif; ?>

	</div><!-- #content -->
</div><!-- #container -->

<?php get_footer(); ?>