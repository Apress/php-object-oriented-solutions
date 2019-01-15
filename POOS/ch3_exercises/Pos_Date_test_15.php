<?php
require_once '../Pos/Date.php';
try {
	// create two Pos_Date objects 
	$now = new Pos_Date();
	$newYear = new Pos_Date();
	// set one of them to January 1, 2009
	$newYear->setDate(2009, 1, 1);
	// calculate the number of days
	$diff = $now->dateDiff2($newYear);
	$unit = abs($diff) > 1 ? 'days' : 'day';
	if ($diff == 0) {
	    echo 'Happy New Year!';
	} elseif ($diff > 0) {
	    echo "$diff $unit left till 2009";
	} else {
	    echo abs($diff) . " $unit since the beginning of 2009";
	}
} catch (Exception $e) {
	echo $e;
}
?>