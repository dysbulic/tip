<?php
  session_start();
  print("<?xml version=\"1.0\" encoding=\"ISO-8859-1\" standalone=\"no\" ?>\n")
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Dynamic Submission Test</title>
    <link rel="stylesheet" type="text/css" href="/styles/main.css" />
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
<script type="text/javascript">
this.addEventListener("load", init, false);

// Holds the list that the elements are in
var elements;
// Holds the text area that new elements come from
var element;
// Keeps track of the number entered
var count = 0;

function init() {
  var elements_block = document.getElementById("elements");
  if(!elements_block) {
    alert("Could not find list to add to");
  }
  elements = document.createElement("ul");
  elements_block.appendChild(elements);
  element = document.getElementById("new_element");
}

function add_element() {
  if(element.value) {
    count++;

    var checkbox = document.createElement("input");
    checkbox.setAttribute("name", "id[]");
    checkbox.setAttribute("type", "checkbox");
    checkbox.setAttribute("checked", "checked");
    checkbox.setAttribute("value", count)
      
    var textbox = document.createElement("input");
    textbox.setAttribute("name", "text-" + count);
    textbox.setAttribute("id", "text-" + count);
    textbox.setAttribute("type", "text");
    textbox.setAttribute("value", element.value)

    var listitem = document.createElement("li");
    listitem.appendChild(checkbox);
    listitem.appendChild(textbox);
    elements.appendChild(listitem);

    element.value = "";
  }
}
</script>
  </head>
  <body onload="init()">
    <?php if(isset($_GET["id"]) || (isset($_SESSION["vars"]) && count($_SESSION["vars"]) > 0)) { ?>
      <ul>
        <?php
          if(!isset($_SESSION['vars'])) {
            $_SESSION['vars'] = array();
            print("<h1>Created vars</h1>\n");
          }
          if(isset($_GET["id"])) {
            foreach($_GET["id"] as $count) {
              array_push($_SESSION['vars'], $_GET["text-" . $count]);
            }
          }
        ?>
        <?php foreach($_SESSION['vars'] as $var) { ?>
          <li><?php echo($var) ?></li>
        <?php } ?>
      </ul>
    <?php } ?>

    <form action="">
      <div id="elements"></div>
      <div><input type="submit" value="Submit" /></div>
    </form>
    <form onsubmit="add_element(); return false;">
      <div style="display: inline;"><input type="text" id="new_element" /></div>
      <div style="display: inline;"><input type="submit" value="Add Element" /></div>
    </form>
  </body>
</html>
