<html>
<?php if(!isset($type)): ?>
<head>
<title>Form creation process</title>
</head>
<body>
<form method="post" action="<?php echo getenv("SCRIPT_NAME"); ?>">
<input type="submit" name="type" value="Add Units or Activities"><br>
<input type="submit" name="type" value="Delete Units or Activities"><br>
<input type="submit" name="type" value="Edit Units Composition"><br>
<input type="submit" name="type" value="Edit Forms Composition"><br>
</form>
</body>
<?php else: ?>
<?php endif; ?>
</html>