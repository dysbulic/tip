<?php
/**
 * @package Vigilance
 */
?>
<?php global $vigilance; ?>
<?php
if ($vigilance->alertboxState() == 'On') { ?>
  <div class="alert-box entry">
    <h2><?php echo $vigilance->alertboxTitle(); ?></h2>
    <?php echo $vigilance->alertboxContent(); ?>
  </div><!--end alert-box-->
<?php
} ?>