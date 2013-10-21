<html>
<head>
<title>Smileys</title>

<?php echo js_insert_smiley('blog', 'comments'); ?>

</head>
<body>

<form name="blog">
<textarea name="comments" cols="40" rows="4"></textarea>
</form>

<p>Click to insert a smiley!</p>

<?php echo $smiley_table; ?>

</body>
</html>
