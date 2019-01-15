<?php
require_once '../Pos/Date.php';
try {
	// create a Pos_Date object 
	$date = new Pos_Date();
	// set date to February 29 in a leap year
	$date->setDate(2008, 2, 29);
	echo '<p>Leap year: ' . $date->format('F jS, Y') . '</p>';
	$date->subYears(2);
	echo '<p>Subtract 2 years: ' .$date->format('F jS, Y') . '</p>';
} catch (Exception $e) {
	echo $e;
}
?>