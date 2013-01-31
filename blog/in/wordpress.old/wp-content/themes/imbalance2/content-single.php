<?php
/**
 * @package Imbalance 2
 */
?>
<div class="post_title clear-fix">
	<h1 class="entry-title"><?php the_title(); ?></h1>

	<div id="nav-above" class="navigation">
		<div class="nav-previous">
		<?php if ( get_previous_post( false ) != null ) : ?>
			<?php previous_post_link( '%link', __( '&laquo; Previous', 'imbalance2' ) ); ?>
		<?php else: ?>
			<?php _e( '&laquo; Previous', 'imbalance2' ); ?>
		<?php endif; ?>
		</div>
		<span class="main-separator">/</span>
		<div class="nav-next">
		<?php if ( get_next_post( false ) != null ) : ?>
			<?php next_post_link( '%link', __( 'Next &raquo;', 'imbalance2' ) ); ?>
		<?php else: ?>
			<?php _e( 'Next &raquo;', 'imbalance2' ); ?>
		<?php endif; ?>
		</div>
	</div><!-- #nav-above -->

	<div class="entry-meta">
		<?php imbalance2_posted_by(); ?>

		<?php imbalance2_posted_on(); ?>

		<?php imbalance2_posted_in(); ?>

		<?php if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
			<span class="main-separator"> / </span><?php echo comments_popup_link( __( 'Leave a comment', 'imbalance2' ), __( 'One Comment', 'imbalance2' ), __( '% Comments', 'imbalance2' ) ); ?>
		<?php endif; ?>

		<?php edit_post_link( __( 'Edit', 'imbalance2' ), '<span class="main-separator"> / </span><span class="edit-link">', '</span>' ); ?>
	</div><!-- .entry-meta -->
</div><!-- .post_title -->

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">

		<?php the_content(); ?>

		<div class="entry-utility">
			<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( '<span>Pages:</span>', 'imbalance2' ), 'after' => '</div>' ) ); ?>
			<?php imbalance2_tags(); ?>
		</div><!-- .entry-utility -->
	</div><!-- .entry-content -->

<?php if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries  ?>
	<div id="entry-author-info" class="clear-fix">
		<div id="author-avatar">
			<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'imbalance2_author_bio_avatar_size', 60 ) ); ?>
		</div><!-- #author-avatar -->
		<div id="author-description">
			<h3><?php printf( esc_attr__( 'About %s', 'imbalance2' ), get_the_author() ); ?></h3>
			<?php the_author_meta( 'description' ); ?>
			<div id="author-link">
	    		<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
	    			<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'imbalance2' ), get_the_author() ); ?>
	    		</a>
	    	</div><!-- #author-link	-->
	    </div><!-- #author-description -->
	</div><!-- #entry-author-info -->
<?php endif; ?>
</div><!-- #post-<?php the_ID(); ?> -->