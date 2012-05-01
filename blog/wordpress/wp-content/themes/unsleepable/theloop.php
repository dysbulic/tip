<?php /*
	This is the loop, which fetches entries from your database. It is used in some
	form on most of the K2 pages. Because of that, to make editing all of them easier,
	it has been placed in its own file, which is then included where needed.
*/ ?>

<?php is_tag(); ?>
<?php /* Initialize The Loop */ if (have_posts()) { $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>

	<?php // Headlines for archives
	if (!is_single() && !is_home() or is_paged()) { ?>
	<div class="pagetitle">
		<h2>
			<?php
				if ( is_category() ) {
					printf( __( 'Archive for the &lsquo;%1$s&rsquo; Category', 'unsleepable' ),
						single_cat_title( '', false )
					);	
									
				} elseif ( is_tag() ) {
					printf( __( 'Posts Tagged &lsquo;%1$s&rsquo;', 'unsleepable' ),
						single_tag_title( '', false )
					);
					
				} elseif ( is_day() ) {
					printf( __( 'Archive for %1$s' ),
						get_the_time('F jS, Y')
					);
					
				}  elseif ( is_month() ) {
					printf( __( 'Archive for %1$s' ),
						get_the_time('F, Y')
					);
					
				}   elseif ( is_year() ) {
					printf( __( 'Archive for %1$s' ),
						get_the_time('Y')
					);
					
				} elseif ( is_search() ) {
					printf( __( 'Search results for &lsquo;%1$s&rsquo;', 'unsleepable' ),
						get_search_query()
					);
					
				} elseif ( is_author() ) {
					$curauth = get_userdata( intval( $author ) );
					printf( __( 'Author Archive for &lsquo;%1$s&rsquo;', 'unsleepable' ),
						$curauth->first_name . ' ' . $curauth->last_name
					);
				}
			?>
		</h2>
	</div>

	<?php } ?>

	<?php /*
		The 'next page' and 'previous page' navigation for permalinks have to be inside the loop.
		The exact opposite is true for the same navigation links on all other pages.
		Also, we don't want them at the top of the frontpage: */
		
		if (!is_single() && !is_home() or is_paged()) include ( get_template_directory() . '/navigation.php' );

		/* Start The Loop */ while (have_posts()) { the_post();	

		if (is_single()) include ( get_template_directory() . '/navigation.php');
	?>
	
			<div id="post-<?php the_ID(); ?>" <?php post_class('item entry'); ?>>
				<div class="itemhead">
					<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permanent Link to "%s"' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a></h3>
					<div class="chronodata"><?php { the_time('dMy') ?><?php } ?></div>
	
					<!-- The following two sections are for a noteworthy plugin currently in alpha. They'll get cleaned up and integrated better -->
					<?php foreach((get_the_category()) as $cat) {  if ($cat->cat_name == 'Noteworthy') { ?>
						<span class="metalink favorite"><img src="<?php bloginfo('template_url'); ?>/images/favorite.gif" alt="<?php esc_attr_e( __( 'Favorite Entry', 'unsleepable' ) ); ?>" /></span>
					<?php } } ?>

						<?php edit_post_link('<img src="'.get_bloginfo(template_directory).'/images/pencil.png" alt="' . __( 'Edit Link', 'unsleepable' ) . '" />','<span class="editlink">','</span>'); ?>					

				</div>
	
				<div class="itemtext">
					<?php if (is_archive() or is_search()) { 
						the_excerpt();
					} else {
						the_content( sprintf( __( 'Continue reading &lsquo;%1$s&rsquo;' ),
							the_title( '', '', false )
						) );
					} ?>
	
					<?php link_pages('<p><strong>' . __( 'Pages:', 'unsleepable' ) . '</strong> ', '</p>', 'number'); ?>
				</div>
				<br class="clear" />
				<small class="metadata">			
					<span class="category"><?php _e( 'Filed under: ', 'unsleepable' ); ?><?php the_category(', '); ?>	</span>
					&nbsp;&nbsp;|&nbsp;&nbsp;<?php comments_popup_link( __( 'Leave a <span>Comment</span>', 'unsleepable' ), __( '1&nbsp;<span>Comment</span>', 'unsleepable' ), __( '%&nbsp;<span>Comments</span>', 'unsleepable' ), __( 'commentslink', 'unsleepable' ), __( '<span class="commentslink">Closed</span>', 'unsleepable' ) ); ?>	
					<br /><?php the_tags( __( 'Tags: ', 'unsleepable' ), ', ', '<br />' ); ?>
				</small>
			</div>

<?php
	/* End The Loop */ }

	/* Insert Paged Navigation */ if (!is_single()) { include ( get_template_directory() . '/navigation.php'); } ?>

<?php /* If there is nothing to loop */  } else { $notfound = '1'; /* So we can tell the sidebar what to do */ ?>

			<div class="center">
				<h2><?php _e( 'Not Found', 'unsleepable' ); ?></h2>
			</div>

			<div class="item">
				<div class="itemtext2">
				<p><?php _e( 'Oh no! You&rsquo;re looking for something which just isn&rsquo;t here! Fear not however,
				errors are to be expected, and luckily there are tools on the sidebar for you to
				use in your search for what you need.', 'unsleepable' ); ?></p>
				</div>
			</div>

<?php /* End Loop Init */ } ?>
