<?php
$author = array('name'    => 'David',
                'city'    => 'London',
                'country' => 'United Kingdom');
$iterator = new ArrayIterator($author);

// Move the iterator to the first item 
$iterator->rewind();
// Loop through each element while the valid() method returns true
while ($iterator->valid()) {
  // Display the key and value of each element
  echo $iterator->key() . ': ' . $iterator->current() . '<br />';
  // Move to the next element
  $iterator->next();
}
?>