<?php
// create a DateTime object
$now = new DateTime();
echo '<p>My local time is ' . $now->format('l, g:i A') . '</p>';
// create DateTimeZone objects for various places
$Katmandu = new DateTimeZone('Asia/Katmandu');
$Moscow = new DateTimeZone('Europe/Moscow');
$Timbuktu = new DateTimeZone('Africa/Timbuktu');
$Chicago = new DateTimeZone('America/Chicago');
$Fiji = new DateTimeZone('Pacific/Fiji');
// reset the time zone for the DateTime object to each time zone
$now->setTimezone($Katmandu);
echo "<p>In Katmandu, it's " . $now->format('l, g:i A') . '</p>';
$now->setTimezone($Moscow);
echo "<p>In Moscow, it's " . $now->format('l, g:i A') . '</p>';
$now->setTimezone($Timbuktu);
echo "<p>In Timbuktu, it's " . $now->format('l, g:i A') . '</p>';
$now->setTimezone($Chicago);
echo "<p>In Chicago, it's " . $now->format('l, g:i A') . '</p>';
$now->setTimezone($Fiji);
echo "<p>And in Fiji, it's " . $now->format('l, g:i A') . '</p>';
?>