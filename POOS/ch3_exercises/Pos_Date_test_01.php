<?php
require_once '../Pos/Date.php';
try {
	// create a Pos_Date object for the default time zone 
	$local = new Pos_Date();
	// use the inherited format() method to display the date and time
	echo '<p>Local time: ' . $local->format('F jS, Y h:i A') . '</p>';
	
	// create a DateTimeZone object
	$tz = new DateTimeZone('Asia/Tokyo');
	// create a new Pos_Date object and pass the time zone as an argument
	$Tokyo = new Pos_Date($tz);
	echo '<p>Tokyo time: ' . $Tokyo->format('F jS, Y h:i A') . '</p>';
} catch (Exception $e) {
	echo $e;
}
?>