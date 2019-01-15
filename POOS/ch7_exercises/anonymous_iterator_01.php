<?php
$numbers = array(5, 10, 8, 35, 50);

foreach (new LimitIterator(new ArrayIterator($numbers), 0, 2) as $num) {
  echo $num . '<br />';
}
?>
