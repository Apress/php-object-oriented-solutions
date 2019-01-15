<?php
// Assign two PHP time zone identifiers to variables.
$timezone1 = 'America/New_York';
$timezone2 = 'America/Los_Angeles';
// Create DateTimeZone objects for each time zone.
$tzObj1 = new DateTimeZone($timezone1);
$tzObj2 = new DateTimeZone($timezone2);
// Use $tzObj1 to get the current date and time in $timezone1.
// Notice that you need the date and time in only one time zone
// to work out the time difference between them.
$now = new DateTime('now', $tzObj1);

// Work out the offset from UTC in time zone 1.
$offset1 = $tzObj1->getOffset($now);
// Use the date and time in time zone 1 to get the UTC offset in the other time zone.
$offset2 = $tzObj2->getOffset($now);
// Work out the time difference by subtracting one offset from the other.
$diff = $offset1 - $offset2;
if ($diff == 0) {
	echo 'Both cities are in the same time zone';
} else {
	// Convert the time difference from seconds.
	// Time zones to the west of the prime meridian produce a negative
	// offset, so use abs() to convert negative numbers to positive.
	// Not all time zones are an exact number of hours from UTC, so
	// use floor() to get an integer. 
	$hours = floor(abs($diff)/60/60);
	// Use modulo to see if there is a remainder.
	// The remainder will still be in seconds,
	// so it needs to be divided by 60. 
	$minutes = ((abs($diff)%3600)/60);
	// Format the time difference as a string.
	$gap = "$hours hour(s) $minutes minutes";
	// Work out which timezone has a bigger offset
	$whichWay = ($offset1 > $offset2) ? 'ahead of' : 'behind';
	echo "<p>$timezone1 is $gap $whichWay $timezone2.</p>";
} 
// Now that the calculation has been performed, get the current
// date and time in the second time zone for confirmation that
// the calculation is accurate.
$otherTime = new DateTime('now', $tzObj2);
echo "<p>For confirmation, the time in $timezone1 is " . $now->format('g:i A');
echo ", and in $timezone2, it's " . $otherTime->format('g:i A') . '</p>';
?>
