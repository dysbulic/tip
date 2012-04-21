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

		<?php if ( is_search() ) : ?>
		<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'imbalance2' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
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