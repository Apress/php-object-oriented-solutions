<?php
$xml = simplexml_load_file('foed.xml');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Using namespaces in SimpleXML</title>
<link href="simplexml.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>updatED</h1>
<?php
foreach ($xml->item as $item) {
	echo '<h2>' . $item->title . '</h2>';
	echo '<p>' . $item->description . '</p>';
	// The colon in the namespace prefix generates a parse error
	echo '<p>Date: ' . $item->dc:date . '</p>';
}
?>
</body>
</html>
