<?php
$Tokyo = new DateTimeZone('Asia/Tokyo');
$now = new DateTime('now', $Tokyo);
echo "<p>In Tokyo, it's " . $now->format('g:i A') . '</p>';
?>