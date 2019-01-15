<?php
require_once '../Pos/Date.php';
try {
	// create a Pos_Date object 
	$date = new Pos_Date();
	// use different methods to display the date parts
	echo '<p>getFullYear(): ' . $date->getFullYear() . '</p>';
	echo '<p>getYear(): ' . $date->getYear() . '</p>';
	echo '<p>getMonth(): ' . $date->getMonth() . '</p>';
	echo '<p>getMonth(1): ' . $date->getMonth(1) . '</p>';
	echo '<p>getMonthName(): ' . $date->getMonthName() . '</p>';
	echo '<p>getMonthAbbr(): ' . $date->getMonthAbbr() . '</p>';
    echo '<p>getDay(): ' . $date->getDay() . '</p>';
    echo '<p>getDay(1): ' . $date->getDay(1) . '</p>';
    echo '<p>getDayOrdinal(): ' . $date->getDayOrdinal() . '</p>';
    echo '<p>getDayName(): ' . $date->getDayName() . '</p>';
    echo '<p>getDayAbbr(): ' . $date->getDayAbbr() . '</p>';
} catch (Exception $e) {
	echo $e;
}
?>