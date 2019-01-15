<?php
require_once '../Pos/Date.php';
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
	// Get the namespaced elements
	$dc = $item->children('dc', true);
	// For PHP 5.0 or 5.1, replace the preceding line with the following one
	// $dc = $item->children('http://purl.org/dc/elements/1.1/');
	try {
	  // Split the date
	  $dateParts = explode('T', $dc->date);
	  // Create a Pos_Date object
	  $date = new Pos_Date();
	  // The first element of $date parts contains the date as YYYY-MM-DD, the format used by Pos_Date::setFromMySQL()
	  $date->setFromMySQL($dateParts[0]);
	echo '<p>Date: ' . $date . '</p>';
	} catch (Exception $e) {
		echo $e->getMessage();
	}
}
?>
</body>
</html>
