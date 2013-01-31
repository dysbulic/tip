<?php
/**
 * @package WordPress
 * @subpackage Fusion
 */
?>
<?php get_header(); ?>

	<div id="mid-content">

	<?php
	// global $wp_query;
	// $curauth = $wp_query->get_queried_object();

	if ( isset( $_GET[ 'author_name' ] ) ) : $curauth = get_userdatabylogin( $author_name );
		else : $curauth = get_userdata( intval( $author ) );
	endif;

	?>

	<h1><?php echo $curauth->display_name; ?></h1>

	<div class="profile">
		<div class="avatar left"><?php echo get_avatar( $curauth->user_email, '128' ); ?></div>
		<div class="info">
		<p>
		<?php
			if ( $curauth->user_description<>'' ): echo $curauth->user_description;
			else: _e( "This user hasn't shared any biographical information", "fusion" );
			endif;
		?>
		</p>
		<?php
			if ( ( $curauth->user_url<>'http://' ) && ( $curauth->user_url<>'' ) ) echo '<p class="im">'.__( 'Homepage:', 'fusion' ).' <a href="'.$curauth->user_url.'">'.$curauth->user_url.'</a></p>';
			if ( $curauth->yim<>'' ) echo '<p class="im">'.__( 'Yahoo Messenger:', 'fusion' ).' <a class="im_yahoo" href="ymsgr:sendIM?'.$curauth->yim.'">'.$curauth->yim.'</a></p>';
			if ( $curauth->jabber<>'' ) echo '<p class="im">'.__( 'Jabber/GTalk:', 'fusion' ).' <a class="im_jabber" href="gtalk:chat?jid='.$curauth->jabber.'">'.$curauth->jabber.'</a></p>';
			if ( $curauth->aim<>'' ) echo '<p class="im">'.__( 'AIM:', 'fusion' ).' <a class="im_aim" href="aim:goIM?screenname='.$curauth->aim.'">'.$curauth->aim.'</a></p>';
		?>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>

	<?php if ( have_posts() ): ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<h3 class="title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'fusion' ), the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a></h3>
			
			<div class="postheader">
				<div class="postinfo">
				<p><?php printf( __( 'Posted in %s on %s', 'fusion' ), get_the_category_list( ', ' ), get_the_time( get_option( 'date_format' ) ) ); ?> <?php edit_post_link( __( 'Edit', 'fusion' ), '| ', '' ); ?></p>
				</div>
			</div>
			
			<div class="postbody entry clearfix">
				<?php fusion_current_post_length(); ?>
				<?php wp_link_pages( array( 'before' => '<p class="postpages"><strong>Pages: </strong> ', 'after' => '</p>', 'next_or_number' => 'number' ) ); ?>
			</div>
		
	<?php
		$posttags = get_the_tags();
		if ($posttags) { ?>
		<p class="tags"><?php the_tags( '' ); ?></p>
	<?php } ?>
		<p class="postcontrols">
	<?php
		global $id, $comment;
		$number = get_comments_number( $id );
	?>

			<a class="<?php if ( $number < 1 ) { echo 'no '; }?>comments" href="<?php comments_link(); ?>"><?php comments_number( __( 'Leave a Comment', 'fusion' ), __( '1 Comment', 'fusion' ), __( '% Comments', 'fusion' ) ); ?></a>
		</p>

		<div class="clear"></div>
		
		</div>

	<?php endwhile; ?>

		<div class="navigation" id="pagenavi">

			<div class="alignleft"><?php next_posts_link( __( '&laquo; Older Entries', 'fusion' ) ); ?></div>
			<div class="alignright"><?php previous_posts_link( __( 'Newer Entries &raquo;', 'fusion' ) ); ?></div>

			<div class="clear"></div>

		</div>

	<?php else : ?>

		<p class="error"><?php _e( 'No posts found by this author.', 'fusion' ); ?></p>

	<?php endif; ?>

	</div>
	<!-- /mid content -->

</div>
<!-- /mid -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>