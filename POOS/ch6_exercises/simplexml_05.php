<?php
$xml = simplexml_load_file('inventory.xml');
// Get the element nodes at the top level of the XML document
$children = $xml->children();
// Loop through each top level node to display its name
foreach ($children as $child) {
  echo 'Node name: ' . $child->getName() . '<br />';
  // Get the attributes for the current node
  $attributes = $child->attributes();
  // Loop through the attributes of the current node
  foreach ($attributes as $attribute) {
    echo 'Attribute ' . $attribute->getName() . ": $attribute<br />";
  }
  // If the current node has no children, display its value
  if (false === $nextChildren = $child->children()) {
    echo "$child<br />";
  } else {
    // Otherwise loop through the next level
    foreach ($nextChildren as $nextChild) {
      echo $nextChild->getName() . ": $nextChild<br />";
    }
    echo '<br />';
  }
}
?>
