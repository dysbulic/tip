<?php
/**
 * The template for displaying search forms in Twenty Eleven
 *
 * @package WordPress
 * @subpackage Quintus
 */
?>
<form method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
    <input type="text" class="field" name="s" id="s" placeholder="<?php esc_attr_e( 'Search', 'quintus' ); ?>" />
    <label for="s" class="assistive-text"><?php _e( 'Search', 'quintus' ); ?></label>
    <input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'quintus' ); ?>" />
</form>