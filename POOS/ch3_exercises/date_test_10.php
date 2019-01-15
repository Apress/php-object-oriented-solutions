<?php
// create a DateTime object
$date = new DateTime('Aug 31, 2008');
echo '<p>Initial date is ' . $date->format('F j, Y') . '</p>';
// add one month
$date->modify('+1 month');
echo '<p>Add one month: ' . $date->format('F j, Y') . '</p>';
// subtract one month
$date->modify('-1 month');
echo '<p>Subtract one month: ' . $date->format('F j, Y') . '</p>';
?>