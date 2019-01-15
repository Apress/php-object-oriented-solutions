<?php $xml = simplexml_load_file('inventory.xml'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Using XPath to drill down into XML</title>
<link href="simplexml.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>PHP Books from Apress</h1>
<?php
$publishers = $xml->xpath('//publisher[. = "Apress"]');
foreach ($publishers as $pub) {
	$title = $pub->xpath('../title');
	$author = $pub->xpath('../author');
	$description = $pub->xpath('../description');
	echo "<h2>$title[0]</h2>";
	echo "<p class='author'>$author[0]</p>";
	echo "<p>$description[0]</p>";
}
?>
</body>
</html>
