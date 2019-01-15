<?php
require_once '../Pos/Date.php';
try {
	// create a Pos_Date object 
	$date = new Pos_Date();
	// set the date to July 4, 2008
	$date->setDate(2008, 7, 4);
	// use different methods to display the date
	echo '<p>getMDY(): ' . $date->getMDY() . '</p>';
	echo '<p>getMDY(1): ' . $date->getMDY(1) . '</p>';
	echo '<p>getDMY(): ' . $date->getDMY() . '</p>';
	echo '<p>getDMY(1): ' . $date->getDMY(1) . '</p>';
	echo '<p>getMySQLFormat(): ' . $date->getMySQLFormat() . '</p>';
} catch (Exception $e) {
	echo $e;
}
?>