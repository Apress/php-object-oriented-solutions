<?php
$numbers = array(5, 10, 8, 35, 50);
##################################################
# This triggers a fatal error, because you can't #
# pass a raw array to an iterator.               #
##################################################
$limiter = new LimitIterator($numbers, 0, 2);
foreach ($limiter as $number) {
	echo $number . '<br />';
}
?>
