<?php
require_once '../Pos/Date.php';
try {
	// create a Pos_Date object 
	$improved = new Pos_Date();
	// set the date to August 31, 2008
	$improved->setDate(2008, 8, 31);
	echo '<p>Starting date: ' . $improved->format('F jS, Y') . '</p>';
	$improved->addMonths(1);
	echo '<p>Add 1 month using Pos_Date::addMonths(): ' . $improved->format('F jS, Y') . '</p>';
	// create a DateTime object set to August 31, 2008
	$original = new DateTime('Aug 31, 2008');
	$original->modify('+1 month');
	echo '<p>Add 1 month using DateTime::modify(): ' . $original->format('F jS, Y') . '</p>';
} catch (Exception $e) {
	echo $e;
}
?>