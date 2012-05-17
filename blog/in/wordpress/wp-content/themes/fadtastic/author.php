<?php get_header(); ?>

		<div id="content_wrapper">
			<div id="content">

			 <?php
			if( isset( $_GET['author_name'] ) ) :
			$curauth = get_userdatabylogin( $author_name );
			else :
			$curauth = get_userdata( intval( $author ) );
			endif;
			?>

			<h1><?php _e( 'About', 'fadtastic' ); ?> <?php echo $curauth->user_firstname; ?> <?php echo $curauth->user_lastname; ?></h1>

			<p><strong><?php _e( 'Profile:', 'fadtastic' ); ?></strong> <?php echo $curauth->user_description; ?></p>
			<p><strong><?php _e( 'Website:', 'fadtastic' ); ?></strong> <a href="<?php echo $curauth->user_url; ?>"><?php echo $curauth->user_url; ?></a></p>


			<h2 class="top_border"><?php _e( 'Latest posts by', 'fadtastic' ); ?> <?php echo $curauth->user_firstname; ?> <?php echo $curauth->user_lastname; ?>:</h2>

			<!-- The Loop -->
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			 <p><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent link to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>">
			<?php the_title(); ?></a><br />
			<small><?php the_time( get_option( 'date_format' ) ); ?> <?php _e( 'Filed under:', 'fadtastic' ); ?> <?php the_category( ', ' ); ?> | <?php the_tags( __( 'Tags: ' ), ', ', ' | '); ?> <?php edit_post_link( __( 'Edit This' ) ); ?></small></p>

			  <?php endwhile; else: ?>
				 <p><?php _e( 'No posts by this author.' ); ?></p>

				<?php endif; ?>

			</div>
		</div>

	<?php get_sidebar(); ?>

<?php get_footer(); ?>
