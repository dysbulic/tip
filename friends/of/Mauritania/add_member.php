<?php
$message = "";
while(!is_null($name = key($_POST))) {
  $message .= "$name: " . $_POST[$name] . "\n";
  next($_POST);
}

mail("membership@forim.org", "Membership Application", $message, "From: membership@forim.org");
// header("Location: http://www.example.com/thankyou.html");

$dues = $_POST['membership'] == 'couple' ? 25 : 15;
$total = $dues + ($_POST['calendar'] == "yes" ? 10 : 0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Friends of Mauritania Membership Payment</title>
    <link rel='stylesheet' href='forim.css' type='text/css' />
    <style type="text/css">
      form input { border: none; }
      .button { background-image: url('http://odin.himinbi.org/gallery/download/9987-1/sand_tile_dark.jpg'); }
      .submit { border: 2px outset; }
    </style>
  </head>
  <body id="confirmation">
    <h1>Membership Application: <?php print $_POST['name_1'] ?></h1>
    <p>Thank you for your application to join FORIM. To complete your application for <?php print $_POST['membership'] ?> membership, you must pay the yearly dues of $<?php print $dues; if($_POST['calendar'] == "yes") { print " and $10 for the 2008 Peace Corps calendar"; } ?>. You may:</p>

    <!-- <form method="post" action="https://sandbox.google.com/checkout/cws/v2/Merchant/849282702695236/checkoutForm" accept-charset="utf-8"> -->
    <form method="post" action="https://checkout.google.com/cws/v2/Merchant/118741940931161/checkoutForm" accept-charset="utf-8">
    <div>
      <input type="hidden" name="item_name_1" value="FORIM membership" />
      <input type="hidden" name="item_quantity_1" value="1"/>
      <?php printf('<input type="hidden" name="item_description_1" value="One year %s membership dues" />', $_POST['membership']) ?>
      <?php printf('<input type="hidden" name="item_price_1" value="%d" />', $dues) ?>
      
      <?php if($_POST['calendar'] == "yes") { ?>
      <input type="hidden" name="item_name_2" value="Calendar" />
      <input type="hidden" name="item_description_2" value="2008 Peace Corps Calendar" />
      <input type="hidden" name="item_quantity_2" value="1" />
      <input type="hidden" name="item_price_2" value="10" />
      <?php } ?>

      <input type="image" name="submit" alt="Checkout through Google" style="width: auto"
        src="https://checkout.google.com/buttons/checkout.gif?merchant_id=118741940931161&w=180&h=46&style=white&variant=text&loc=en_US" />
      <!--
      <input type="submit" class="submit button" value="Pay via Google Checkout" />
      -->
      </div>
    </form>

    <p>Alternatively, you may mail a check for $<?php print $total ?> to:</p>

    <div class="address">
      <div>Friends of Mauritania</div>
      <div>PO Box 33068</div>
      <div>Washington, DC 20033-0068</div>
    </div>

    <!--
    <p>To join FORIM through the National Peace Corps Association (NPCA) call 202-293-7728 or write to:</p>

    <div class="address">
      <div>NPCA/Membership Director</div>
      <div>1900 L Street</div>
      <div>Suite 205</div>
      <div>Washington, DC 20036</div>
    </div>
    -->
  </body>
</html>
