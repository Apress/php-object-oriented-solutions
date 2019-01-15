<?php
require_once '../Pos/Date.php';
try {
	// create a Pos_Date object 
	$date = new Pos_Date();
	// use read-only properties to display the date parts
	echo '<p>MDY: ' . $date->MDY . '</p>';
    echo '<p>MDY0: ' . $date->MDY0 . '</p>';
    echo '<p>DMY: ' . $date->DMY . '</p>';
    echo '<p>DMY0: ' . $date->DMY0 . '</p>';
    echo '<p>MySQL: ' . $date->MySQL . '</p>';
	echo '<p>fullYear: ' . $date->fullYear . '</p>';
	echo '<p>year: ' . $date->Year . '</p>';
	echo '<p>month: ' . $date->Month . '</p>';
	echo '<p>month0: ' . $date->month0 . '</p>';
	echo '<p>monthName: ' . $date->monthName . '</p>';
	echo '<p>monthAbbr: ' . $date->monthAbbr. '</p>';
    echo '<p>day: ' . $date->day . '</p>';
    echo '<p>day0: ' . $date->day0 . '</p>';
    echo '<p>dayOrdinal: ' . $date->dayOrdinal. '</p>';
    echo '<p>dayName: ' . $date->dayName . '</p>';
    echo '<p>dayAbbr: ' . $date->dayAbbr . '</p>';
} catch (Exception $e) {
	echo $e;
}
?>