<?php
function printRadioScale($name, $vals, $scale = undefined) {
?>
<table>
  <tr>
    <?php if($scale == undefined) { ?>
      <?php foreach($vals as $val) { ?>
        <td><?php print $val; ?></td>
      <?php } ?>
    <?php } else { ?>
      <th rowspan="2"><?php print $vals[0]; ?></th>
      <?php for($i = 1; $i <= $scale; $i++) { ?>
        <td><?php print $i ?></td>
      <?php } ?>
      <th rowspan="2"><?php print $vals[1]; ?></th>
    <?php } ?>
  </tr>
  <tr>
    <?php if($scale == undefined) { ?>
      <?php foreach($vals as $val) { ?>
        <td><?php printf('<input type="radio" name="%s" value="%s" />', $name, $val); ?></td>
      <?php } ?>
    <?php } else { ?>
      <?php for($i = 1; $i <= $scale; $i++) { ?>
        <td><?php printf('<input type="radio" name="%s" value="%s" />', $name, $i); ?></td>
      <?php } ?>
    <?php } ?>
  </tr>
</table>
<?php } ?>
