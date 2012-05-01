<?php
/**
 * @package WordPress
 * @subpackage Structure
 */
get_header();
?>

<div id="content">

	<div id="contentleft" class="narrowcolumn">
    
		<!-- This sets the $curauth variable -->
        
		<?php
		
			if(isset($_GET['author_name'])) :
			$curauth = get_userdatabylogin($author_name);
			else :
			$curauth = get_userdata(intval($author));
			endif;
			
		?>
        
		<div class="posttitle">
			<h3><?php echo $curauth->display_name; ?></h3>
        </div>
        
        <p><?php if(function_exists('get_avatar')) { echo get_avatar($author, '120'); } ?></p>
		<p><strong><?php _e("Profile:", 'structuretheme'); ?></strong> <?php echo $curauth->user_description; ?></p>
		<h5><?php _e("Posts by", 'structuretheme'); ?> <?php echo $curauth->display_name; ?>:</h5>
        
		<ul>
       
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<li>
				<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
				<?php the_title(); ?></a>
			</li>
			<?php endwhile; else: ?>
			<p><?php _e('No posts by this author.'); ?></p>
			<?php endif; ?>

		</ul>
        
	</div>
			
	<?php get_sidebar( 'right' ); ?>

</div>

<!-- The main column ends  -->

<?php get_footer(); ?>