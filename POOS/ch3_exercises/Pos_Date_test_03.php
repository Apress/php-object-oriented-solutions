<?php
require_once '../Pos/Date.php';
try {
	// create a Pos_Date object for the default time zone 
	$local = new Pos_Date();
	// use the inherited format() method to display the date and time
	echo '<p>Time now: ' . $local->format('F jS, Y h:i A') . '</p>';
	$local->setTime(12, 30);
	$local->setDate(2008, 9, 31);
	echo '<p>Date and time reset: ' . $local->format('F jS, Y h:i A') . '</p>';
} catch (Exception $e) {
	echo $e;
}
?>