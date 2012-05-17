<?php
/**
 * The template for displaying home page.
 *
 * @package WordPress
 * @subpackage The Morning After
 */
get_header(); ?>

<?php get_template_part( 'top-banner' ); ?>

<?php $morningafter_options = morningafter_get_theme_options(); ?>

<div id="home_content" class="column full-width">
	
	<div id="home_left" class="column first">
		
		<?php
			// Display the latest post, ignoring Sticky posts and Aside posts
			$latest_args = array(
				'posts_per_page' => 1,
				'post__not_in' => get_option( 'sticky_posts' ),
				'tax_query' => array(
					array(
						'taxonomy' => 'post_format',
						'terms' => array( 'post-format-aside' ),
						'field' => 'slug',
						'operator' => 'NOT IN'
					)
				)				
			);
			$the_query = new WP_Query( $latest_args ); 
			while ( $the_query->have_posts() ) : $the_query->the_post();
		?>			
			<div id="latest_post" <?php post_class(); ?>>
				
				<h3 class="mast"><?php _e( 'Latest Post','woothemes' ); ?></h3>
			
				<div id="latest_post_image">
					<?php if ( has_post_thumbnail() ) { ?>
						<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'woothemes' ), the_title_attribute( 'echo=0' ) ); ?>">
							<?php the_post_thumbnail( array( 470, 'auto' ), array( 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?>
						</a>
					<?php } ?>
				</div>
			
				<h2 class="latest_post_title" id="post-<?php the_ID(); ?>"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'woothemes' ), the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a></h2>
				
				<div class="post_meta">
					<?php _e( 'Posted by','woothemes' );?> <?php the_author_posts_link(); ?> <span class="latest_post_date"><span class="dot">&sdot;</span> <?php the_time( get_option( 'date_format' ) ); ?></span>
				</div>
			
				<?php if ( $morningafter_options['show_full_home'] == "1" ) the_content( __( 'Continue reading <span class="meta-nav">&raquo;</span>', 'woothemes' ) ); else the_excerpt(); ?>
			
				<div class="latest_post_meta">
					<span class="latest_read_on"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'woothemes' ), the_title_attribute( 'echo=0' ) ); ?>"><?php _e( 'Continue Reading','woothemes' ); ?></a></span>
					<span class="latest_comments"><?php comments_popup_link( __( 'Leave a Comment', 'woothemes' ), __( '1 Comment', 'woothemes' ), __( '% Comments', 'woothemes' ) ); ?></span>
					<span class="latest_category"><?php the_category( ', ' ); ?></span>
				</div>
			
			</div><!-- end #latest_post -->
		<?php endwhile; ?>
				
		<?php
			// Display all featured (sticky) posts, ignoring Aside posts
			$sticky_posts = get_option( 'sticky_posts' );
			if ( ! empty ( $sticky_posts ) ) :
				$sticky_args = array(
					'post__in' => $sticky_posts,
					'posts_per_page' => -1,
					'tax_query' => array(
						array(
							'taxonomy' => 'post_format',
							'terms' => array( 'post-format-aside' ),
							'field' => 'slug',
							'operator' => 'NOT IN'
						)
					)
				);
				$sticky_query = new WP_Query( $sticky_args );
				if ( $sticky_query->have_posts() ) :
		?>
		
			<div id="home_featured" class="clear-fix">
				
				<h3 class="home_featured">
					<?php echo esc_html( stripslashes ( $morningafter_options['featured_heading'] ) ); ?>
				</h3>
				
				<?php while ( $sticky_query->have_posts() ) : $sticky_query->the_post(); ?>
		
					<div class="feat_content clear-fix">
						
						<?php if ( has_post_thumbnail() ) { ?>
							<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'woothemes' ), the_title_attribute( 'echo=0' ) ); ?>">
								<?php the_post_thumbnail( 'featured_thumbnail', array( 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?>
							</a>
						<?php } ?>
						
						<div class="feat_title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'woothemes' ), the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a></div>
							
						<div class="post_meta">
							<?php _e( 'Posted by','woothemes' );?> <?php the_author_posts_link(); ?> <span class="dot">&sdot;</span> <?php the_time( get_option( 'date_format' ) ); ?> <span class="dot">&sdot;</span> <?php comments_popup_link( __( 'Leave a Comment', 'woothemes' ), __( '1 Comment', 'woothemes' ), __( '% Comments', 'woothemes' ) ); ?>
						</div>
						
						<div class="feat_exc">
							<?php if ( $morningafter_options['show_full_home'] == "1" ) the_content( __( 'Continue reading <span class="meta-nav">&raquo;</span>', 'woothemes' ) ); else the_excerpt(); ?>
						</div>
					
					</div><!-- end .feat_content -->
	
				<?php endwhile; ?>
			</div><!-- end #home_featured -->
			<?php endif; ?>
		
		<?php endif; ?>
		
		<?php
			// Display all Aside posts
			$args = array(
				'posts_per_page' => -1,
				'tax_query' => array(
					array(
						'taxonomy' => 'post_format',
						'field' => 'slug',
						'terms' => array( 'post-format-aside' )
					)
				)	
			);
			$aside_query = new WP_Query( $args );
			if ( $aside_query->have_posts() ) :
		?>
		
		<div id="home_asides">
		
			<?php 
				$aside_heading = esc_html( stripslashes( $morningafter_options['aside_heading'] ) );
				if ( $aside_heading ) {
					echo '<h3 class="mast">' . $aside_heading . '</h3>';
				}				
			?>
			
			<ul class="arrow">
				<?php while ( $aside_query->have_posts() ) : $aside_query->the_post();?>
					<li><?php echo strip_shortcodes( strip_tags( get_the_content(), '<a>') ); ?></li>
				<?php endwhile; ?>
			</ul>
		
		</div><!-- end #home_aside -->
		
		<?php endif; ?>		
		
	</div><!-- end #home_left -->

	<div id="home_right" class="column last">
		
		<?php if ( is_active_sidebar( 'feature-widget-area' ) ) : ?>
			<div id="feature">
				<?php dynamic_sidebar( 'feature-widget-area' ); ?>
			</div>
		<?php endif; ?>

		<div class="column secondary-sidebar first">
			<?php if ( !dynamic_sidebar( 'secondary-sidebar' ) ) : ?>
				<div class="widget widget_recent_entries">
					<h3 class="mast"><?php _e( 'Recent Posts', 'woothemes' ); ?></h3>
					<ul>
						<?php
							$recent_entries = new WP_Query();
							$recent_entries->query( 'order=DESC&posts_per_page=10' );
							while ( $recent_entries->have_posts() ) : $recent_entries->the_post();
						?>
							<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
						<?php endwhile; ?>
					</ul>
				</div>
				<div class="widget widget_pages">
					<h3 class="mast"><?php _e( 'Pages', 'woothemes' ); ?></h3>
					<ul>
						<?php wp_list_pages( 'title_li=' ); ?>
					</ul>
				</div>

			<?php endif; ?>
		</div><!-- end .secondary-sidebar -->
		
		<?php get_sidebar(); ?>
	
	</div><!-- end #home_right -->

</div><!-- home_content -->

<?php get_footer(); ?>