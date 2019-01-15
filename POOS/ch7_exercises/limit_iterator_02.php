<?php
$numbers = array(5, 10, 8, 35, 50);

// Prepare the array for use with an iterator
$iterator = new ArrayIterator($numbers);

// Pass the converted array to the LimitIterator
$limiter = new LimitIterator($iterator, 0, 2);

// Loop through the LimitIterator object
foreach ($limiter as $number) {
  echo $number . '<br />';
}
?>
