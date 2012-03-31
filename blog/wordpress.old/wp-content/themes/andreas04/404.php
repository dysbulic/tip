<?php get_header(); ?>
  
    <div id="content">
    
      <div id="left">
            
              
              <div class="entry">
            
                <h2><?php _e( 'Hi there! You seem to be lost.', 'andreas04' ); ?></h2>

				<p><?php _e( 'The address you tried going to doesn&rsquo;t exist on our blog. Don’t worry. It&rsquo;s possible that the page you&rsquo;re looking for has been moved to a different address or you may have mis-typed the address.', 'andreas04' );  ?></p>
				<p><?php _e( 'Perhaps searching will help.', 'andreas04' );  ?></p>
				<?php get_search_form(); ?>                
				<br />
              </div>
  
      </div>

      <?php get_sidebar(); ?>

      <?php get_footer(); ?>

    </div>
  
  </div>
</body>
</html>
