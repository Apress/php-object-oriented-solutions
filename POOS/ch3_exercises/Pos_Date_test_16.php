<?php
require_once '../Pos/Date.php';
try {
	// create a Pos_Date object
	$now = new Pos_Date();
	echo $now;
} catch (Exception $e) {
	echo $e;
}
?>