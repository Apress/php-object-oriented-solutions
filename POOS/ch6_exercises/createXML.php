<?php
// Create the root element
$newXML = new SimpleXMLElement('<root></root>');
// Add an element node
$book1 = $newXML->addChild('book');
// Add child nodes with text nodes
$book1->addChild('title', 'Build Your Own XML');
$book1->addChild('author', 'All My Own Work');

// Add a second element node with child nodes
$book2 = $newXML->addChild('book');
$book2->addChild('title', 'Transcendental XML');
$book2->addChild('author', 'XML Guru');

// Send an XML header and output the XML to a browser
header('Content-Type: text/xml');
echo $newXML->asXML();
?>