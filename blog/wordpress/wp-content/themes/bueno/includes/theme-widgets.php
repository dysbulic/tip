<?php
/**
 * @package WordPress
 * @subpackage Bueno
 */

// =============================== Search widget ======================================
function searchWidget()
{
include(TEMPLATEPATH . '/searchform.php');
}
register_sidebar_widget('Bueno Search', 'SearchWidget');

// =============================== BS Media TAG Widget ======================================

class Bueno_featured extends WP_Widget {

   function Bueno_featured() {
	   $widget_ops = array('description' => __( 'Populate your sidebar with posts from a tag category.', 'woothemes' ) );
       parent::WP_Widget(false, __('Bueno Featured Posts', 'woothemes'),$widget_ops);      
   }
   

   function widget($args, $instance) {  
   
    $tag_id = $instance['tag_id'];
    $num = $instance['num'];
	$title = $instance['title'];
    $content = $instance['content'];

     $tag_name = get_term_by('id', $tag_id, 'post_tag');
     $string = "tag=" . $tag_name->slug ."&showposts=$num";
     $posts = get_posts($string);
	 
	 if($title == ''){ $title = 'Featured Posts'; } 
	 
     global $post;
     ?>
     <div class="widget widget-bueno-featured">
     <h3><?php echo $title; ?></h3>
        <ul>
                    
            <?php if ($posts) : $count = 0; ?>
            <?php foreach ($posts as $post) : setup_postdata($post); $count++; ?>
                                                                        
			<li <?php if ( has_post_thumbnail() ): ?>class="has-thumbnail"<?php endif; ?>>
			
				<?php if ( has_post_thumbnail() ) : ?>
				<span class="thumb">
				<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail( array(70,70) ); ?></a>
				</span>
				<?php endif; ?>
				<div class="right">
					<h4><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4>
					<?php if ( $content == "excerpt" ) : ?>
					<?php the_excerpt(); ?>
					<?php elseif ( $content == "content" ) : ?>
					<?php the_content(); ?>
					<?php endif; ?>
				</div>
			</li>
                <!-- Post Ends -->
                
            <?php endforeach; else: ?>
            <?php endif; ?>
            </ul>
            
            <div class="fix"></div>
            
            </div>
            
            <?php         
		   	
            
   }

   function update($new_instance, $old_instance) {                
       return $new_instance;
   }

   function form($instance) {        
       
       $tag_id = esc_attr($instance['tag_id']);
       $num = esc_attr($instance['num']);
       $title = esc_attr($instance['title']);
       $content = esc_attr($instance['content']);
      
	if($content == '') {$content = 'excerpt';}

       ?>
       

		<p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','woothemes'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
        </p>
        <p>
	   	   <label for="<?php echo $this->get_field_id('tag_id'); ?>"><?php _e('Show posts tagged with:','woothemes'); ?></label>
	       <?php $tags = get_tags(); print_r($cats); ?>
	       <select name="<?php echo $this->get_field_name('tag_id'); ?>" class="widefat" id="<?php echo $this->get_field_id('tag_id'); ?>">
           <option value="">-- Please Select --</option>
			<?php
			
           	foreach ($tags as $tag){
           	?><option value="<?php echo $tag->term_id; ?>" <?php if($tag_id == $tag->term_id){ echo "selected='selected'";} ?>><?php echo $tag->name . ' (' . $tag->count . ')'; ?></option><?php
           	}
           ?>
           </select>
       </p>
       <p>
          <label for="<?php echo $this->get_field_id('content'); ?>"><?php _e('Show content as: ','woothemes'); ?></label>
          <select name="<?php echo $this->get_field_name('content'); ?>" class="widefat" id="<?php echo $this->get_field_id('content'); ?>">
           <option value="content" <?php if($content == "content"){ echo "selected='selected'";} ?>>Full content</option> 
           <option value="excerpt" <?php if($content == "excerpt"){ echo "selected='selected'";} ?>>Excerpts</option>
           </select>
       </p>  
      <?php
   }

} 

register_widget('Bueno_featured');

?>